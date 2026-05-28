@php
    $selectedSupplier = data_get($this->form->getState(), 'supplier');
@endphp

<div class="flex flex-wrap gap-1">
    @foreach (range('A', 'Z') as $letter)
        <button
            type="button"
            wire:click="$set('formData.supplier', '{{ $letter }}')"
            class="px-3 py-1 rounded-md text-sm border transition
                {{ $selectedSupplier === $letter
                    ? 'bg-blue-600 text-white border-2 border-blue-700 ring-2 ring-blue-300'
                    : 'bg-gray-100 text-gray-800 border-gray-300 hover:bg-blue-500 hover:text-white hover:border-blue-600' }}">
            {{ $letter }}
        </button>
    @endforeach
</div>
