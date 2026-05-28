<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeKayuResource\Pages;
use App\Filament\Resources\GradeKayuResource\RelationManagers;
use App\Models\GradeKayu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GradeKayuResource extends Resource
{
    protected static ?string $model = GradeKayu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Kayu';

    protected static ?string $navigationLabel = 'Grade Kayu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_grade_kayu')
                    ->label('Nama Grade Kayu')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('jenis_kayu_id')
                    ->label('Jenis Kayu')
                    ->options(\App\Models\JenisKayu::pluck('nama_jenis_kayu', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_grade_kayu')
                    ->label('Nama Grade Kayu')
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
            'index' => Pages\ListGradeKayus::route('/'),
            'create' => Pages\CreateGradeKayu::route('/create'),
            'edit' => Pages\EditGradeKayu::route('/{record}/edit'),
        ];
    }
}
