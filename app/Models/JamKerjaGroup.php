<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JamKerjaSchedule;
use App\Models\Karyawan;
use App\Models\IstirahatRule;
use App\Models\LemburSetting;
use App\Models\JamKerjaPolicy;

class JamKerjaGroup extends Model
{
    use HasFactory;

    protected $table = 'jam_kerja_groups';

    protected $fillable = [
        'nama',
        'kode',
        'jam_masuk',
        'jam_pulang',
        'jenis',
        'durasi_sebelum_masuk',
        'durasi_setelah_masuk',
        'durasi_sebelum_pulang',
        'durasi_setelah_pulang',
        'toleransi_terlambat',
        'toleransi_pulang_awal',
        'min_half_day',
        'min_full_day',
        'aktif',
    ];

    protected $casts = [
        'jam_masuk' => 'datetime:H:i:s',
        'jam_pulang' => 'datetime:H:i:s',
        'aktif' => 'boolean',
    ];

    public function jamKerjaSchedules()
    {
        return $this->hasMany(JamKerjaSchedule::class);
    }

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function istirahatRules()
    {
        return $this->hasMany(IstirahatRule::class);
    }

    public function lemburSettings()
    {
        return $this->hasMany(LemburSetting::class);
    }

    public function jamKerjaPolicies()
    {
        return $this->hasMany(JamKerjaPolicy::class);
    }
}