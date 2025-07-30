<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sub_category'
    ];
    public function items()
    {
        return $this->hasMany(Item::class, 'sub_category_id');
    }
}
