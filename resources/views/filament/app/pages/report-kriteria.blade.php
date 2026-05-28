<x-filament-panels::page>
    <x-filament-panels::header
        :actions="$this->getHeaderActions()"
    >
        <x-slot name="heading">
            Report AHP - {{ $this->record->nama_group_kriteria }}
        </x-slot>
        <x-slot name="description">
            Terakhir dihitung: {{ $this->ahpResult->created_at->format('d F Y H:i') }}
        </x-slot>
    </x-filament-panels::header>

    <div class="space-y-6">
        <!-- Original Matrix -->
        {{ $this->getOriginalMatrixTable() }}

        <!-- Normalized Matrix -->
        {{ $this->getNormalizedMatrixTable() }}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 m-auto">
            <!-- Bobot Kriteria -->
            <x-filament::section>
            <x-slot name="heading">
                <x-filament::section.heading>
                Bobot Kriteria
                </x-filament::section.heading>
            </x-slot>

            <div class="space-y-4">
                @php
                $weights = is_array($this->ahpResult->weights)
                    ? $this->ahpResult->weights
                    : json_decode($this->ahpResult->weights, true);

                // Ensure weights sum to 1 (normalize if needed)
                $sum = array_sum($weights);
                $normalizedWeights = array_map(function($w) use ($sum) {
                    return $sum != 0 ? $w / $sum : 0;
                }, $weights);
                @endphp

                <table class="min-w-full divide-y divide-gray-200">
                <tbody>
                    @foreach($kriterias as $index => $kriteria)
                    <tr>
                        <td class="py-2 px-4">
                        <span class="font-medium">C{{ $index + 1 }}.</span>
                        </td>
                        <th class="py-2 px-4 text-left font-medium text-gray-700">
                        {{ $kriteria->nama_kriteria }}
                        </th>
                        <td class="py-2 px-4 text-right">
                        <span class="font-bold">
                            {{ number_format($normalizedWeights[$index] * 100, 2) }}%
                        </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            </x-filament::section>

            <!-- Konsistensi -->
            <x-filament::section>
            <x-slot name="heading">
                <x-filament::section.heading>
                Uji Konsistensi
                </x-filament::section.heading>
            </x-slot>

            <div class="space-y-2">
                <div class="flex justify-between">
                <span>λ<sub>max</sub>:</span>
                <span>{{ number_format($this->ahpResult->lambda_max, 4) }}</span>
                </div>
                <div class="flex justify-between">
                <span>Consistency Index (CI):</span>
                <span>{{ number_format($this->ahpResult->consistency_index, 4) }}</span>
                </div>
                <div class="flex justify-between">
                <span>Random Index (RI):</span>
                <span>{{ number_format($this->ahpResult->random_index, 4) }}</span>
                </div>
                <div class="flex justify-between">
                <span>Consistency Ratio (CR):</span>
                <span class="{{ $this->ahpResult->is_consistent ? 'text-success-600' : 'text-danger-600' }}">
                    {{ number_format($this->ahpResult->consistency_ratio, 4) }}
                    ({{ $this->ahpResult->is_consistent ? 'Konsisten' : 'Tidak Konsisten' }})
                </span>
                </div>
            </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
