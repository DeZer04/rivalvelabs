<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PesananPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'penawaran_penjualan_id',
        'nomor_pesanan',
        'tanggal_pesanan',
        'status_pesanan',
    ];

    public function Buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function PenawaranPenjualan()
    {
        return $this->belongsTo(PenawaranPenjualan::class);
    }

    public function DetailPesananPenjualan()
    {
        return $this->hasMany(DetailPesananPenjualan::class);
    }

    public function pengirimanPenjualans()
    {
        return $this->belongsToMany(
            PengirimanPenjualan::class,
            'pengiriman_pesanan_penjualans',
            'pesanan_penjualan_id',
            'pengiriman_penjualan_id'
        )->withTimestamps();
    }

    public function updateStatusPesanan()
    {
        $total = $this->details->sum('jumlah_item');
        $terkirim = $this->details->sum('jumlah_item_dikirim');

        if ($terkirim == 0) {
            $this->status_pesanan = 'menunggu diproses';
        } elseif ($terkirim < $total) {
            $this->status_pesanan = 'sebagian terproses';
        } else {
            $this->status_pesanan = 'terproses';
        }

        $this->save();
    }

    public static function generateNomorPesanan($buyerId, $tanggal)
    {
        $count = static::count() + 1;
        return 'PO#' . $count;
    }
}
