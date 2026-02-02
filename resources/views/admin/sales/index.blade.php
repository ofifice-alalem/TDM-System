@extends('layouts.app')

@section('title', 'إدارة فواتير البيع')

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    
    .tabs-container { display: flex; gap: 8px; margin-bottom: 24px; background: rgba(100, 116, 139, 0.05); padding: 6px; border-radius: 14px; border: 1px solid var(--border-light); }
    body.dark-mode .tabs-container { border-color: var(--border-dark); }
    .tab-btn { flex: 1; padding: 10px; border: none; background: transparent; border-radius: 10px; font-weight: 700; color: #64748b; cursor: pointer; transition: all 0.3s; font-family: 'Tajawal', sans-serif; }
    .tab-btn.active { background: var(--card-light); color: var(--primary); box-shadow: var(--shadow-sm); }
    body.dark-mode .tab-btn.active { background: var(--primary); color: white; }
    
    .filters-bar { background: var(--card-light); padding: 24px; border-radius: 16px; margin-bottom: 24px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; border: 1px solid var(--border-light); }
    body.dark-mode .filters-bar { background: var(--card-dark); border-color: var(--border-dark); }
    
    .filter-group label { display: block; font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 8px; }
    .search-input { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-light); background: var(--bg-light); color: var(--text-light); font-size: 14px; font-family: 'Tajawal', sans-serif; }
    body.dark-mode .search-input { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .search-input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1); outline: none; }
    
    .invoices-list { display: flex; flex-direction: column; gap: 16px; }
    .invoice-card { background: var(--card-light); border-radius: 20px; padding: 24px; border: 1px solid var(--border-light); display: flex; align-items: center; gap: 20px; transition: all 0.3s; }
    body.dark-mode .invoice-card { background: var(--card-dark); border-color: var(--border-dark); }
    .invoice-card:hover { transform: scale(1.01); box-shadow: var(--shadow-md); border-color: var(--primary); }
    
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
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .btn-action { padding: 10px 20px; background: var(--primary); color: white; border-radius: 10px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; }
    .btn-action:hover { background: #7c3aed; transform: translateY(-2px); }
    
    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
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
    <button class="tab-btn" onclick="switchTab('rejected', this)">مرفوضة</button>
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
    <div class="empty-state">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle></svg>
        <h3>جاري تحميل الفواتير...</h3>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allInvoices = [];
    let currentStatus = 'all';

    async function fetchInvoices() {
        try {
            const response = await fetch('/api/admin/sales', {
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
            container.innerHTML = '<div class="empty-state"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg><h3>لا توجد فواتير</h3></div>';
            return;
        }

        container.innerHTML = filtered.map(inv => {
            const statusMap = {
                'pending': { label: 'قيد الانتظار', class: 'status-pending', bg: 'rgba(245, 158, 11, 0.1)', color: '#f59e0b', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>' },
                'approved': { label: 'موثقة', class: 'status-approved', bg: 'rgba(59, 130, 246, 0.1)', color: '#3b82f6', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' },
                'cancelled': { label: 'ملغية', class: 'status-cancelled', bg: 'rgba(100, 116, 139, 0.1)', color: '#64748b', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line></svg>' },
                'rejected': { label: 'مرفوضة', class: 'status-rejected', bg: 'rgba(239, 68, 68, 0.1)', color: '#ef4444', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' }
            };
            const status = statusMap[inv.status] || { label: inv.status, class: '', bg: 'rgba(139, 92, 246, 0.1)', color: 'var(--primary)' };

            return `
                <div class="invoice-card">
                    <div class="invoice-icon" style="background: ${status.bg}; color: ${status.color};">
                        ${status.icon}
                    </div>
                    <div class="invoice-info">
                        <div class="invoice-num">#${inv.invoice_number}</div>
                        <div><span class="info-label">المسوق</span><div class="info-value">${inv.marketer_name}</div></div>
                        <div><span class="info-label">المتجر</span><div class="info-value">${inv.store_name}</div></div>
                        <div><span class="info-label">المبلغ</span><div class="info-value">${parseFloat(inv.total_amount).toFixed(2)} د</div></div>
                        <div class="status-badge ${status.class}">${status.icon.replace('width="24" height="24"', 'width="18" height="18"')} ${status.label}</div>
                    </div>
                    <a href="/admin/sales/${inv.id}" class="btn-action">تفاصيل<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
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
