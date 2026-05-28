<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\IzinRequest;

class AbsensiRekap extends Model
{
    use HasFactory;

    protected $table = 'absensi_rekaps';

    protected $fillable = [
        'tanggal',
        'nip',
        'jam_masuk',
        'jam_pulang',
        'terlambat',
        'pulang_awal',
        'durasi_kerja',
        'durasi_lembur',
        'status',
        'izin_request_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime:H:i:s',
        'jam_pulang' => 'datetime:H:i:s',
    ];

    public function izinRequest()
    {
        return $this->belongsTo(IzinRequest::class);
    }
}
