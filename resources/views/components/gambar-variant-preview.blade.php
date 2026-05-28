@if ($gambar)
    <img src="{{ asset('storage/' . $gambar) }}" class="rounded-lg shadow max-w-[200px]" alt="Gambar Varian">
@else
    <p class="text-gray-500 text-sm italic">Belum ada gambar dipilih</p>
@endif
