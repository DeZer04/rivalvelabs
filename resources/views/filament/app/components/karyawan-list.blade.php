<div class="space-y-4">
    @foreach($karyawans as $karyawan)
        <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow">
            <div>
                <h3 class="font-medium">{{ $karyawan->nama_karyawan }}</h3>
                <p class="text-sm text-gray-500">{{ $karyawan->divisi->nama_divisi ?? 'Tanpa Divisi' }}</p>
            </div>
            <a
                href="{{ route('filament.app.pages.penilaian-detail', [
                    'karyawan' => $karyawan->id,
                    'nama_penilaian' => request('nama_penilaian'),
                    'tahun' => request('tahun'),
                    'periode' => request('periode'),
                    'group_kriteria_id' => request('group_kriteria_id')
                ]) }}"
                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
                Nilai
            </a>
        </div>
    @endforeach
</div>
