<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Absensi;
use Filament\Forms;
use Carbon\Carbon;

class LaporanAbsensi extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Absensi';
    protected static string $view = 'filament.app.pages.laporan-absensi';

    public function getDailyQuery(array $filter = [])
    {
        return Absensi::query()
            ->withoutGlobalScopes()
            ->selectRaw("
                CONCAT(tanggal, '-', nip) as id,
                tanggal,
                nip,
                MIN(jam) as scan_masuk,
                MAX(jam) as scan_keluar
            ")
            ->with('karyawan')
            ->when($filter['dari'] ?? null, fn($q, $d) =>
                $q->where('tanggal', '>=', $d)
            )
            ->when($filter['sampai'] ?? null, fn($q, $s) =>
                $q->where('tanggal', '<=', $s)
            )
            ->groupBy('tanggal', 'nip')
            ->orderBy('nip')
            ->orderBy('tanggal');
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(function () {
                $filters = $this->tableFilters['range_tanggal'] ?? [];
                return $this->getDailyQuery($filters);
            })
            ->columns([
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jam_masuk')
                    ->label('Scan Masuk')
                    ->formatStateUsing(function ($record) {
                        $jamMasuk = $record->jam_masuk;
                        if (!$jamMasuk) return '-';
                        
                        // Tambahkan indikator terlambat
                        $terlambat = $record->terlambat;
                        if ($terlambat > 0) {
                            return $jamMasuk . ' ⚠️';
                        }
                        return $jamMasuk;
                    }),

                Tables\Columns\TextColumn::make('jam_keluar')
                    ->label('Scan Keluar')
                    ->formatStateUsing(function ($record) {
                        $jamKeluar = $record->jam_keluar;
                        if (!$jamKeluar) return '-';
                        
                        // Tambahkan indikator pulang cepat
                        $pulangCepat = $record->pulang_cepat;
                        if ($pulangCepat > 0) {
                            return $jamKeluar . ' ⚠️';
                        }
                        return $jamKeluar;
                    }),

                Tables\Columns\TextColumn::make('durasi')
                    ->label('Durasi'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->color(fn ($record) => 
                        match(true) {
                            str_contains($record->status ?? '', 'Tidak Hadir') => 'danger',
                            str_contains($record->status ?? '', 'Setengah Hari') => 'warning',
                            str_contains($record->status ?? '', 'Terlambat') => 'warning',
                            str_contains($record->status ?? '', 'Pulang Cepat') => 'warning',
                            default => 'success'
                        }
                    ),

                Tables\Columns\TextColumn::make('terlambat')
                    ->label('Terlambat (menit)')
                    ->formatStateUsing(fn ($state) => 
                        $state > 0 ? $state . ' menit' : '-'
                    )
                    ->color(fn ($state) => 
                        $state > 0 ? 'danger' : 'gray'
                    ),

                Tables\Columns\TextColumn::make('pulang_cepat')
                    ->label('Pulang Cepat (menit)')
                    ->formatStateUsing(fn ($state) => 
                        $state > 0 ? $state . ' menit' : '-'
                    )
                    ->color(fn ($state) => 
                        $state > 0 ? 'warning' : 'gray'
                    ),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->default('-'),
            ])
            ->filters([
                Tables\Filters\Filter::make('range_tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->required(),

                        Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->required(),
                    ])
                    ->query(function ($query, $data) {
                        if (!empty($data['dari'])) {
                            $query->where('tanggal', '>=', $data['dari']);
                        }
                        if (!empty($data['sampai'])) {
                            $query->where('tanggal', '<=', $data['sampai']);
                        }
                        return $query;
                    }),
            ])
            ->defaultSort('tanggal', 'desc');
    }

}