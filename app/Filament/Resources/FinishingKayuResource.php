<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinishingKayuResource\Pages;
use App\Filament\Resources\FinishingKayuResource\RelationManagers;
use App\Models\FinishingKayu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinishingKayuResource extends Resource
{
    protected static ?string $model = FinishingKayu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Kayu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_finishing_kayu')
                    ->label('Nama Finishing Kayu')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('jenis_kayu_id')
                    ->label('Jenis Kayu')
                    ->options(\App\Models\JenisKayu::pluck('nama_jenis_kayu', 'id'))
                    ->required(),
            ])->columns([
                'sm' => 1,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_finishing_kayu')
                    ->label('Nama Finishing Kayu')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenisKayu.nama_jenis_kayu')
                    ->label('Jenis Kayu')
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
            'index' => Pages\ListFinishingKayus::route('/'),
            'create' => Pages\CreateFinishingKayu::route('/create'),
            'edit' => Pages\EditFinishingKayu::route('/{record}/edit'),
        ];
    }
}
