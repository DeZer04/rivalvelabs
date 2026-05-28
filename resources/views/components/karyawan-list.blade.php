@php
    $divisiId = $this->data['divisi_id'] ?? null;

    $karyawans = \App\Models\Karyawan::query()
        ->when($divisiId, fn($q) => $q->where('divisi_id', $divisiId))
        ->get();
@endphp

<table class="min-w-full border border-gray-200 text-sm">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-2 py-1 border">Nama</th>
            <th class="px-2 py-1 border">Divisi</th>
            <th class="px-2 py-1 border">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($karyawans as $karyawan)
            <tr>
                <td class="px-2 py-1 border">{{ $karyawan->nama_karyawan }}</td>
                <td class="px-2 py-1 border">{{ $karyawan->divisi->nama_divisi ?? '-' }}</td>
                <td class="px-2 py-1 border text-center">
                    <button
                            wire:click="nilaiKaryawan({{ $karyawan->id }})"
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            Nilai
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
