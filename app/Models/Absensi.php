<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'tanggal',
        'jam',
        'nip',
        'pin',
        'sn',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam' => 'datetime:H:i:s',
    ];

}