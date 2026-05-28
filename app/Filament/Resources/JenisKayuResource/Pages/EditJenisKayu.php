<?php

namespace App\Filament\Resources\JenisKayuResource\Pages;

use App\Filament\Resources\JenisKayuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisKayu extends EditRecord
{
    protected static string $resource = JenisKayuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
