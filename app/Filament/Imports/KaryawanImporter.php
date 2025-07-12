<?php

namespace App\Filament\Imports;

use App\Models\Karyawan;
use Carbon\Carbon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class KaryawanImporter extends Importer
{
    protected static ?string $model = Karyawan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama_karyawan')
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('nik')
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('email')
                ->rules(['nullable', 'email']),

            ImportColumn::make('telepon')
                ->rules(['nullable', 'string']),

            ImportColumn::make('alamat')
                ->rules(['nullable', 'string']),

            ImportColumn::make('divisi_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('jabatan_id')
                ->numeric()
                ->rules(['nullable', 'integer']),

            ImportColumn::make('tanggal_masuk')
                ->rules(['nullable', 'date']),

            ImportColumn::make('tanggal_keluar')
                ->rules(['nullable', 'date']),

            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required', 'in:aktif,nonaktif']),

            ImportColumn::make('foto')
                ->rules(['nullable', 'string']),

            ImportColumn::make('jenis_kelamin')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),

            ImportColumn::make('tanggal_lahir')
                ->rules(['nullable', 'date']),
        ];
    }


    public function resolveRecord(): ?Karyawan
{
    return new Karyawan([
        'nama_karyawan' => $this->data['nama_karyawan'] ?? null,
        'nik' => $this->data['nik'] ?? null,
        'email' => $this->data['email'] ?? null,
        'telepon' => $this->data['telepon'] ?? null,
        'alamat' => $this->data['alamat'] ?? null,
        'divisi_id' => $this->data['divisi_id'] ?? null,
        'jabatan_id' => $this->data['jabatan_id'] ?? null,
        'tanggal_masuk' => $this->parseDate($this->data['tanggal_masuk']),
        'tanggal_keluar' => $this->parseDate($this->data['tanggal_keluar']),
        'status' => $this->data['status'] ?? null,
        'foto' => $this->data['foto'] ?? null,
        'jenis_kelamin' => $this->data['jenis_kelamin'] ?? null,
        'tanggal_lahir' => $this->parseDate($this->data['tanggal_lahir']),
    ]);
}

    public function mutateBeforeFill(array $data): array
    {
        // Normalisasi format tanggal
        foreach (['tanggal_masuk', 'tanggal_keluar', 'tanggal_lahir'] as $field) {
            if (!empty($data[$field])) {
                $data[$field] = $this->parseTanggal($data[$field]);
            }
        }

        return $data;
    }

    protected function parseDate($value): ?string
{
    try {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    } catch (\Exception $e) {
        return null;
    }
}

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor data karyawan telah selesai dan berhasil mengimpor ' .
            number_format($import->successful_rows) . ' ' . str('baris')->plural($import->successful_rows) . '.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diimpor.';
        }

        return $body;
    }
}
