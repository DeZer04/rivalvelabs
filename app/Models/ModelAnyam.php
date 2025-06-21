<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelAnyam extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_model_anyam',
        'gambar_model_anyam',
    ];

    public function itemVariants()
    {
        return $this->hasMany(ItemVariant::class);
    }

    protected $casts = [
        'gambar_model_anyam' => 'array',
    ];
}
