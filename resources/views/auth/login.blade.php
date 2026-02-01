<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة التوزيع</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css">
    <script src="/js/config.js"></script>
</head>
<body>
    <div class="login-background">
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>
        <div class="bg-shape shape-3"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <!-- Left Panel - Login Form -->
            <div class="login-form-panel">
                <div class="form-header">
                    <h1 class="login-title">تسجيل الدخول</h1>
                    <p class="login-subtitle">أدخل بيانات الحساب للوصول للنظام</p>
                </div>
                
                <form id="loginForm">
                    <div class="form-group">
                        <label for="username" class="form-label">اسم المستخدم</label>
                        <div class="input-wrapper">
                            <input type="text" class="form-input" id="username" placeholder="admin / bestmarketer / salesman / warehouse" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <div class="input-wrapper">
                            <input type="password" class="form-input" id="password" placeholder="********" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">دخول للنظام</button>
                </form>
                
                <div id="error-message" class="alert alert-danger" style="display: none;"></div>
            </div>

            <!-- Right Panel - Quick Access -->
            <div class="quick-access-panel">
                <div class="theme-toggle" title="تبديل المظهر"></div>
                
                <div class="quick-header">
                    <div class="shield-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <h2 class="quick-access-title">الدخول السريع</h2>
                    <p class="quick-access-subtitle">اختر دورك الوظيفي للبدء فوراً</p>
                </div>
                
                <div class="quick-access-grid">
                    <div class="quick-login-btn admin-btn" onclick="quickLogin('admin', 'admin123')">
                        <div class="btn-content">
                            <span class="btn-title">دخول كمسؤول</span>
                            <span class="btn-subtitle">SYSTEM ADMINISTRATOR</span>
                        </div>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="quick-login-btn marketer-btn vip" onclick="quickLogin('keeper1', 'keeper123')">
                        <div class="btn-content">
                            <span class="btn-title">المسوق الأفضل <span class="badge-vip">VIP</span></span>
                            <span class="btn-subtitle">BEST MARKETER ACCOUNT</span>
                        </div>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                        </div>
                    </div>
                    
                    <div class="quick-login-btn salesman-btn" onclick="quickLogin('salesman1', 'sales123')">
                        <div class="btn-content">
                            <span class="btn-title">دخول كمندوب</span>
                            <span class="btn-subtitle">SALESMAN ACCOUNT</span>
                        </div>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        </div>
                    </div>
                    
                    <div class="quick-login-btn warehouse-btn" onclick="quickLogin('salesman2', 'sales123')">
                        <div class="btn-content">
                            <span class="btn-title">أمين مخزن</span>
                            <span class="btn-subtitle">WAREHOUSE ACCOUNT</span>
                        </div>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                    </div>
                </div>
                
                <div class="login-footer">
                    <div>جميع الحقوق محفوظة © تقنية التوزيع 2024</div>
                    <div class="system-version">إصدار النظام v2.5.0</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
        }

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            const btn = e.target.querySelector('.btn-primary');
            const originalText = btn.textContent;
            btn.textContent = 'جاري التحقق...';
            btn.disabled = true;
            
            try {
                const response = await fetch(apiUrl(API_CONFIG.AUTH.LOGIN), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    // Success animation or message
                    btn.textContent = 'تم بنجاح!';
                    btn.style.background = '#48bb78';
                    
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 800);
                } else {
                    showError(data.message || 'خطأ في بيانات الدخول');
                }
            } catch (error) {
                showError('لا يمكن الاتصال بالخادم حالياً');
            } finally {
                if (btn.textContent !== 'تم بنجاح!') {
                    btn.textContent = originalText;
                    btn.disabled = false;
                }
            }
        });

        function showError(msg) {
            const errorDiv = document.getElementById('error-message');
            errorDiv.textContent = msg;
            errorDiv.style.display = 'block';
            errorDiv.classList.add('shake');
            setTimeout(() => errorDiv.classList.remove('shake'), 500);
        }
    </script>
</body>
</html>