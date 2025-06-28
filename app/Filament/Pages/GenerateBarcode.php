<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms;
use Filament\Forms\Components\{Select, Grid, Hidden, Card, Section, ViewField};
use Illuminate\Support\Str;
use App\Models\{Buyer, PesananPenjualan, Item};

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

                        ViewField::make('supplier')
                            ->label('Pilih Supplier (A-Z)')
                            ->view('components.supplier-selector')
                            ->reactive(),
                    ]),

                    Hidden::make('barcode')->dehydrated(),

                ])
        ];
    }

    public function generateBarcode()
    {
        $formData = $this->form->getState();

        $kode = 'BR-' . strtoupper(\Str::random(6));
        $this->barcode = $kode;

        $this->form->fill(array_merge($formData, [
            'barcode' => $kode,
        ]));
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }
}
