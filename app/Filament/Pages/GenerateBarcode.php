<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms;
use Filament\Forms\Components\{Select, Grid, Hidden, Card, Section, TextInput, ViewField};
use Illuminate\Support\Str;
use App\Models\{Buyer, PesananPenjualan, Item};
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Torgodly\Html2Media\Actions\Html2MediaAction;

class GenerateBarcode extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string $view = 'filament.pages.generate-barcode';
    public ?array $formData = [];

    public ?string $barcode = null;

    public function mount(): void
    {
        $this->form->fill([]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make()
                ->statePath('formData')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('buyer_id')
                            ->label('Pilih Buyer')
                            ->options(Buyer::pluck('nama_buyer', 'id'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('pesanan_id', null);
                                $set('item_variant_id', null);
                            }),

                        Select::make('pesanan_id')
                            ->label('Pilih Pesanan')
                            ->options(function (callable $get) {
                                $buyerId = $get('buyer_id');
                                return $buyerId
                                    ? PesananPenjualan::where('buyer_id', $buyerId)->pluck('nomor_pesanan', 'id')
                                    : [];
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('item_variant_id', null);
                            }),

                        Select::make('item_variant_id')
                            ->label('Pilih Item Variant')
                            ->options(function (callable $get) {
                                $pesananId = $get('pesanan_id');
                                if (!$pesananId) return [];

                                return \App\Models\DetailPesananPenjualan::where('pesanan_penjualan_id', $pesananId)
                                    ->with('ItemVariant')
                                    ->get()
                                    ->mapWithKeys(function ($detail) {
                                        return [$detail->item_variant_id => optional($detail->ItemVariant)->nama_variant];
                                    })
                                    ->filter()
                                    ->toArray();
                            })
                            ->reactive(),

                        TextInput::make('supplier_line')
                            ->label('Kode Supplier (A-Z)')
                            ->placeholder('Masukkan kode supplier (A-Z)')
                            ->maxLength(1)
                            ->reactive(),

                        TextInput::make('container_number')
                            ->label('Nomor Kontainer')
                            ->placeholder('Masukkan nomor kontainer')
                            ->maxLength(3)
                            ->reactive(),
                    ]),

                    Hidden::make('barcode')->dehydrated(),

                ])
        ];
    }

    public function generateBarcode()
    {
        $formData = $this->form->getState();
        Log::info('Form Data:', $formData);

        // Ambil data dari formData['formData'] jika ada
        $data = $formData['formData'] ?? $formData;

        $itemVariantId = $data['kode_itemvariants'] ?? null;
        $buyerId = $data['buyer_id'] ?? null;
        $pesananId = $data['pesanan_id'] ?? null;
        $supplierLine = $data['supplier_line'] ?? 'X'; // default 'X' jika tidak dipilih
        $containerNumber = $data['container_number'] ?? null;

        // Ambil item_id dari item_variant jika diperlukan
        $itemId = null;
        if ($itemVariantId) {
            $detail = \App\Models\DetailPesananPenjualan::where('kode_itemvariants', $itemVariantId)->first();
            $itemId = $detail?->item_id;
        }

        // Format angka agar sesuai digit (zero-padded)
        $itemPart = str_pad($itemId ?? 0, 4, '0', STR_PAD_LEFT);
        $buyerPart = str_pad($buyerId ?? 0, 2, '0', STR_PAD_LEFT);
        $poPart = str_pad($pesananId ?? 0, 2, '0', STR_PAD_LEFT);
        $linePart = strtoupper(substr($supplierLine ?? 'X', 0, 1)); // hanya 1 karakter
        $containerPart = str_pad(preg_replace('/\D/', '', $containerNumber ?? 0), 3, '0', STR_PAD_LEFT);

        $barcode = "{$itemPart}.{$buyerPart}.{$poPart}.{$linePart}.{$containerPart}";

        $this->barcode = $barcode;

        // Pastikan mengisi ke formData['formData'] jika struktur nested
        if (isset($formData['formData'])) {
            $formData['formData']['barcode'] = $barcode;
            $this->form->fill($formData);
        } else {
            $this->form->fill(array_merge($data, [
            'barcode' => $barcode,
            ]));
        }
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

}
