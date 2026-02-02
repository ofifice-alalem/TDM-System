@extends('layouts.app')

@section('title', 'مخزني الفعلي')

@push('styles')
<style>
    .page-header-alt {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .header-title-group {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .header-icon {
        width: 56px;
        height: 56px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .header-text h1 {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 4px;
        color: var(--text-light);
    }

    body.dark-mode .header-text h1 { color: var(--text-dark); }

    .header-text p {
        font-size: 14px;
        color: #64748b;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--card-light);
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }

    body.dark-mode .stat-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .stat-card.actual .stat-value { color: var(--success); }
    .stat-card.reserved .stat-value { color: var(--warning); }
    .stat-card.value .stat-value { color: var(--info); }

    .stat-unit {
        font-size: 14px;
        color: #64748b;
    }

    .tabs-container {
        background: rgba(100, 116, 139, 0.05);
        border-radius: 16px;
        padding: 6px;
        display: flex;
        gap: 8px;
        margin-bottom: 32px;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .tabs-container {
        border-color: var(--border-dark);
        background: rgba(255, 255, 255, 0.03);
    }

    .tab-btn {
        flex: 1;
        padding: 12px;
        border: none;
        background: transparent;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 16px;
        font-family: 'Tajawal', sans-serif;
    }

    .tab-btn.active {
        background: var(--card-light);
        color: var(--primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    body.dark-mode .tab-btn.active {
        background: var(--primary);
        color: white;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .product-card {
        background: var(--card-light);
        border-radius: 24px;
        padding: 28px;
        border: 1px solid var(--border-light);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        display: flex;
        flex-direction: column;
    }

    body.dark-mode .product-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }

    body.dark-mode .product-card:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .product-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .product-icon-box {
        width: 44px;
        height: 44px;
        background: rgba(139, 92, 246, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .product-card:hover .product-icon-box {
        background: var(--primary);
        color: white;
        transform: scale(1.1);
    }

    .product-main {
        text-align: right;
        margin-bottom: 28px;
    }

    .product-name {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--text-light);
    }

    body.dark-mode .product-name { color: var(--text-dark); }

    .product-barcode {
        font-size: 13px;
        color: #94a3b8;
        letter-spacing: 1px;
    }

    .product-divider {
        height: 1px;
        background: radial-gradient(circle, var(--border-light) 0%, transparent 100%);
        margin-bottom: 28px;
    }

    body.dark-mode .product-divider {
        background: radial-gradient(circle, var(--border-dark) 0%, transparent 100%);
    }

    .product-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .detail-label {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 600;
    }

    .detail-value {
        font-size: 18px;
        font-weight: 800;
    }

    .price-val { color: var(--success); }
    .stock-val { color: var(--text-light); }
    body.dark-mode .stock-val { color: var(--text-dark); }

    .search-filter {
        display: flex;
        gap: 16px;
        margin-bottom: 32px;
    }

    .search-container-alt {
        flex: 1;
        position: relative;
    }

    .search-container-alt input {
        width: 100%;
        padding: 14px 48px;
        border-radius: 16px;
        border: 1px solid var(--border-light);
        background: var(--card-light);
        color: var(--text-light);
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    body.dark-mode .search-container-alt input {
        background: var(--card-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .search-container-alt .search-icon {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: #94a3b8;
    }

    .content-header-premium {
        background: white;
        padding: 16px 24px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-light);
    }

    body.dark-mode .content-header-premium {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
    }

    .search-box-premium {
        position: relative;
        flex: 1;
        max-width: 500px;
    }

    .search-box-premium input {
        width: 100%;
        padding: 10px 40px 10px 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        font-size: 15px;
        font-weight: 500;
        direction: rtl;
        transition: all 0.3s ease;
        font-family: 'Tajawal', sans-serif;
    }

    body.dark-mode .search-box-premium input {
        background: var(--bg-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .search-icon-svg {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .section-title-premium {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
    }

    body.dark-mode .section-title-premium {
        color: var(--text-dark);
    }

    .btn-add-request {
        padding: 10px 24px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Tajawal', sans-serif;
    }

    .btn-add-request:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    /* Override grid for image matching */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card actual">
        <div class="stat-label">المخزون الفعلي</div>
        <div class="stat-value" id="actualTotal">0</div>
        <div class="stat-unit">قطعة</div>
    </div>
    <div class="stat-card reserved">
        <div class="stat-label">المحجوز</div>
        <div class="stat-value" id="reservedTotal">0</div>
        <div class="stat-unit">قطعة</div>
    </div>
    <div class="stat-card value">
        <div class="stat-label">القيمة الكلية</div>
        <div class="stat-value" id="totalValue">0.00</div>
        <div class="stat-unit">دينار</div>
    </div>
</div>

<div class="tabs-container">
    <button class="tab-btn active" onclick="switchTab('actual', this)">المخزون الفعلي</button>
    <button class="tab-btn" onclick="switchTab('reserved', this)">المحجوز</button>
</div>

<div class="content-header-premium">
    <div class="top-row">
        <h2 class="section-title-premium">إدارة المنتجات</h2>
        <div style="display: flex; align-items: center; gap: 16px; flex: 1; justify-content: flex-end;">
            <div class="search-box-premium">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon-svg"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="searchInput" placeholder="بحث بالاسم أو الباركود...">
            </div>
            <button class="btn-add-request" onclick="window.location.href='/marketer/requests/create'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                إضافة طلب
            </button>
        </div>
    </div>
</div>

<div class="products-grid" id="productsGrid">
    <div class="empty-state">
        <div class="empty-art">🔄</div>
        <h3>جاري تحميل البيانات</h3>
        <p>يرجى الانتظار قليلاً بينما نقوم بجلب تفاصيل المخزون الخاص بك...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let currentTab = 'actual';
    let allData = { actual: [], reserved: [], products: [] };

    async function fetchData() {
        try {
            const headers = { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' };
            const [resActual, resReserved, resProducts] = await Promise.all([
                fetch('/api/marketer/stock/actual', { headers }),
                fetch('/api/marketer/stock/reserved', { headers }),
                fetch('/api/products', { headers })
            ]);

            const actual = await resActual.json();
            const reserved = await resReserved.json();
            const products = await resProducts.json();

            allData = {
                actual: actual.data || [],
                reserved: reserved.data || [],
                products: products.data || products || []
            };

            updateStats();
            renderProducts();
        } catch (error) {
            console.error('Fetch Error:', error);
            showError();
        }
    }

    function updateStats() {
        const actualCount = allData.actual.reduce((s, i) => s + parseInt(i.quantity || 0), 0);
        const reservedCount = allData.reserved.reduce((s, i) => s + parseInt(i.quantity || 0), 0);
        const value = allData.actual.reduce((s, i) => {
            const p = allData.products.find(x => x.id === i.product_id);
            return s + (p ? parseFloat(p.current_price || 0) * parseInt(i.quantity || 0) : 0);
        }, 0);

        animateValue('actualTotal', 0, actualCount, 1000);
        animateValue('reservedTotal', 0, reservedCount, 1000);
        document.getElementById('totalValue').textContent = value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function renderProducts(filter = '') {
        const grid = document.getElementById('productsGrid');
        const activeData = currentTab === 'actual' ? allData.actual : allData.reserved;
        
        // Group by product_id and sum quantities
        const groupedData = {};
        activeData.forEach(item => {
            if (groupedData[item.product_id]) {
                groupedData[item.product_id].quantity += parseInt(item.quantity || 0);
            } else {
                groupedData[item.product_id] = { ...item, quantity: parseInt(item.quantity || 0) };
            }
        });
        
        const grouped = Object.values(groupedData);
        
        const filtered = grouped.filter(item => {
            const product = allData.products.find(p => p.id === item.product_id);
            if (!product) return false;
            const searchStr = `${product.name} ${product.barcode || ''}`.toLowerCase();
            return searchStr.includes(filter.toLowerCase());
        });

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div class="empty-state">
                    <div class="empty-art">📦</div>
                    <h3>لا توجد نتائج</h3>
                    <p>${filter ? 'لم نجد أي منتج يطابق بحثك' : 'لا يوجد منتجات في هذا القسم حالياً'}</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = filtered.map(item => {
            const product = allData.products.find(p => p.id === item.product_id);
            if (!product) return '';

            return `
                <div class="product-card">
                    <div class="product-top">
                        <div class="product-icon-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                        <span class="badge-status">نشط</span>
                    </div>
                    <div class="product-main">
                        <h3 class="product-name" style="font-size: 20px;">${product.name}</h3>
                        <p class="product-barcode">الباركود: ${product.barcode || 'غير متوفر'}</p>
                    </div>
                    <div class="product-divider" style="margin-bottom: 20px;"></div>
                    <div class="product-details">
                        <div class="detail-item">
                            <span class="detail-label" style="font-weight: 500;">المخزون الحالي</span>
                            <span class="detail-value stock-val" style="font-size: 16px;">${item.quantity} وحدة</span>
                        </div>
                        <div class="detail-item" style="align-items: flex-end; text-align: left;">
                            <span class="detail-label" style="font-weight: 500;">السعر</span>
                            <span class="detail-value price-val" style="font-size: 18px; color: #10b981;">${parseFloat(product.current_price || 0).toFixed(2)} دينار</span>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function switchTab(tab, btn) {
        currentTab = tab;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderProducts(document.getElementById('searchInput').value);
    }

    function animateValue(id, start, end, duration) {
        const obj = document.getElementById(id);
        const range = end - start;
        let current = start;
        const increment = end > start ? 1 : -1;
        const stepTime = Math.abs(Math.floor(duration / range));
        
        if (range === 0) {
            obj.innerHTML = end;
            return;
        }

        const timer = setInterval(() => {
            current += increment;
            obj.innerHTML = current;
            if (current == end) clearInterval(timer);
        }, stepTime || 1);
    }

    function showError() {
        document.getElementById('productsGrid').innerHTML = `
            <div class="empty-state">
                <div class="empty-art">⚠️</div>
                <h3>حدث خطأ ما</h3>
                <p>تعذر تحميل بيانات المخزون. يرجى التحقق من اتصالك بالإنترنت والمحاولة مجدداً.</p>
                <button onclick="location.reload()" class="btn btn-primary" style="margin-top: 16px;">تحديث الصفحة</button>
            </div>
        `;
    }

    document.getElementById('searchInput').addEventListener('input', (e) => {
        renderProducts(e.target.value);
    });

    fetchData();
</script>
@endpush

