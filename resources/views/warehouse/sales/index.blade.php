@extends('layouts.app')

@section('title', 'إدارة فواتير البيع')

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    
    .tabs-container { display: flex; gap: 8px; margin-bottom: 24px; background: rgba(100, 116, 139, 0.05); padding: 6px; border-radius: 14px; border: 1px solid var(--border-light); }
    .tab-btn { flex: 1; padding: 10px; border: none; background: transparent; border-radius: 10px; font-weight: 700; color: #64748b; cursor: pointer; font-family: 'Tajawal', sans-serif; }
    .tab-btn.active { background: var(--card-light); color: var(--primary); }
    
    .filters-bar { background: var(--card-light); padding: 24px; border-radius: 16px; margin-bottom: 24px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; border: 1px solid var(--border-light); }
    body.dark-mode .filters-bar { background: var(--card-dark); border-color: var(--border-dark); }
    
    .filter-group label { display: block; font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 8px; }
    .search-input { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-light); background: var(--bg-light); color: var(--text-light); font-family: 'Tajawal', sans-serif; }
    body.dark-mode .search-input { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    
    .invoices-list { display: flex; flex-direction: column; gap: 16px; }
    .invoice-card { background: var(--card-light); border-radius: 20px; padding: 24px; border: 1px solid var(--border-light); display: flex; align-items: center; gap: 20px; }
    body.dark-mode .invoice-card { background: var(--card-dark); border-color: var(--border-dark); }
    
    .invoice-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .invoice-info { flex: 1; display: grid; grid-template-columns: 1.2fr 1fr 1fr 1fr 1fr; align-items: center; gap: 20px; }
    .invoice-num { font-size: 18px; font-weight: 800; color: var(--primary); }
    .info-label { font-size: 11px; color: #94a3b8; font-weight: 600; }
    .info-value { font-size: 14px; font-weight: 700; color: var(--text-light); }
    body.dark-mode .info-value { color: var(--text-dark); }
    
    .status-badge { padding: 6px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-approved { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    
    .btn-action { padding: 10px 20px; background: var(--primary); color: white; border-radius: 10px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
        إدارة فواتير البيع
    </h1>
</div>

<div class="tabs-container">
    <button class="tab-btn active" onclick="switchTab('all', this)">الكل</button>
    <button class="tab-btn" onclick="switchTab('pending', this)">قيد الانتظار</button>
    <button class="tab-btn" onclick="switchTab('approved', this)">موثقة</button>
    <button class="tab-btn" onclick="switchTab('cancelled', this)">ملغية</button>
</div>

<div class="filters-bar">
    <div class="filter-group">
        <label>رقم الفاتورة</label>
        <input type="text" id="searchInput" class="search-input" placeholder="ابحث برقم الفاتورة...">
    </div>
    <div class="filter-group">
        <label>المسوق</label>
        <input type="text" id="marketerSearch" class="search-input" placeholder="ابحث باسم المسوق...">
    </div>
    <div class="filter-group">
        <label>المتجر</label>
        <input type="text" id="storeSearch" class="search-input" placeholder="ابحث باسم المتجر...">
    </div>
</div>

<div class="invoices-list" id="invoicesList">
    <div style="text-align: center; padding: 60px; color: #94a3b8;">جاري تحميل الفواتير...</div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allInvoices = [];
    let currentStatus = 'all';

    async function fetchInvoices() {
        try {
            const response = await fetch('/api/warehouse/sales', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            allInvoices = result.data || [];
            renderInvoices();
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function switchTab(status, btn) {
        currentStatus = status;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderInvoices();
    }

    function renderInvoices() {
        const container = document.getElementById('invoicesList');
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const marketerValue = document.getElementById('marketerSearch').value.toLowerCase();
        const storeValue = document.getElementById('storeSearch').value.toLowerCase();
        
        let filtered = allInvoices.filter(inv => {
            if (currentStatus !== 'all' && inv.status !== currentStatus) return false;
            if (searchValue && !inv.invoice_number.toLowerCase().includes(searchValue)) return false;
            if (marketerValue && !inv.marketer_name.toLowerCase().includes(marketerValue)) return false;
            if (storeValue && !inv.store_name.toLowerCase().includes(storeValue)) return false;
            return true;
        });

        if (filtered.length === 0) {
            container.innerHTML = '<div style="text-align: center; padding: 60px; color: #94a3b8;">لا توجد فواتير</div>';
            return;
        }

        container.innerHTML = filtered.map(inv => {
            const statusMap = {
                'pending': { label: 'قيد الانتظار', class: 'status-pending', bg: 'rgba(245, 158, 11, 0.1)', color: '#f59e0b' },
                'approved': { label: 'موثقة', class: 'status-approved', bg: 'rgba(59, 130, 246, 0.1)', color: '#3b82f6' },
                'cancelled': { label: 'ملغية', class: 'status-cancelled', bg: 'rgba(100, 116, 139, 0.1)', color: '#64748b' }
            };
            const status = statusMap[inv.status] || { label: inv.status, class: '', bg: 'rgba(139, 92, 246, 0.1)', color: 'var(--primary)' };

            return `
                <div class="invoice-card">
                    <div class="invoice-icon" style="background: ${status.bg}; color: ${status.color};">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
                    </div>
                    <div class="invoice-info">
                        <div class="invoice-num">#${inv.invoice_number}</div>
                        <div><span class="info-label">المسوق</span><div class="info-value">${inv.marketer_name}</div></div>
                        <div><span class="info-label">المتجر</span><div class="info-value">${inv.store_name}</div></div>
                        <div><span class="info-label">المبلغ</span><div class="info-value">${parseFloat(inv.total_amount).toFixed(2)} د</div></div>
                        <div class="status-badge ${status.class}">${status.label}</div>
                    </div>
                    <a href="/warehouse/sales/${inv.id}" class="btn-action">تفاصيل</a>
                </div>
            `;
        }).join('');
    }

    document.getElementById('searchInput').addEventListener('input', renderInvoices);
    document.getElementById('marketerSearch').addEventListener('input', renderInvoices);
    document.getElementById('storeSearch').addEventListener('input', renderInvoices);
    fetchInvoices();
</script>
@endpush
