<?php

namespace App\Filament\Resources\ModelAnyamResource\Pages;

use App\Filament\Resources\ModelAnyamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModelAnyam extends EditRecord
{
    protected static string $resource = ModelAnyamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
