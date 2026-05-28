<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarnaAnyamResource\Pages;
use App\Filament\Resources\WarnaAnyamResource\RelationManagers;
use App\Models\WarnaAnyam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WarnaAnyamResource extends Resource
{
    protected static ?string $model = WarnaAnyam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Anyam';

    protected static ?string $navigationLabel = 'Warna Anyam';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenis_anyam_id')
                    ->label('Jenis Anyam')
                    ->options(\App\Models\JenisAnyam::pluck('nama_jenis_anyam', 'id'))
                    ->required(),

                Forms\Components\TextInput::make('nama_warna_anyam')
                    ->label('Nama Warna Anyam')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jenisAnyam.nama_jenis_anyam')
                    ->label('Jenis Anyam')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_warna_anyam')
                    ->label('Nama Warna Anyam')
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
            'index' => Pages\ListWarnaAnyams::route('/'),
            'create' => Pages\CreateWarnaAnyam::route('/create'),
            'edit' => Pages\EditWarnaAnyam::route('/{record}/edit'),
        ];
    }
}
