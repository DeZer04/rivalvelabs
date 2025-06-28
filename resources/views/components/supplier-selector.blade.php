@php
    $selectedSupplier = data_get($this->form->getState(), 'supplier');
@endphp

<div class="flex flex-wrap gap-1">
    @foreach (range('A', 'Z') as $letter)
        <button
            type="button"
            wire:click="$set('formData.supplier', '{{ $letter }}')"
            class="px-3 py-1 rounded-md text-sm border
                {{ $selectedSupplier === $letter ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-800' }}">
            {{ $letter }}
        </button>
    @endforeach
</div>
