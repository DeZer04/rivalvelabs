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
        'jam_kerja_group_id',
    ];

    public function jamKerjaGroup()
    {
        return $this->belongsTo(JamKerjaGroup::class);
    }

    public function izinRequests()
    {
        return $this->hasMany(IzinRequest::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'nip', 'nik');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    
}
