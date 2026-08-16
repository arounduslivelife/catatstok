<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <title>CatatStok - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="app-container">
        @yield('content')
        
        @if(!isset($hideNav))
        <nav class="bottom-nav">
            <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="material-symbols-rounded">dashboard</i>
                <span>Beranda</span>
            </a>
            <a href="{{ url('/products') }}" class="nav-item {{ request()->is('products*') ? 'active' : '' }}">
                <i class="material-symbols-rounded">inventory_2</i>
                <span>Produk</span>
            </a>
            <a href="{{ url('/transactions/create?type=IN') }}" class="nav-item {{ request()->is('transactions/create*') && request('type') == 'IN' ? 'active' : '' }}">
                <i class="material-symbols-rounded">add_box</i>
                <span>Masuk</span>
            </a>
            <a href="{{ url('/transactions/create?type=OUT') }}" class="nav-item {{ request()->is('transactions/create*') && request('type') == 'OUT' ? 'active' : '' }}">
                <i class="material-symbols-rounded">indeterminate_check_box</i>
                <span>Keluar</span>
            </a>
            <a href="{{ url('/reports') }}" class="nav-item {{ request()->is('reports') ? 'active' : '' }}">
                <i class="material-symbols-rounded">summarize</i>
                <span>Laporan</span>
            </a>
        </nav>
        @endif
    </div>
</body>
</html>
