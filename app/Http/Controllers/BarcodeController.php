<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\PesananPenjualan;
use App\Models\DetailPesananPenjualan;
use App\Models\ItemVariant;
use App\Models\Supplier;
use Illuminate\Support\Facades\Log;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeController extends Controller
{
    public function create()
    {
        $buyers = Buyer::pluck('nama_buyer', 'id');
        return view('barcode.create', compact('buyers'));
    }

    protected function extractNumberFromNomorPesanan($nomorPesanan)
    {
        // Extract number from PO#62 format
        if (preg_match('/PO#(\d+)/', $nomorPesanan, $matches)) {
            return (int)$matches[1];
        }
        return 0;
    }

    protected function getNomorPesananNumber($nomorPesanan)
    {
        return $this->extractNumberFromNomorPesanan($nomorPesanan);
    }

    protected function generateBarcodeText($variantId, $buyerId, $orderSequence, $supplierCode, $containerNumber)
    {
        $kodeItem = str_pad($variantId, 4, '0', STR_PAD_LEFT);
        $supplier = strtoupper($supplierCode);
        $buyerCode = str_pad($buyerId, 2, '0', STR_PAD_LEFT);
        $pesananCode = str_pad($orderSequence, 3, '0', STR_PAD_LEFT);
        $kontainer = strtoupper($containerNumber);

        return "S/N:{$kodeItem}{$buyerCode}{$pesananCode}{$supplier}{$kontainer}";
    }


    public function generate(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|exists:buyers,id',
            'nomor_pesanan' => 'required|exists:pesanan_penjualans,nomor_pesanan',
            'item_variant_id' => 'required|exists:item_variants,id',
            'supplier_code' => 'required|alpha|max:1',
            'nomor_container' => 'required|alpha_num|max:3',
        ]);

        $buyer = Buyer::find($request->buyer_id);
        $pesanan = PesananPenjualan::where('nomor_pesanan', $request->nomor_pesanan)->first();

        // Pastikan pesanan milik buyer yang benar
        if ($pesanan->buyer_id != $buyer->id) {
            return back()->withErrors(['nomor_pesanan' => 'Nomor pesanan tidak sesuai dengan buyer yang dipilih']);
        }

        $variant = $pesanan->DetailPesananPenjualan
            ->where('item_variant_id', $request->item_variant_id)
            ->first()?->ItemVariant;

        $pesananNumber = $this->extractNumberFromNomorPesanan($request->nomor_pesanan);

        // Format nomor pesanan untuk barcode (3 digit)
        $pesananCode = str_pad($pesananNumber, 3, '0', STR_PAD_LEFT);

        $barcodeText = $this->generateBarcodeText(
            $variant->id,
            $buyer->id,
            $pesananCode, // Menggunakan kode pesanan 3 digit
            $request->supplier_code,
            $request->nomor_container
        );

        return redirect()->route('barcode.create')->with('barcodeText', $barcodeText);
    }

    protected function decodeBarcode($barcode)
    {
        try {
            // Validasi panjang barcode minimal
            if (strlen($barcode) < 13) {
                throw new \Exception('Barcode terlalu pendek');
            }

            // Decode barcode
            $variantId = (int) substr($barcode, 0, 4);    // 0001
            $buyerId = (int) substr($barcode, 4, 2);       // 05
            $pesananCode = substr($barcode, 6, 3);         // 001 → dicocokkan ke PO#001
            $supplierCode = substr($barcode, 9, 1);        // X
            $containerNumber = substr($barcode, 10, 3);    // 01A

            if ($variantId <= 0 || $buyerId <= 0 || intval($pesananCode) <= 0) {
                throw new \Exception('Format numerik tidak valid');
            }

            // Ambil data variant dan buyer
            $variant = ItemVariant::find($variantId);
            if (!$variant) throw new \Exception('Item variant tidak ditemukan');

            $buyer = Buyer::find($buyerId);
            if (!$buyer) throw new \Exception('Buyer tidak ditemukan');

            // Supplier lookup
            $supplier = Supplier::where('kode_supplier', 'LIKE', '%' . $supplierCode . '%')->first();
            if (!$supplier) throw new \Exception('Production Line tidak ditemukan');

            // Cari semua pesanan dari buyer yang cocok dengan nomor pesanan
            $pesananList = PesananPenjualan::where('buyer_id', $buyerId)
                ->where('nomor_pesanan', 'LIKE', 'PO#' . $pesananCode . '%')
                ->get();

            if ($pesananList->isEmpty()) {
                throw new \Exception('Pesanan tidak ditemukan');
            } elseif ($pesananList->count() > 1) {
                throw new \Exception('Lebih dari satu pesanan ditemukan untuk buyer dan nomor tersebut');
            }

            $pesanan = $pesananList->first();

            // Cek apakah variant ini ada di pesanan
            $itemInOrder = DetailPesananPenjualan::where('pesanan_penjualan_id', $pesanan->id)
                ->where('item_variant_id', $variantId)
                ->exists();
            if (!$itemInOrder) {
                throw new \Exception('Item tidak ditemukan dalam pesanan');
            }

            return [
                'variant' => $variant,
                'buyer' => $buyer,
                'pesanan' => $pesanan,
                'supplier' => $supplier,
                'pesanan_code' => $pesanan->nomor_pesanan,
                'order_sequence' => $pesananCode,
                'supplier_code' => $supplierCode,
                'container_number' => $containerNumber,
                'original_barcode' => $barcode,
                'is_valid' => true,
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'variant' => null,
                'buyer' => null,
                'pesanan' => null,
                'supplier' => null,
                'pesanan_code' => null,
                'order_sequence' => null,
                'supplier_code' => null,
                'container_number' => null,
                'original_barcode' => $barcode,
                'is_valid' => false,
                'error' => $e->getMessage()
            ];
        }
    }


    public function decode(Request $request)
    {
        $request->validate([
            'barcode_input' => 'required|string'
        ]);

        $decodedData = $this->decodeBarcode($request->barcode_input);

        return back()->with('decodedData', $decodedData);
    }

    public function getPesanan($buyerId)
    {
        $orders = PesananPenjualan::where('buyer_id', $buyerId)
            ->orderBy('created_at')
            ->get()
            ->map(function ($order) {
                // Ensure consistent format
                if (!str_contains($order->nomor_pesanan, '#')) {
                    $order->nomor_pesanan = 'PO#' . $order->nomor_pesanan;
                }
                return $order;
            });

        return response()->json($orders);
    }

    public function getItemVariant($pesananId)
    {
        // Find the order by ID
        $pesanan = PesananPenjualan::find($pesananId);

        if (!$pesanan) {
            return response()->json([]);
        }

        // Get all item variants for this order
        $variants = DetailPesananPenjualan::with('ItemVariant')
            ->where('pesanan_penjualan_id', $pesanan->id)
            ->get()
            ->pluck('ItemVariant')
            ->unique('id')
            ->values();

        return response()->json($variants);
    }

    public function image($text)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($text, $generator::TYPE_CODE_128, 2, 60);

        return response($barcode)->header('Content-Type', 'image/png');
    }
}
