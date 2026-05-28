<?php

namespace App\Filament\Resources\JenisKayuResource\Pages;

use App\Filament\Resources\JenisKayuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisKayus extends ListRecords
{
    protected static string $resource = JenisKayuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
