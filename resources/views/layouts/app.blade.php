<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventory Management') - RetroCam IMS (Laravel)</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @auth
        <nav class="navbar">
            <a href="{{ route('dashboard') }}" class="brand">
                <span>📦</span> RetroCam IMS <small style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">(Laravel Edition)</small>
            </a>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Produk</a>
                <a href="{{ route('stock.index') }}" class="{{ request()->routeIs('stock.*') ? 'active' : '' }}">Pergerakan Stok</a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Kategori</a>
                    <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">Supplier</a>
                @endif
            </div>
            <div class="user-badge">
                <span>👤 {{ Auth::user()->name }}</span>
                <span class="role-tag role-{{ Auth::user()->role }}">{{ Auth::user()->role }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 8px;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                </form>
            </div>
        </nav>
    @endauth

    <main class="container">
        {{-- Flash Feedback Alerts --}}
        @if(session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="danger">
                {{ session('error') }}
            </x-alert>
        @endif

        @if($errors->any())
            <x-alert type="danger">
                <ul style="padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} RetroCam Inventory Management System • Pertemuan 5 Laravel Fundamental (Migrasi P4)</p>
    </footer>
</body>
</html>
