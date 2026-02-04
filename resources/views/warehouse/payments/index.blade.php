@extends('layouts.app')

@section('title', 'إيصالات القبض')

@push('styles')
<style>
    :root { --card-radius: 20px; --accent-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); }
    .page-header { margin-bottom: 32px; animation: fadeInDown 0.6s ease-out; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .tabs-container { display: flex; gap: 8px; margin-bottom: 24px; background: rgba(100, 116, 139, 0.05); padding: 6px; border-radius: 14px; border: 1px solid var(--border-light); }
    body.dark-mode .tabs-container { border-color: var(--border-dark); }
    .tab-btn { flex: 1; padding: 10px; border: none; background: transparent; border-radius: 10px; font-weight: 700; color: #64748b; cursor: pointer; transition: all 0.3s ease; font-family: 'Tajawal', sans-serif; }
    .tab-btn.active { background: var(--card-light); color: var(--primary); box-shadow: var(--shadow-sm); }
    body.dark-mode .tab-btn.active { background: var(--primary); color: white; }
    .filters-bar { background: var(--card-light); padding: 24px; border-radius: var(--card-radius); margin-bottom: 24px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; border: 1px solid var(--border-light); box-shadow: var(--shadow-sm); }
    body.dark-mode .filters-bar { background: var(--card-dark); border-color: var(--border-dark); }
    .filter-group label { display: block; font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 8px; }
    .search-input-premium { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-light); background: var(--bg-light); color: var(--text-light); font-size: 14px; transition: all 0.3s ease; font-family: 'Tajawal', sans-serif; }
    body.dark-mode .search-input-premium { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .search-input-premium:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1); outline: none; }
    .payments-list { display: flex; flex-direction: column; gap: 16px; }
    .payment-card-premium { background: var(--card-light); border-radius: var(--card-radius); padding: 24px; border: 1px solid var(--border-light); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease; box-shadow: var(--shadow-sm); animation: fadeInRight 0.6s ease-out; }
    body.dark-mode .payment-card-premium { background: var(--card-dark); border-color: var(--border-dark); }
    .payment-card-premium:hover { transform: scale(1.01); box-shadow: var(--shadow-md); border-color: var(--primary); }
    .payment-icon-box { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .payment-info-group { flex: 1; display: grid; grid-template-columns: 1.2fr 1fr 1fr 1fr 1fr; align-items: center; gap: 20px; }
    .payment-num { font-size: 18px; font-weight: 800; color: var(--primary); }
    .info-col { display: flex; flex-direction: column; }
    .info-label { font-size: 11px; color: #94a3b8; font-weight: 600; }
    .info-value { font-size: 14px; font-weight: 700; color: var(--text-light); }
    body.dark-mode .info-value { color: var(--text-dark); }
    .amount-value { font-size: 16px; font-weight: 800; color: #10b981; }
    .status-badge-premium { padding: 6px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; justify-self: start; }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    .btn-action { padding: 10px 20px; background: var(--primary); color: white; border-radius: 10px; font-weight: 700; font-size: 14px; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; }
    .btn-action:hover { background: #7c3aed; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); }
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInRight { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
    .empty-state-premium { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state-premium svg { width: 64px; height: 64px; margin-bottom: 20px; opacity: 0.5; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
        إدارة إيصالات القبض
    </h1>
</div>

<div class="tabs-container">
    <button class="tab-btn active" onclick="switchTab('all', this)">كل الإيصالات</button>
    <button class="tab-btn" onclick="switchTab('pending', this)">قيد الانتظار</button>
    <button class="tab-btn" onclick="switchTab('approved', this)">موثق</button>
    <button class="tab-btn" onclick="switchTab('rejected', this)">مرفوض</button>
    <button class="tab-btn" onclick="switchTab('cancelled', this)">ملغي</button>
</div>

<div class="filters-bar">
    <div class="filter-group">
        <label>رقم الإيصال</label>
        <input type="text" id="searchPayment" class="search-input-premium" placeholder="ابحث برقم الإيصال...">
    </div>
    <div class="filter-group">
        <label>اسم المتجر</label>
        <input type="text" id="searchStore" class="search-input-premium" placeholder="ابحث باسم المتجر...">
    </div>
    <div class="filter-group">
        <label>اسم المسوق</label>
        <input type="text" id="searchMarketer" class="search-input-premium" placeholder="ابحث باسم المسوق...">
    </div>
</div>

<div class="payments-list" id="paymentsList">
    <div class="empty-state-premium">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <h3>جاري تحميل الإيصالات...</h3>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allPayments = [];
    let currentStatus = 'all';

    async function fetchPayments() {
        try {
            const response = await fetch('/api/warehouse/payments', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            allPayments = result.data?.data || result.data || [];
            renderPayments();
        } catch (error) {
            console.error('Error:', error);
            showError();
        }
    }

    function switchTab(status, btn) {
        currentStatus = status;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderPayments();
    }

    function renderPayments() {
        const container = document.getElementById('paymentsList');
        const searchPayment = document.getElementById('searchPayment').value.toLowerCase();
        const searchStore = document.getElementById('searchStore').value.toLowerCase();
        const searchMarketer = document.getElementById('searchMarketer').value.toLowerCase();
        
        let filtered = allPayments.filter(pay => {
            if (currentStatus !== 'all' && pay.status !== currentStatus) return false;
            if (searchPayment && !pay.payment_number.toLowerCase().includes(searchPayment)) return false;
            if (searchStore && !pay.store_name.toLowerCase().includes(searchStore)) return false;
            if (searchMarketer && !pay.marketer_name.toLowerCase().includes(searchMarketer)) return false;
            return true;
        });

        if (filtered.length === 0) {
            container.innerHTML = `<div class="empty-state-premium"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg><h3>لا توجد إيصالات تطابق بحثك</h3></div>`;
            return;
        }

        container.innerHTML = filtered.map(pay => {
            const statusMap = {
                'pending': { label: 'قيد الانتظار', class: 'status-pending', bg: 'rgba(245, 158, 11, 0.1)', color: '#f59e0b', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>' },
                'approved': { label: 'موثق', class: 'status-approved', bg: 'rgba(16, 185, 129, 0.1)', color: '#10b981', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' },
                'rejected': { label: 'مرفوض', class: 'status-rejected', bg: 'rgba(239, 68, 68, 0.1)', color: '#ef4444', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>' },
                'cancelled': { label: 'ملغي', class: 'status-cancelled', bg: 'rgba(100, 116, 139, 0.1)', color: '#64748b', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line></svg>' }
            };
            const status = statusMap[pay.status] || { label: pay.status, class: '', icon: '', bg: 'rgba(139, 92, 246, 0.1)', color: 'var(--primary)' };

            return `
                <div class="payment-card-premium">
                    <div class="payment-icon-box" style="background: ${status.bg}; color: ${status.color};">${status.icon}</div>
                    <div class="payment-info-group">
                        <div class="payment-num">#${pay.payment_number}</div>
                        <div class="info-col">
                            <span class="info-label">المتجر</span>
                            <span class="info-value">${pay.store_name}</span>
                        </div>
                        <div class="info-col">
                            <span class="info-label">المسوق</span>
                            <span class="info-value">${pay.marketer_name}</span>
                        </div>
                        <div class="info-col">
                            <span class="info-label">المبلغ</span>
                            <span class="amount-value">${parseFloat(pay.amount).toLocaleString()} دينار</span>
                        </div>
                        <div class="status-badge-premium ${status.class}">${status.icon.replace('width="24" height="24"', 'width="18" height="18"')} ${status.label}</div>
                    </div>
                    <a href="/warehouse/payments/${pay.id}" class="btn-action">تفاصيل<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
                </div>
            `;
        }).join('');
    }

    function showError() {
        document.getElementById('paymentsList').innerHTML = '<div class="empty-state-premium">⚠️ <h3>خطأ في تحميل البيانات</h3></div>';
    }

    document.getElementById('searchPayment').addEventListener('input', renderPayments);
    document.getElementById('searchStore').addEventListener('input', renderPayments);
    document.getElementById('searchMarketer').addEventListener('input', renderPayments);

    fetchPayments();
</script>
@endpush
