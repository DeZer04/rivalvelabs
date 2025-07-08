<?php

namespace App\Filament\Pages;

use App\Models\GroupKriteria;
use App\Models\Kriteria;
use App\Models\PairwiseComparison;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AhpKalkulasi extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?int $groupKriteriaId = null;
    public array $comparisons = [];
    public bool $showResults = false;
    public bool $showAdjustmentModal = false;

    public array $consistencyResults = [
        'lambda_max' => 0,
        'index' => 0,
        'random_index' => 0,
        'ratio' => 0,
        'is_consistent' => false,
        'original_matrix' => [],
        'normalized_matrix' => [],
        'weights' => [],
        'kriterias' => [],
        'weighted_sum' => [],
        'consistency_vector' => []
    ];

    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static string $view = 'filament.app.pages.ahp-kalkulasi';
    protected static ?string $title = 'AHP Calculation';
    protected static ?string $navigationLabel = 'AHP Calculation';

    public array $ahpScale = [
        9 => '9 - Absolutely more important',
        8 => '8 - Intermediate',
        7 => '7 - Very strongly more important',
        6 => '6 - Intermediate',
        5 => '5 - Strongly more important',
        4 => '4 - Intermediate',
        3 => '3 - Moderately more important',
        2 => '2 - Intermediate',
        1 => '1 - Equally important',
        '1/2' => '1/2 - Intermediate',
        '1/3' => '1/3 - Moderately less important',
        '1/4' => '1/4 - Intermediate',
        '1/5' => '1/5 - Strongly less important',
        '1/6' => '1/6 - Intermediate',
        '1/7' => '1/7 - Very strongly less important',
        '1/8' => '1/8 - Intermediate',
        '1/9' => '1/9 - Absolutely less important'
    ];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('groupKriteriaId')
                ->label('Select Criteria Group')
                ->options(GroupKriteria::uncalculated()->pluck('nama_group_kriteria', 'id'))
                ->reactive()
                ->afterStateUpdated(fn () => $this->resetForm())
                ->required()
                ->disabled(fn () => GroupKriteria::uncalculated()->count() === 0)
                ->helperText(function () {
                    if (GroupKriteria::uncalculated()->count() === 0) {
                        return 'All criteria groups have been calculated';
                    }
                    return null;
                }),
        ];
    }

    protected function resetForm(): void
    {
        $this->comparisons = [];
        $this->showResults = false;
        $this->consistencyResults = [
            'lambda_max' => 0,
            'index' => 0,
            'random_index' => 0,
            'ratio' => 0,
            'is_consistent' => false,
            'original_matrix' => [],
            'normalized_matrix' => [],
            'weights' => [],
            'kriterias' => [],
            'weighted_sum' => [],
            'consistency_vector' => []
        ];
        $this->generateComparisonMatrix();
    }

    protected function generateComparisonMatrix(): void
    {
        $this->comparisons = [];

        if (!$this->groupKriteriaId) {
            return;
        }

        $kriterias = Kriteria::where('group_kriteria_id', $this->groupKriteriaId)
            ->orderBy('id')
            ->get();

        foreach ($kriterias as $i => $kriteria1) {
            foreach ($kriterias as $j => $kriteria2) {
                if ($i < $j) {
                    $key = "{$kriteria1->id}_{$kriteria2->id}";
                    $existing = PairwiseComparison::where('group_kriteria_id', $this->groupKriteriaId)
                        ->where('kriteria_1_id', $kriteria1->id)
                        ->where('kriteria_2_id', $kriteria2->id)
                        ->first();

                    $this->comparisons[$key] = $existing ? $this->formatComparisonValue($existing->nilai_perbandingan) : null;
                }
            }
        }
    }

    protected function formatComparisonValue(float $value): string
    {
        if ($value >= 1) {
            return (string)$value;
        }

        $fractions = [
            0.111 => '1/9',
            0.125 => '1/8',
            0.143 => '1/7',
            0.167 => '1/6',
            0.2 => '1/5',
            0.25 => '1/4',
            0.333 => '1/3',
            0.5 => '1/2'
        ];

        $closest = null;
        $minDiff = PHP_FLOAT_MAX;

        foreach ($fractions as $decimal => $fraction) {
            $diff = abs($value - $decimal);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $fraction;
            }
        }

        return $closest ?? (string)$value;
    }

    protected function parseComparisonValue(string $value): float
    {
        if (str_contains($value, '/')) {
            [$numerator, $denominator] = explode('/', $value);
            return $numerator / $denominator;
        }

        return (float)$value;
    }

    public function calculateResults(): void
    {
        try {
            $this->validate([
                'groupKriteriaId' => 'required|exists:group_kriterias,id',
                'comparisons' => 'required|array'
            ]);

            $this->validateComparisons();

            $kriterias = Kriteria::where('group_kriteria_id', $this->groupKriteriaId)
                ->orderBy('id')
                ->get();

            if ($kriterias->isEmpty()) {
                throw new \Exception('No criteria found for selected group');
            }

            $matrix = $this->buildComparisonMatrix($kriterias);
            $normalizedMatrix = $this->normalizeMatrix($matrix);
            $weights = $this->calculateWeights($normalizedMatrix);
            $consistency = $this->checkConsistency($matrix, $weights);

            $this->consistencyResults = array_merge($this->consistencyResults, [
                'original_matrix' => $matrix,
                'normalized_matrix' => $normalizedMatrix,
                'weights' => $weights,
                'kriterias' => $kriterias,
                ...$consistency
            ]);

            $this->showResults = true;

            Notification::make()
                ->title('Calculation Complete')
                ->body('AHP calculation was successful')
                ->success()
                ->send();

        } catch (ValidationException $e) {
            Notification::make()
                ->title('Validation Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Calculation Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function validateComparisons(): void
    {
        $invalidPairs = [];

        foreach ($this->comparisons as $key => $value) {
            if (is_null($value) || !array_key_exists($value, $this->ahpScale)) {
                $invalidPairs[] = $key;
            }
        }

        if (!empty($invalidPairs)) {
            throw ValidationException::withMessages([
                'comparisons' => 'All pairwise comparisons must be selected from the available scale'
            ]);
        }
    }

    protected function buildComparisonMatrix(Collection $kriterias): array
    {
        $size = $kriterias->count();
        $matrix = array_fill(0, $size, array_fill(0, $size, 1));

        foreach ($kriterias as $i => $kriteria1) {
            foreach ($kriterias as $j => $kriteria2) {
                if ($i < $j) {
                    $key = "{$kriteria1->id}_{$kriteria2->id}";
                    $value = $this->parseComparisonValue($this->comparisons[$key] ?? '1');
                    $matrix[$i][$j] = $value;
                    $matrix[$j][$i] = 1 / $value;
                } elseif ($i === $j) {
                    $matrix[$i][$j] = 1;
                }
            }
        }

        return $matrix;
    }

    protected function normalizeMatrix(array $matrix): array
    {
        $size = count($matrix);
        $columnSums = array_fill(0, $size, 0);

        for ($j = 0; $j < $size; $j++) {
            for ($i = 0; $i < $size; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        $normalized = array_fill(0, $size, array_fill(0, $size, 0));
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $normalized[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
            }
        }

        return $normalized;
    }

    protected function calculateWeights(array $normalizedMatrix): array
    {
        $size = count($normalizedMatrix);
        $weights = array_fill(0, $size, 0);

        for ($i = 0; $i < $size; $i++) {
            $weights[$i] = array_sum($normalizedMatrix[$i]) / $size;
        }

        return $weights;
    }

    protected function checkConsistency(array $matrix, array $weights): array
    {
        $size = count($matrix);

        // For matrices of size 1 or 2, consistency is automatically satisfied
        if ($size <= 2) {
            return [
                'lambda_max' => $size,
                'index' => 0,
                'random_index' => 0,
                'ratio' => 0,
                'is_consistent' => true
            ];
        }

        $randomIndices = [
            3 => 0.58, 4 => 0.9, 5 => 1.12,
            6 => 1.24, 7 => 1.32, 8 => 1.41,
            9 => 1.45, 10 => 1.49
        ];

        $weightedSum = $this->calculateWeightedSum($matrix, $weights);
        $consistencyVector = $this->calculateConsistencyVector($matrix, $weights);

        $lambdaMax = array_sum($consistencyVector) / $size;
        $consistencyIndex = ($lambdaMax - $size) / ($size - 1);
        $randomIndex = $randomIndices[$size] ?? 1.49;
        $consistencyRatio = $randomIndex > 0 ? ($consistencyIndex / $randomIndex) : 0;

        return [
            'lambda_max' => $lambdaMax,
            'index' => $consistencyIndex,
            'random_index' => $randomIndex,
            'ratio' => $consistencyRatio,
            'is_consistent' => $consistencyRatio < 0.1
        ];
    }

    protected function calculateWeightedSum(array $matrix, array $weights): array
    {
        $size = count($matrix);
        $weightedSum = array_fill(0, $size, 0);

        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $weightedSum[$i] += $matrix[$i][$j] * $weights[$j];
            }
        }

        return $weightedSum;
    }

    protected function calculateConsistencyVector(array $matrix, array $weights): array
    {
        $size = count($matrix);
        $weightedSum = $this->calculateWeightedSum($matrix, $weights);
        $consistencyVector = array_fill(0, $size, 0);

        for ($i = 0; $i < $size; $i++) {
            $consistencyVector[$i] = $weightedSum[$i] / $weights[$i];
        }

        return $consistencyVector;
    }

    public function submit(): void
    {
        if (!$this->consistencyResults['is_consistent']) {
            Notification::make()
                ->title('Inconsistent Matrix')
                ->body('CR ≥ 0.1. Please adjust comparisons before saving.')
                ->danger()
                ->send();
            return;
        }

        PairwiseComparison::where('group_kriteria_id', $this->groupKriteriaId)->delete();

        foreach ($this->comparisons as $key => $value) {
            [$id1, $id2] = explode('_', $key);
            $numericValue = $this->parseComparisonValue($value);
            $inverseValue = $numericValue > 0 ? 1 / $numericValue : 0;

            PairwiseComparison::create([
                'group_kriteria_id' => $this->groupKriteriaId,
                'kriteria_1_id' => $id1,
                'kriteria_2_id' => $id2,
                'nilai_perbandingan' => $numericValue,
            ]);

            PairwiseComparison::create([
                'group_kriteria_id' => $this->groupKriteriaId,
                'kriteria_1_id' => $id2,
                'kriteria_2_id' => $id1,
                'nilai_perbandingan' => $inverseValue,
            ]);
        }

        GroupKriteria::where('id', $this->groupKriteriaId)
            ->update(['is_calculated' => true]);

        Notification::make()
            ->title('Saved Successfully')
            ->body('Pairwise comparisons saved successfully')
            ->success()
            ->send();
    }

    public function getKriteriaPairs(): Collection
    {
        $pairs = collect();

        if (!$this->groupKriteriaId) {
            return $pairs;
        }

        $kriterias = Kriteria::where('group_kriteria_id', $this->groupKriteriaId)
            ->orderBy('id')
            ->get();

        foreach ($kriterias as $i => $k1) {
            foreach ($kriterias as $j => $k2) {
                if ($i < $j) {
                    $pairs->push([
                        'key' => "{$k1->id}_{$k2->id}",
                        'label' => "How important is <b>{$k1->nama_kriteria}</b> compared to <b>{$k2->nama_kriteria}</b>",
                        'kriteria1' => $k1->nama_kriteria,
                        'kriteria2' => $k2->nama_kriteria,
                    ]);
                }
            }
        }

        return $pairs;
    }

    public function resetCalculation($groupId): void
    {
        GroupKriteria::where('id', $groupId)
            ->update(['is_calculated' => false]);

        Notification::make()
            ->title('Reset Successful')
            ->body('Group can be recalculated now')
            ->success()
            ->send();
    }
}
