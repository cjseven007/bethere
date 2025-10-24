<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- ... -->
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @livewireStyles
    <style>
        body {
            padding-top: 56px;
        }

        @media (max-width: 991.98px) {
            body {
                padding-top: 60px;
            }
        }

        /* Desktop sidebar sticks under the fixed navbar */
        @media (min-width: 992px) {
            #sidebar {
                top: 56px;
                height: calc(100vh - 56px);
            }
        }
    </style>
</head>

<body class="bg-light">
    {{-- FIXED HEADER --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="bi bi-robot"></i> <span class="fw-bold">BeThere</span>
            </a>

            {{-- Burger toggles the OFFCANVAS on mobile --}}
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="d-flex ms-auto align-items-center">
                @auth
                    <div class="dropdown">
                        <a class="text-white text-decoration-none dropdown-toggle" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown">
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

    {{-- MOBILE SIDEBAR: Offcanvas --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileSidebarLabel">
                <i class="bi bi-robot me-2"></i>Menu
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('partials.nav-items', ['mobile' => true])
        </div>
    </div>

    <div class="container-fluid">
        <div class="row min-vh-100">
            {{-- DESKTOP SIDEBAR (hidden on mobile) --}}
            <nav id="sidebar" class="col-lg-2 d-none d-lg-flex bg-white border-end p-0 flex-column position-sticky"
                style="z-index: 1000;">
                @include('partials.nav-items')
            </nav>

            {{-- MAIN --}}
            <main class="col-lg-10 col-12 py-3">
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

    {{-- jQuery + Bootstrap Bundle (if your app.js doesn’t already include Bootstrap JS) --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- Bootstrap JS bundle (includes Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    @livewireScripts
    @stack('scripts')
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropdownTrigger = document.getElementById('userDropdown');

        // Create a Bootstrap Dropdown instance
        const dropdown = new bootstrap.Dropdown(dropdownTrigger);

        // Toggle on click manually (use your own event if needed)
        dropdownTrigger.addEventListener('click', (e) => {
            e.preventDefault(); // prevent page jump
            dropdown.toggle();
        });
    });
</script>

</html>
