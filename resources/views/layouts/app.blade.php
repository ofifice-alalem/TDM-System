<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة التوزيع')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('styles')
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon">
                    <i data-lucide="zap"></i>
                </div>
                <div class="logo-text">
                    <h1>تقنية</h1>
                    <p>نظام إدارة التوزيع</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="/dashboard" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <span>لوحة التحكم</span>
                    <i data-lucide="layout-dashboard"></i>
                </a>

                <!-- Admin Links -->
                <div class="nav-section admin-only" style="display: none;">
                    <a href="#" class="nav-item {{ Request::is('products*') ? 'active' : '' }}">
                        <span>المنتجات</span>
                        <i data-lucide="package"></i>
                    </a>
                    <a href="#" class="nav-item {{ Request::is('stores*') ? 'active' : '' }}">
                        <span>المتاجر</span>
                        <i data-lucide="store"></i>
                    </a>
                    <a href="#" class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
                        <span>المستخدمين</span>
                        <i data-lucide="users"></i>
                    </a>
                    <a href="#" class="nav-item {{ Request::is('reports*') ? 'active' : '' }}">
                        <span>التقارير</span>
                        <i data-lucide="bar-chart-3"></i>
                    </a>
                </div>

                <!-- Warehouse Keeper Links -->
                <div class="nav-section warehouse-only" style="display: none;">
                    <a href="/warehouse/requests" class="nav-item {{ Request::is('warehouse/requests*') ? 'active' : '' }}">
                        <span>طلبات المسوقين</span>
                        <i data-lucide="clipboard-list"></i>
                    </a>
                    <a href="#" class="nav-item {{ Request::is('warehouse/stock*') ? 'active' : '' }}">
                        <span>المخزون الرئيسي</span>
                        <i data-lucide="warehouse"></i>
                    </a>
                    <a href="#" class="nav-item {{ Request::is('warehouse/logs*') ? 'active' : '' }}">
                        <span>سجل الحركات</span>
                        <i data-lucide="file-text"></i>
                    </a>
                </div>

                <!-- Salesman/Marketer Links -->
                <div class="nav-section salesman-only" style="display: none;">
                    <a href="/marketer/requests" class="nav-item {{ Request::is('marketer/requests*') ? 'active' : '' }}">
                        <span>طلباتي</span>
                        <i data-lucide="shopping-cart"></i>
                    </a>
                    <a href="/marketer/requests/create" class="nav-item {{ Request::is('marketer/requests/create') ? 'active' : '' }}">
                        <span>طلب بضاعة جديد</span>
                        <i data-lucide="plus-circle"></i>
                    </a>
                    <a href="#" class="nav-item {{ Request::is('marketer/sales*') ? 'active' : '' }}">
                        <span>فواتير البيع</span>
                        <i data-lucide="receipt"></i>
                    </a>
                    <a href="#" class="nav-item {{ Request::is('marketer/stock*') ? 'active' : '' }}">
                        <span>مخزوني</span>
                        <i data-lucide="box"></i>
                    </a>
                    <a href="#" class="nav-item {{ Request::is('marketer/commissions*') ? 'active' : '' }}">
                        <span>عمولاتي</span>
                        <i data-lucide="dollar-sign"></i>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <p class="keeper-badge">KEEPER</p>
                <p class="version-text">Taqnia Distribution 2024 ©</p>
                <p class="version-text">V 2.5.0</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <div class="header-left">
                    <div class="icon-btn theme-toggle">
                        <i data-lucide="moon"></i>
                    </div>
                    <div class="icon-btn">
                        <i data-lucide="bell"></i>
                    </div>
                    <div class="icon-btn">
                        <i data-lucide="sparkles"></i>
                    </div>
                </div>

                <div class="header-right">
                    <div class="user-profile">
                        <div class="user-info">
                            <h4 id="user-display-name">جاري التحميل...</h4>
                            <p id="user-display-role">المستخدم</p>
                        </div>
                        <div class="user-avatar" id="user-avatar-initials">
                            <i data-lucide="user"></i>
                        </div>
                        <div class="icon-btn logout-btn" onclick="logout()" title="تسجيل الخروج" style="margin-right: 12px; border-right: 1px solid var(--border-color); padding-right: 12px; border-radius: 0;">
                            <i data-lucide="log-out"></i>
                        </div>
                    </div>
                </div>
            </header>

            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const API_BASE_URL = 'http://127.0.0.1:8000/api';
        
        // Initialize Lucide icons
        lucide.createIcons();

        // Theme Management
        const ThemeManager = {
            init() {
                this.loadTheme();
                this.bindEvents();
            },
            loadTheme() {
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
            },
            toggleTheme() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            },
            bindEvents() {
                document.querySelector('.theme-toggle')?.addEventListener('click', () => this.toggleTheme());
            }
        };

        function logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/';
        }

        // Update user info and show role-based menu
        const user = JSON.parse(localStorage.getItem('user'));
        if (user) {
            document.getElementById('user-display-name').textContent = user.full_name;
            document.getElementById('user-display-role').textContent = getRoleDisplayName(user.role);
            
            // Show menu items based on role
            if (user.role === 'admin') {
                document.querySelector('.admin-only').style.display = 'block';
                document.querySelector('.warehouse-only').style.display = 'block';
                document.querySelector('.salesman-only').style.display = 'block';
            } else if (user.role === 'warehouse_keeper') {
                document.querySelector('.warehouse-only').style.display = 'block';
            } else if (user.role === 'salesman') {
                document.querySelector('.salesman-only').style.display = 'block';
            }
        }

        function getRoleDisplayName(role) {
            const roles = {
                'admin': 'مدير النظام',
                'warehouse_keeper': 'أمين المخزن',
                'salesman': 'مسوق'
            };
            return roles[role] || 'مستخدم';
        }
        
        // Initialize theme
        ThemeManager.init();
    </script>
    @stack('scripts')
</body>
</html>
