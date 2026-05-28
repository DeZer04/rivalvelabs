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

    public function mutateBeforeFill(array $data): array
    {
        foreach (['tanggal_masuk', 'tanggal_keluar', 'tanggal_lahir'] as $field) {
            if (!empty($data[$field])) {
                $data[$field] = $this->parseTanggal($data[$field]);
            }
        }

        return $data;
    }

    protected function parseTanggal($value): ?string
    {
        $formats = ['d F Y', 'd M Y', 'Y-m-d', 'd/m/Y', 'd-m-Y'];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Exception $e) {
                // continue to next format
            }
        }

        // fallback to Carbon::parse()
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function resolveRecord(): ?Karyawan
    {
        // Selalu buat record baru (jangan isi data manual di sini)
        return new Karyawan();
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
