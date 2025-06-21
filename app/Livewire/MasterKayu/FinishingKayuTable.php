<?php

namespace App\Http\Livewire\MasterKayu;

use Livewire\Component;
use Filament\Tables;
use Filament\Forms;
use App\Models\FinishingKayu;
use App\Models\JenisKayu;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;

class FinishingKayuTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function render()
    {
        return view('livewire.master-kayu.finishing-kayu-table');
    }

    protected function getTableQuery()
    {
        return FinishingKayu::with('jenisKayu');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama_finishing_kayu')->label('Finishing'),
            Tables\Columns\TextColumn::make('jenisKayu.nama_jenis_kayu')->label('Jenis Kayu'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()->form([
                Forms\Components\TextInput::make('nama_finishing_kayu')->required(),
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
                Forms\Components\TextInput::make('nama_finishing_kayu')->required(),
                Forms\Components\Select::make('jenis_kayu_id')
                    ->label('Jenis Kayu')
                    ->options(JenisKayu::pluck('nama_jenis_kayu', 'id'))
                    ->required(),
            ]),
            Tables\Actions\DeleteAction::make(),
        ];
    }
}
