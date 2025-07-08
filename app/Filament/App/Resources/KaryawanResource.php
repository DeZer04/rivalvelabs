<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\KaryawanResource\Pages;
use App\Filament\App\Resources\KaryawanResource\RelationManagers;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_karyawan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nik')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telepon')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\Textarea::make('alamat')
                    ->maxLength(65535),
                Forms\Components\Select::make('divisi_id')
                    ->relationship('divisi', 'nama_divisi')
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama_divisi')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->default(now()),
                Forms\Components\DatePicker::make('tanggal_keluar'),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('karyawan-foto')
                    ->maxSize(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_karyawan')->label('Nama Karyawan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nik')->label('NIK')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('telepon')->label('Telepon')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat')->limit(30),
                Tables\Columns\TextColumn::make('divisi.nama')->label('Divisi')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')->label('Tanggal Masuk')->date()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_keluar')->label('Tanggal Keluar')->date()->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                Tables\Columns\ImageColumn::make('foto')->label('Foto')->circular(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
