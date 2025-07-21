<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\KaryawanResource\Pages;
use App\Filament\App\Resources\KaryawanResource\RelationManagers;
use App\Filament\Imports\KaryawanImporter;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
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
                Forms\Components\ToggleButtons::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        true => 'Laki-laki',
                        false => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->nullable()
                    ->label('Tanggal Lahir'),
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
                Forms\Components\Select::make('jabatan_id')
                    ->relationship('jabatan', 'nama_jabatan')
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama_jabatan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('divisi_id')
                            ->relationship('divisi', 'nama_divisi')
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_divisi')
                                    ->required()
                                    ->maxLength(255),
                            ]),
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
            ->headerActions([
                ImportAction::make()
                    ->importer(KaryawanImporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('nik')->label('NIK')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('foto')->label('Foto')->circular(),
                Tables\Columns\TextColumn::make('nama_karyawan')->label('Nama Karyawan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->colors([
                        'primary' => fn ($state) => $state === 1,      // Laki-laki: biru
                        'warning' => fn ($state) => $state === 0,        // Perempuan: pink
                    ])
                    ->formatStateUsing(fn (bool $state) => $state ? 'Laki-laki' : 'Perempuan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('umur')
                    ->label('Umur')
                    ->state(function ($record) {
                        return \Carbon\Carbon::parse($record->tanggal_lahir)->age . ' tahun';
                    }),
                Tables\Columns\TextColumn::make('divisi.nama_divisi')->label('Divisi')->sortable(),
		        Tables\Columns\TextColumn::make('jabatan.nama_jabatan')->label('Jabatan')->sortable(),
                Tables\Columns\TextColumn::make('lamabekerja')
                    ->label('Lama Bekerja')
                    ->state(function ($record) {
                        return $record->tanggal_masuk
                            ? \Carbon\Carbon::parse($record->tanggal_masuk)->diffForHumans()
                            : '-';
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'aktif',
                        'danger' => 'nonaktif',
                    ]),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ]),
                Tables\Filters\SelectFilter::make('divisi_id')
                    ->relationship('divisi', 'nama_divisi')
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
