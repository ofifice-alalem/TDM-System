@extends('layouts.app')

@section('title', 'فاتورة بيع جديدة')

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-title { font-size: 28px; font-weight: 800; color: var(--text-light); }
    body.dark-mode .page-title { color: var(--text-dark); }
    
    .form-card { background: var(--card-light); border-radius: 16px; padding: 32px; border: 1px solid var(--border-light); margin-bottom: 24px; }
    body.dark-mode .form-card { background: var(--card-dark); border-color: var(--border-dark); }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 8px; }
    .form-group select, .form-group input, .form-group textarea { width: 100%; padding: 12px 16px; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-light); color: var(--text-light); font-family: 'Tajawal', sans-serif; }
    body.dark-mode .form-group select, body.dark-mode .form-group input, body.dark-mode .form-group textarea { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .form-group select:focus, .form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
    
    .products-list { margin-bottom: 24px; }
    .product-item { display: grid; grid-template-columns: 1fr 150px 80px; gap: 16px; align-items: center; padding: 20px; background: var(--bg-light); border-radius: 12px; margin-bottom: 12px; border: 1px solid var(--border-light); }
    body.dark-mode .product-item { background: var(--bg-dark); border-color: var(--border-dark); }
    
    .btn-remove { padding: 10px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .btn-add-product { padding: 12px 24px; background: rgba(139, 92, 246, 0.1); color: var(--primary); border: 2px dashed var(--primary); border-radius: 12px; font-weight: 600; cursor: pointer; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; }
    
    .summary-card { background: rgba(139, 92, 246, 0.05); padding: 24px; border-radius: 16px; border: 1px solid rgba(139, 92, 246, 0.1); }
    .summary-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border-light); }
    .summary-row.total { font-size: 20px; font-weight: 800; color: var(--primary); border-bottom: none; padding-top: 16px; }
    
    .form-actions { display: flex; gap: 16px; justify-content: flex-end; margin-top: 32px; }
    .btn { padding: 12px 32px; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-secondary { background: var(--card-light); color: var(--text-light); border: 1px solid var(--border-light); }
    body.dark-mode .btn-secondary { background: var(--card-dark); color: var(--text-dark); border-color: var(--border-dark); }
    
    .alert { padding: 16px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; }
    .alert-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .alert-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">فاتورة بيع جديدة</h1>
</div>

<div id="alertContainer"></div>

<div class="form-card">
    <div class="form-group">
        <label>المتجر</label>
        <select id="storeSelect">
            <option value="">اختر المتجر</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>ملاحظات</label>
        <textarea id="notesInput" rows="2" placeholder="ملاحظات إضافية (اختياري)"></textarea>
    </div>
</div>

<div class="form-card">
    <h3 style="margin-bottom: 20px;">المنتجات</h3>
    <div class="products-list" id="productsList"></div>
    <button type="button" class="btn-add-product" onclick="addProductRow()">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        إضافة منتج
    </button>
</div>

<div class="summary-card">
    <div class="summary-row"><span>المجموع الفرعي:</span><span id="subtotalValue">0.00 د</span></div>
    <div class="summary-row"><span>خصم المنتجات:</span><span id="productDiscountValue">0.00 د</span></div>
    <div class="summary-row"><span>خصم الفاتورة:</span><span id="invoiceDiscountValue">0.00 د</span></div>
    <div class="summary-row total"><span>الإجمالي:</span><span id="totalValue">0.00 د</span></div>
</div>

<div class="form-actions">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='/marketer/sales'">إلغاء</button>
    <button type="button" class="btn btn-primary" onclick="submitInvoice()">إنشاء الفاتورة</button>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let stores = [], products = [], productRows = [];

    async function init() {
        try {
            const [storesRes, productsRes, stockRes] = await Promise.all([
                fetch('/api/stores', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }}),
                fetch('/api/products', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }}),
                fetch('/api/marketer/stock/actual', { headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }})
            ]);
            
            stores = (await storesRes.json()).data || [];
            const allProducts = (await productsRes.json()).data || [];
            const stock = (await stockRes.json()).data || [];
            
            products = allProducts.map(p => {
                const s = stock.find(st => st.product_id === p.id);
                return { ...p, stock: s ? s.quantity : 0 };
            }).filter(p => p.stock > 0);
            
            document.getElementById('storeSelect').innerHTML = '<option value="">اختر المتجر</option>' + stores.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
            addProductRow();
        } catch (error) {
            showAlert('فشل تحميل البيانات', 'error');
        }
    }

    function addProductRow() {
        const id = Date.now();
        productRows.push(id);
        const row = document.createElement('div');
        row.className = 'product-item';
        row.id = `product-row-${id}`;
        row.innerHTML = `
            <select class="product-select" data-id="${id}" onchange="updateRow(${id})">
                <option value="">اختر المنتج</option>
                ${products.map(p => `<option value="${p.id}" data-price="${p.current_price}" data-stock="${p.stock}">${p.name} - ${p.current_price} د (متوفر: ${p.stock})</option>`).join('')}
            </select>
            <input type="number" class="quantity-input" data-id="${id}" placeholder="الكمية" min="1" value="1" onchange="updateSummary()">
            <button type="button" class="btn-remove" onclick="removeProductRow(${id})">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </button>
        `;
        document.getElementById('productsList').appendChild(row);
    }

    function updateRow(id) {
        const select = document.querySelector(`.product-select[data-id="${id}"]`);
        const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
        const option = select.options[select.selectedIndex];
        if (option && option.value) {
            input.setAttribute('max', option.getAttribute('data-stock'));
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
        productRows.forEach(id => {
            const select = document.querySelector(`.product-select[data-id="${id}"]`);
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            if (select && input && select.value) {
                const option = select.options[select.selectedIndex];
                const price = parseFloat(option.getAttribute('data-price')) || 0;
                const qty = parseInt(input.value) || 0;
                subtotal += price * qty;
            }
        });
        
        document.getElementById('subtotalValue').textContent = subtotal.toFixed(2) + ' د';
        document.getElementById('productDiscountValue').textContent = '0.00 د';
        document.getElementById('invoiceDiscountValue').textContent = '0.00 د';
        document.getElementById('totalValue').textContent = subtotal.toFixed(2) + ' د';
    }

    async function submitInvoice() {
        const storeId = document.getElementById('storeSelect').value;
        const notes = document.getElementById('notesInput').value;
        
        if (!storeId) {
            showAlert('يرجى اختيار المتجر', 'error');
            return;
        }
        
        const items = [];
        for (const id of productRows) {
            const select = document.querySelector(`.product-select[data-id="${id}"]`);
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            if (select && input && select.value) {
                items.push({ product_id: select.value, quantity: parseInt(input.value) });
            }
        }
        
        if (items.length === 0) {
            showAlert('يرجى إضافة منتج واحد على الأقل', 'error');
            return;
        }
        
        try {
            const response = await fetch('/api/marketer/sales', {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ store_id: storeId, items, notes })
            });
            
            const result = await response.json();
            if (response.ok) {
                showAlert('تم إنشاء الفاتورة بنجاح', 'success');
                setTimeout(() => window.location.href = '/marketer/sales', 1500);
            } else {
                showAlert(result.message || 'حدث خطأ', 'error');
            }
        } catch (error) {
            showAlert('حدث خطأ أثناء إنشاء الفاتورة', 'error');
        }
    }

    function showAlert(message, type) {
        document.getElementById('alertContainer').innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => document.getElementById('alertContainer').innerHTML = '', 5000);
    }

    init();
</script>
@endpush
