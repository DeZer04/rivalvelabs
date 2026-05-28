<?php

namespace App\Filament\App\Pages;

use App\Models\Karyawan;
use App\Models\Kriteria;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class PenilaianDetail extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.app.pages.penilaian-detail';

    public Karyawan $karyawan;
    public array $data = [];

    public function mount(Karyawan $karyawan): void
    {
        $this->karyawan = $karyawan;

        $this->form->fill([
            'nama_penilaian' => request('nama_penilaian'),
            'tahun' => request('tahun'),
            'periode' => request('periode'),
            'group_kriteria_id' => request('group_kriteria_id'),
        ]);
    }

    protected function getFormSchema(): array
    {
        $kriterias = Kriteria::where('group_kriteria_id', request('group_kriteria_id'))->get();

        return [
            Section::make('Informasi Penilaian')
                ->schema([
                    TextInput::make('nama_penilaian')
                        ->disabled(),
                    // ... other disabled fields ...
                ]),

            Section::make($this->karyawan->nama_karyawan)
                ->schema(
                    $kriterias->map(function ($kriteria) {
                        return TextInput::make("nilai.{$kriteria->id}")
                            ->label($kriteria->nama_kriteria)
                            ->numeric()
                            ->required();
                    })->toArray()
                ),
        ];
    }

    public function submit()
    {
        // Save the assessment for this specific employee
        // Similar to your original submit() but for one karyawan
    }
}
