<?php

namespace App\Filament\Resources\ModelAnyamResource\Pages;

use App\Filament\Resources\ModelAnyamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModelAnyams extends ListRecords
{
    protected static string $resource = ModelAnyamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
