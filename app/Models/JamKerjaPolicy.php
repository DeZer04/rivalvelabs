<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JamKerjaGroup;

class JamKerjaPolicy extends Model
{
    use HasFactory;

    protected $table = 'jam_kerja_policies';

    protected $fillable = [
        'jam_kerja_group_id',
        'tanpa_scan_masuk',
        'menit_terlambat',
        'tanpa_scan_pulang',
        'menit_pulang_cepat',
    ];

    public function jamKerjaGroup()
    {
        return $this->belongsTo(JamKerjaGroup::class);
    }
}
