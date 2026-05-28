<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AbsensiResource\Pages;
use App\Filament\App\Resources\AbsensiResource\RelationManagers;
use App\Models\Absensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ImportAction;
use App\Filament\Imports\AbsensiImporter;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Absensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(AbsensiImporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d-m-Y')
                    ->label('Tanggal'),
                Tables\Columns\TextColumn::make('jam')
                    ->time('H:i:s')
                    ->label('Jam'),
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP'),
                Tables\Columns\TextColumn::make('pin')
                    ->label('PIN'),
                Tables\Columns\TextColumn::make('sn')
                    ->label('SN'),
            ])
            ->filters([
                
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
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
