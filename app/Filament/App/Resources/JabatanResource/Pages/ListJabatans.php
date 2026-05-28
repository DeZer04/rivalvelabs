<?php

namespace App\Filament\App\Resources\JabatanResource\Pages;

use App\Filament\App\Resources\JabatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJabatans extends ListRecords
{
    protected static string $resource = JabatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
