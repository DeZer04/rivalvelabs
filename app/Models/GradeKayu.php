<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeKayu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_grade_kayu',
        'jenis_kayu_id',
    ];

    /**
     * Get the wood type associated with the grade.
     */
    public function jenisKayu()
    {
        return $this->belongsTo(JenisKayu::class);
    }
}
