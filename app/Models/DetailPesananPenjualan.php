<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_penjualan_id',
        'item_id',
        'jumlah_item',
        'jumlah_item_dikirim',
        'harga_satuan',
        'total_harga',
        'keterangan',
    ];

    public function PesananPenjualan()
    {
        return $this->belongsTo(PesananPenjualan::class, 'pesanan_penjualan_id');
    }

    public function Item()
    {
        return $this->belongsTo(Item::class);
    }

    public function ItemVariant()
    {
        return $this->belongsTo(ItemVariant::class, 'item_id');
    }
}
