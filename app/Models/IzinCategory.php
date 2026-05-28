<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinCategory extends Model
{
    /** @use HasFactory<\Database\Factories\IzinCategoryFactory> */
    use HasFactory;

    protected $table = 'izin_categories';

    protected $fillable = [
        'nama',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function izinRequests()
    {
        return $this->hasMany(IzinRequest::class);
    }
}
