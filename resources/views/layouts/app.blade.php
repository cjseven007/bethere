@php
    use Illuminate\Support\Facades\Route;

    $navItems = [
        ['label' => 'Users', 'icon' => 'bi-people', 'route' => 'users.index', 'pattern' => 'users*'],
        [
            'label' => 'Scan Attendance',
            'icon' => 'bi-qr-code-scan',
            'route' => 'attendance.scan',
            'pattern' => 'attendance/scan*',
        ],
        ['label' => 'Settings', 'icon' => 'bi-gear', 'route' => 'settings.index', 'pattern' => 'settings*'],
    ];

    $hrefFor = fn($item) => !empty($item['route']) && Route::has($item['route'])
        ? route($item['route'])
        : url($item['pattern'] ?? '#');

    $isActive = fn($item) => !empty($item['route']) && Route::has($item['route'])
        ? request()->routeIs($item['route'] . '*')
        : (!empty($item['pattern'])
            ? request()->is($item['pattern'])
            : false);
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'BeThere')</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @livewireStyles

    <style>
        /* Add body padding to prevent content hiding behind fixed header */
        body {
            padding-top: 56px;
            /* default navbar height */
        }

        @media (max-width: 991.98px) {
            body {
                padding-top: 60px;
            }
        }
    </style>
</head>

<body class="bg-light">

    {{-- FIXED HEADER --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            {{-- Brand --}}
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="bi bi-robot"></i> <span class="fw-bold">BeThere</span>
            </a>

            {{-- Sidebar toggler on mobile --}}
            <button class="navbar-toggler" type="button" aria-controls="sidebar" aria-expanded="false"
                aria-label="Toggle sidebar" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Right side (auth) --}}
            <div class="d-flex ms-auto align-items-center">
                @auth
                    <div class="dropdown">
                        <a class="text-white text-decoration-none dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="btn btn-outline-light btn-sm me-2" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-warning btn-sm" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row min-vh-100">
            {{-- SIDEBAR --}}
            <nav id="sidebar"
                class="col-lg-2 col-md-3 col-12 collapse show bg-white border-end p-0 d-flex flex-column vh-100 position-sticky"
                style="top: 56px;">
                <div class="list-group list-group-flush py-2 flex-grow-1 overflow-auto">
                    @foreach ($navItems as $item)
                        <a href="{{ $hrefFor($item) }}"
                            class="list-group-item list-group-item-action d-flex align-items-center {{ $isActive($item) ? 'active' : '' }}">
                            <i class="bi {{ $item['icon'] }} me-2"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </nav>

            {{-- MAIN --}}
            <main class="col-lg-10 col-md-9 col-12 py-3">
                @hasSection('page_actions')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h4 m-0">@yield('page_title', 'Dashboard')</h1>
                        <div>@yield('page_actions')</div>
                    </div>
                @else
                    <h1 class="h4 mb-3">@yield('page_title', 'Dashboard')</h1>
                @endif

                @if (session('ok'))
                    <div class="alert alert-success">{{ session('ok') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts
</body>

</html>
