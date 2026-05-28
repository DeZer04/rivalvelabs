<?php

namespace App\Filament\Resources\PengirimanPenjualanResource\Pages;

use App\Filament\Resources\PengirimanPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengirimanPenjualans extends ListRecords
{
    protected static string $resource = PengirimanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
