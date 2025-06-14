<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisAnyam extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jenis_anyam',
    ];

    /**
     * Get the warna anyams associated with the weaving type.
     */
    public function warnaAnyams()
    {
        return $this->hasMany(WarnaAnyam::class);
    }
}
