<?php

namespace App\Services;

class AbsensiProcessor
{
    public function process(Absensi $absensi)
    {
        $karyawan = Karyawan::where('nip', $absensi->nip)->first();
        if (!$karyawan) return;

        $jamKerja = $karyawan->jamKerjaGroup;
        $setting  = AbsensiSetting::first();

        $rekap = AbsensiRekap::firstOrCreate([
            'tanggal' => $absensi->tanggal,
            'nip'     => $absensi->nip,
        ]);

        if (!$rekap->jam_masuk) {
            $rekap->jam_masuk = $absensi->jam;
        } else {
            $rekap->jam_pulang = $absensi->jam;
        }

        // Hitung keterlambatan
        $rekap->terlambat = max(
            0,
            Carbon::parse($rekap->jam_masuk)
                ->diffInMinutes($jamKerja->jam_masuk, false)
        );

        // Durasi kerja
        if ($rekap->jam_masuk && $rekap->jam_pulang) {
            $rekap->durasi_kerja =
                Carbon::parse($rekap->jam_masuk)
                ->diffInMinutes($rekap->jam_pulang);
        }

        $rekap->status = 'HADIR';
        $rekap->save();
    }
}
