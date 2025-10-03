<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengirimanPenjualanResource\Pages;
use App\Models\Buyer;
use App\Models\PesananPenjualan;
use App\Models\DetailPesananPenjualan;
use App\Models\PengirimanPenjualan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PengirimanPenjualanResource extends Resource
{
    protected static ?string $model = PengirimanPenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Penjualan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pengiriman')->schema([
                    Select::make('buyer_id')
                        ->label('Buyer')
                        ->relationship('buyer', 'nama_buyer')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set, callable $get) => 
                            $set('nomor_pengiriman', PengirimanPenjualan::generateNomorPengiriman($get('buyer_id'), $get('tanggal_pengiriman')))
                        ),

                    Select::make('pesanan_penjualan_id')
                        ->label('Pesanan Penjualan')
                        ->options(function (callable $get) {
                            $buyerId = $get('buyer_id');
                            if (!$buyerId) return [];

                            // hanya ambil pesanan dari buyer tsb
                            return PesananPenjualan::where('buyer_id', $buyerId)
                                ->pluck('nomor_pesanan', 'id');
                        })
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if (!$state) return;

                            $detailPesanan = DetailPesananPenjualan::where('pesanan_penjualan_id', $state)->get();

                            $items = $detailPesanan->map(function ($d) {
                                return [
                                    'item_id' => $d->item_id,
                                    'item_variant_id' => $d->item_variant_id,
                                    'jumlah_item' => $d->jumlah_item - $d->jumlah_item_dikirim, // hanya sisa
                                ];
                            })->toArray();

                            $set('detailPengirimanPenjualan', $items);

                            Notification::make()
                                ->title('Detail Pesanan Dimuat')
                                ->body('Detail item dari pesanan sudah otomatis dimasukkan ke pengiriman.')
                                ->success()
                                ->send();
                        }),

                    TextInput::make('nomor_pengiriman')
                        ->label('Nomor Pengiriman')
                        ->dehydrated()
                        ->readOnly(),

                    DatePicker::make('tanggal_pengiriman')
                        ->label('Tanggal Pengiriman')
                        ->default(now())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set, callable $get) => 
                            $set('nomor_pengiriman', PengirimanPenjualan::generateNomorPengiriman($get('buyer_id'), $get('tanggal_pengiriman')))
                        ),
                ])->columns(2),

                Section::make('Detail Pengiriman')->schema([
                    Repeater::make('detailPengirimanPenjualan')
                        ->relationship()
                        ->schema([
                            Select::make('item_id')
                                ->label('Item')
                                ->relationship('item', 'nama_item')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->reactive(),

                            Select::make('item_variant_id')
                                ->label('Varian')
                                ->options(function (callable $get) {
                                    $itemId = $get('item_id');
                                    if (!$itemId) return [];
                                    return \App\Models\ItemVariant::where('item_id', $itemId)
                                        ->pluck('nama_variant', 'id');
                                })
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state) {
                                    $variant = \App\Models\ItemVariant::find($state);
                                    if ($variant) {
                                        $set('gambar_variant', $variant->gambar_item[0] ?? null);
                                    }
                                }),

                            Placeholder::make('preview_gambar_variant')
                                ->label('Preview')
                                ->content(function (callable $get) {
                                    $variantId = $get('item_variant_id');
                                    if (!$variantId) return null;
                                    $variant = \App\Models\ItemVariant::find($variantId);
                                    if (!$variant || !$variant->gambar_item) return 'Tidak ada gambar.';
                                    
                                    $html = '';
                                    foreach ((array) $variant->gambar_item as $g) {
                                        $html .= '<img src="' . asset('storage/' . $g) . '" style="max-height: 120px; margin-right: 8px; border-radius: 6px;" />';
                                    }
                                    return new HtmlString($html);
                                })
                                ->visible(fn (callable $get) => $get('item_variant_id') !== null)
                                ->columnSpanFull(),

                            TextInput::make('jumlah_item')
                                ->label('Jumlah Dikirim')
                                ->numeric()
                                ->required()
                                ->minValue(1),
                        ])
                        ->defaultItems(1)
                        ->collapsible()
                        ->grid(2),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pengiriman')
                    ->label('Nomor Pengiriman')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('buyer.nama_buyer')
                    ->label('Buyer')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('pesananPenjualan.nomor_pesanan')
                    ->label('Pesanan Terkait')
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('tanggal_pengiriman')
                    ->label('Tanggal Pengiriman')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengirimanPenjualans::route('/'),
            'create' => Pages\CreatePengirimanPenjualan::route('/create'),
            'edit' => Pages\EditPengirimanPenjualan::route('/{record}/edit'),
        ];
    }
}
