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

    public function PengirimanPenjualan()
    {
        return $this->hasMany(PengirimanPenjualan::class);
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
        if (!$buyerId || !$tanggal) return null;

        $tanggalFormat = \Carbon\Carbon::parse($tanggal)->format('Y-m-d');
        $tanggalString = \Carbon\Carbon::parse($tanggal)->format('Ymd');
        $prefix = 'PO';

        $buyer = \App\Models\Buyer::find($buyerId);
        $buyerCode = 'BYR';

        if ($buyer && $buyer->nama_buyer) {
            $nama = strtoupper($buyer->nama_buyer);
            $buyerCode = substr(preg_replace('/[^A-Z]/', '', $nama), 0, 3);
            if (strlen($buyerCode) < 3) {
                $buyerCode = str_pad($buyerCode, 3, 'X');
            }
        }

        $count = static::where('buyer_id', $buyerId)
            ->whereDate('tanggal_pesanan', $tanggalFormat)
            ->count() + 1;

        $urut = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "{$prefix}/{$tanggalString}/{$buyerCode}/{$urut}";
    }
}
