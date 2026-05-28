<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $fillable = [
        'nama_penilaian',
        'tahun',
        'periode',
        'group_kriteria_id',
        'penilai_id',
    ];

    public function groupKriteria()
    {
        return $this->belongsTo(GroupKriteria::class);
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    public function penilaianKaryawans()
    {
        return $this->hasMany(PenilaianKaryawan::class);
    }
}
