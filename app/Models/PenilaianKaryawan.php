<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianKaryawan extends Model
{
    protected $fillable = [
        'penilaian_id',
        'karyawan_id',
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function detailKriterias()
    {
        return $this->hasMany(PenilaianDetailKriteria::class, 'penilaian_karyawan_id');
    }
}
