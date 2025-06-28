<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'nomor_telepon',
        'kode_supplier',
    ];

    protected $casts = [
        'kode_supplier' => 'array',
    ];
}
