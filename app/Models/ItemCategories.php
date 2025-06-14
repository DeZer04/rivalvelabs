<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_item_category',
    ];

    /**
     * Get the items associated with the category.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
