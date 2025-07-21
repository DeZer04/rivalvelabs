<?php

namespace App\Filament\App\Pages;

use App\Models\GroupKriteria;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReportKriteria extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.report-kriteria';

    public GroupKriteria $record;
    public $ahpResult;
    public $kriterias;

    public function mount(GroupKriteria $record)
    {
        $this->record = $record;
        $this->ahpResult = $record->ahpResult;

        if (!$this->ahpResult) {
            abort(404, 'Tidak ada hasil perhitungan untuk grup ini');
        }

        $this->kriterias = $record->kriterias()->orderBy('id')->get();
    }

    public function getMatrixTable(Table $table, array $matrix, string $title): Table
    {
        return $table
            ->heading($title)
            ->columns([
                TextColumn::make('kriteria')
                    ->label('Kriteria'),
                ...array_map(function ($i) {
                    return TextColumn::make("value.{$i}")
                        ->label(fn () => $this->kriterias[$i]->nama_kriteria)
                        ->numeric(decimalPlaces: 4);
                }, range(0, count($this->kriterias) - 1))
            ])
            ->query(
                collect($matrix)->map(function ($row, $index) {
                    return [
                        'kriteria' => $this->kriterias[$index]->nama_kriteria,
                        'value' => $row
                    ];
                })->toQuery()
            );
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
