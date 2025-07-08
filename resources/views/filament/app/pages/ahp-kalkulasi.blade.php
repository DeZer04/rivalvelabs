<x-filament-panels::page>
    <x-filament-panels::form wire:submit="calculateResults">
        {{ $this->form }}

        @if ($groupKriteriaId)
            <x-filament::section>
                <x-slot name="heading">
                    Panduan Nilai Perbandingan AHP
                </x-slot>
                <x-slot name="description">
                    Skala perbandingan berpasangan menurut metode AHP
                </x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-filament::card>
                        <div class="p-4 space-y-2">
                            <p class="font-medium">Nilai Integer:</p>
                            <ul class="text-sm space-y-1 list-disc ml-5">
                                <li><span class="font-bold">1</span> = Sama penting</li>
                                <li><span class="font-bold">3</span> = Sedikit lebih penting</li>
                                <li><span class="font-bold">5</span> = Jelas lebih penting</li>
                                <li><span class="font-bold">7</span> = Sangat jelas lebih penting</li>
                                <li><span class="font-bold">9</span> = Mutlak lebih penting</li>
                            </ul>
                        </div>
                    </x-filament::card>

                    <x-filament::card>
                        <div class="p-4 space-y-2">
                            <p class="font-medium">Nilai Lainnya:</p>
                            <ul class="text-sm space-y-1 list-disc ml-5">
                                <li><span class="font-bold">2,4,6,8</span> = Nilai tengah</li>
                                <li><span class="font-bold">1/3</span> = Sedikit kurang penting</li>
                                <li><span class="font-bold">1/5</span> = Jelas kurang penting</li>
                                <li><span class="font-bold">1/7</span> = Sangat kurang penting</li>
                                <li><span class="font-bold">1/9</span> = Mutlak kurang penting</li>
                            </ul>
                        </div>
                    </x-filament::card>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    Matriks Perbandingan Berpasangan
                </x-slot>
                <x-slot name="description">
                    Isi nilai perbandingan untuk setiap pasangan kriteria
                </x-slot>

                <div class="space-y-6">
                    @foreach ($this->getKriteriaPairs() as $pair)
                        <x-filament::input.wrapper>
                            <x-filament::input.label>
                                {!! $pair['label'] !!}
                            </x-filament::input.label>
                            <div class="flex items-center gap-2">
                                <x-filament::input.select
                                    wire:model="comparisons.{{ $pair['key'] }}"
                                    required
                                >
                                    <option value="">Pilih nilai perbandingan</option>
                                    @foreach($ahpScale as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </x-filament::input.select>

                                @if($comparisons[$pair['key']] ?? false)
                                    <x-filament::badge>
                                        Nilai: {{ $comparisons[$pair['key']] }}
                                    </x-filament::badge>
                                @endif
                            </div>
                        </x-filament::input.wrapper>
                    @endforeach
                </div>
            </x-filament::section>

            <x-filament::actions>
                <x-filament::actions.action
                    type="submit"
                    icon="heroicon-o-calculator"
                    size="md"
                >
                    Hitung AHP
                </x-filament::actions.action>

                <x-filament::actions.action
                    wire:click="generateComparisonMatrix"
                    color="gray"
                    icon="heroicon-o-arrow-path"
                    size="md"
                >
                    Reset Perbandingan
                </x-filament::actions.action>
            </x-filament::actions>
        @endif
    </x-filament-panels::form>

    @if($showResults)
        {{-- Results display section would go here --}}
        @include('filament.app.pages.ahp-results')
    @endif
</x-filament-panels::page>
