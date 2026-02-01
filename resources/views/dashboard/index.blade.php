@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="page-container">
    <!-- Page Header -->
    <div class="page-header" style="margin-bottom: 2rem;">
        <div class="page-title">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main);">لوحة التحكم</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">نظرة عامة سريعة على أداء النظام اليوم</p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="stats-grid-container">
        <div class="stats-card-premium">
            <div class="stats-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i data-lucide="package"></i>
            </div>
            <div class="stats-data">
                <h3>إجمالي المنتجات</h3>
                <p id="total-products">0</p>
            </div>
        </div>
        <div class="stats-card-premium">
            <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i data-lucide="check-circle"></i>
            </div>
            <div class="stats-data">
                <h3>المنتجات النشطة</h3>
                <p id="active-products">0</p>
            </div>
        </div>
        <div class="stats-card-premium">
            <div class="stats-icon" style="background: rgba(167, 139, 250, 0.1); color: #a78bfa;">
                <i data-lucide="database"></i>
            </div>
            <div class="stats-data">
                <h3>المخزون الرئيسي</h3>
                <p id="main-stock">0</p>
            </div>
        </div>
        <div class="stats-card-premium">
            <div class="stats-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i data-lucide="store"></i>
            </div>
            <div class="stats-data">
                <h3>إجمالي المتاجر</h3>
                <p id="total-stores">0</p>
            </div>
        </div>
    </div>

    <!-- Products Management Section -->
    <div class="section-container" style="margin-top: 3rem;">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main);">إدارة المنتجات</h2>
            <div class="search-bar" style="max-width: 350px;">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" id="product-search" placeholder="بحث بالاسم أو الباركود..." class="search-input">
            </div>
        </div>
        
        <div id="products-grid" class="products-grid">
            <!-- Products will be loaded here -->
            <div style="grid-column: 1/-1; text-align: center; padding: 4rem;">
                <i data-lucide="loader" class="animate-spin" style="margin: 0 auto 1rem; width: 40px; height: 40px;"></i>
                <p>جاري تحميل البيانات...</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Inline styles moved to dashboard.css or cleaned up here */
    .animate-spin { animation: spin 1s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endsection

@push('scripts')
<script>
    let allProducts = [];

    async function loadDashboard() {
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/';
            return;
        }

        try {
            const [productsRes, storesRes] = await Promise.all([
                fetch(`${API_BASE_URL}/products`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                }),
                fetch(`${API_BASE_URL}/stores`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                })
            ]);

            const productsData = await productsRes.json();
            const storesData = await storesRes.json();

            allProducts = productsData.data || [];
            const stores = storesData.data || [];
            const activeProducts = allProducts.filter(p => p.is_active);
            const totalStock = allProducts.reduce((sum, p) => sum + (p.main_stock?.quantity || 0), 0);

            document.getElementById('total-products').textContent = allProducts.length;
            document.getElementById('active-products').textContent = activeProducts.length;
            document.getElementById('main-stock').textContent = totalStock;
            document.getElementById('total-stores').textContent = stores.length;

            renderProducts();
            lucide.createIcons();
        } catch (error) {
            console.error('Error loading dashboard:', error);
        }
    }

    function renderProducts() {
        const searchTerm = document.getElementById('product-search').value.toLowerCase();
        const filtered = allProducts.filter(p => 
            p.name.toLowerCase().includes(searchTerm) || 
            (p.barcode && p.barcode.toLowerCase().includes(searchTerm))
        );

        const grid = document.getElementById('products-grid');
        if (filtered.length === 0) {
            grid.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 4rem; background: var(--card-bg); border-radius: 24px; border: 1px dashed var(--border-color);">
                    <i data-lucide="search-x" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p>لا توجد منتجات تطابق بحثك</p>
                </div>
            `;
        } else {
            grid.innerHTML = filtered.map(product => {
                const stock = product.main_stock?.quantity || 0;
                const stockPercent = Math.min((stock / 200) * 100, 100);
                const stockClass = stock > 100 ? 'stock-high' : stock > 50 ? 'stock-medium' : 'stock-low';
                
                return `
                    <div class="product-card">
                        <div class="product-card-header">
                            <div class="product-icon-box">
                                <i data-lucide="package"></i>
                            </div>
                            <div class="product-status-badge" 
                                 style="background: ${product.is_active ? 'rgba(16, 185, 129, 0.1)' : 'rgba(100, 116, 139, 0.1)'}; 
                                        color: ${product.is_active ? '#10b981' : '#64748b'};
                                        padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700;">
                                ${product.is_active ? 'نشط' : 'غير نشط'}
                            </div>
                        </div>
                        
                        <div class="product-info-main">
                            <h3 class="product-name" title="${product.name}">${product.name}</h3>
                            <span class="product-barcode">الباركود: ${product.barcode || 'غير متوفر'}</span>
                        </div>
                        
                        <div class="product-stats">
                            <div class="stat-item">
                                <span class="stat-label">المخزون الحالي</span>
                                <span class="stat-value">${stock} وحدة</span>
                                <div class="stock-indicator" style="margin-top: 8px;">
                                    <div class="stock-bar ${stockClass}" style="width: ${stockPercent}%"></div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">السعر</span>
                                <span class="stat-value product-price" style="font-size: 1.25rem;">${product.current_price} ريال</span>
                            </div>
                        </div>
                        
                        <div class="product-actions" style="border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                            <button class="btn-product-action primary">
                                <i data-lucide="eye" style="width: 16px;"></i>
                                عرض التفاصيل
                            </button>
                            <button class="btn-product-action" title="تعديل">
                                <i data-lucide="edit-3" style="width: 16px;"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }
        lucide.createIcons();
    }

    document.getElementById('product-search').addEventListener('input', renderProducts);

    loadDashboard();
</script>
@endpush
