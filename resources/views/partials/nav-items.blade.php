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

<div
    class="list-group list-group-flush py-2 @isset($mobile) @else flex-grow-1 overflow-auto @endisset">
    @foreach ($navItems as $item)
        <a href="{{ $hrefFor($item) }}"
            class="list-group-item list-group-item-action d-flex align-items-center {{ $isActive($item) ? 'active' : '' }}">
            <i class="bi {{ $item['icon'] }} me-2"></i>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</div>
