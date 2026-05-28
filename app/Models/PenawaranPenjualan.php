<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenawaranPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'nomor_penawaran',
        'tanggal_penawaran',
        'status_penawaran',
    ];

    public function Buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function DetailPenawaranPenjualan()
    {
        return $this->hasMany(DetailPenawaranPenjualan::class);
    }

    public function PesananPenjualan()
    {
        return $this->hasOne(PesananPenjualan::class);
    }

    public static function generateNomorPenawaran($buyerId, $tanggal)
    {
        if (!$buyerId || !$tanggal) return null;

        $tanggal = \Carbon\Carbon::parse($tanggal)->format('Y-m-d');

        $buyer = \App\Models\Buyer::find($buyerId);
        $buyerCode = 'BYR';

        if ($buyer && $buyer->nama_buyer) {
            $nama = strtoupper($buyer->nama_buyer);
            $buyerCode = substr(preg_replace('/[^A-Z]/', '', $nama), 0, 3);
            if (strlen($buyerCode) < 3) {
                $buyerCode = str_pad($buyerCode, 3, 'X');
            }
        }

        $tgl = \Carbon\Carbon::parse($tanggal)->format('Ymd');
        $count = static::where('buyer_id', $buyerId)
            ->whereDate('tanggal_penawaran', $tanggal)
            ->count() + 1;

        return sprintf('RIV/INQ/%s/%s/%03d', $buyerCode, $tgl, $count);
    }

}
