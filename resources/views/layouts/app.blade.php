<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gym System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js for interactions -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        @stack('scripts')
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Content - Responsive margin based on screen size -->
            <main class="md:ml-64">
                @if (isset($header))
                    <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-4 md:py-6">
                        {{ $header }}
                    </div>
                @endif

                <div class="px-3 md:px-8 py-4 md:py-6 w-full">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>