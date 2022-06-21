@props([
    'title',
    'anotherText',
])

<div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 ">
    <h1 class="text-2xl font-semibold">{{ $title }}</h1>
    <a
        href="https://github.com/Kamona-WD/kwd-dashboard"
        target="_blank"
        class="px-4 py-2 text-sm bg-gray-500 text-white rounded-md focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark"
    >
        View on github-{{ $anotherText }}
    </a>
</div>
