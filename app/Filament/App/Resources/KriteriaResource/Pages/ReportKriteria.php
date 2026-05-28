<?php

namespace App\Filament\App\Resources\KriteriaResource\Pages;

use App\Filament\App\Resources\KriteriaResource;
use App\Models\AhpResult;
use App\Models\GroupKriteria;
use App\Models\Kriteria;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReportKriteria extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = KriteriaResource::class;
    protected static string $view = 'filament.app.pages.report-kriteria';

    public GroupKriteria $record;
    public $ahpResult;
    public $kriterias;

    public function mount(GroupKriteria $record)
    {
        $this->record = $record;
        $this->ahpResult = $record->ahpResult;

        // Log all variables on ahpResult
        if ($this->ahpResult) {
            \Log::info('ahpResult variables:', $this->ahpResult->toArray());
        }

        if (!$this->ahpResult) {
            abort(404, 'Tidak ada hasil perhitungan untuk grup ini');
        }

        $this->kriterias = $record->kriterias()->orderBy('id')->get();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Kriteria::query()->whereIn('id', $this->kriterias->pluck('id')))
            ->columns([])
            ->paginated(false);
    }

    protected function getOriginalMatrixTable(): Table
    {
        $matrix = json_decode($this->ahpResult->original_matrix, true);

        if (!is_array($matrix)) {
            abort(500, 'Invalid matrix data format');
        }

        return $this->buildMatrixTable(
            $matrix,
            'Matrix Perbandingan Berpasangan'
        );
    }

    protected function getNormalizedMatrixTable(): Table
    {
        $matrix = json_decode($this->ahpResult->normalized_matrix, true);

        if (!is_array($matrix)) {
            abort(500, 'Invalid matrix data format');
        }

        return $this->buildMatrixTable(
            $matrix,
            'Matrix Normalisasi'
        );
    }

    protected function buildMatrixTable(array $matrix, string $title): Table
    {
        return Table::make($this)
            ->heading($title)
            ->query(Kriteria::query()->whereIn('id', $this->kriterias->pluck('id')))
            ->columns([
                TextColumn::make('nama_kriteria')
                    ->label('Kriteria')
                    ->weight('bold'),
            ])
            ->paginated(false)
            ->headerActions([])
            ->actions([])
            ->bulkActions([])
            ->content(fn () => view('ahp.matrix-table', [
                'matrix' => $matrix,
                'kriterias' => $this->kriterias
            ]));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Cetak Report')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(route('ahp.report.pdf', $this->record->id))
                ->openUrlInNewTab(),
        ];
    }
}
