<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKayu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jenis_kayu',
    ];

    /**
     * Get the grades associated with the wood type.
     */
    public function grades()
    {
        return $this->hasMany(GradeKayu::class);
    }

    /**
     * Get the finishes associated with the wood type.
     */
    public function finishes()
    {
        return $this->hasMany(FinishingKayu::class);
    }
}
