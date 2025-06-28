<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_category_id',
        'sub_category_id',
        'master_gambar_item',
        'nama_item',
        'width',
        'height',
        'depth',
        'seat_height'
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategories::class, 'item_category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategories::class, 'sub_category_id');
    }

    public function variants()
    {
        return $this->hasMany(ItemVariant::class);
    }

    public function detailPenawaranPenjualan()
    {
        return $this->hasMany(DetailPenawaranPenjualan::class);
    }

    public function detailPesananPenjualan()
    {
        return $this->hasMany(DetailPesananPenjualan::class);
    }

    public function detailPengirimanPenjualan()
    {
        return $this->hasMany(DetailPengirimanPenjualan::class);
    }


}
