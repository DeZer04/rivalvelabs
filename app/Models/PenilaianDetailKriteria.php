<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianDetailKriteria extends Model
{
    protected $fillable = [
        'penilaian_karyawan_id',
        'kriteria_id',
        'nilai',
    ];

    public function penilaianKaryawan()
    {
        return $this->belongsTo(PenilaianKaryawan::class, 'penilaian_karyawan_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
