<?php

namespace App\Filament\Imports;

use App\Models\Absensi;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class AbsensiImporter extends Importer
{
    protected static ?string $model = Absensi::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tanggal')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->castStateUsing(function ($state) {
                    try {
                        return \Carbon\Carbon::createFromFormat('d-m-Y', $state)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return null; // atau throw error
                    }
                }),
            ImportColumn::make('jam')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('nip')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('pin')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('sn')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
                
        ];
    }

    public function resolveRecord(): ?Absensi
    {
        // return Absensi::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Absensi();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your absensi import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
