<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\IzinOption;
use App\Models\IzinRequest;

class IzinType extends Model
{
    use HasFactory;

    protected $table = 'izin_types';

    protected $fillable = [
        'kode',
        'nama',
        'perlu_jam',
        'boleh_setengah_hari',
        'boleh_multi_hari',
        'hasil_status_absensi',
        'hitung_kerja',
        'hitung_lembur',
        'potong_jatah_cuti',
        'aktif',
    ];

    protected $casts = [
        'perlu_jam' => 'boolean',
        'boleh_setengah_hari' => 'boolean',
        'boleh_multi_hari' => 'boolean',
        'hitung_kerja' => 'boolean',
        'hitung_lembur' => 'boolean',
        'potong_jatah_cuti' => 'boolean',
        'aktif' => 'boolean',
    ];

    public function izinOptions()
    {
        return $this->hasMany(IzinOption::class);
    }

    public function izinRequests()
    {
        return $this->hasMany(IzinRequest::class);
    }
}
