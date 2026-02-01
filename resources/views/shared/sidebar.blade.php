<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            </div>
            <div class="logo-text">
                <h3>تقنية</h3>
                <span>نظام إدارة التوزيع</span>
            </div>
        </div>
    </div>
    
    <div class="nav-menu">
        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </span>
            <span class="nav-label">لوحة التحكم</span>
        </a>
        <a href="/marketer/stock" class="nav-link {{ request()->is('marketer/stock') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </span>
            <span class="nav-label">المخزن الرئيسي</span>
        </a>
        <a href="#" class="nav-link">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </span>
            <span class="nav-label">فواتير المصنع</span>
        </a>
        <a href="#" class="nav-link">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
            </span>
            <span class="nav-label">طلبات المسوقين</span>
        </a>
        <a href="#" class="nav-link">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </span>
            <span class="nav-label">توثيق البيع</span>
        </a>
        <a href="#" class="nav-link">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M16 13H8"></path><path d="M16 17H8"></path><path d="M10 9H8"></path></svg>
            </span>
            <span class="nav-label">توثيق إيصالات القبض</span>
        </a>
        <a href="#" class="nav-link">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><polyline points="3 3 3 8 8 8"></polyline></svg>
            </span>
            <span class="nav-label">طلبات الإرجاع</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="#" class="nav-link">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            </span>
            <span class="nav-label">الإعدادات</span>
        </a>
    </div>
</div>

<style>
    .sidebar {
        position: fixed;
        right: 0;
        top: 0;
        width: 280px;
        height: 100vh;
        background: var(--card-light);
        padding: 24px 16px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        border-left: 1px solid var(--border-light);
    }

    body.dark-mode .sidebar {
        background: #111827;
        border: none;
    }

    .sidebar-header {
        margin-bottom: 48px;
        padding: 0 8px;
    }

    .logo-container {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 16px;
    }

    .logo-icon {
        width: 44px;
        height: 44px;
        background: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 8px 16px rgba(139, 92, 246, 0.4);
    }

    .logo-text {
        text-align: right;
    }

    .logo-text h3 {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-light);
        line-height: 1;
        margin-bottom: 4px;
    }

    body.dark-mode .logo-text h3 { color: white; }

    .logo-text span {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 500;
    }

    .nav-menu {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
    }

    .nav-link {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 14px;
        text-decoration: none;
        color: #64748b;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 15px;
    }

    body.dark-mode .nav-link { color: #94a3b8; }

    .nav-link:hover {
        color: var(--primary);
        background: rgba(139, 92, 246, 0.05);
    }

    body.dark-mode .nav-link:hover {
        color: white;
        background: rgba(255, 255, 255, 0.05);
    }

    .nav-link.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
    }

    .nav-icon {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-footer {
        margin-top: auto;
        padding-top: 24px;
        border-top: 1px solid var(--border-light);
    }

    body.dark-mode .sidebar-footer { border-color: rgba(255, 255, 255, 0.05); }
</style>
