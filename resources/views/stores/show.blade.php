@extends('layouts.app')

@section('title', 'تفاصيل المتجر')

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--card-light); border: 1px solid var(--border-light); border-radius: 12px; color: var(--text-light); text-decoration: none; font-weight: 700; transition: all 0.3s; margin-bottom: 16px; }
    body.dark-mode .back-btn { background: var(--card-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .back-btn:hover { background: var(--primary); color: white; border-color: var(--primary); }
    
    .store-header { background: var(--card-light); border-radius: 24px; padding: 32px; border: 1px solid var(--border-light); margin-bottom: 24px; }
    body.dark-mode .store-header { background: var(--card-dark); border-color: var(--border-dark); }
    .store-title { font-size: 32px; font-weight: 800; color: var(--text-light); margin-bottom: 8px; }
    body.dark-mode .store-title { color: var(--text-dark); }
    .store-info { display: flex; gap: 24px; color: #64748b; font-size: 14px; }
    
    .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px; }
    .summary-card { background: var(--card-light); border-radius: 20px; padding: 24px; border: 1px solid var(--border-light); }
    body.dark-mode .summary-card { background: var(--card-dark); border-color: var(--border-dark); }
    .summary-label { font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 8px; }
    .summary-value { font-size: 24px; font-weight: 800; }
    
    .ledger-section { background: var(--card-light); border-radius: 24px; padding: 32px; border: 1px solid var(--border-light); }
    body.dark-mode .ledger-section { background: var(--card-dark); border-color: var(--border-dark); }
    .section-title { font-size: 20px; font-weight: 800; color: var(--text-light); margin-bottom: 24px; }
    body.dark-mode .section-title { color: var(--text-dark); }
    
    .ledger-table { width: 100%; border-collapse: collapse; }
    .ledger-table th { text-align: right; padding: 12px; font-size: 13px; font-weight: 700; color: #64748b; border-bottom: 2px solid var(--border-light); }
    body.dark-mode .ledger-table th { border-color: var(--border-dark); }
    .ledger-table td { padding: 16px 12px; border-bottom: 1px solid var(--border-light); color: var(--text-light); }
    body.dark-mode .ledger-table td { border-color: var(--border-dark); color: var(--text-dark); }
    .ledger-table tr:hover { background: rgba(139, 92, 246, 0.05); }
    
    .badge { padding: 4px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; display: inline-block; }
    .badge-sale { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-payment { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-return { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
</style>
@endpush

@section('content')
<div class="page-header">
    <a href="/stores" class="back-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        رجوع
    </a>
</div>

<div class="store-header" id="storeHeader">
    <div style="text-align: center; padding: 40px; color: #94a3b8;">جاري التحميل...</div>
</div>

<div class="summary-grid" id="summaryGrid"></div>

<div class="ledger-section">
    <h2 class="section-title">سجل الحركات</h2>
    <div id="ledgerContent">
        <div style="text-align: center; padding: 40px; color: #94a3b8;">جاري التحميل...</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    const storeId = {{ $storeId }};

    async function fetchStoreDetails() {
        try {
            const response = await fetch(`/api/stores/debts/${storeId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            renderStoreDetails(result.data);
        } catch (error) {
            document.getElementById('storeHeader').innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444;">⚠️ خطأ في تحميل البيانات</div>';
        }
    }

    function renderStoreDetails(data) {
        const store = data.store;
        const summary = data.summary;
        const ledger = data.ledger;

        document.getElementById('storeHeader').innerHTML = `
            <h1 class="store-title">${store.name}</h1>
            <div class="store-info">
                <span>👤 ${store.owner_name || 'غير محدد'}</span>
                <span>📱 ${store.phone || 'غير محدد'}</span>
                <span>📍 ${store.location || 'غير محدد'}</span>
            </div>
        `;

        const remaining = parseFloat(summary.remaining_debt);
        document.getElementById('summaryGrid').innerHTML = `
            <div class="summary-card">
                <div class="summary-label">إجمالي الفواتير</div>
                <div class="summary-value" style="color: #3b82f6;">${parseFloat(summary.total_sales).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">إجمالي المدفوع</div>
                <div class="summary-value" style="color: #10b981;">${parseFloat(summary.total_payments).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">إجمالي المرتجع</div>
                <div class="summary-value" style="color: #f59e0b;">${parseFloat(summary.total_returns).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">المتبقي</div>
                <div class="summary-value" style="color: ${remaining > 0 ? '#ef4444' : '#10b981'};">${Math.abs(remaining).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
            </div>
        `;

        if (ledger.length === 0) {
            document.getElementById('ledgerContent').innerHTML = '<div style="text-align: center; padding: 40px; color: #94a3b8;">لا توجد حركات مسجلة</div>';
            return;
        }

        const typeMap = {
            'sale': { label: 'فاتورة بيع', class: 'badge-sale' },
            'payment': { label: 'دفعة', class: 'badge-payment' },
            'return': { label: 'إرجاع', class: 'badge-return' }
        };

        document.getElementById('ledgerContent').innerHTML = `
            <table class="ledger-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>رقم المستند</th>
                        <th>المسوق</th>
                        <th>المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    ${ledger.map(l => {
                        const type = typeMap[l.entry_type];
                        const docNumber = l.sale_invoice_number || l.payment_receipt_number || l.return_number || '-';
                        const date = new Date(l.created_at).toLocaleDateString('ar-EG');
                        const sign = l.entry_type === 'sale' ? '+' : '-';
                        const color = l.entry_type === 'sale' ? '#ef4444' : '#10b981';
                        
                        return `
                            <tr>
                                <td>${date}</td>
                                <td><span class="badge ${type.class}">${type.label}</span></td>
                                <td>${docNumber}</td>
                                <td>${l.marketer_name || '-'}</td>
                                <td style="font-weight: 800; color: ${color};">${sign}${parseFloat(l.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        `;
    }

    fetchStoreDetails();
</script>
@endpush
