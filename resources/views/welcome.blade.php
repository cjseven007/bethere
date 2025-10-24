<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Vite (preferred) --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @else
        {{-- Fallback: Bootstrap + Icons CDN for local/plain installs --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    @endif
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    {{-- Header (auth-aware) --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="bi bi-robot"></i>
                <span class="fw-bold">{{ config('app.name', 'Laravel') }}</span>
            </a>

            @if (Route::has('login'))
                <div class="ms-auto">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-warning btn-sm">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    {{-- Main --}}
    <main class="flex-grow-1 py-5">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                {{-- Left: Getting started --}}
                <div class="col-lg-7">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h1 class="h4 mb-2">Let’s get started</h1>
                            <p class="text-secondary mb-4">
                                Laravel has an incredibly rich ecosystem. We suggest starting with the following.
                            </p>

                            <ol class="list-group list-group-numbered mb-4">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-semibold">Read the Documentation</div>
                                        Everything you need to build and ship quickly.
                                    </div>
                                    <a class="btn btn-link" href="https://laravel.com/docs" target="_blank"
                                        rel="noreferrer">
                                        Open <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-semibold">Watch on Laracasts</div>
                                        Hands-on screencasts and courses for all levels.
                                    </div>
                                    <a class="btn btn-link" href="https://laracasts.com" target="_blank"
                                        rel="noreferrer">
                                        Open <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>
                                </li>
                            </ol>

                            <a href="https://cloud.laravel.com" target="_blank" rel="noreferrer" class="btn btn-dark">
                                <i class="bi bi-cloud-upload me-1"></i> Deploy now
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right: Logo / Hero (simple, minimal) --}}
                <div class="col-lg-5">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="text-center text-muted">
                                <i class="bi bi-layers fs-1 d-block mb-2"></i>
                                <div class="fw-semibold">Laravel</div>
                                <div class="small">Happy coding!</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /row -->
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-top py-3">
        <div class="container d-flex justify-content-between small text-muted">
            <span>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}</span>
            <span>Bootstrap template — minimal & clean</span>
        </div>
    </footer>

</body>

</html>
