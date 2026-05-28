<?php

namespace App\Filament\Resources\FinishingKayuResource\Pages;

use App\Filament\Resources\FinishingKayuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinishingKayus extends ListRecords
{
    protected static string $resource = FinishingKayuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
