<x-filament-panels::page>
    <x-filament::section>
        <form wire:submit.prevent="calculateResults">
            <div class="space-y-6">
                {{ $this->form }}

                @if($groupKriteriaId)
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium">Pairwise Comparisons</h3>
                        <p class="text-sm text-gray-500">
                            Completed {{ $this->completedCount }} of {{ $this->getKriteriaPairs()->count() }} comparisons
                        </p>

                        <div class="overflow-auto rounded-lg border">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comparison</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Importance</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($this->getKriteriaPairs() as $pair)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {!! $pair['label'] !!}
                                            </td>
                                            <td class="px-4 py-3">
                                                <select
                                                    wire:model="comparisons.{{ $pair['key'] }}"
                                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                                >
                                                    <option value="">Select importance level</option>
                                                    @foreach($ahpScale as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>

                                                @error("comparisons.{$pair['key']}")
                                                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <x-filament::button
                            type="button"
                            wire:click="resetForm"
                            color="gray"
                        >
                            Reset
                        </x-filament::button>

                        <x-filament::button
                            type="submit"

                        >
                            Calculate
                        </x-filament::button>
                    </div>
                @endif
            </div>
        </form>
    </x-filament::section>

    @if($showResults)
        <x-filament::section class="mt-6">
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium">Calculation Results</h3>

                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $consistencyResults['is_consistent'] ? 'bg-success-100 text-success-800' : 'bg-danger-100 text-danger-800' }}">
                            {{ $consistencyResults['is_consistent'] ? 'Consistent' : 'Inconsistent' }}
                        </span>
                        <span class="text-sm text-gray-500">
                            CR = {{ number_format($consistencyResults['ratio'], 4) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="font-medium">Criteria Weights</h4>
                        <div class="overflow-hidden border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criteria</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($consistencyResults['kriterias'] as $index => $kriteria)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $kriteria->nama_kriteria }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ number_format($consistencyResults['weights'][$index], 4) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="font-medium">Consistency Details</h4>
                        <div class="overflow-hidden border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Lambda Max</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ number_format($consistencyResults['lambda_max'], 4) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Consistency Index (CI)</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ number_format($consistencyResults['index'], 4) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Random Index (RI)</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ number_format($consistencyResults['random_index'], 4) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Consistency Ratio (CR)</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ number_format($consistencyResults['ratio'], 4) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-medium">Original Comparison Matrix</h4>
                    <div class="overflow-auto">
                        <table class="min-w-full border">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border"></th>
                                    @foreach($consistencyResults['kriterias'] as $kriteria)
                                        <th class="px-4 py-2 border">{{ $kriteria->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consistencyResults['original_matrix'] as $i => $row)
                                    <tr>
                                        <td class="px-4 py-2 border font-medium">{{ $consistencyResults['kriterias'][$i]->nama_kriteria }}</td>
                                        @foreach($row as $value)
                                            <td class="px-4 py-2 border text-center">{{ number_format($value, 3) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-medium">Normalized Matrix</h4>
                    <div class="overflow-auto">
                        <table class="min-w-full border">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border"></th>
                                    @foreach($consistencyResults['kriterias'] as $kriteria)
                                        <th class="px-4 py-2 border">{{ $kriteria->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consistencyResults['normalized_matrix'] as $i => $row)
                                    <tr>
                                        <td class="px-4 py-2 border font-medium">{{ $consistencyResults['kriterias'][$i]->nama_kriteria }}</td>
                                        @foreach($row as $value)
                                            <td class="px-4 py-2 border text-center">{{ number_format($value, 3) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end">
                    <x-filament::button
                        type="button"
                        wire:click="submit"
                        :disabled="!$consistencyResults['is_consistent']"
                        color="{{ $consistencyResults['is_consistent'] ? 'primary' : 'gray' }}"
                    >
                        Save Results
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
