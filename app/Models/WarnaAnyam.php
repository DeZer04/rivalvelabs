<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarnaAnyam extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_warna_anyam',
        'jenis_anyam_id',
    ];

    /**
     * Get the jenis anyam associated with the warna anyam.
     */
    public function jenisAnyam()
    {
        return $this->belongsTo(JenisAnyam::class);
    }
}
