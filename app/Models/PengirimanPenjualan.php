<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_penjualan_id',
        'nomor_pengiriman',
        'tanggal_pengiriman',
    ];

    public function PesananPenjualan()
    {
        return $this->belongsTo(PesananPenjualan::class);
    }

    public function DetailPengirimanPenjualan()
    {
        return $this->hasMany(DetailPengirimanPenjualan::class);
    }
}
