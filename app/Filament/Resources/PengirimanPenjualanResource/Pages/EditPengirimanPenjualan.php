<?php

namespace App\Filament\Resources\PengirimanPenjualanResource\Pages;

use App\Filament\Resources\PengirimanPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengirimanPenjualan extends EditRecord
{
    protected static string $resource = PengirimanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
