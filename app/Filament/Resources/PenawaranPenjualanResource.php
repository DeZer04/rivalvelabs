<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenawaranPenjualanResource\Pages;
use App\Models\PenawaranPenjualan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class PenawaranPenjualanResource extends Resource
{
    protected static ?string $model = PenawaranPenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Penjualan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Select::make('buyer_id')
                            ->label('Buyer')
                            ->relationship('Buyer', 'nama_buyer')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Buyer')
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $set('nomor_penawaran', PenawaranPenjualan::generateNomorPenawaran(
                                    $get('buyer_id'),
                                    $get('tanggal_penawaran')
                                ));
                            })
                            ->columnSpan(3),

                        DatePicker::make('tanggal_penawaran')
                            ->label('Tanggal Penawaran')
                            ->default(now())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $set('nomor_penawaran', PenawaranPenjualan::generateNomorPenawaran(
                                    $get('buyer_id'),
                                    $get('tanggal_penawaran')
                                ));
                            })
                            ->columnSpan(2),

                        TextInput::make('nomor_penawaran')
                            ->label('Nomor Penawaran')
                            ->readOnly()
                            ->disabled()
                            ->dehydrated() // tetap tersimpan saat submit
                            ->columnSpan(3),

                        Select::make('status_penawaran')
                            ->label('Status Penawaran')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ])
                            ->default('draft')
                            ->required()
                            ->columnSpan(2),

                    ])
                    ->columns(5),



                Repeater::make('DetailPenawaranPenjualan')
                    ->label('Detail Penawaran')
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

                        TextInput::make('harga_satuan')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->required()
                            ->readOnly(),

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

                        // gambar_variant hanya untuk preview, tidak didehidrasi ke database
                        Hidden::make('gambar_variant')
                            ->dehydrated(false),

                        Forms\Components\ViewField::make('preview_gambar')
                            ->label('Gambar Varian')
                            ->visible(fn (callable $get) => filled($get('gambar_variant')))
                            ->view('components.gambar-variant-preview')
                            ->viewData(fn (callable $get) => [
                                'gambar' => $get('gambar_variant'),
                            ])
                            ->dehydrated(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->grid(2)
                    ->defaultItems(1)
                    ->reorderable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_penawaran'),
                Tables\Columns\TextColumn::make('Buyer.nama_buyer')->label('Buyer'),
                Tables\Columns\TextColumn::make('tanggal_penawaran')->date(),
                Tables\Columns\TextColumn::make('status_penawaran'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPenawaranPenjualans::route('/'),
            'create' => Pages\CreatePenawaranPenjualan::route('/create'),
            'edit' => Pages\EditPenawaranPenjualan::route('/{record}/edit'),
        ];
    }
}
