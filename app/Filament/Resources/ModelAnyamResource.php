<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModelAnyamResource\Pages;
use App\Filament\Resources\ModelAnyamResource\RelationManagers;
use App\Models\ModelAnyam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModelAnyamResource extends Resource
{
    protected static ?string $model = ModelAnyam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Anyam';

    protected static ?string $navigationLabel = 'Model Anyam';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_model_anyam')
                    ->label('Nama Model Anyam')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\FileUpload::make('gambar_model_anyam')
                    ->label('Gambar Model Anyam')
                    ->image()
                    ->required()
                    ->maxSize(1024) // 1 MB
                    ->preserveFilenames()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_model_anyam')
                    ->label('Nama Model Anyam'),
                Tables\Columns\ImageColumn::make('gambar_model_anyam')
                    ->label('Gambar Model Anyam')
                    ->circular()
                    ->size(50),
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
            'index' => Pages\ListModelAnyams::route('/'),
            'create' => Pages\CreateModelAnyam::route('/create'),
            'edit' => Pages\EditModelAnyam::route('/{record}/edit'),
        ];
    }
}
