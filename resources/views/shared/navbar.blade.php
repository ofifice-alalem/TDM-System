<div class="top-nav">
    <div class="nav-left">
        <button class="logout-btn" title="تسجيل الخروج">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </button>
        <div class="user-profile">
            <div class="user-avatar-premium">
                👤
            </div>
            <div class="user-info-premium">
                <strong>{{ auth()->user()->name ?? 'مدير النظام' }}</strong>
                <span>المستخدم</span>
            </div>
        </div>
    </div>

    <div class="nav-right">
        <button class="action-icon-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        </button>
        <button class="action-icon-btn">
            <div class="pulse-badge"></div>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        </button>
        <button class="action-icon-btn" onclick="toggleTheme()" id="themeBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        </button>
    </div>
</div>

<style>
    .top-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding: 0 4px;
        background: transparent;
    }

    .nav-left {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .logout-btn {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: var(--card-light);
        border: 1px solid var(--border-light);
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    body.dark-mode .logout-btn {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.05);
        color: #94a3b8;
        box-shadow: none;
    }

    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border-color: rgba(239, 68, 68, 0.2);
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-premium {
        width: 44px;
        height: 44px;
        background: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 8px 16px rgba(139, 92, 246, 0.3);
    }

    .user-info-premium {
        display: flex;
        flex-direction: column;
        text-align: right;
    }

    .user-info-premium strong {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-light);
        line-height: 1.2;
    }

    body.dark-mode .user-info-premium strong { color: var(--text-dark); }

    .user-info-premium span {
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .action-icon-btn {
        width: 42px;
        height: 42px;
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.3s ease;
    }

    body.dark-mode .action-icon-btn { color: #94a3b8; }

    .action-icon-btn:hover {
        color: var(--primary);
        transform: translateY(-2px);
    }

    body.dark-mode .action-icon-btn:hover { color: white; }

    .pulse-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 8px;
        height: 8px;
        background: var(--danger);
        border-radius: 50%;
        border: 2px solid var(--bg-light);
    }

    body.dark-mode .pulse-badge { border-color: var(--bg-dark); }
</style>
