<div
    {{-- x-data="{ value: $wire.editing.input_start_date }" --}}
    {{-- x-data="{ value: @entangle('editing.input_start_date') }" --}}
    x-data="{ value: @entangle($attributes->wire('model')) }"
    x-init="
        new Pikaday({
            field: $refs.input,
            format:'YYYY-MM-DD'
        })
    "
    {{-- @change="$dispatch('input', $event.target.value)" --}}
    x-on:change="value = $event.target.value"
    class="flex rounded-md shadow-sm"
>
    <span class="inline-flex items-center px-3 rounded-1-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-plus" viewBox="0 0 16 16">
            <path d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7z"/>
            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
        </svg>
    </span>

    <input
        x-ref="input"
        x-bind:value="value"
        {{ $attributes->whereDoesntStartWith('wire:model') }}
        class="rounded-none rounded-r-md flex-1 form-input block w-full transition duration-150 ease-in-out"
    />
</div>

@push('styles')
@endpush

@push('scrips')
@endpush
