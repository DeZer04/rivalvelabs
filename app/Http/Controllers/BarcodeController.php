<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\PesananPenjualan;
use App\Models\DetailPesananPenjualan;
use App\Models\ItemVariant;
use App\Models\Supplier;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeController extends Controller
{
    public function create()
    {
        $buyers = Buyer::pluck('nama_buyer', 'id');
        return view('barcode.create', compact('buyers'));
    }

    protected function getOrderSequenceNumber($buyerId, $pesananId)
    {
        // Get all orders for this buyer ordered by creation date
        $orders = PesananPenjualan::where('buyer_id', $buyerId)
            ->orderBy('created_at')
            ->pluck('id')
            ->toArray();

        // Find the position of the current order in the sequence
        $sequence = array_search($pesananId, $orders) + 1;

        return $sequence;
    }

    protected function generateBarcodeText($variantId, $buyerId, $orderSequence, $supplierCode, $containerNumber)
    {
        $kodeItem = str_pad($variantId, 4, '0', STR_PAD_LEFT);
        $supplier = strtoupper($supplierCode);
        $buyerCode = str_pad($buyerId, 2, '0', STR_PAD_LEFT);
        $pesananCode = str_pad($orderSequence, 2, '0', STR_PAD_LEFT);
        $kontainer = strtoupper($containerNumber);

        return "{$kodeItem}{$buyerCode}{$pesananCode}{$supplier}{$kontainer}";
    }


    public function generate(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|exists:buyers,id',
            'pesanan_id' => 'required|exists:pesanan_penjualans,id',
            'item_variant_id' => 'required|exists:item_variants,id',
            'supplier_code' => 'required|alpha|max:1',
            'nomor_container' => 'required|alpha_num|max:3',
        ]);

        $buyer = Buyer::find($request->buyer_id);
        $pesanan = PesananPenjualan::find($request->pesanan_id);
        $variant = $pesanan->DetailPesananPenjualan
            ->where('item_variant_id', $request->item_variant_id)
            ->first()?->ItemVariant;

        $orderSequence = $this->getOrderSequenceNumber($request->buyer_id, $request->pesanan_id);

        $barcodeText = $this->generateBarcodeText(
            $variant->id,
            $buyer->id,
            $orderSequence,
            $request->supplier_code,
            $request->nomor_container
        );

        return redirect()->route('barcode.create')->with('barcodeText', $barcodeText);
    }

    protected function decodeBarcode($barcode)
    {
        try {
            // Extract components from barcode based on your format
            $variantId = (int) substr($barcode, 0, 4);
            $buyerId = (int) substr($barcode, 4, 2);
            $orderSequence = (int) substr($barcode, 6, 2);
            $supplierCode = substr($barcode, 8, 1);
            $containerNumber = substr($barcode, 9);

            // Fetch related models
            $variant = ItemVariant::find($variantId);
            $buyer = Buyer::find($buyerId);

            // Find supplier by the code
            $supplier = Supplier::whereJsonContains('kode_supplier', [$supplierCode => $supplierCode])
                ->first();

            // Get the actual pesanan_id based on sequence number if buyer exists
            $pesanan = null;
            if ($buyer) {
                $pesanan = PesananPenjualan::where('buyer_id', $buyerId)
                    ->orderBy('created_at')
                    ->skip($orderSequence - 1)
                    ->first();
            }

            return [
                'variant' => $variant,
                'buyer' => $buyer,
                'pesanan' => $pesanan,
                'supplier' => $supplier,
                'order_sequence' => $orderSequence,
                'supplier_code' => $supplierCode,
                'container_number' => $containerNumber,
                'original_barcode' => $barcode,
                'is_valid' => $variant && $buyer && $pesanan && $supplier
            ];
        } catch (\Exception $e) {
            return [
                'is_valid' => false,
                'error' => 'Invalid barcode format',
                'original_barcode' => $barcode
            ];
        }
    }

    public function decode(Request $request)
    {
        $request->validate([
            'barcode_input' => 'required|alpha_num|min:10|max:12'
        ]);

        $decodedData = $this->decodeBarcode($request->barcode_input);

        return redirect()->route('barcode.create')->with('decodedData', $decodedData);
    }

    public function getPesanan($buyerId)
    {
        $orders = PesananPenjualan::where('buyer_id', $buyerId)
            ->orderBy('created_at')
            ->get()
            ->map(function ($order, $index) {
                $order->nomor_pesanan = 'Order #' . ($index + 1) . ' - ' . $order->nomor_pesanan;
                return $order;
            });

        return response()->json($orders);
    }

    public function getItemVariant($pesananId)
    {
        $detail = DetailPesananPenjualan::with('ItemVariant')
            ->where('pesanan_penjualan_id', $pesananId)
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
