<?php

namespace App\Filament\Resources\PenawaranPenjualanResource\Pages;

use App\Filament\Resources\PenawaranPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenawaranPenjualan extends EditRecord
{
    protected static string $resource = PenawaranPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
