<?php

namespace App\Filament\Resources\WarnaAnyamResource\Pages;

use App\Filament\Resources\WarnaAnyamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarnaAnyams extends ListRecords
{
    protected static string $resource = WarnaAnyamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
