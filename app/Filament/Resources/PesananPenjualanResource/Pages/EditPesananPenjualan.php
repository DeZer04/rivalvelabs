<?php

namespace App\Filament\Resources\PesananPenjualanResource\Pages;

use App\Filament\Resources\PesananPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPesananPenjualan extends EditRecord
{
    protected static string $resource = PesananPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
