@extends('layouts.app')

@section('title', 'فاتورة بيع جديدة')

@push('styles')
<style>
    .page-header {
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-light);
        margin-bottom: 8px;
    }

    body.dark-mode .page-title { color: var(--text-dark); }

    .page-subtitle {
        color: #64748b;
        font-size: 14px;
    }

    .form-card {
        background: var(--card-light);
        border-radius: 16px;
        padding: 32px;
        border: 1px solid var(--border-light);
        margin-bottom: 24px;
    }

    body.dark-mode .form-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .form-section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    body.dark-mode .form-section-title {
        color: var(--text-dark);
        border-color: var(--border-dark);
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-light);
    }

    body.dark-mode .form-group label { color: var(--text-dark); }

    .form-group select,
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        background: var(--bg-light);
        color: var(--text-light);
        font-size: 14px;
        font-family: 'Tajawal', sans-serif;
        transition: all 0.3s ease;
    }

    body.dark-mode .form-group select,
    body.dark-mode .form-group input,
    body.dark-mode .form-group textarea {
        background: var(--bg-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .form-group select:focus,
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .products-list {
        margin-bottom: 24px;
    }

    .product-item {
        display: grid;
        grid-template-columns: 1fr 150px 80px;
        gap: 16px;
        align-items: start;
        padding: 24px;
        background: var(--bg-light);
        border-radius: 16px;
        margin-bottom: 16px;
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }

    body.dark-mode .product-item {
        background: var(--bg-dark);
        border-color: var(--border-dark);
    }

    .product-item:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.08);
    }

    .product-select-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .product-item select,
    .product-item input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-light);
        background: var(--card-light);
        color: var(--text-light);
        font-size: 14px;
        font-family: 'Tajawal', sans-serif;
        transition: all 0.3s ease;
    }

    body.dark-mode .product-item select,
    body.dark-mode .product-item input {
        background: var(--card-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .product-info {
        font-size: 12px;
        color: #64748b;
        display: flex;
        gap: 16px;
        font-weight: 600;
        padding: 0 4px;
    }

    .product-info span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .promo-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .btn-remove {
        padding: 8px;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        align-self: flex-start;
        height: 38px;
        width: 38px;
    }

    .btn-remove:hover {
        background: rgba(239, 68, 68, 0.2);
        transform: scale(1.05);
    }

    .btn-remove svg {
        width: 16px;
        height: 16px;
    }

    .btn-add-product {
        padding: 14px 24px;
        background: rgba(139, 92, 246, 0.05);
        color: var(--primary);
        border: 2px dashed var(--primary);
        border-radius: 14px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-add-product:hover {
        background: rgba(139, 92, 246, 0.1);
        border-style: solid;
    }

    .summary-card {
        background: var(--card-light);
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--border-light);
        margin-bottom: 24px;
    }

    body.dark-mode .summary-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-light);
        font-weight: 600;
        color: #64748b;
    }

    body.dark-mode .summary-row { border-color: var(--border-dark); }

    .summary-row.total {
        font-size: 22px;
        font-weight: 800;
        color: var(--primary);
        border-bottom: none;
        padding-top: 20px;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        margin-top: 32px;
    }

    .btn {
        padding: 14px 36px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Tajawal', sans-serif;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-secondary {
        background: var(--card-light);
        color: var(--text-light);
        border: 1px solid var(--border-light);
    }

    body.dark-mode .btn-secondary {
        background: var(--card-dark);
        color: var(--text-dark);
        border-color: var(--border-dark);
    }

    .btn-secondary:hover {
        background: rgba(139, 92, 246, 0.05);
    }

    .alert {
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 600;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .alert-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .alert-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">فاتورة بيع جديدة</h1>
    <p class="page-subtitle">قم بتسجيل عملية بيع جديدة وتحديد المتجر والمنتجات</p>
</div>

<div id="alertContainer"></div>

<div class="form-card">
    <h2 class="form-section-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        بيانات المتجر
    </h2>
    <div class="form-group">
        <label>المتجر</label>
        <select id="storeSelect">
            <option value="">اختر المتجر</option>
        </select>
    </div>
    
    <div class="form-group" style="margin-bottom: 0;">
        <label>ملاحظات الفاتورة</label>
        <textarea id="notesInput" rows="2" placeholder="أدخل أي ملاحظات إضافية هنا..."></textarea>
    </div>
</div>

<div class="form-card">
    <h2 class="form-section-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
        المنتجات المباعة
    </h2>
    <div class="products-list" id="productsList"></div>
    <button type="button" class="btn-add-product" onclick="addProductRow()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        إضافة منتج للفاتورة
    </button>
</div>

<div class="summary-card">
    <div class="summary-row">
        <span>المجموع الفرعي:</span>
        <span id="subtotalValue">0.00 دينار</span>
    </div>
    <div class="summary-row">
        <span>خصم المنتجات:</span>
        <span id="productDiscountValue">0.00 دينار</span>
    </div>
    <div class="summary-row">
        <span>خصم الفاتورة:</span>
        <span id="invoiceDiscountValue">0.00 دينار</span>
    </div>
    <div class="summary-row total">
        <span>الإجمالي النهائي:</span>
        <span id="totalValue">0.00 دينار</span>
    </div>
</div>

<div class="form-actions">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='/marketer/sales'">إلغاء العملية</button>
    <button type="button" class="btn btn-primary" onclick="submitInvoice()">إنشاء الفاتورة الآن</button>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let stores = [], products = [], productRows = [];

    async function init() {
        try {
            const [storesRes, productsRes, stockRes, promotionsRes, discountsRes] = await Promise.all([
                fetch('/api/stores', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }}),
                fetch('/api/products', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }}),
                fetch('/api/marketer/stock/actual', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }}),
                fetch('/api/marketer/promotions/active', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }}),
                fetch('/api/marketer/discounts/active', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }})
            ]);
            
            const storesData = await storesRes.json();
            const productsData = await productsRes.json();
            const stockData = await stockRes.json();
            const promotionsData = await promotionsRes.json();
            const discountsData = await discountsRes.json();
            
            stores = storesData.data || storesData || [];
            const allProducts = productsData.data || productsData || [];
            const stock = stockData.data || stockData || [];
            const allPromotions = promotionsData.data || [];
            window.discounts = discountsData.data || [];
            
            window.promotions = {};
            allPromotions.forEach(p => window.promotions[p.product_id] = p);
            
            products = allProducts.map(p => {
                const s = stock.find(st => st.product_id === p.id);
                return { ...p, current_price: p.price || p.current_price, stock: s ? s.quantity : 0 };
            }).filter(p => p.stock > 0);
            
            document.getElementById('storeSelect').innerHTML = '<option value="">اختر المتجر</option>' + stores.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
            
            if (stores.length === 0) {
                showAlert('لا توجد متاجر متاحة. يرجى إضافة متاجر أولاً من قسم المتاجر.', 'error');
            }
            
            addProductRow();
        } catch (error) {
            showAlert('فشل تحميل البيانات الأساسية', 'error');
        }
    }

    function addProductRow() {
        const id = Date.now();
        productRows.push(id);
        const row = document.createElement('div');
        row.className = 'product-item';
        row.id = `product-row-${id}`;
        row.innerHTML = `
            <div class="product-select-wrapper">
                <select class="product-select" data-id="${id}" onchange="updateRow(${id})">
                    <option value="">اختر المنتج</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.current_price}" data-stock="${p.stock}">${p.name} - ${parseFloat(p.current_price).toFixed(2)} د (متوفر: ${p.stock})</option>`).join('')}
                </select>
                <div class="product-info" id="info-${id}" style="display: none;">
                    <span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        السعر: <span id="price-${id}">0</span> د
                    </span>
                    <span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        المتوفر بمخزنك: <span id="stock-${id}">0</span>
                    </span>
                    <span id="promo-${id}" style="display: none;">
                        <span class="promo-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            <span id="promo-text-${id}"></span>
                        </span>
                    </span>
                </div>
            </div>
            <input type="number" class="quantity-input" data-id="${id}" placeholder="الكمية" min="1" value="1" onchange="updateSummary()">
            <button type="button" class="btn-remove" onclick="removeProductRow(${id})" title="حذف المنتج">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
            </button>
        `;
        document.getElementById('productsList').appendChild(row);
    }

    function updateRow(id) {
        const select = document.querySelector(`.product-select[data-id="${id}"]`);
        const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
        const option = select.options[select.selectedIndex];
        const info = document.getElementById(`info-${id}`);
        const priceSpan = document.getElementById(`price-${id}`);
        const stockSpan = document.getElementById(`stock-${id}`);
        const promoSpan = document.getElementById(`promo-${id}`);
        const promoText = document.getElementById(`promo-text-${id}`);
        
        if (option && option.value) {
            const price = option.getAttribute('data-price');
            const stock = option.getAttribute('data-stock');
            const productId = option.value;
            input.setAttribute('max', stock);
            info.style.display = 'flex';
            priceSpan.textContent = parseFloat(price).toFixed(2);
            stockSpan.textContent = stock;
            
            if (window.promotions[productId]) {
                const promo = window.promotions[productId];
                promoSpan.style.display = 'block';
                promoText.textContent = `🎁 اشتري ${promo.min_quantity} واحصل على ${promo.free_quantity} مجاناً`;
            } else {
                promoSpan.style.display = 'none';
            }
            
            if (parseInt(input.value) > parseInt(stock)) {
                input.value = stock;
            }
        } else {
            info.style.display = 'none';
            promoSpan.style.display = 'none';
        }
        updateSummary();
    }

    function removeProductRow(id) {
        document.getElementById(`product-row-${id}`)?.remove();
        productRows = productRows.filter(r => r !== id);
        updateSummary();
    }

    function updateSummary() {
        let subtotal = 0;
        let productDiscount = 0;
        
        productRows.forEach(id => {
            const select = document.querySelector(`.product-select[data-id="${id}"]`);
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            if (select && input && select.value) {
                const option = select.options[select.selectedIndex];
                const price = parseFloat(option.getAttribute('data-price')) || 0;
                const qty = parseInt(input.value) || 0;
                const productId = select.value;
                
                subtotal += price * qty;
                
                if (window.promotions && window.promotions[productId]) {
                    const promo = window.promotions[productId];
                    if (qty >= promo.min_quantity) {
                        const times = Math.floor(qty / promo.min_quantity);
                        const freeQty = times * promo.free_quantity;
                        productDiscount += freeQty * price;
                    }
                }
            }
        });
        
        let invoiceDiscount = 0;
        if (window.discounts && subtotal > 0) {
            const applicable = window.discounts
                .filter(d => parseFloat(d.min_amount) <= subtotal)
                .sort((a, b) => parseFloat(b.min_amount) - parseFloat(a.min_amount))[0];
            
            if (applicable) {
                if (applicable.discount_type === 'percentage') {
                    invoiceDiscount = subtotal * (parseFloat(applicable.discount_percentage) / 100);
                } else {
                    invoiceDiscount = parseFloat(applicable.discount_amount);
                }
            }
        }
        
        const total = subtotal - invoiceDiscount;
        const formatter = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 });
        document.getElementById('subtotalValue').textContent = formatter.format(subtotal) + ' د';
        document.getElementById('productDiscountValue').textContent = formatter.format(productDiscount) + ' د';
        document.getElementById('invoiceDiscountValue').textContent = formatter.format(invoiceDiscount) + ' د';
        document.getElementById('totalValue').textContent = formatter.format(total) + ' د';
    }

    async function submitInvoice() {
        const storeId = document.getElementById('storeSelect').value;
        const notes = document.getElementById('notesInput').value;
        
        if (!storeId) {
            showAlert('يرجى اختيار المتجر أولاً', 'error');
            return;
        }
        
        const items = [];
        for (const id of productRows) {
            const select = document.querySelector(`.product-select[data-id="${id}"]`);
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            if (select && input && select.value) {
                const qty = parseInt(input.value);
                const max = parseInt(input.getAttribute('max'));
                if (qty > max) {
                    showAlert(`الكمية المطلوبة تتجاوز المتوفر (${max}) لأحد المنتجات`, 'error');
                    return;
                }
                items.push({ product_id: select.value, quantity: qty });
            }
        }
        
        if (items.length === 0) {
            showAlert('يرجى إضافة منتج واحد على الأقل للفاتورة', 'error');
            return;
        }
        
        try {
            const btn = document.querySelector('.btn-primary');
            btn.disabled = true;
            btn.textContent = 'جاري الحفظ...';
            
            const response = await fetch('/api/marketer/sales', {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ store_id: storeId, items, notes })
            });
            
            const result = await response.json();
            if (response.ok) {
                showAlert('تم إنشاء فاتورة البيع بنجاح', 'success');
                setTimeout(() => window.location.href = '/marketer/sales', 1500);
            } else {
                showAlert(result.message || 'حدث خطأ غير متوقع', 'error');
                btn.disabled = false;
                btn.textContent = 'إنشاء الفاتورة الآن';
            }
        } catch (error) {
            showAlert('حدث خطأ في الاتصال بالخادم', 'error');
            const btn = document.querySelector('.btn-primary');
            btn.disabled = false;
            btn.textContent = 'إنشاء الفاتورة الآن';
        }
    }

    function showAlert(message, type) {
        const container = document.getElementById('alertContainer');
        container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => container.innerHTML = '', 5000);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    init();
</script>
@endpush
