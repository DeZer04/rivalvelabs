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
        // Check if this is an AJAX request
        $isAjax = $request->ajax() || $request->wantsJson();

        try {
            $request->validate([
                'buyer_id' => 'required|exists:buyers,id',
                'pesanan_id' => 'required|exists:pesanan_penjualans,id', // Changed from nomor_pesanan to pesanan_id
                'item_variant_id' => 'required|exists:item_variants,id',
                'supplier_code' => 'required|alpha|max:1',
                'nomor_container' => 'required|alpha_num|max:3',
            ]);

            $buyer = Buyer::find($request->buyer_id);
            $pesanan = PesananPenjualan::find($request->pesanan_id); // Changed to find by ID

            // Pastikan pesanan milik buyer yang benar
            if ($pesanan->buyer_id != $buyer->id) {
                $errorMessage = 'Nomor pesanan tidak sesuai dengan buyer yang dipilih';

                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['pesanan_id' => [$errorMessage]]
                    ], 422);
                }

                return back()->withErrors(['pesanan_id' => $errorMessage]);
            }

            // Check if the variant exists in this order
            $variant = $pesanan->DetailPesananPenjualan
                ->where('item_variant_id', $request->item_variant_id)
                ->first()?->ItemVariant;

            if (!$variant) {
                $errorMessage = 'Item variant tidak ditemukan dalam pesanan ini';

                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['item_variant_id' => [$errorMessage]]
                    ], 422);
                }

                return back()->withErrors(['item_variant_id' => $errorMessage]);
            }

            // Extract order sequence from pesanan
            $pesananNumber = $this->extractNumberFromNomorPesanan($pesanan->nomor_pesanan);

            // Format nomor pesanan untuk barcode (3 digit)
            $pesananCode = str_pad($pesananNumber, 3, '0', STR_PAD_LEFT);

            $barcodeText = $this->generateBarcodeText(
                $variant->id,
                $buyer->id,
                $pesananCode,
                $request->supplier_code,
                $request->nomor_container
            );

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'barcodeText' => $barcodeText,
                    'data' => [
                        'buyer' => $buyer,
                        'pesanan' => $pesanan,
                        'variant' => $variant
                    ]
                ]);
            }

            return redirect()->route('barcode.create')->with('barcodeText', $barcodeText);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Barcode generation error: ' . $e->getMessage());

            $errorMessage = 'Terjadi kesalahan saat menggenerate barcode';

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'error' => $errorMessage
                ], 500);
            }

            return back()->withErrors(['general' => $errorMessage]);
        }
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

            $pesananCode = ltrim($pesananCode, '0'); // "062" -> "62"
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
                'variant' => [
                    'id' => $variant->id,
                    'nama_variant' => $variant->nama_variant,
                    'kode_itemvariants' => $variant->kode_itemvariants,
                ],
                'buyer' => [
                    'id' => $buyer->id,
                    'nama_buyer' => $buyer->nama_buyer,
                ],
                'pesanan' => [
                    'id' => $pesanan->id,
                    'nomor_pesanan' => $pesanan->nomor_pesanan,
                    'tanggal_pesanan' => $pesanan->tanggal_pesanan,
                ],
                'supplier' => [
                    'id' => $supplier->id,
                    'nama_supplier' => $supplier->nama_supplier,
                    'kode_supplier' => $supplier->kode_supplier,
                ],
                'order_sequence' => $pesananCode,
                'supplier_code' => $supplierCode,
                'container_number' => $containerNumber,
                'original_barcode' => $barcode,
                'is_valid' => true,
                'error' => null,
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

        if ($decodedData['is_valid']) {
            return response()->json([
                'success' => true,
                'data' => $decodedData
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => $decodedData['error'],
            'data' => $decodedData
        ], 422);
    }

    public function getPesanan($buyerId)
    {
        $orders = PesananPenjualan::where('buyer_id', $buyerId)
            ->orderBy('created_at')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'nomor_pesanan' => str_contains($order->nomor_pesanan, '#')
                        ? $order->nomor_pesanan
                        : 'PO#' . $order->nomor_pesanan,
                ];
            });

        return response()->json($orders);
    }

    public function getItemVariant($pesananId)
    {
        $pesanan = PesananPenjualan::find($pesananId);

        if (!$pesanan) {
            return response()->json([]);
        }

        $variants = DetailPesananPenjualan::with('ItemVariant:id,nama_variant')
            ->where('pesanan_penjualan_id', $pesanan->id)
            ->get()
            ->pluck('ItemVariant')
            ->unique('id')
            ->values()
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'nama_variant' => $variant->nama_variant,
                ];
            });

        return response()->json($variants);
    }

    public function image($text)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($text, $generator::TYPE_CODE_128, 2, 60);

        return response($barcode)->header('Content-Type', 'image/png');
    }
}
