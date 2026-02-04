@extends('layouts.app')

@section('title', 'طلب إرجاع جديد')

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-title { font-size: 28px; font-weight: 800; color: var(--text-light); }
    body.dark-mode .page-title { color: var(--text-dark); }
    .form-card { background: var(--card-light); border-radius: 16px; padding: 32px; border: 1px solid var(--border-light); margin-bottom: 24px; }
    body.dark-mode .form-card { background: var(--card-dark); border-color: var(--border-dark); }
    .form-group { margin-bottom: 24px; }
    .form-group label { display: block; font-size: 14px; font-weight: 700; color: var(--text-light); margin-bottom: 8px; }
    body.dark-mode .form-group label { color: var(--text-dark); }
    .form-group select, .form-group input { width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--border-light); background: var(--bg-light); color: var(--text-light); font-family: 'Tajawal', sans-serif; }
    body.dark-mode .form-group select, body.dark-mode .form-group input { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .items-container { display: flex; flex-direction: column; gap: 16px; }
    .item-row { display: grid; grid-template-columns: 2fr 1fr auto; gap: 16px; align-items: end; padding: 16px; background: rgba(139, 92, 246, 0.05); border-radius: 12px; }
    .btn { padding: 12px 32px; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #7c3aed; }
    .btn-secondary { background: var(--card-light); color: var(--text-light); }
    body.dark-mode .btn-secondary { background: var(--card-dark); color: var(--text-dark); }
    .btn-danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .btn-danger:hover { background: #ef4444; color: white; }
    .btn-add-item { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .btn-add-item:hover { background: #10b981; color: white; }
    .form-actions { display: flex; gap: 16px; justify-content: flex-end; margin-top: 32px; }
    .alert { padding: 16px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; }
    .alert-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .alert-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">طلب إرجاع بضاعة من متجر</h1>
</div>

<div id="alertContainer"></div>

<div class="form-card">
    <div class="form-group">
        <label>اختر الفاتورة الأصلية *</label>
        <select id="invoiceSelect" onchange="loadInvoiceItems()">
            <option value="">-- اختر فاتورة --</option>
        </select>
    </div>

    <div id="itemsSection" style="display: none;">
        <h3 style="margin-bottom: 16px; font-weight: 700;">المنتجات المرجعة</h3>
        <div class="items-container" id="itemsContainer"></div>
        <button type="button" class="btn btn-add-item" onclick="addItemRow()" style="margin-top: 16px;">+ إضافة منتج</button>
    </div>
</div>

<div class="form-actions">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='/marketer/store-returns'">إلغاء</button>
    <button type="button" class="btn btn-primary" onclick="submitReturn()">إنشاء طلب الإرجاع</button>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let invoices = [];
    let selectedInvoice = null;
    let itemCounter = 0;

    async function loadInvoices() {
        try {
            const response = await fetch('/api/marketer/sales?status=approved', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            invoices = result.data?.data || result.data || [];
            
            const select = document.getElementById('invoiceSelect');
            invoices.forEach(inv => {
                const option = document.createElement('option');
                option.value = inv.id;
                option.textContent = `${inv.invoice_number} - ${inv.store_name} - ${parseFloat(inv.total_amount).toFixed(2)} دينار`;
                select.appendChild(option);
            });
        } catch (error) {
            showAlert('فشل تحميل الفواتير', 'error');
        }
    }

    async function loadInvoiceItems() {
        const invoiceId = document.getElementById('invoiceSelect').value;
        if (!invoiceId) {
            document.getElementById('itemsSection').style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`/api/marketer/sales/${invoiceId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            selectedInvoice = result.data;
            
            document.getElementById('itemsSection').style.display = 'block';
            document.getElementById('itemsContainer').innerHTML = '';
            itemCounter = 0;
            
            if (selectedInvoice.items && selectedInvoice.items.length > 0) {
                addItemRow();
            }
        } catch (error) {
            showAlert('فشل تحميل بنود الفاتورة', 'error');
        }
    }

    function addItemRow() {
        const container = document.getElementById('itemsContainer');
        const row = document.createElement('div');
        row.className = 'item-row';
        row.id = `item-${itemCounter}`;
        
        row.innerHTML = `
            <div class="form-group" style="margin: 0;">
                <label>المنتج</label>
                <select id="product-${itemCounter}" onchange="updateMaxQuantity(${itemCounter})">
                    <option value="">-- اختر منتج --</option>
                    ${selectedInvoice.items.map(item => `
                        <option value="${item.id}" data-product-id="${item.product_id}" data-max="${item.quantity}" data-price="${item.unit_price}">
                            ${item.product_name} (متاح: ${item.quantity})
                        </option>
                    `).join('')}
                </select>
            </div>
            <div class="form-group" style="margin: 0;">
                <label>الكمية المرجعة</label>
                <input type="number" id="quantity-${itemCounter}" min="1" placeholder="الكمية" oninput="validateQuantity(${itemCounter})">
            </div>
            <button type="button" class="btn btn-danger" onclick="removeItemRow(${itemCounter})">حذف</button>
        `;
        
        container.appendChild(row);
        itemCounter++;
    }

    function updateMaxQuantity(index) {
        const select = document.getElementById(`product-${index}`);
        const input = document.getElementById(`quantity-${index}`);
        const option = select.options[select.selectedIndex];
        
        if (option.value) {
            const max = parseInt(option.dataset.max);
            input.max = max;
            input.value = '';
        }
    }

    function validateQuantity(index) {
        const input = document.getElementById(`quantity-${index}`);
        const max = parseInt(input.max);
        
        if (input.value && max && parseInt(input.value) > max) {
            input.value = max;
        }
    }

    function removeItemRow(index) {
        document.getElementById(`item-${index}`).remove();
    }

    async function submitReturn() {
        const invoiceId = document.getElementById('invoiceSelect').value;
        if (!invoiceId) {
            showAlert('يرجى اختيار فاتورة', 'error');
            return;
        }

        const items = [];
        for (let i = 0; i < itemCounter; i++) {
            const productSelect = document.getElementById(`product-${i}`);
            const quantityInput = document.getElementById(`quantity-${i}`);
            
            if (productSelect && quantityInput && productSelect.value && quantityInput.value) {
                const option = productSelect.options[productSelect.selectedIndex];
                items.push({
                    sales_invoice_item_id: parseInt(productSelect.value),
                    product_id: parseInt(option.dataset.productId),
                    quantity: parseInt(quantityInput.value)
                });
            }
        }

        if (items.length === 0) {
            showAlert('يرجى إضافة منتج واحد على الأقل', 'error');
            return;
        }

        try {
            const response = await fetch('/api/marketer/store-returns', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ sales_invoice_id: parseInt(invoiceId), items })
            });

            const result = await response.json();
            
            if (response.ok) {
                showAlert('تم إنشاء طلب الإرجاع بنجاح', 'success');
                setTimeout(() => window.location.href = '/marketer/store-returns', 1500);
            } else {
                showAlert(result.message || 'حدث خطأ', 'error');
            }
        } catch (error) {
            showAlert('حدث خطأ أثناء إنشاء الطلب', 'error');
        }
    }

    function showAlert(message, type) {
        const container = document.getElementById('alertContainer');
        container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => container.innerHTML = '', 5000);
    }

    loadInvoices();
</script>
@endpush
