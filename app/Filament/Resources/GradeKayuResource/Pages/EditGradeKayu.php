<?php

namespace App\Filament\Resources\GradeKayuResource\Pages;

use App\Filament\Resources\GradeKayuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGradeKayu extends EditRecord
{
    protected static string $resource = GradeKayuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
