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
use phpDocumentor\Reflection\DocBlock\Tags\See;

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
                    Grid::make([
                        'default' => 5,
                        'md' => 5,
                    ])
                        ->schema([
                            Select::make('item_category_id')
                                ->label('Kategori')
                                ->relationship('category', 'nama_item_category') // model Item
                                ->createOptionForm([
                                    TextInput::make('nama_item_category')->label('Nama Kategori')->required(),
                                ])
                                ->required(),

                            Select::make('sub_category_id')
                                ->label('Sub Kategori')
                                ->relationship('subCategory', 'nama_sub_category')
                                ->createOptionForm([
                                    Select::make('item_category_id')
                                        ->label('Kategori')
                                        ->relationship('itemCategory', 'nama_item_category')
                                        ->required(),

                                    TextInput::make('nama_sub_category')
                                        ->required()
                                        ->label('Nama Sub Kategori'),
                                ])
                                ->reactive()
                                ->required(),


                            TextInput::make('nama_item')
                                ->label('Nama Item')
                                ->required()
                                ->columnSpan(3),
                    ]),

                    // Master Gambar + Dimensi W/D/H di kanan atas
                    Grid::make([
                        'default' => 8,
                        'md' => 8,
                    ])
                        ->schema([
                            // Gambar item (kiri, 6/7)
                            FileUpload::make('master_gambar_item')
                                ->image()
                                ->label('Gambar Utama Item')
                                ->required()
                                ->columnSpan(7),

                            // Dimensi W/D/H (kanan, 1/7 - vertical stack)
                            Grid::make(1)
                                ->schema([
                                    TextInput::make('width')
                                        ->label('Width')
                                        ->suffix(' cm')
                                        ->numeric(),

                                    TextInput::make('depth')
                                        ->label('Depth')
                                        ->suffix(' cm')
                                        ->numeric(),

                                    TextInput::make('height')
                                        ->label('Height')
                                        ->suffix(' cm')
                                        ->numeric(),

                                    TextInput::make('seat_height')
                                        ->label('Seat Height')
                                        ->suffix(' cm')
                                        ->numeric(),

                                ])
                                ->columnSpan(1),
                        ]),

                    // Repeater Varian
                    Repeater::make('variants')
                        ->relationship('variants')
                        ->label('Varian Item')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('nama_variant')->required()->label('Nama Varian'),

                                FileUpload::make('gambar_item')
                                    ->image()
                                    ->multiple(),

                                Textarea::make('deskripsi_item')->columnSpanFull(),

                                // Jenis Kayu
                                Select::make('jenis_kayu_id')
                                    ->label('Jenis Kayu')
                                    ->relationship('jenisKayu', 'nama_jenis_kayu')
                                    ->createOptionModalHeading('Tambah Jenis Kayu Baru')
                                    ->createOptionForm([
                                        TextInput::make('nama_jenis_kayu')->required(),
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
                                    ])
                                    ->required(),

                                // Jenis Anyam
                                Select::make('jenis_anyam_id')
                                    ->label('Jenis Anyam')
                                    ->relationship('jenisAnyam', 'nama_jenis_anyam')
                                    ->createOptionModalHeading('Tambah Jenis Anyam Baru')
                                    ->createOptionForm([
                                        TextInput::make('nama_jenis_anyam')->required(),
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
                                    ])
                                    ->required(),

                                // Model Anyam
                                Select::make('model_anyam_id')
                                    ->label('Model Anyam')
                                    ->relationship('modelAnyam', 'nama_model_anyam')
                                    ->createOptionModalHeading('Tambah Model Anyam Baru')
                                    ->createOptionForm([
                                        TextInput::make('nama_model_anyam')->required(),
                                        FileUpload::make('gambar_model_anyam')->image(),
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

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('master_gambar_item')
                    ->label('Gambar Utama')
                    ->size(100)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_item')->searchable(isIndividual: true)->sortable(),
                Tables\Columns\TextColumn::make('category.nama_item_category')->label('Kategori'),
                Tables\Columns\TextColumn::make('subCategory.nama_sub_category')->label('Sub Kategori'),
                Tables\Columns\TextColumn::make('variants.nama_variant')->label('Varian'),
            ])
            ->filters([])
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
