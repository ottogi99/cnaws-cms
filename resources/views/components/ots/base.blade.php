<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>

    <body>
        <div x-data="setup()" x-init="$refs.loading.classList.add('hidden'); setColors(color);" :class="{ 'dark': isDark }">
            <div class="flex h-screen antialiased text-gray-900 bg-gray-100 dark:bg-dark dark:text-light">
                <!-- Loading screen -->
                <div
                    x-ref="loading"
                    class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker"
                >
                    Loading.....
                </div>

                <!-- Sidebar -->
                <x-ots.side-bar></x-ots.side-bar>

                <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">
                    <x-ots.nav-bar></x-ots.nav-bar>
                    <!-- Main content -->
                    <main>
                        <!-- Content header -->
                        @isset($header)
                            <x-ots.header>
                                <x-slot name="title">
                                    {{ $header }}
                                </x-slot>
                                <x-slot name="anotherText">
                                    BBB
                                </x-slot>
                            </x-ots.header>
                        @endisset

                        <!-- Content -->
                        {{ $slot }}
                    </main>

                    <x-ots.footer/>
                </div>

                @stack('modals')
                @livewireScripts
            </div>
        </div>
    </body>
</html>

