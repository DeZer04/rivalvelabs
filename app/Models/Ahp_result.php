<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ahp_result extends Model
{
    protected $table = 'ahp_results';

    protected $fillable = [
        'group_kriteria_id',
        'original_matrix',
        'normalized_matrix',
        'weights',
        'lambda_max',
        'consistency_index',
        'random_index',
        'consistency_ratio',
        'is_consistent',
        'weighted_sum',
        'consistency_vector',
    ];

    protected $casts = [
        'original_matrix' => 'array',
        'normalized_matrix' => 'array',
        'weights' => 'array',
        'weighted_sum' => 'array',
        'consistency_vector' => 'array',
        'is_consistent' => 'boolean',
    ];

    public static function getReportData(int $groupKriteriaId): array
    {
        $result = self::with('groupKriteria')->where('group_kriteria_id', $groupKriteriaId)->firstOrFail();

        $kriterias = Kriteria::where('group_kriteria_id', $groupKriteriaId)
            ->orderBy('id')
            ->get()
            ->pluck('nama_kriteria')
            ->toArray();

        return [
            'group' => $result->groupKriteria,
            'kriterias' => $kriterias,
            'original_matrix' => $result->original_matrix,
            'normalized_matrix' => $result->normalized_matrix,
            'weights' => $result->weights,
            'lambda_max' => $result->lambda_max,
            'consistency_index' => $result->consistency_index,
            'random_index' => $result->random_index,
            'consistency_ratio' => $result->consistency_ratio,
            'is_consistent' => $result->is_consistent,
            'weighted_sum' => $result->weighted_sum,
            'consistency_vector' => $result->consistency_vector,
            'created_at' => $result->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function groupKriteria()
    {
        return $this->belongsTo(GroupKriteria::class, 'group_kriteria_id');
    }


}
