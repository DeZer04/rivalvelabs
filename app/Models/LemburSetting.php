<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JamKerjaGroup;

class LemburSetting extends Model
{
    use HasFactory;

    protected $table = 'lembur_settings';

    protected $fillable = [
        'jam_kerja_group_id',
        'minimal_lembur',
        'maksimal_lembur',
        'lembur_libur_rutin',
        'lembur_libur_nasional',
        'hitung_pakai_index',
    ];

    protected $casts = [
        'lembur_libur_rutin' => 'boolean',
        'lembur_libur_nasional' => 'boolean',
        'hitung_pakai_index' => 'boolean',
    ];

    public function jamKerjaGroup()
    {
        return $this->belongsTo(JamKerjaGroup::class);
    }
}
