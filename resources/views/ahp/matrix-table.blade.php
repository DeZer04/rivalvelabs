<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">Kriteria</th>
                @foreach($kriterias as $index => $kriteria)
                    <th scope="col" class="px-6 py-3">C{{ $index + 1 }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($kriterias as $rowIndex => $rowKriteria)
                <tr class="bg-white border-b">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        C{{ $rowIndex + 1 }}
                    </th>
                    @foreach($kriterias as $colIndex => $colKriteria)
                        <td class="px-6 py-4">
                            {{ number_format($matrix[$rowIndex][$colIndex], 2) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
