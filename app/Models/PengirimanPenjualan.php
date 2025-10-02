<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'nomor_pengiriman',
        'tanggal_pengiriman',
    ];

    // Relasi ke Buyer
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    // Relasi Many-to-Many ke PesananPenjualan
    public function pesananPenjualans()
    {
        return $this->belongsToMany(
            PesananPenjualan::class,
            'pengiriman_pesanan_penjualans', // nama tabel pivot
            'pengiriman_penjualan_id',
            'pesanan_penjualan_id'
        )->withTimestamps();
    }

    // Relasi One-to-Many ke DetailPengirimanPenjualan
    public function detailPengirimanPenjualan()
    {
        return $this->hasMany(DetailPengirimanPenjualan::class);
    }
}
