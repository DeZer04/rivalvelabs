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
                Section::make('Informasi Pengiriman')
                    ->schema([
                        Select::make('buyer_id')
                            ->label('Buyer')
                            ->relationship('buyer', 'nama_buyer')
                            ->required()
                            ->preload()
                            ->searchable(),

                        Select::make('pesananPenjualans')
                            ->label('Pesanan Penjualan')
                            ->multiple() // Many-to-Many
                            ->relationship('pesananPenjualans', 'nomor_pesanan')
                            ->required()
                            ->preload()
                            ->searchable(),

                        TextInput::make('nomor_pengiriman')
                            ->label('Nomor Pengiriman')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),

                        DatePicker::make('tanggal_pengiriman')
                            ->label('Tanggal Pengiriman')
                            ->default(now())
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Detail Pengiriman')
                    ->schema([
                        Repeater::make('DetailPengirimanPenjualan')
                            ->relationship()
                            ->schema([
                                Select::make('item_id')
                                    ->label('Item')
                                    ->relationship('item', 'nama_item')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Select::make('item_variant_id')
                                    ->label('Item Variant')
                                    ->relationship('itemVariant', 'nama_variant')
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('jumlah_item')
                                    ->label('Jumlah Item')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),
                            ])
                            ->columns(3)
                            ->defaultItems(1),
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

                Tables\Columns\TextColumn::make('pesananPenjualans.nomor_pesanan')
                    ->label('Pesanan Terkait')
                    ->badge()
                    ->separator(', '),

                Tables\Columns\TextColumn::make('tanggal_pengiriman')
                    ->label('Tanggal Pengiriman')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
