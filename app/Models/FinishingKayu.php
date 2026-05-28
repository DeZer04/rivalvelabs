<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishingKayu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_finishing_kayu',
        'jenis_kayu_id',
    ];

    /**
     * Get the wood type associated with the finish.
     */
    public function jenisKayu()
    {
        return $this->belongsTo(JenisKayu::class);
    }
}
