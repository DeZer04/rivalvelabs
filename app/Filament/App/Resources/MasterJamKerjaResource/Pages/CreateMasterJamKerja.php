<?php

namespace App\Filament\App\Resources\MasterJamKerjaResource\Pages;

use App\Filament\App\Resources\MasterJamKerjaResource;
use App\Models\JamKerjaGroup;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model; // ⭐ WAJIB
use Illuminate\Support\Facades\DB;

class CreateMasterJamKerja extends CreateRecord
{
    protected static string $resource = MasterJamKerjaResource::class;

    protected static bool $canCreateAnother = false;

    protected function getFormActions(): array
    {
        return [];
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {

            $jamKerja = JamKerjaGroup::create([
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

            if (!empty($data['policy'])) {
                $jamKerja->jamKerjaPolicies()->create($data['policy']);
            }

            if (!empty($data['absensiSetting'])) {
                $jamKerja->absensiSetting()->create($data['absensiSetting']);
            }

            return $jamKerja; // ⭐ Eloquent Model
        });
    }
}
