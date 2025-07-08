<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';

    protected $fillable = [
        'nama_karyawan',
        'nik',
        'email',
        'telepon',
        'alamat',
        'divisi_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'status',
        'foto',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}
