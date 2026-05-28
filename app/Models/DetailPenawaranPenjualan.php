<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenawaranPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penawaran_penjualan_id',
        'item_id',
        'jumlah_item',
        'harga_satuan',
        'diskon',
        'total_harga',
        'keterangan',
    ];

    public function PenawaranPenjualan()
    {
        return $this->belongsTo(PenawaranPenjualan::class);
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
