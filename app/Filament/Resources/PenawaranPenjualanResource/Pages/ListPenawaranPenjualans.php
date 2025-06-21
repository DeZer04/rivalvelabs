<?php

namespace App\Filament\Resources\PenawaranPenjualanResource\Pages;

use App\Filament\Resources\PenawaranPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenawaranPenjualans extends ListRecords
{
    protected static string $resource = PenawaranPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
