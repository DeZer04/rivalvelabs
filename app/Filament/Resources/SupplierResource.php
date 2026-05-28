<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->rows(2),

                TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->maxLength(255),

                KeyValue::make('kode_supplier')
                    ->label('Kode Supplier')
                    ->keyLabel('Kode')
                    ->valueLabel('Deskripsi')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_supplier')->label('Nama')->searchable()->sortable(),
                TextColumn::make('nomor_telepon')->label('Telepon')->sortable(),
                TextColumn::make('alamat')->label('Alamat')->limit(30),
               TextColumn::make('kode_supplier')
                    ->label('Kode Supplier')
                    ->formatStateUsing(function ($state, $record, $column) {
                        $state = $column->getState(); // 🟢 AMBIL MANUAL DARI RECORD

                        if (is_string($state)) {
                            $state = json_decode($state, true);
                        }

                        if (!is_array($state)) {
                            return '-';
                        }

                        return collect($state)
                            ->keys()
                            ->map(fn($key) => "<span class='inline-flex items-center rounded-full bg-primary-100 px-2 py-0.5 text-xs font-medium text-primary-800 ring-1 ring-inset ring-primary-600/20'>$key</span>")
                            ->implode(' ');
                    })
                    ->html(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->successNotificationTitle('Supplier deleted successfully')
                    ->color('danger'),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
