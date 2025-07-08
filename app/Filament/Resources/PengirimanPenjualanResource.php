<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengirimanPenjualanResource\Pages;
use App\Filament\Resources\PengirimanPenjualanResource\RelationManagers;
use App\Models\PengirimanPenjualan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengirimanPenjualanResource extends Resource
{
    protected static ?string $model = PengirimanPenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Penjualan';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pengiriman Penjualan')
                    ->schema([
                        Section::make('Informasi Pengiriman')
                            ->schema([
                                Select::make('buyer_id')
                                    ->label('Buyer')
                                    ->relationship('Buyer', 'nama_buyer')
                                    ->required()
                                    ->preload()
                                    ->reactive(),
                            ]),
                        Select::make('pesanan_penjualan_id')
                            ->label('Pesanan Penjualan')
                            ->options(function (callable $get) {

                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('nomor_pengiriman')
                            ->label('Nomor Pengiriman')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('tanggal_pengiriman')
                            ->label('Tanggal Pengiriman')
                            ->required()
                            ->default(now()),
                    ]),
                Section::make('Detail Pengiriman')
                    ->schema([
                        Repeater::make('DetailPengirimanPenjualan')
                            ->relationship()
                            ->schema([
                                Select::make('item_id')
                                    ->label('Item')
                                    ->relationship('Item', 'nama_item')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('item_variant_id')
                                    ->label('Item Variant')
                                    ->relationship('ItemVariant', 'nama_variant')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('jumlah_order')
                                    ->label('Jumlah Order')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1),
                                TextInput::make('jumlah_kirim')
                                    ->label('Jumlah Kirim')
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPengirimanPenjualans::route('/'),
            'create' => Pages\CreatePengirimanPenjualan::route('/create'),
            'edit' => Pages\EditPengirimanPenjualan::route('/{record}/edit'),
        ];
    }
}
