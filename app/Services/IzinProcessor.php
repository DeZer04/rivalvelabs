<?php

namespace App\Services;

class IzinProcessor 
{
    public function applyToAbsensi(IzinRequest $izin)
    {
        $period = CarbonPeriod::create(
            $izin->tanggal_mulai,
            $izin->tanggal_selesai
        );

        foreach ($period as $tanggal) {
            $rekap = AbsensiRekap::firstOrCreate([
                'tanggal' => $tanggal,
                'nip' => $izin->karyawan->nip,
            ]);

            $rekap->izin_request_id = $izin->id;
            $rekap->status = $izin->izinType->hasil_status_absensi;

            if (!$izin->izinType->hitung_kerja) {
                $rekap->durasi_kerja = 0;
            }

            if (!$izin->izinType->hitung_lembur) {
                $rekap->durasi_lembur = 0;
            }

            $rekap->save();
        }
    }
}