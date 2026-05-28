<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JamKerjaGroup;

class IstirahatRule extends Model
{
    use HasFactory;

    protected $table = 'istirahat_rules';

    protected $fillable = [
        'jam_kerja_group_id',
        'durasi_kerja_min',
        'potong_istirahat',
        'tidak_istirahat_jadi_lembur',
        'batas_istirahat',
    ];

    protected $casts = [
        'tidak_istirahat_jadi_lembur' => 'boolean',
    ];

    public function jamKerjaGroup()
    {
        return $this->belongsTo(JamKerjaGroup::class);
    }
}
