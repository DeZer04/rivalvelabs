<?php

namespace App\Filament\App\Resources\KriteriaResource\Pages;

use App\Filament\App\Resources\KriteriaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKriteria extends EditRecord
{
    protected static string $resource = KriteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
