<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'تقنية التوزيع')</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    @include('shared.styles')
    
    <style>
        .main-content {
            margin-right: 260px;
            padding: 24px 80px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(100%); }
            .main-content { margin-right: 0; }
        }
    </style>
    @stack('styles')
</head>
<body class="{{ (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark') ? 'dark-mode' : '' }}">
    
    @include('shared.sidebar')

    <main class="main-content">
        @include('shared.navbar')
        @yield('content')
    </main>

    <script>
        function toggleTheme() {
            const body = document.body;
            body.classList.toggle('dark-mode');
            const theme = body.classList.contains('dark-mode') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
            document.cookie = `theme=${theme}; path=/; max-age=31536000`;
            
            const themeBtn = document.getElementById('themeBtn');
            if (themeBtn) {
                themeBtn.querySelector('.mode-icon').textContent = theme === 'dark' ? '☀️' : '🌙';
            }
        }

        // Apply theme from localStorage on load to avoid flicker
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            const themeBtn = document.getElementById('themeBtn');
            if (themeBtn) {
                themeBtn.querySelector('.mode-icon').textContent = '☀️';
            }
        }
    </script>
    @stack('scripts')
</body>
</html>

