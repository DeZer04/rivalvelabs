<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PairwiseComparison extends Model
{
    protected $table = 'pairwise_comparisons';

    protected $fillable = [
        'kriteria_id',
        'kriteria_1_id',
        'kriteria_2_id',
        'nilai_perbandingan',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function kriteria1()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_1_id');
    }

    public function kriteria2()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_2_id');
    }
}
