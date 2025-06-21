<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisKayuResource\Pages;
use App\Filament\Resources\JenisKayuResource\RelationManagers;
use App\Models\JenisKayu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisKayuResource extends Resource
{
    protected static ?string $model = JenisKayu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Kayu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_jenis_kayu')
                    ->label('Nama Jenis Kayu')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
            ])->columns([
                'sm' => 1,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_jenis_kayu')
                    ->label('Nama Jenis Kayu')
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
            'index' => Pages\ListJenisKayus::route('/'),
            'create' => Pages\CreateJenisKayu::route('/create'),
            'edit' => Pages\EditJenisKayu::route('/{record}/edit'),
        ];
    }
}
