<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesananPenjualanResource\Pages;
use App\Filament\Resources\PesananPenjualanResource\RelationManagers;
use App\Models\Buyer;
use App\Models\DetailPenawaranPenjualan;
use App\Models\PenawaranPenjualan;
use App\Models\PesananPenjualan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class PesananPenjualanResource extends Resource
{
    protected static ?string $model = PesananPenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pesanan')->schema([
                    Select::make('buyer_id')
                        ->label('Buyer')
                        ->relationship('Buyer', 'nama_buyer')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->placeholder('Pilih Buyer')
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, callable $get) {
                            $set('nomor_pesanan', \App\Models\PesananPenjualan::generateNomorPesanan(
                                $get('buyer_id'),
                                $get('tanggal_pesanan')
                            ));
                        }),

                    Select::make('penawaran_penjualan_id')
                        ->label('Penawaran (optional)')
                        ->options(function (callable $get) {
                            $buyerId = $get('buyer_id');

                            if (!$buyerId) return [];
                            // Ambil penawaran yang belum pernah dijadikan pesanan
                            return PenawaranPenjualan::where('buyer_id', $buyerId)
                                ->whereDoesntHave('pesananPenjualan')
                                ->pluck('nomor_penawaran', 'id');
                        })
                        ->searchable()
                        ->nullable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if (!$state) return;

                            $detailPenawaran = \App\Models\DetailPenawaranPenjualan::where('penawaran_penjualan_id', $state)->get();

                            $items = $detailPenawaran->map(function ($item) {
                                return [
                                    'item_id' => $item->item_id,
                                    'item_variant_id' => $item->item_variant_id,
                                    'jumlah_item' => $item->jumlah_item,
                                    'jumlah_item_dikirim' => 0,
                                    'harga_satuan' => $item->harga_satuan,
                                    'diskon' => $item->diskon,
                                    'total_harga' => $item->total_harga,
                                    'keterangan' => $item->keterangan,
                                ];
                            })->toArray();

                            $set('detailPesananPenjualan', $items);

                            Notification::make()
                                ->title('Detail Penawaran Berhasil Dimuat')
                                ->body('Semua item dari penawaran telah dimasukkan ke dalam detail pesanan.')
                                ->success()
                                ->send();
                        }),

                    TextInput::make('nomor_pesanan')
                        ->label('Nomor Pesanan')
                        ->dehydrated(),

                    DatePicker::make('tanggal_pesanan')
                        ->label('Tanggal Pesanan')
                        ->default(now())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, callable $get) {
                            $set('nomor_pesanan', \App\Models\PesananPenjualan::generateNomorPesanan(
                                $get('buyer_id'),
                                $get('tanggal_pesanan')
                            ));
                        }),

                    Select::make('status_pesanan')
                        ->label('Status Pesanan')
                        ->options([
                            'menunggu diproses' => 'Menunggu Diproses',
                            'sebagian terproses' => 'Sebagian Terproses',
                            'terproses' => 'Terproses',
                        ])
                        ->default('menunggu diproses')
                        ->disabled()
                        ->dehydrated(),
                ])->columns(2),

                Section::make('Detail Pesanan')->schema([
                    Repeater::make('detailPesananPenjualan')
                        ->relationship()
                        ->schema([
                            Select::make('item_id')
                                ->label('Item')
                                ->relationship('Item', 'nama_item')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->reactive(),

                            Select::make('item_variant_id')
                                ->label('Varian')
                                ->options(function (callable $get) {
                                    $itemId = $get('item_id');
                                    if (!$itemId) return [];
                                    return \App\Models\ItemVariant::where('item_id', $itemId)
                                        ->pluck('nama_variant', 'id');
                                })
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state) {
                                    $variant = \App\Models\ItemVariant::find($state);
                                    if ($variant) {
                                        $set('harga_satuan', $variant->harga);
                                        $set('gambar_variant', $variant->gambar_item[0] ?? null);
                                    }
                                })
                                ->afterStateHydrated(function (callable $set, callable $get, $state) {
                                    $variant = \App\Models\ItemVariant::find($state);
                                    if ($variant) {
                                        $set('harga_satuan', $variant->harga);
                                        $set('gambar_variant', $variant->gambar_item[0] ?? null);
                                    }
                                }),

                            Placeholder::make('preview_gambar_variant')
                                ->label('Preview Gambar')
                                ->content(function (callable $get) {
                                    $variantId = $get('item_variant_id');
                                    if (!$variantId) return null;

                                    $variant = \App\Models\ItemVariant::find($variantId);
                                    if (!$variant || !$variant->gambar_item) return 'Tidak ada gambar.';

                                    $html = '';
                                    $gambarArray = is_array($variant->gambar_item)
                                        ? $variant->gambar_item
                                        : json_decode($variant->gambar_item, true);

                                    if (!$gambarArray || !is_array($gambarArray)) return 'Format gambar tidak valid.';

                                    foreach ($gambarArray as $gambar) {
                                        $html .= '<img src="' . asset('storage/' . $gambar) . '" style="max-height: 150px; margin-right: 10px; border-radius: 8px;" />';
                                    }

                                    return new HtmlString($html);
                                })
                                ->visible(fn (callable $get) => $get('item_variant_id') !== null)
                                ->columnSpanFull(),

                            TextInput::make('jumlah_item')
                                ->label('Jumlah')
                                ->numeric()
                                ->required()
                                ->afterStateUpdated(function (callable $set, callable $get) {
                                    $harga = $get('harga_satuan') ?? 0;
                                    $jumlah = $get('jumlah_item') ?? 0;
                                    $diskon = $get('diskon') ?? 0;
                                    $set('total_harga', ($harga * $jumlah) - $diskon);
                                }),
                            TextInput::make('jumlah_item_dikirim')->numeric()->default(0)->readOnly(),
                            TextInput::make('harga_satuan')
                                ->label('Harga Satuan')
                                ->numeric()
                                ->required()
                                ->readOnly(),
                            TextInput::make('diskon')
                                ->label('Diskon')
                                ->numeric()
                                ->default(0)
                                ->afterStateUpdated(function (callable $set, callable $get) {
                                    $harga = $get('harga_satuan') ?? 0;
                                    $jumlah = $get('jumlah_item') ?? 0;
                                    $diskon = $get('diskon') ?? 0;
                                    $set('total_harga', ($harga * $jumlah) - $diskon);
                                }),
                            TextInput::make('total_harga')
                                ->label('Total Harga')
                                ->numeric()
                                ->required()
                                ->default(0)
                                ->readOnly(),
                            TextInput::make('keterangan')
                                ->label('Keterangan'),
                        ])
                        ->defaultItems(1)
                        ->collapsible()
                        ->grid(2),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pesanan')
                    ->label('Nomor Pesanan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('buyer.nama_buyer')
                    ->label('Buyer')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pesanan')
                    ->label('Tanggal Pesanan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_pesanan')
                    ->label('Status Pesanan')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesananPenjualans::route('/'),
            'create' => Pages\CreatePesananPenjualan::route('/create'),
            'edit' => Pages\EditPesananPenjualan::route('/{record}/edit'),
        ];
    }
}
