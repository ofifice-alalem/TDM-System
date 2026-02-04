@extends('layouts.app')

@section('title', 'طلبات سحب الأرباح')

@push('styles')
<style>
    :root { --card-radius: 20px; --accent-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; animation: fadeInDown 0.6s ease-out; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .btn-add-request { padding: 12px 24px; background: var(--primary); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; text-decoration: none; font-family: 'Tajawal', sans-serif; }
    .btn-add-request:hover { background: #7c3aed; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); }
    .balance-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 32px;
        animation: fadeInUp 0.8s ease-out;
    }

    .bank-card {
        padding: 24px;
        border-radius: 24px;
        position: relative;
        overflow: hidden;
        color: white;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .bank-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .bank-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        pointer-events: none;
    }

    .card-profits {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .card-withdrawn {
        background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
    }

    .card-available {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        opacity: 0.9;
    }

    .card-label {
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .card-chip {
        width: 40px;
        height: 30px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .balance-amount {
        font-size: 28px;
        font-weight: 800;
        margin-top: auto;
        letter-spacing: -0.5px;
    }

    .card-footer {
        margin-top: 12px;
        font-size: 12px;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .tabs-container { display: flex; gap: 8px; margin-bottom: 24px; background: rgba(100, 116, 139, 0.05); padding: 6px; border-radius: 14px; border: 1px solid var(--border-light); }
    body.dark-mode .tabs-container { border-color: var(--border-dark); }
    .tab-btn { flex: 1; padding: 10px; border: none; background: transparent; border-radius: 10px; font-weight: 700; color: #64748b; cursor: pointer; transition: all 0.3s ease; font-family: 'Tajawal', sans-serif; font-size: 15px; }
    .tab-btn.active { background: var(--card-light); color: var(--primary); box-shadow: var(--shadow-sm); }
    body.dark-mode .tab-btn.active { background: var(--primary); color: white; }
    .requests-list { display: flex; flex-direction: column; gap: 16px; }
    .request-card-premium { background: var(--card-light); border-radius: var(--card-radius); padding: 24px; border: 1px solid var(--border-light); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease; box-shadow: var(--shadow-sm); animation: fadeInRight 0.6s ease-out; }
    body.dark-mode .request-card-premium { background: var(--card-dark); border-color: var(--border-dark); }
    .request-card-premium:hover { transform: scale(1.01); box-shadow: var(--shadow-md); border-color: var(--primary); }
    .request-icon-box { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .request-info-group { flex: 1; display: grid; grid-template-columns: 1fr 1fr 1fr; align-items: center; gap: 20px; }
    .amount-display { font-size: 20px; font-weight: 800; color: var(--primary); }
    .date-info { display: flex; flex-direction: column; }
    .date-label { font-size: 11px; color: #94a3b8; font-weight: 600; }
    .date-value { font-size: 14px; font-weight: 700; color: var(--text-light); }
    body.dark-mode .date-value { color: var(--text-dark); }
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
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        طلبات سحب الأرباح
    </h1>
    <a href="/marketer/withdrawals/create" class="btn-add-request">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        طلب سحب جديد
    </a>
</div>

<div class="balance-grid">
    <!-- Profits Card -->
    <div class="bank-card card-profits">
        <div class="card-header">
            <span class="card-label">إجمالي الأرباح</span>
            <div class="card-chip"></div>
        </div>
        <div class="balance-amount" id="totalCommissions">جاري التحميل...</div>
        <div class="card-footer">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
            نمو العمولات التراكمي
        </div>
    </div>

    <!-- Withdrawn Card -->
    <div class="bank-card card-withdrawn">
        <div class="card-header">
            <span class="card-label">إجمالي المسحوب</span>
            <div class="card-chip"></div>
        </div>
        <div class="balance-amount" id="totalWithdrawals">جاري التحميل...</div>
        <div class="card-footer">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M23 18l-9.5-9.5-5 5L1 6"></path><polyline points="17 18 23 18 23 12"></polyline></svg>
            عمليات السحب المنفذة
        </div>
    </div>

    <!-- Available Card -->
    <div class="bank-card card-available">
        <div class="card-header">
            <span class="card-label">الرصيد المتاح للسحب</span>
            <div class="card-chip"></div>
        </div>
        <div class="balance-amount" id="balanceAmount">جاري التحميل...</div>
        <div class="card-footer">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            جاهز للتحويل الفوري
        </div>
    </div>
</div>

<div class="tabs-container">
    <button class="tab-btn active" onclick="switchTab('all', this)">كل الطلبات</button>
    <button class="tab-btn" onclick="switchTab('pending', this)">قيد الانتظار</button>
    <button class="tab-btn" onclick="switchTab('approved', this)">معتمد</button>
    <button class="tab-btn" onclick="switchTab('rejected', this)">مرفوض</button>
    <button class="tab-btn" onclick="switchTab('cancelled', this)">ملغي</button>
</div>

<div class="requests-list" id="requestsList">
    <div class="empty-state-premium">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <h3>جاري تحميل الطلبات...</h3>
    </div>
</div>

@include('shared.pagination')
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allRequests = [];
    let currentStatus = 'all';

    async function fetchBalance() {
        try {
            const response = await fetch('/api/marketer/withdrawals/balance', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            document.getElementById('totalCommissions').textContent = parseFloat(result.data.total_commissions).toFixed(2) + ' دينار';
            document.getElementById('totalWithdrawals').textContent = parseFloat(result.data.total_withdrawals).toFixed(2) + ' دينار';
            document.getElementById('balanceAmount').textContent = parseFloat(result.data.available_balance).toFixed(2) + ' دينار';
        } catch (error) {
            document.getElementById('balanceAmount').textContent = 'خطأ في التحميل';
        }
    }

    async function fetchData(page = 1) {
        try {
            let url = `/api/marketer/withdrawals?page=${page}`;
            if (currentStatus !== 'all') url += `&status=${currentStatus}`;
            
            const response = await fetch(url, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            allRequests = result.data?.data || result.data || [];
            updatePagination(result.data);
            renderRequests();
        } catch (error) {
            showError();
        }
    }

    async function fetchRequests(page = 1) {
        await fetchData(page);
    }

    function switchTab(status, btn) {
        currentStatus = status;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        fetchData(1);
    }

    function renderRequests() {
        const container = document.getElementById('requestsList');

        if (allRequests.length === 0) {
            container.innerHTML = '<div class="empty-state-premium"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg><h3>لا توجد طلبات</h3></div>';
            return;
        }

        container.innerHTML = allRequests.map(req => {
            const statusMap = {
                'pending': { label: 'قيد الانتظار', class: 'status-pending', bg: 'rgba(245, 158, 11, 0.1)', color: '#f59e0b', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>' },
                'approved': { label: 'معتمد', class: 'status-approved', bg: 'rgba(16, 185, 129, 0.1)', color: '#10b981', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' },
                'rejected': { label: 'مرفوض', class: 'status-rejected', bg: 'rgba(239, 68, 68, 0.1)', color: '#ef4444', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>' },
                'cancelled': { label: 'ملغي', class: 'status-cancelled', bg: 'rgba(100, 116, 139, 0.1)', color: '#64748b', icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line></svg>' }
            };
            const status = statusMap[req.status] || { label: req.status, class: '', icon: '', bg: 'rgba(139, 92, 246, 0.1)', color: 'var(--primary)' };
            const createdAtDate = new Date(req.created_at);
            const dateStr = createdAtDate.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
            const timeStr = createdAtDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

            return `
                <div class="request-card-premium">
                    <div class="request-icon-box" style="background: ${status.bg}; color: ${status.color};">${status.icon}</div>
                    <div class="request-info-group">
                        <div class="amount-display">${parseFloat(req.requested_amount).toFixed(2)} دينار</div>
                        <div class="date-info">
                            <span class="date-label">التوقيت</span>
                            <span class="date-value">${dateStr} | ${timeStr}</span>
                        </div>
                        <div class="status-badge-premium ${status.class}">${status.icon.replace('width="24" height="24"', 'width="18" height="18"')} ${status.label}</div>
                    </div>
                    <a href="/marketer/withdrawals/${req.id}" class="btn-action">عرض التفاصيل<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
                </div>
            `;
        }).join('');
    }

    function showError() {
        document.getElementById('requestsList').innerHTML = '<div class="empty-state-premium">⚠️ <h3>خطأ في تحميل البيانات</h3></div>';
    }

    fetchBalance();
    fetchRequests();
</script>
@endpush
