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

    protected function getOrderSequenceNumber($buyerId, $nomorPesanan)
    {
        // Ambil angka dari nomor pesanan (PO#62 -> 62)
        $pesananNumber = $this->extractNumberFromNomorPesanan($nomorPesanan);

        // Get all orders for this buyer ordered by creation date
        $orders = PesananPenjualan::where('buyer_id', $buyerId)
            ->orderBy('created_at')
            ->get()
            ->map(function ($order) {
                return $this->extractNumberFromNomorPesanan($order->nomor_pesanan);
            })
            ->toArray();

        // Find the position of the current order in the sequence
        $sequence = array_search($pesananNumber, $orders) + 1;

        return $sequence;
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

        return "{$kodeItem}{$buyerCode}{$pesananCode}{$supplier}{$kontainer}";
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
            if (strlen($barcode) < 11) {
                throw new \Exception('Barcode terlalu pendek');
            }

            // Extract components from barcode based on your format
            $variantId = (int) substr($barcode, 0, 4);
            $buyerId = (int) substr($barcode, 4, 2);
            $pesananCode = (int) substr($barcode, 6, 3); // 3 digit nomor pesanan
            $supplierCode = substr($barcode, 9, 1);
            $containerNumber = substr($barcode, 10, 3); // Ambil maksimal 3 karakter untuk nomor container

            // Validasi data numerik
            if ($variantId <= 0 || $buyerId <= 0 || $pesananCode <= 0) {
                throw new \Exception('Format numerik tidak valid');
            }

            // Fetch related models dengan error handling
            $variant = ItemVariant::find($variantId);
            if (!$variant) {
                throw new \Exception('Item variant tidak ditemukan');
            }

            $buyer = Buyer::find($buyerId);
            if (!$buyer) {
                throw new \Exception('Buyer tidak ditemukan');
            }

            // Cari supplier dengan kode yang sesuai
            $supplier = Supplier::where('kode_supplier', 'LIKE', '%"' . $supplierCode . '"%')
                ->orWhere('kode_supplier', 'LIKE', "%" . $supplierCode . "%")
                ->first();
            if (!$supplier) {
                throw new \Exception('Supplier tidak ditemukan');
            }

            // Cari pesanan berdasarkan buyer dan nomor pesanan
            $pesanan = PesananPenjualan::where('buyer_id', $buyerId)
                        ->where('nomor_pesanan', 'LIKE', 'PO#' . $pesananCode . '%')
                        ->first();
            if (!$pesanan) {
                throw new \Exception('Pesanan tidak ditemukan');
            }

            // Verifikasi bahwa item variant ada di pesanan tersebut
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
                'pesanan_code' => $pesananCode,
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

    public function getItemVariant($nomorPesanan)
    {
        // If the parameter comes as "PO", convert it to "PO#62" format
        if (strpos($nomorPesanan, '#') === false) {
            // Find the first order that starts with this prefix
            $pesanan = PesananPenjualan::where('nomor_pesanan', 'like', $nomorPesanan.'#%')->first();
        } else {
            $pesanan = PesananPenjualan::where('nomor_pesanan', $nomorPesanan)->first();
        }

        if (!$pesanan) {
            return response()->json([]);
        }

        $detail = DetailPesananPenjualan::with('ItemVariant')
            ->where('pesanan_penjualan_id', $pesanan->id)
            ->get();

        return response()->json($detail->pluck('ItemVariant'));
    }

    public function image($text)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($text, $generator::TYPE_CODE_128, 2, 60);

        return response($barcode)->header('Content-Type', 'image/png');
    }
}
