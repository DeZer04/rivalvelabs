<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisAnyamResource\Pages;
use App\Filament\Resources\JenisAnyamResource\RelationManagers;
use App\Models\JenisAnyam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\View\TablesRenderHook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisAnyamResource extends Resource
{
    protected static ?string $model = JenisAnyam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Anyam';

    protected static ?string $navigationLabel = 'Jenis Anyam';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_jenis_anyam')
                    ->label('Nama Jenis Anyam')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('nama_jenis_anyam')
                    ->label('Nama Jenis Anyam')
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
            'index' => Pages\ListJenisAnyams::route('/'),
            'create' => Pages\CreateJenisAnyam::route('/create'),
            'edit' => Pages\EditJenisAnyam::route('/{record}/edit'),
        ];
    }
}
