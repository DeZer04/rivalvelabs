<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\IzinType;
use App\Models\IzinRequest;

class IzinOption extends Model
{
    use HasFactory;

    protected $table = 'izin_options';

    protected $fillable = [
        'izin_type_id',
        'kode',
        'nama',
        'perlu_jam',
        'override_event',
    ];

    protected $casts = [
        'perlu_jam' => 'boolean',
    ];

    public function izinType()
    {
        return $this->belongsTo(IzinType::class);
    }

    public function izinRequests()
    {
        return $this->hasMany(IzinRequest::class);
    }
}
