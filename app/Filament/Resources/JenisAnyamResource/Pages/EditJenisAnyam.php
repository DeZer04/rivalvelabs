<?php

namespace App\Filament\Resources\JenisAnyamResource\Pages;

use App\Filament\Resources\JenisAnyamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisAnyam extends EditRecord
{
    protected static string $resource = JenisAnyamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
