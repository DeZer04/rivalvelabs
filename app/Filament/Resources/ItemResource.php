<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\FinishingKayu;
use App\Models\GradeKayu;
use App\Models\Item;
use App\Models\ItemCategories;
use App\Models\JenisAnyam;
use App\Models\JenisKayu;
use App\Models\WarnaAnyam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    // Kategori & Nama Item
                    Grid::make(2)->schema([
                        Select::make('item_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'nama_item_category')
                            ->createOptionForm([
                                TextInput::make('nama_item_category')->required()->label('Nama Kategori'),
                                Placeholder::make('existing_data')
                                    ->label('Data Kategori yang Sudah Ada')
                                    ->content(fn () => ItemCategories::pluck('nama_item_category')->implode(', '))
                                    ->columnSpanFull(),
                            ])
                            ->required(),

                        TextInput::make('nama_item')->label('Nama Item')->required(),
                    ]),

                    // Dimensi W x D x H
                    Grid::make(3)->schema([
                        TextInput::make('width')->numeric()->label('W'),
                        TextInput::make('depth')->numeric()->label('D'),
                        TextInput::make('height')->numeric()->label('H'),
                    ]),

                    // Repeater Varian
                    Repeater::make('variants')
                        ->relationship('variants')
                        ->label('Varian Item')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('nama_variant')->required()->label('Nama Varian'),

                                FileUpload::make('gambar_item')->image(),

                                Textarea::make('deskripsi_item')->columnSpanFull(),

                                // Jenis Kayu
                                Select::make('jenis_kayu_id')
                                    ->label('Jenis Kayu')
                                    ->relationship('jenisKayu', 'nama_jenis_kayu')
                                    ->createOptionModalHeading('Tambah Jenis Kayu Baru')
                                    ->createOptionForm([
                                        TextInput::make('nama_jenis_kayu')->required(),
                                        Placeholder::make('existing_data')
                                            ->label('Data Jenis Kayu yang Sudah Ada')
                                            ->content(fn () => JenisKayu::pluck('nama_jenis_kayu')->implode(', '))
                                            ->columnSpanFull(),
                                    ])
                                    ->required(),

                                // Grade Kayu
                                Select::make('grade_kayu_id')
                                    ->label('Grade Kayu')
                                    ->relationship('gradeKayu', 'nama_grade_kayu')
                                    ->createOptionModalHeading('Tambah Grade Kayu Baru')
                                    ->createOptionForm([
                                        Select::make('jenis_kayu_id')
                                            ->relationship('jenisKayu', 'nama_jenis_kayu')
                                            ->required(),
                                        TextInput::make('nama_grade_kayu')->required(),
                                        Placeholder::make('existing_data')
                                            ->label('Data Grade Kayu yang Sudah Ada')
                                            ->content(fn () => GradeKayu::pluck('nama_grade_kayu')->implode(', '))
                                            ->columnSpanFull(),
                                    ])
                                    ->required(),

                                // Finishing Kayu
                                Select::make('finishing_kayu_id')
                                    ->label('Finishing Kayu')
                                    ->relationship('finishingKayu', 'nama_finishing_kayu')
                                    ->createOptionModalHeading('Tambah Finishing Kayu Baru')
                                    ->createOptionForm([
                                        Select::make('jenis_kayu_id')
                                            ->relationship('jenisKayu', 'nama_jenis_kayu')
                                            ->required(),
                                        TextInput::make('nama_finishing_kayu')->required(),
                                        Placeholder::make('existing_data')
                                            ->label('Data Finishing Kayu yang Sudah Ada')
                                            ->content(fn () => FinishingKayu::pluck('nama_finishing_kayu')->implode(', '))
                                            ->columnSpanFull(),
                                    ])
                                    ->required(),

                                // Jenis Anyam
                                Select::make('jenis_anyam_id')
                                    ->label('Jenis Anyam')
                                    ->relationship('jenisAnyam', 'nama_jenis_anyam')
                                    ->createOptionModalHeading('Tambah Jenis Anyam Baru')
                                    ->createOptionForm([
                                        TextInput::make('nama_jenis_anyam')->required(),
                                        Placeholder::make('existing_data')
                                            ->label('Data Jenis Anyam yang Sudah Ada')
                                            ->content(fn () => JenisAnyam::pluck('nama_jenis_anyam')->implode(', '))
                                            ->columnSpanFull(),
                                    ])
                                    ->required(),

                                // Warna Anyam
                                Select::make('warna_anyam_id')
                                    ->label('Warna Anyam')
                                    ->relationship('warnaAnyam', 'nama_warna_anyam')
                                    ->createOptionModalHeading('Tambah Warna Anyam Baru')
                                    ->createOptionForm([
                                        Select::make('jenis_anyam_id')
                                            ->relationship('jenisAnyam', 'nama_jenis_anyam')
                                            ->required(),
                                        TextInput::make('nama_warna_anyam')->required(),
                                        Placeholder::make('existing_data')
                                            ->label('Data Warna Anyam yang Sudah Ada')
                                            ->content(fn () => WarnaAnyam::pluck('nama_warna_anyam')->implode(', '))
                                            ->columnSpanFull(),
                                    ])
                                    ->required(),

                                TextInput::make('harga')->numeric()->required(),
                            ]),
                        ])
                        ->columns(2)
                        ->grid(2)
                        ->deletable(true)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['nama_variant'] ?? null),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_item')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.nama_item_category')->label('Kategori'),
                Tables\Columns\TextColumn::make('variants.nama_variant')->label('Varian'),
            ])
            ->filters([])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
