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

        @if(auth()->user()->role->name === 'salesman')
        <a href="/marketer/stock" class="nav-link {{ request()->is('marketer/stock') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </span>
            <span class="nav-label">مخزني</span>
        </a>
        <a href="/stores" class="nav-link {{ request()->is('stores*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            </span>
            <span class="nav-label">ديون المتاجر</span>
        </a>
        <a href="/marketer/requests" class="nav-link {{ request()->is('marketer/requests*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </span>
            <span class="nav-label">طلبات البضاعة</span>
        </a>
        <a href="/marketer/returns" class="nav-link {{ request()->is('marketer/returns*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            </span>
            <span class="nav-label">إرجاع البضاعة</span>
        </a>
        <a href="/marketer/sales" class="nav-link {{ request()->is('marketer/sales*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            </span>
            <span class="nav-label">فواتير البيع</span>
        </a>
        <a href="/marketer/payments" class="nav-link {{ request()->is('marketer/payments*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
            </span>
            <span class="nav-label">إيصالات القبض</span>
        </a>
        <a href="/marketer/withdrawals" class="nav-link {{ request()->is('marketer/withdrawals*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </span>
            <span class="nav-label">سحب الأرباح</span>
        </a>
        <a href="/marketer/store-returns" class="nav-link {{ request()->is('marketer/store-returns*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
            </span>
            <span class="nav-label">إرجاع من المتاجر</span>
        </a>
        @endif

        @if(auth()->user()->role->name === 'warehouse_keeper')
        <a href="/stores" class="nav-link {{ request()->is('stores*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            </span>
            <span class="nav-label">ديون المتاجر</span>
        </a>
        <a href="/warehouse/requests" class="nav-link {{ request()->is('warehouse/requests*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </span>
            <span class="nav-label">طلبات المسوقين</span>
        </a>
        <a href="/warehouse/returns" class="nav-link {{ request()->is('warehouse/returns*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            </span>
            <span class="nav-label">إرجاع البضاعة</span>
        </a>
        <a href="/warehouse/sales" class="nav-link {{ request()->is('warehouse/sales*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            </span>
            <span class="nav-label">فواتير البيع</span>
        </a>
        <a href="/warehouse/payments" class="nav-link {{ request()->is('warehouse/payments*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
            </span>
            <span class="nav-label">إيصالات القبض</span>
        </a>
        <a href="/warehouse/store-returns" class="nav-link {{ request()->is('warehouse/store-returns*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
            </span>
            <span class="nav-label">إرجاع من المتاجر</span>
        </a>
        <a href="/admin/withdrawals" class="nav-link {{ request()->is('admin/withdrawals*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </span>
            <span class="nav-label">طلبات السحب</span>
        </a>
        @endif

        @if(auth()->user()->role->name === 'admin')
        <a href="/stores" class="nav-link {{ request()->is('stores*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            </span>
            <span class="nav-label">ديون المتاجر</span>
        </a>
        <a href="/admin/products" class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </span>
            <span class="nav-label">إدارة المنتجات</span>
        </a>
        <a href="/admin/users" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </span>
            <span class="nav-label">إدارة المستخدمين</span>
        </a>
        <a href="/admin/marketers" class="nav-link {{ request()->is('admin/marketers*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </span>
            <span class="nav-label">إدارة المسوقين</span>
        </a>
        <a href="/admin/sales" class="nav-link {{ request()->is('admin/sales*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            </span>
            <span class="nav-label">فواتير البيع</span>
        </a>
        <a href="/admin/withdrawals" class="nav-link {{ request()->is('admin/withdrawals*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </span>
            <span class="nav-label">طلبات السحب</span>
        </a>
        @endif
    </div>

    <div class="sidebar-footer">
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
