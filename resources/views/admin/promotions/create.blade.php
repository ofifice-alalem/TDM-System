@extends('layouts.app')

@section('title', 'إضافة عرض ترويجي')

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .form-card { background: var(--card-light); border-radius: 20px; padding: 32px; border: 1px solid var(--border-light); max-width: 800px; margin: 0 auto; }
    body.dark-mode .form-card { background: var(--card-dark); border-color: var(--border-dark); }
    .form-group { margin-bottom: 24px; }
    .form-label { display: block; font-size: 14px; font-weight: 700; color: var(--text-light); margin-bottom: 8px; }
    body.dark-mode .form-label { color: var(--text-dark); }
    .form-input, .form-select { width: 100%; padding: 12px 16px; border: 1px solid var(--border-light); border-radius: 12px; font-size: 14px; font-family: 'Tajawal', sans-serif; background: var(--card-light); color: var(--text-light); }
    body.dark-mode .form-input, body.dark-mode .form-select { background: var(--card-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .form-input:focus, .form-select:focus { outline: none; border-color: var(--primary); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px; }
    .btn { padding: 12px 24px; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #7c3aed; }
    .btn-secondary { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    .btn-secondary:hover { background: rgba(100, 116, 139, 0.2); }
    .promo-preview { background: rgba(139, 92, 246, 0.1); border: 2px dashed var(--primary); border-radius: 12px; padding: 20px; text-align: center; margin-top: 16px; }
    .promo-preview-text { font-size: 20px; font-weight: 800; color: var(--primary); }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        إضافة عرض ترويجي جديد
    </h1>
</div>

<div class="form-card">
    <form id="promotionForm">
        <div class="form-group">
            <label class="form-label">المنتج</label>
            <select class="form-select" id="productId" required>
                <option value="">اختر المنتج...</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">الحد الأدنى للكمية المشتراة</label>
                <input type="number" class="form-input" id="minQuantity" min="1" required oninput="updatePreview()">
            </div>
            <div class="form-group">
                <label class="form-label">الكمية المجانية</label>
                <input type="number" class="form-input" id="freeQuantity" min="1" required oninput="updatePreview()">
            </div>
        </div>

        <div class="promo-preview" id="promoPreview" style="display: none;">
            <div class="promo-preview-text" id="promoPreviewText"></div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">تاريخ البداية</label>
                <input type="date" class="form-input" id="startDate" required>
            </div>
            <div class="form-group">
                <label class="form-label">تاريخ النهاية</label>
                <input type="date" class="form-input" id="endDate" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='/admin/promotions'">إلغاء</button>
            <button type="submit" class="btn btn-primary">حفظ العرض الترويجي</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';

    async function loadProducts() {
        try {
            const response = await fetch('/api/admin/products', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            const select = document.getElementById('productId');
            result.data.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = product.name;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    function updatePreview() {
        const minQty = document.getElementById('minQuantity').value;
        const freeQty = document.getElementById('freeQuantity').value;
        const preview = document.getElementById('promoPreview');
        const previewText = document.getElementById('promoPreviewText');

        if (minQty && freeQty) {
            preview.style.display = 'block';
            previewText.textContent = `🎁 اشتري ${minQty} واحصل على ${freeQty} مجاناً`;
        } else {
            preview.style.display = 'none';
        }
    }

    document.getElementById('promotionForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = {
            product_id: document.getElementById('productId').value,
            min_quantity: document.getElementById('minQuantity').value,
            free_quantity: document.getElementById('freeQuantity').value,
            start_date: document.getElementById('startDate').value,
            end_date: document.getElementById('endDate').value
        };

        try {
            const response = await fetch('/api/admin/promotions', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                showModal('✅ نجاح', result.message, () => {
                    window.location.href = '/admin/promotions';
                });
            } else {
                showModal('⚠️ خطأ', result.message || 'حدث خطأ أثناء الحفظ');
            }
        } catch (error) {
            showModal('⚠️ خطأ', 'حدث خطأ أثناء الحفظ');
        }
    });

    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('startDate').value = today;
    const nextMonth = new Date();
    nextMonth.setMonth(nextMonth.getMonth() + 1);
    document.getElementById('endDate').value = nextMonth.toISOString().split('T')[0];

    loadProducts();
</script>
@endpush
