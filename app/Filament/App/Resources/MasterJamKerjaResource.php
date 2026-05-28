<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\MasterJamKerjaResource\Pages;
use App\Filament\App\Resources\MasterJamKerjaResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\JamKerjaGroup;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
// Tambahkan import berikut:
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
// Import untuk Html jika ingin styling custom:
use Illuminate\Support\HtmlString;
// Atau jika ingin menggunakan komponen Button:
use Filament\Forms\Components\Button;
use Filament\Notifications\Notification;

class MasterJamKerjaResource extends Resource
{
    protected static ?string $model = JamKerjaGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)->schema([
                    Wizard::make([
                        Step::make('Shift / Jam Kerja')
                            ->schema(self::shiftTab()),
                        
                        Step::make('Tidak Scan')
                            ->schema(self::policyTab()),
                        
                        Step::make('Istirahat')
                            ->schema(self::istirahatTab()),
                        
                        Step::make('Lembur')
                            ->schema(self::lemburTab()),
                        
                        Step::make('Pembulatan')
                            ->schema(self::pembulatanTab()),
                    ])
                    
                    ->submitAction(
                        // Gunakan HtmlString bukan Html::button()
                        new HtmlString('<button type="submit" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-400 focus:bg-primary-700 dark:focus:bg-primary-400 focus:ring-offset-primary-700 dark:focus:ring-offset-primary-400">
                            Simpan
                        </button>')
                    )
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListMasterJamKerjas::route('/'),
            'create' => Pages\CreateMasterJamKerja::route('/create'),
            'edit' => Pages\EditMasterJamKerja::route('/{record}/edit'),
        ];
    }

    protected static function shiftTab(): array
    {
        return [
            TextInput::make('kode')
                ->label('Kode')
                ->required(),
            TextInput::make('nama')
                ->label('Nama')
                ->required(),
            
            Grid::make(2)->schema([
                TimePicker::make('jam_masuk')
                    ->label('Jam Masuk')
                    ->required(),
                TimePicker::make('jam_pulang')
                    ->label('Jam Pulang')
                    ->required(),
            ]),

            Grid::make(2)->schema([
                TextInput::make('durasi_sebelum_masuk')
                    ->label('Durasi Sebelum Masuk')
                    ->numeric()
                    ->suffix('menit'),
                TextInput::make('durasi_setelah_masuk')
                    ->label('Durasi Setelah Masuk')
                    ->numeric()
                    ->suffix('menit'),
                TextInput::make('durasi_sebelum_pulang')
                    ->label('Durasi Sebelum Pulang')
                    ->numeric()
                    ->suffix('menit'),
                TextInput::make('durasi_setelah_pulang')
                    ->label('Durasi Setelah Pulang')
                    ->numeric()
                    ->suffix('menit'),
            ]),

            Grid::make(2)->schema([
                TextInput::make('toleransi_terlambat')
                    ->label('Toleransi Terlambat')
                    ->numeric()
                    ->suffix('menit'),
                TextInput::make('toleransi_pulang_awal')
                    ->label('Toleransi Pulang Awal')
                    ->numeric()
                    ->suffix('menit'),
            ]),

            Grid::make(2)->schema([
                TextInput::make('min_half_day')
                    ->label('Minimal Durasi Half Day')
                    ->numeric()
                    ->suffix('menit'),
                TextInput::make('min_full_day')
                    ->label('Minimal Durasi Full Day')
                    ->numeric()
                    ->suffix('menit'),
            ]),
        ];
    }

    protected static function policyTab(): array
    {
        return [
            Fieldset::make('Tidak Scan Masuk Dianggap')
                ->schema([
                    Radio::make('policy.tanpa_scan_masuk')
                        ->options([
                            'none' => 'Tidak ada hukuman',
                            'terlambat' => 'Terlambat',
                            'halfday' => 'Dianggap setengah hari',
                            'alpha' => 'Tidak hadir',
                        ])
                        ->default('none'),

                    TextInput::make('policy.menit_terlambat')
                        ->numeric()
                        ->suffix('menit')
                        ->visible(fn ($get) => $get('policy.tanpa_scan_masuk') === 'terlambat'),
                ]),

            Fieldset::make('Tidak Scan Pulang Dianggap')
                ->schema([
                    Radio::make('policy.tanpa_scan_pulang')
                        ->options([
                            'none' => 'Tidak ada hukuman',
                            'pulang_cepat' => 'Pulang cepat',
                            'halfday' => 'Dianggap setengah hari',
                            'alpha' => 'Tidak hadir',
                        ])
                        ->default('none'),

                    TextInput::make('policy.menit_pulang_cepat')
                        ->numeric()
                        ->suffix('menit')
                        ->visible(fn ($get) => $get('policy.tanpa_scan_pulang') === 'pulang_cepat'),
                ]),
        ];
    }

    protected static function istirahatTab(): array
    {
        return [
            Repeater::make('istirahatRules')
                ->relationship()
                ->schema([
                    TextInput::make('durasi_kerja_min')->numeric()->suffix('menit'),
                    Toggle::make('potong_istirahat'),
                    Toggle::make('tidak_istirahat_jadi_lembur'),
                    TextInput::make('batas_istirahat')->numeric()->suffix('menit'),
                ])
        ];
    }

    protected static function lemburTab(): array
    {
        return [
            Repeater::make('lemburSettings')
                ->relationship()
                ->schema([
                    TextInput::make('minimal_lembur')->numeric()->suffix('menit'),
                    TextInput::make('maksimal_lembur')->numeric()->suffix('menit'),
                    Toggle::make('lembur_libur_rutin'),
                    Toggle::make('lembur_libur_nasional'),
                    Toggle::make('hitung_pakai_index'),
                ])
        ];
    }

    protected static function pembulatanTab(): array
    {
        return [
            Toggle::make('absensiSetting.auto_rounding')
                ->label('Aktifkan Pembulatan'),

            TextInput::make('absensiSetting.rounding_interval')
                ->numeric()
                ->suffix('menit')
                ->visible(fn ($get) => $get('absensiSetting.auto_rounding')),
        ];
    }
}
