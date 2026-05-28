<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiSetting extends Model
{
    protected $table = 'absensi_settings';
    
    protected $fillable = [
        'jam_kerja_group_id',
        'durasi_kerja_menit',
        'toleransi_keterlambatan',
        'toleransi_pulang_cepat',
        'auto_rounding',
        'rounding_interval'
    ];

    protected $casts = [
        'auto_rounding' => 'boolean'
    ];

    public function jamKerjaGroup()
    {
        return $this->belongsTo(JamKerjaGroup::class);
    }
}