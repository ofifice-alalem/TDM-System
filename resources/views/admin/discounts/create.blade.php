@extends('layouts.app')

@section('title', 'إضافة قاعدة خصم')

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
    .discount-type-selector { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .type-option { padding: 16px; border: 2px solid var(--border-light); border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; font-weight: 700; }
    body.dark-mode .type-option { border-color: var(--border-dark); }
    .type-option.active { border-color: var(--primary); background: rgba(139, 92, 246, 0.1); color: var(--primary); }
    .type-option:hover { border-color: var(--primary); }
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px; }
    .btn { padding: 12px 24px; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #7c3aed; }
    .btn-secondary { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    .btn-secondary:hover { background: rgba(100, 116, 139, 0.2); }
    .hidden { display: none; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        إضافة قاعدة خصم جديدة
    </h1>
</div>

<div class="form-card">
    <form id="discountForm">
        <div class="form-group">
            <label class="form-label">الحد الأدنى لقيمة الفاتورة (دينار)</label>
            <input type="number" class="form-input" id="minAmount" step="0.01" min="0" required>
        </div>

        <div class="form-group">
            <label class="form-label">نوع الخصم</label>
            <div class="discount-type-selector">
                <div class="type-option active" onclick="selectType('percentage')">
                    <div>نسبة مئوية (%)</div>
                </div>
                <div class="type-option" onclick="selectType('fixed')">
                    <div>مبلغ ثابت (دينار)</div>
                </div>
            </div>
            <input type="hidden" id="discountType" value="percentage">
        </div>

        <div class="form-group" id="percentageGroup">
            <label class="form-label">نسبة الخصم (%)</label>
            <input type="number" class="form-input" id="discountPercentage" step="0.01" min="0" max="100">
        </div>

        <div class="form-group hidden" id="amountGroup">
            <label class="form-label">مبلغ الخصم (دينار)</label>
            <input type="number" class="form-input" id="discountAmount" step="0.01" min="0">
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
            <button type="button" class="btn btn-secondary" onclick="window.location.href='/admin/discounts'">إلغاء</button>
            <button type="submit" class="btn btn-primary">حفظ قاعدة الخصم</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';

    function selectType(type) {
        document.querySelectorAll('.type-option').forEach(el => el.classList.remove('active'));
        event.target.closest('.type-option').classList.add('active');
        document.getElementById('discountType').value = type;

        if (type === 'percentage') {
            document.getElementById('percentageGroup').classList.remove('hidden');
            document.getElementById('amountGroup').classList.add('hidden');
            document.getElementById('discountPercentage').required = true;
            document.getElementById('discountAmount').required = false;
        } else {
            document.getElementById('percentageGroup').classList.add('hidden');
            document.getElementById('amountGroup').classList.remove('hidden');
            document.getElementById('discountPercentage').required = false;
            document.getElementById('discountAmount').required = true;
        }
    }

    document.getElementById('discountForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const type = document.getElementById('discountType').value;
        const data = {
            min_amount: document.getElementById('minAmount').value,
            discount_type: type,
            discount_percentage: type === 'percentage' ? document.getElementById('discountPercentage').value : null,
            discount_amount: type === 'fixed' ? document.getElementById('discountAmount').value : null,
            start_date: document.getElementById('startDate').value,
            end_date: document.getElementById('endDate').value
        };

        try {
            const response = await fetch('/api/admin/discounts', {
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
                    window.location.href = '/admin/discounts';
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
    const nextYear = new Date();
    nextYear.setFullYear(nextYear.getFullYear() + 1);
    document.getElementById('endDate').value = nextYear.toISOString().split('T')[0];
</script>
@endpush
