<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_category_id', 'nama_item', 'width', 'height', 'depth'
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategories::class, 'item_category_id');
    }

    public function variants()
    {
        return $this->hasMany(ItemVariant::class);
    }


}
