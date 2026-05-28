<?php

namespace App\Filament\App\Resources\MasterJamKerjaResource\Pages;

use App\Filament\App\Resources\MasterJamKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;


class EditMasterJamKerja extends EditRecord
{
    protected static string $resource = MasterJamKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        DB::transaction(function () use ($data) {

            // 1️⃣ Update JamKerjaGroup (utama)
            $this->record->update([
                'kode' => $data['kode'],
                'nama' => $data['nama'],
                'jam_masuk' => $data['jam_masuk'],
                'jam_pulang' => $data['jam_pulang'],
                'durasi_sebelum_masuk' => $data['durasi_sebelum_masuk'],
                'durasi_setelah_masuk' => $data['durasi_setelah_masuk'],
                'durasi_sebelum_pulang' => $data['durasi_sebelum_pulang'],
                'durasi_setelah_pulang' => $data['durasi_setelah_pulang'],
                'toleransi_terlambat' => $data['toleransi_terlambat'],
                'toleransi_pulang_awal' => $data['toleransi_pulang_awal'],
                'min_half_day' => $data['min_half_day'],
                'min_full_day' => $data['min_full_day'],
            ]);

            // 2️⃣ Policy (tidak scan)
            if (isset($data['policy'])) {
                $this->record->jamKerjaPolicies()
                    ->updateOrCreate([], $data['policy']);
            }

            // 3️⃣ Pembulatan
            if (isset($data['absensiSetting'])) {
                $this->record->absensiSetting()
                    ->updateOrCreate([], $data['absensiSetting']);
            }
        });

        // ⛔ Jangan biarkan Filament save ulang
        return [];
    }
}
