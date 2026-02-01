<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة التوزيع')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">نظام إدارة التوزيع</h1>
            <div class="flex items-center gap-4">
                <span id="user-name" class="text-gray-700"></span>
                <button onclick="logout()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">تسجيل الخروج</button>
            </div>
        </div>
    </nav>

    @yield('content')

    <script>
        const API_BASE_URL = 'http://127.0.0.1:8000/api';
        
        function logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/';
        }
    </script>
    @stack('scripts')
</body>
</html>
