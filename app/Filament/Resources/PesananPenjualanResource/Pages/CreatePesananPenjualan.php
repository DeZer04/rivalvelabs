<?php

namespace App\Filament\Resources\PesananPenjualanResource\Pages;

use App\Filament\Resources\PesananPenjualanResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\View;

class CreatePesananPenjualan extends CreateRecord
{
    protected static string $resource = PesananPenjualanResource::class;

    public function mount(): void
    {
        parent::mount();

        Filament::registerRenderHook(
            'scripts.end',
            fn () => <<<'HTML'
                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        let formChanged = false;
                        const form = document.querySelector("form");

                        if (form) {
                            form.addEventListener("change", () => {
                                formChanged = true;
                            });

                            form.addEventListener("submit", () => {
                                formChanged = false;
                            });

                            window.addEventListener("beforeunload", function (e) {
                                if (formChanged) {
                                    e.preventDefault();
                                    e.returnValue = "Perubahan Anda belum disimpan. Apakah yakin ingin keluar?";
                                }
                            });
                        }
                    });
                </script>
            HTML
        );
    }
}
