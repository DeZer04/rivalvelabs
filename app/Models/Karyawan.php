<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = 'karyawans';

    protected $fillable = [
        'nama_karyawan',
        'nik',
        'email',
        'telepon',
        'alamat',
        'divisi_id',
        'jabatan_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'status',
        'foto',
        'jenis_kelamin',
        'tanggal_lahir',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
