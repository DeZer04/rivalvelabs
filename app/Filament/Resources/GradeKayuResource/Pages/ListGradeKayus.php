<?php

namespace App\Filament\Resources\GradeKayuResource\Pages;

use App\Filament\Resources\GradeKayuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGradeKayus extends ListRecords
{
    protected static string $resource = GradeKayuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
