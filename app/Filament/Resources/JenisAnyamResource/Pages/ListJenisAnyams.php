<?php

namespace App\Filament\Resources\JenisAnyamResource\Pages;

use App\Filament\Resources\JenisAnyamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisAnyams extends ListRecords
{
    protected static string $resource = JenisAnyamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
