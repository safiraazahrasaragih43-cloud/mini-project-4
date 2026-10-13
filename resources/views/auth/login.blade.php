<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RetroCam IMS Laravel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="form-container" style="width: 100%; max-width: 420px;">
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="font-size: 2.5rem; margin-bottom: 10px;">📦</div>
            <h1>RetroCam IMS</h1>
            <p class="subtitle">Laravel Inventory Management System (P5)</p>
        </div>

        @if(session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif

        @if($errors->any())
            <x-alert type="danger">
                {{ $errors->first() }}
            </x-alert>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username" placeholder="admin atau staff" value="{{ old('username') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Masuk ke Sistem</button>
        </form>

        <div style="margin-top: 25px; padding-top: 15px; border-top: 1px solid #334155; font-size: 0.85rem; color: #94a3b8;">
            <p><strong>Akun Demo Seeder:</strong></p>
            <ul style="padding-left: 20px; margin-top: 5px;">
                <li>Admin: <code>admin</code> / <code>password123</code></li>
                <li>Staff: <code>staff</code> / <code>password123</code></li>
            </ul>
        </div>
    </div>
</body>
</html>
