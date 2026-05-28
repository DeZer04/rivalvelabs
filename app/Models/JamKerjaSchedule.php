<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JamKerjaGroup;

class JamKerjaSchedule extends Model
{
    use HasFactory;

    protected $table = 'jam_kerja_schedules';

    protected $fillable = [
        'jam_kerja_group_id',
        'hari',
        'libur',
    ];

    protected $casts = [
        'libur' => 'boolean',
    ];

    public function jamKerjaGroup()
    {
        return $this->belongsTo(JamKerjaGroup::class);
    }
}
