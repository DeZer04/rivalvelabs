<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Karyawan;
use App\Models\IzinType;
use App\Models\IzinOption;
use App\Models\IzinCategory;
use App\Models\User;
use App\Models\AbsensiRekap;

class IzinRequest extends Model
{
    use HasFactory;

    protected $table = 'izin_requests';

    protected $fillable = [
        'karyawan_id',
        'izin_type_id',
        'izin_option_id',
        'izin_category_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'catatan',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'jam_mulai' => 'datetime:H:i:s',
        'jam_selesai' => 'datetime:H:i:s',
        'approved_at' => 'datetime',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function izinType()
    {
        return $this->belongsTo(IzinType::class);
    }

    public function izinOption()
    {
        return $this->belongsTo(IzinOption::class);
    }

    public function izinCategory()
    {
        return $this->belongsTo(IzinCategory::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function absensiRekaps()
    {
        return $this->hasMany(AbsensiRekap::class);
    }
}
