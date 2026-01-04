<!DOCTYPE html>
<html lang="en" data-theme="{{ Cookie::get('theme', 'light') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - Astroaura</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>

<body class="auth-body">
    <div class="auth-container">
        <!-- Theme Toggle for Auth Pages -->
        <div class="auth-theme-toggle">
            <button class="theme-toggle" id="themeToggle">
                <i class="bi bi-moon"></i>
            </button>
        </div>

        <!-- Auth Content -->
        <div class="auth-card">
            <div class="auth-header">
                @yield('auth-header')
            </div>

            <div class="auth-content">
                @yield('auth-content')
            </div>

            <div class="auth-footer">
                @yield('auth-footer')
            </div>
        </div>

        <!-- Background Elements -->
        <div class="auth-bg-elements">
            <div class="bg-circle bg-circle-1"></div>
            <div class="bg-circle bg-circle-2"></div>
            <div class="bg-circle bg-circle-3"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/auth.js') }}"></script>

    @stack('scripts')
</body>

</html>