<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'nama_variant',
        'gambar_item',
        'deskripsi_item',
        'jenis_kayu_id',
        'grade_kayu_id',
        'finishing_kayu_id',
        'jenis_anyam_id',
        'warna_anyam_id',
        'harga',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function jenisKayu()
    {
        return $this->belongsTo(JenisKayu::class);
    }

    public function gradeKayu()
    {
        return $this->belongsTo(GradeKayu::class);
    }

    public function finishingKayu()
    {
        return $this->belongsTo(FinishingKayu::class);
    }

    public function jenisAnyam()
    {
        return $this->belongsTo(JenisAnyam::class);
    }

    public function warnaAnyam()
    {
        return $this->belongsTo(WarnaAnyam::class);
    }

    /**
     * Get the price of the item variant.
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->harga, 0, ',', '.');
    }


}
