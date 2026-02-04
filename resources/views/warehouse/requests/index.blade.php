@extends('layouts.app')

@section('title', 'طلبات المسوقين')

@push('styles')
<style>
    :root {
        --card-radius: 20px;
        --accent-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    }

    .page-header {
        margin-bottom: 32px;
        animation: fadeInDown 0.6s ease-out;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    body.dark-mode .page-title { color: var(--text-dark); }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: var(--card-light);
        border-radius: var(--card-radius);
        padding: 24px;
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
        animation: fadeInDown 0.6s ease-out;
    }

    body.dark-mode .stat-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--primary);
    }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-light);
    }

    body.dark-mode .stat-value { color: var(--text-dark); }

    /* Products Section */
    .products-section {
        background: var(--card-light);
        border-radius: var(--card-radius);
        padding: 24px;
        border: 1px solid var(--border-light);
        margin-bottom: 32px;
        box-shadow: var(--shadow-sm);
    }

    body.dark-mode .products-section {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-light);
    }

    body.dark-mode .section-header { border-color: var(--border-dark); }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    body.dark-mode .section-title { color: var(--text-dark); }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
    }

    .product-card-premium {
        background: var(--bg-light);
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    body.dark-mode .product-card-premium {
        background: rgba(255, 255, 255, 0.03);
        border-color: var(--border-dark);
    }

    .product-card-premium::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--accent-gradient);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card-premium:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .product-card-premium:hover::before { opacity: 1; }

    .product-icon {
        width: 44px;
        height: 44px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        color: var(--primary);
    }

    .product-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 40px;
    }

    body.dark-mode .product-name { color: var(--text-dark); }

    .product-qty {
        font-size: 24px;
        font-weight: 900;
        color: var(--primary);
        letter-spacing: -0.5px;
    }

    .product-unit {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Filters and Tabs */
    .tabs-container {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        background: rgba(100, 116, 139, 0.05);
        padding: 6px;
        border-radius: 14px;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .tabs-container { border-color: var(--border-dark); }

    .tab-btn {
        flex: 1;
        padding: 10px;
        border: none;
        background: transparent;
        border-radius: 10px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Tajawal', sans-serif;
    }

    .tab-btn.active {
        background: var(--card-light);
        color: var(--primary);
        box-shadow: var(--shadow-sm);
    }

    body.dark-mode .tab-btn.active {
        background: var(--primary);
        color: white;
    }

    .filters-bar {
        background: var(--card-light);
        padding: 24px;
        border-radius: var(--card-radius);
        margin-bottom: 24px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
    }

    body.dark-mode .filters-bar {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 8px;
    }

    .search-input-premium {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        background: var(--bg-light);
        color: var(--text-light);
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: 'Tajawal', sans-serif;
    }

    body.dark-mode .search-input-premium {
        background: var(--bg-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .search-input-premium:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        outline: none;
    }

    /* Requests List */
    .requests-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .request-card-premium {
        background: var(--card-light);
        border-radius: var(--card-radius);
        padding: 24px;
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
        animation: fadeInRight 0.6s ease-out;
    }

    body.dark-mode .request-card-premium {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .request-card-premium:hover {
        transform: scale(1.01);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .request-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .request-info-group {
        flex: 1;
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1fr;
        align-items: center;
        gap: 20px;
    }

    .invoice-num {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
    }

    .marketer-info {
        display: flex;
        flex-direction: column;
    }

    .marketer-label {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 600;
    }

    .marketer-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-light);
    }
    body.dark-mode .marketer-name { color: var(--text-dark); }

    .date-info {
        display: flex;
        flex-direction: column;
    }

    .status-badge-premium {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        justify-self: start;
    }

    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-documented { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; }

    .btn-action {
        padding: 10px 20px;
        background: var(--primary);
        color: white;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .empty-state-premium {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state-premium svg {
        width: 64px;
        height: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        إدارة طلبات المسوقين
    </h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">إجمالي المخزون الرئيسي</span>
            <span class="stat-value" id="mainStockTotal">0</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">أصناف متوفرة للطلب</span>
            <span class="stat-value" id="availableProductsCount">0</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">طلبات قيد المعالجة</span>
            <span class="stat-value" id="pendingRequestsCount">0</span>
        </div>
    </div>
</div>

<div class="products-section">
    <div class="section-header">
        <h2 class="section-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
            المخزون الرئيسي الحالي
        </h2>
        <div id="lastUpdated" style="font-size: 12px; color: #94a3b8;">جاري التحديث...</div>
    </div>
    <div class="products-grid" id="productsGrid">
        <div class="empty-state-premium">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <h3>جاري تحميل المنتجات...</h3>
        </div>
    </div>
</div>

<div class="tabs-container">
    <button class="tab-btn active" onclick="switchTab('all', this)">كل الطلبات</button>
    <button class="tab-btn" onclick="switchTab('pending', this)">قيد الانتظار</button>
    <button class="tab-btn" onclick="switchTab('approved', this)">المعتمدة</button>
    <button class="tab-btn" onclick="switchTab('documented', this)">الموثقة</button>
    <button class="tab-btn" onclick="switchTab('rejected', this)">المرفوضة</button>
    <button class="tab-btn" onclick="switchTab('cancelled', this)">الملغية</button>
</div>

<div class="filters-bar">
    <div class="filter-group">
        <label>رقم الفاتورة</label>
        <input type="text" id="searchInput" class="search-input-premium" placeholder="ابحث برقم الفاتورة...">
    </div>
    <div class="filter-group">
        <label>اسم المسوق</label>
        <input type="text" id="marketerSearch" class="search-input-premium" placeholder="ابحث باسم المسوق...">
    </div>
</div>

<div class="requests-list" id="requestsList">
    <div class="empty-state-premium">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <h3>جاري تحميل الطلبات...</h3>
    </div>
</div>

@include('shared.pagination')
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allRequests = [];
    let currentStatus = 'all';

    async function fetchData(page = 1) {
        try {
            let url = `/api/warehouse/requests?page=${page}`;
            if (currentStatus !== 'all') url += `&status=${currentStatus}`;
            
            const [reqResponse, prodResponse] = await Promise.all([
                fetch(url, { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } }),
                fetch('/api/products', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } })
            ]);
            
            const reqResult = await reqResponse.json();
            const prodResult = await prodResponse.json();
            
            allRequests = reqResult.data?.data || reqResult.data || [];
            const products = Array.isArray(prodResult) ? prodResult : (prodResult.data?.data || prodResult.data || []);
            
            updatePagination(reqResult.data);
            updateStats(allRequests, products);
            renderProducts(products);
            renderRequests();
            
            document.getElementById('lastUpdated').textContent = 'آخر تحديث: ' + new Date().toLocaleTimeString('ar-EG');
        } catch (error) {
            console.error('Error:', error);
            showError();
        }
    }

    function updateStats(requests, products) {
        const totalStock = products.reduce((sum, p) => sum + (p.main_stock_quantity || 0), 0);
        const availableCount = products.filter(p => p.main_stock_quantity > 0).length;
        const pendingCount = requests.filter(r => r.status === 'pending').length;

        animateValue('mainStockTotal', totalStock);
        animateValue('availableProductsCount', availableCount);
        animateValue('pendingRequestsCount', pendingCount);
    }

    function animateValue(id, end) {
        const obj = document.getElementById(id);
        let start = 0;
        const duration = 1000;
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const value = Math.floor(progress * end);
            obj.innerHTML = value.toLocaleString('en-US');
            if (progress < 1) requestAnimationFrame(update);
        }
        requestAnimationFrame(update);
    }

    function renderProducts(products) {
        const grid = document.getElementById('productsGrid');
        
        if (!products || products.length === 0) {
            grid.innerHTML = '<div class="empty-state-premium">📦 <h3>لا توجد منتجات متوفرة</h3></div>';
            return;
        }

        grid.innerHTML = products.map(p => `
            <div class="product-card-premium">
                <div class="product-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                </div>
                <div class="product-name" title="${p.name}">${p.name}</div>
                <div class="product-qty">${(p.main_stock_quantity || 0).toLocaleString('en-US')}</div>
                <div class="product-unit">وحدة متوفرة</div>
            </div>
        `).join('');
    }

    function switchTab(status, btn) {
        currentStatus = status;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        fetchData(1);
    }

    function renderRequests() {
        const container = document.getElementById('requestsList');
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const marketerValue = document.getElementById('marketerSearch').value.toLowerCase();
        
        let filtered = allRequests.filter(req => {
            if (currentStatus !== 'all' && req.status !== currentStatus) return false;
            if (searchValue && !req.invoice_number.toLowerCase().includes(searchValue)) return false;
            if (marketerValue && !req.marketer_name.toLowerCase().includes(marketerValue)) return false;
            return true;
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="empty-state-premium">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                    <h3>لا توجد طلبات تطابق بحثك</h3>
                </div>`;
            return;
        }

        container.innerHTML = filtered.map(req => {
            const statusMap = {
                'pending': { 
                    label: 'قيد الانتظار', 
                    class: 'status-pending', 
                    bg: 'rgba(245, 158, 11, 0.1)',
                    color: '#f59e0b',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>' 
                },
                'approved': { 
                    label: 'معتمد', 
                    class: 'status-approved', 
                    bg: 'rgba(16, 185, 129, 0.1)',
                    color: '#10b981',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' 
                },
                'documented': { 
                    label: 'موثق', 
                    class: 'status-documented', 
                    bg: 'rgba(59, 130, 246, 0.1)',
                    color: '#3b82f6',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>' 
                },
                'rejected': { 
                    label: 'مرفوض', 
                    class: 'status-rejected', 
                    bg: 'rgba(239, 68, 68, 0.1)',
                    color: '#ef4444',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>' 
                },
                'cancelled': { 
                    label: 'ملغي', 
                    class: 'status-cancelled', 
                    bg: 'rgba(100, 116, 139, 0.1)',
                    color: '#64748b',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line></svg>' 
                }
            };
            const status = statusMap[req.status] || { label: req.status, class: '', icon: '', bg: 'rgba(139, 92, 246, 0.1)', color: 'var(--primary)' };
            const createdAtDate = new Date(req.created_at);
            const dateStr = createdAtDate.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
            const timeStr = createdAtDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

            return `
                <div class="request-card-premium">
                    <div class="request-icon-box" style="background: ${status.bg}; color: ${status.color};">
                        ${status.icon}
                    </div>
                    <div class="request-info-group">
                        <div class="invoice-num">#${req.invoice_number}</div>
                        <div class="marketer-info">
                            <span class="marketer-label">المسوق</span>
                            <span class="marketer-name">${req.marketer_name}</span>
                        </div>
                        <div class="date-info">
                            <span class="marketer-label">التوقيت</span>
                            <span class="marketer-name" style="font-size: 13px;">${dateStr} | ${timeStr}</span>
                        </div>
                        <div class="status-badge-premium ${status.class}">
                            ${status.icon}
                            ${status.label}
                        </div>
                    </div>
                    <a href="/warehouse/requests/${req.id}" class="btn-action">
                        تفاصيل
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            `;
        }).join('');
    }

    function showError() {
        document.getElementById('requestsList').innerHTML = '<div class="empty-state-premium">⚠️ <h3>خطأ في تحميل البيانات</h3></div>';
    }

    document.getElementById('searchInput').addEventListener('input', renderRequests);
    document.getElementById('marketerSearch').addEventListener('input', renderRequests);

    fetchData();
</script>
@endpush
