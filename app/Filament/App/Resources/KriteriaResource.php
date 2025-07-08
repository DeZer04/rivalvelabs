<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\KriteriaResource\Pages;
use App\Models\GroupKriteria;
use App\Models\Kriteria;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KriteriaResource extends Resource
{
    protected static ?string $model = GroupKriteria::class; // GUNAKAN model GroupKriteria

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Group dan Kriteria';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Group Kriteria')
                    ->schema([
                        Forms\Components\TextInput::make('nama_group_kriteria')
                            ->label('Nama Group Kriteria')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),

                Section::make('List Kriteria')
                    ->schema([
                        Forms\Components\Repeater::make('kriterias')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('nama_kriteria')
                                    ->label('Nama Kriteria')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Select::make('is_benefit')
                                    ->label('Jenis Kriteria')
                                    ->options([
                                        true => 'Menguntungkan',
                                        false => 'Merugikan',
                                    ])
                                    ->default(true)
                                    ->required(),
                            ])
                            ->grid(2),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_group_kriteria')
                    ->label('Nama Group'),
                Tables\Columns\IconColumn::make('is_calculated')
                    ->boolean()
                    ->label('Dihitung?'),
                Tables\Columns\TextColumn::make('kriterias_count')
                    ->counts('kriterias')
                    ->label('Jumlah Kriteria'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKriterias::route('/'),
            'create' => Pages\CreateKriteria::route('/create'),
            'edit' => Pages\EditKriteria::route('/{record}/edit'),
        ];
    }
}
