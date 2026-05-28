<?php

namespace App\Livewire\MasterKayu;

use App\Models\JenisKayu;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Component;

class JenisKayuTable extends Component
{
    use InteractsWithTable, InteractsWithForms;

    public function render()
    {
        return view('livewire.master-kayu.jenis-kayu-table');
    }

    protected function getTableQuery()
    {
        return JenisKayu::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('nama_jenis_kayu')->label('Nama Jenis Kayu'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make()->form([
                TextInput::make('nama_jenis_kayu')
                    ->required(),
            ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()->form([
                TextInput::make('nama_jenis_kayu')
                    ->required(),
            ]),
            DeleteAction::make(),
        ];
    }



}
