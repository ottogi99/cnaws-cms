@props([
    'placeholder' => '',
])

<div {{ $attributes }}>
    <label for="email-address" class="hidden text-sm font-medium text-gray-700">Email address</label>
    <input
        type="text"
        name="email-address"
        id="email-address"
        autocomplete="email"
        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        placeholder="{{ $placeholder }}"
    >
</div>
