@props([
    'leadingAddOn' => false,
    'isDisabled' => false,
])

<div class="flex rounded-md shadow-sm">
    @if ($leadingAddOn)
    <span class="inline-flex items-center px-3 rounded-1-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
        {{ $leadingAddOn }}
    </span>
    @endif

    <input type="hidden" {{ $attributes }}
        class="{{ $leadingAddOn ? 'rounded-none rounded-r-md' : '' }} flex-1 form-input block w-full transition duration-150 ease-in-out"
        {{ $isDisabled === 'true' ? 'disabled' : ''}}
    />
</div>


{{-- <div>
    <label for="email-address" class="hidden text-sm font-medium text-gray-700">Email address</label>
    <input
        type="text"
        name="email-address"
        id="email-address"
        autocomplete="email"
        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
    >
</div>
 --}}
