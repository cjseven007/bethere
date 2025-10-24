<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <main class="container d-flex flex-column justify-content-center align-items-center flex-grow-1 py-5">

        <div class="text-center mb-4">
            <a href="/" class="d-inline-block">
                <x-application-logo class="w-25 h-25 text-secondary" />
            </a>
            <h2 class="mt-3 fw-semibold text-secondary">{{ config('app.name', 'BeThere') }}</h2>
        </div>

        <div class="card shadow-sm border-0" style="max-width: 420px; width: 100%;">
            <div class="card-body p-4">
                {{ $slot }}
            </div>
        </div>
    </main>

    <footer class="text-center text-muted py-3 small">
        &copy; {{ date('Y') }} BeThere AI Attendance
    </footer>
</body>

</html>
