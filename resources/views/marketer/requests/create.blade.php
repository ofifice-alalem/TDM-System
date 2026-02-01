@extends('layouts.app')

@section('title', 'طلب بضاعة جديد')

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
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border-light);
    }

    body.dark-mode .form-section-title {
        color: var(--text-dark);
        border-color: var(--border-dark);
    }

    .products-list {
        margin-bottom: 24px;
    }

    .product-item {
        display: grid;
        grid-template-columns: 1fr 150px 80px;
        gap: 16px;
        align-items: center;
        padding: 16px;
        background: var(--bg-light);
        border-radius: 12px;
        margin-bottom: 12px;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .product-item {
        background: var(--bg-dark);
        border-color: var(--border-dark);
    }

    .product-item select,
    .product-item input {
        width: 100%;
        padding: 10px 16px;
        border-radius: 8px;
        border: 1px solid var(--border-light);
        background: var(--card-light);
        color: var(--text-light);
        font-size: 14px;
        font-family: 'Tajawal', sans-serif;
    }

    body.dark-mode .product-item select,
    body.dark-mode .product-item input {
        background: var(--card-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .btn-remove {
        padding: 10px;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.3s ease;
    }

    .btn-remove:hover {
        background: rgba(239, 68, 68, 0.2);
    }

    .btn-add-product {
        padding: 12px 24px;
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary);
        border: 2px dashed var(--primary);
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-add-product:hover {
        background: rgba(139, 92, 246, 0.15);
    }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        margin-top: 32px;
    }

    .btn {
        padding: 12px 32px;
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
        background: rgba(139, 92, 246, 0.1);
    }

    .alert {
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 600;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">طلب بضاعة جديد</h1>
    <p class="page-subtitle">قم بإضافة المنتجات والكميات المطلوبة</p>
</div>

<div id="alertContainer"></div>

<div class="form-card">
    <h2 class="form-section-title">المنتجات المطلوبة</h2>
    
    <div class="products-list" id="productsList"></div>
    
    <button type="button" class="btn-add-product" onclick="addProductRow()">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        إضافة منتج
    </button>
</div>

<div class="form-actions">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='/marketer/requests'">إلغاء</button>
    <button type="button" class="btn btn-primary" onclick="submitRequest()">إرسال الطلب</button>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let products = [];
    let productRows = [];

    async function fetchProducts() {
        try {
            const [productsRes, stockRes] = await Promise.all([
                fetch('/api/products', {
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                }),
                fetch('/api/warehouse/main-stock', {
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                })
            ]);
            
            const productsData = await productsRes.json();
            const stockData = await stockRes.json();
            
            products = (productsData.data || productsData || []).map(p => {
                const stock = (stockData.data || stockData || []).find(s => s.product_id === p.id);
                return { ...p, stock_quantity: stock ? stock.quantity : 0 };
            });
            
            addProductRow();
        } catch (error) {
            console.error('Error:', error);
            showAlert('فشل تحميل المنتجات', 'error');
        }
    }

    function addProductRow() {
        const id = Date.now();
        productRows.push(id);
        
        const productsList = document.getElementById('productsList');
        const row = document.createElement('div');
        row.className = 'product-item';
        row.id = `product-row-${id}`;
        row.innerHTML = `
            <select class="product-select" data-id="${id}">
                <option value="">اختر المنتج</option>
                ${products.map(p => `<option value="${p.id}" data-stock="${p.stock_quantity}">${p.name} - ${p.current_price} دينار (متوفر: ${p.stock_quantity})</option>`).join('')}
            </select>
            <input type="number" class="quantity-input" data-id="${id}" placeholder="الكمية" min="1" value="1">
            <button type="button" class="btn-remove" onclick="removeProductRow(${id})">🗑️</button>
        `;
        productsList.appendChild(row);
    }

    function removeProductRow(id) {
        const row = document.getElementById(`product-row-${id}`);
        if (row) row.remove();
        productRows = productRows.filter(r => r !== id);
    }

    async function submitRequest() {
        const items = [];
        
        for (const id of productRows) {
            const productSelect = document.querySelector(`.product-select[data-id="${id}"]`);
            const quantityInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
            
            if (productSelect && quantityInput) {
                const productId = productSelect.value;
                const quantity = parseInt(quantityInput.value);
                
                if (productId && quantity > 0) {
                    items.push({ product_id: productId, quantity });
                }
            }
        }

        if (items.length === 0) {
            showAlert('يرجى إضافة منتج واحد على الأقل', 'error');
            return;
        }

        try {
            const response = await fetch('/api/marketer/requests', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ items })
            });

            const result = await response.json();
            
            if (response.ok) {
                showAlert('تم إنشاء الطلب بنجاح', 'success');
                setTimeout(() => window.location.href = '/marketer/requests', 1500);
            } else {
                showAlert(result.message || 'حدث خطأ أثناء إنشاء الطلب', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('حدث خطأ أثناء إرسال الطلب', 'error');
        }
    }

    function showAlert(message, type) {
        const container = document.getElementById('alertContainer');
        container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => container.innerHTML = '', 5000);
    }

    fetchProducts();
</script>
@endpush
