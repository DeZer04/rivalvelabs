<x-filament-panels::page>
    {{ $this->form }}

    <div class="mt-6 flex gap-3">
        <x-filament::button wire:click="generateBarcode">
            Generate Barcode
        </x-filament::button>

        @if ($this->barcode)
            <a href="{{ route('barcode.print', ['code' => $this->barcode]) }}" target="_blank">
                <x-filament::button color="gray">
                    Cetak Barcode
                </x-filament::button>
            </a>
        @endif
    </div>

    @if ($this->barcode)
        <div class="mt-6 text-center">
            <h2 class="text-lg font-bold">Preview Barcode</h2>
            <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $this->barcode }}&code=Code128&translate-esc=true" alt="Barcode" class="mx-auto">
            <p class="text-sm mt-2 text-gray-600">{{ $this->barcode }}</p>
        </div>
    @endif

</x-filament-panels::page>
