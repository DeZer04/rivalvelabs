<?php

namespace App\Filament\App\Pages;

use App\Models\GroupKriteria;
use App\Models\Karyawan;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\PenilaianKaryawan;
use App\Models\PenilaianDetailKriteria;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Page;

class PenilaianWizard extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Penilaian';

    protected static string $view = 'filament.app.pages.penilaian-wizard';

    // State
    public ?int $penilaianId = null;
    public ?array $data = [];
    public array $selectedKaryawans = [];
    public array $nilai = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Step::make('Informasi Penilaian')
                    ->schema([
                        Forms\Components\TextInput::make('nama_penilaian')
                            ->required()
                            ->label('Nama Penilaian'),

                        Forms\Components\Select::make('tahun')
                            ->required()
                            ->options(
                                collect(range(date('Y'), date('Y') - 5))
                                    ->mapWithKeys(fn($year) => [$year => $year])
                            )
                            ->label('Tahun'),

                        Forms\Components\Select::make('periode')
                            ->required()
                            ->options([
                                'Jan-Jun' => 'Januari - Juni',
                                'Jul-Des' => 'Juli - Desember'
                            ])
                            ->label('Periode'),

                        Forms\Components\Select::make('group_kriteria_id')
                            ->options(GroupKriteria::pluck('nama_group_kriteria', 'id'))
                            ->required()
                            ->label('Group Kriteria'),
                    ]),

                Step::make('Pilih Karyawan')
                    ->schema([
                        Forms\Components\Select::make('karyawans')
                            ->label('Pilih Karyawan yang Dinilai')
                            ->options(function () {
                                return Karyawan::with('divisi')
                                    ->get()
                                    ->groupBy('divisi.nama_divisi')
                                    ->mapWithKeys(function ($group, $divisi) {
                                        return [$divisi => $group->pluck('nama_karyawan', 'id')->toArray()];
                                    })
                                    ->toArray();
                            })
                            ->multiple()
                            ->required()
                            ->preload()
                            ->searchable(),
                    ]),

                Step::make('Konfirmasi')
                    ->schema([
                        Forms\Components\Placeholder::make('selected_karyawans')
                            ->label('Karyawan yang Akan Dinilai')
                            ->content(function ($get) {
                                $karyawans = Karyawan::whereIn('id', $get('karyawans') ?? [])->get();

                                return view('filament.app.components.karyawan-list', [
                                    'karyawans' => $karyawans
                                ]);
                            }),
                    ]),
            ])
            ->statePath('data') // <-- Tambahkan ini
            ->submitAction('Simpan')
        ];
    }

    /** Generate form dinamis untuk nilai */
    protected function generateNilaiSchema(): array
    {
        if (!$this->data['group_kriteria_id'] ?? null || empty($this->data['karyawans'])) {
            return [
                Forms\Components\Placeholder::make('info')
                    ->content('Silakan pilih Group Kriteria & Karyawan terlebih dahulu.')
            ];
        }

        $kriterias = Kriteria::where('group_kriteria_id', $this->data['group_kriteria_id'])->get();
        $schema = [];

        foreach ($this->data['karyawans'] as $karyawanId) {
            $karyawan = Karyawan::find($karyawanId);

            $schema[] = Forms\Components\Section::make($karyawan->nama_karyawan)
                ->schema(
                    $kriterias->map(function ($kriteria) use ($karyawanId) {
                        return Forms\Components\TextInput::make("nilai.{$karyawanId}.{$kriteria->id}")
                            ->numeric()
                            ->required()
                            ->label($kriteria->nama_kriteria);
                    })->toArray()
                );
        }

        return $schema;
    }

    public function submit()
    {
        // 1. Simpan Penilaian
        $penilaian = Penilaian::create([
            'nama_penilaian' => $this->data['nama_penilaian'],
            'tahun' => $this->data['tahun'],
            'periode' => $this->data['periode'],
            'group_kriteria_id' => $this->data['group_kriteria_id'],
            'penilai_id' => Auth::id(),
        ]);

        // 2. Simpan Karyawan + Detail Kriteria
        foreach ($this->data['karyawans'] as $karyawanId) {
            $penilaianKaryawan = PenilaianKaryawan::create([
                'penilaian_id' => $penilaian->id,
                'karyawan_id' => $karyawanId,
            ]);

            $kriterias = Kriteria::where('group_kriteria_id', $penilaian->group_kriteria_id)->get();

            foreach ($kriterias as $kriteria) {
                PenilaianDetailKriteria::create([
                    'penilaian_karyawan_id' => $penilaianKaryawan->id,
                    'kriteria_id' => $kriteria->id,
                    'nilai' => $this->nilai[$karyawanId][$kriteria->id] ?? null,
                ]);
            }
        }

        $this->notify('success', 'Penilaian berhasil disimpan!');
        return redirect()->route('filament.app.pages.penilaian-wizard');
    }
}
