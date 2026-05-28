<?php

namespace App\Http\Livewire\MasterKayu;

use Livewire\Component;
use Filament\Tables;
use Filament\Forms;
use App\Models\GradeKayu;
use App\Models\JenisKayu;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;

class GradeKayuTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function render()
    {
        return view('livewire.master-kayu.grade-kayu-table');
    }

    protected function getTableQuery()
    {
        return GradeKayu::with('jenisKayu');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama_grade_kayu')->label('Grade'),
            Tables\Columns\TextColumn::make('jenisKayu.nama_jenis_kayu')->label('Jenis Kayu'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()->form([
                Forms\Components\TextInput::make('nama_grade_kayu')->required(),
                Forms\Components\Select::make('jenis_kayu_id')
                    ->label('Jenis Kayu')
                    ->options(JenisKayu::pluck('nama_jenis_kayu', 'id'))
                    ->required(),
            ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make()->form([
                Forms\Components\TextInput::make('nama_grade_kayu')->required(),
                Forms\Components\Select::make('jenis_kayu_id')
                    ->label('Jenis Kayu')
                    ->options(JenisKayu::pluck('nama_jenis_kayu', 'id'))
                    ->required(),
            ]),
            Tables\Actions\DeleteAction::make(),
        ];
    }
}
