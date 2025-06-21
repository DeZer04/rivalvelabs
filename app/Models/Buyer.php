<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_buyer',
        'alamat_buyer',
        'telepon_buyer',
        'email_buyer',
        'kontak_person_buyer',
    ];

    public function PenawaranPenjualan()
    {
        return $this->hasMany(PenawaranPenjualan::class);
    }

    public function PesananPenjualan()
    {
        return $this->hasMany(PesananPenjualan::class);
    }

}
