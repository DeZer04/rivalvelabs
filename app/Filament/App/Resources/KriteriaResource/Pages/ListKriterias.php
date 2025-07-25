<?php

namespace App\Filament\App\Resources\KriteriaResource\Pages;

use App\Filament\App\Resources\KriteriaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKriterias extends ListRecords
{
    protected static string $resource = KriteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
