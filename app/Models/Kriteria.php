<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriterias';

    protected $fillable = [
        'group_kriteria_id',
        'nama_kriteria',
        'is_benefit',
    ];

    public function groupKriteria()
    {
        return $this->belongsTo(GroupKriteria::class);
    }
}
