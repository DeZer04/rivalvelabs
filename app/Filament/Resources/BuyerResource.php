<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuyerResource\Pages;
use App\Filament\Resources\BuyerResource\RelationManagers;
use App\Models\Buyer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BuyerResource extends Resource
{
    protected static ?string $model = Buyer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_buyer')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('alamat_buyer')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telepon_buyer')
                    ->nullable()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email_buyer')
                    ->nullable()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kontak_person_buyer')
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_buyer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat_buyer'),
                Tables\Columns\TextColumn::make('telepon_buyer'),
                Tables\Columns\TextColumn::make('email_buyer'),
                Tables\Columns\TextColumn::make('kontak_person_buyer')
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
            'index' => Pages\ListBuyers::route('/'),
            'create' => Pages\CreateBuyer::route('/create'),
            'edit' => Pages\EditBuyer::route('/{record}/edit'),
        ];
    }
}
