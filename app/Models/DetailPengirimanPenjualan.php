<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengirimanPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengiriman_penjualan_id',
        'item_id',
        'jumlah_item',
    ];

    public function PengirimanPenjualan()
    {
        return $this->belongsTo(PengirimanPenjualan::class, 'pengiriman_penjualan_id');
    }

    public function Item()
    {
        return $this->belongsTo(Item::class);
    }
}
