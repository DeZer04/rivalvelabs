<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupKriteria extends Model
{
    protected $table = 'group_kriterias';

    protected $fillable = [
        'nama_group_kriteria',
        'is_calculated',
    ];

    public function kriterias()
    {
        return $this->hasMany(Kriteria::class);
    }

    public function scopeUncalculated($query)
    {
        return $query->where('is_calculated', false);
    }

    public function ahpResult()
    {
        return $this->hasOne(Ahp_result::class);
    }
}
