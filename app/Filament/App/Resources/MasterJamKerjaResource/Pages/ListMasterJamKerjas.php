<?php

namespace App\Filament\App\Resources\MasterJamKerjaResource\Pages;

use App\Filament\App\Resources\MasterJamKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterJamKerjas extends ListRecords
{
    protected static string $resource = MasterJamKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
