@extends('layouts.app')

@section('title', 'إدارة إرجاع البضاعة من المتاجر')

@push('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .tabs-container { display: flex; gap: 8px; margin-bottom: 24px; background: rgba(100, 116, 139, 0.05); padding: 6px; border-radius: 14px; }
    .tab-btn { flex: 1; padding: 10px; border: none; background: transparent; border-radius: 10px; font-weight: 700; color: #64748b; cursor: pointer; transition: all 0.3s; font-family: 'Tajawal', sans-serif; }
    .tab-btn.active { background: var(--card-light); color: var(--primary); box-shadow: var(--shadow-sm); }
    body.dark-mode .tab-btn.active { background: var(--primary); color: white; }
    .returns-list { display: flex; flex-direction: column; gap: 16px; }
    .return-card { background: var(--card-light); border-radius: 20px; padding: 24px; border: 1px solid var(--border-light); display: flex; align-items: center; gap: 20px; transition: all 0.3s; }
    body.dark-mode .return-card { background: var(--card-dark); border-color: var(--border-dark); }
    .return-card:hover { transform: scale(1.01); box-shadow: var(--shadow-md); border-color: var(--primary); }
    .return-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .return-info { flex: 1; display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr; gap: 20px; align-items: center; }
    .info-label { font-size: 11px; color: #94a3b8; font-weight: 600; }
    .info-value { font-size: 14px; font-weight: 700; color: var(--text-light); }
    body.dark-mode .info-value { color: var(--text-dark); }
    .amount-display { font-size: 20px; font-weight: 800; color: var(--primary); }
    .status-badge { padding: 6px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    .btn-action { padding: 10px 20px; background: var(--primary); color: white; border-radius: 10px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; }
    .btn-action:hover { background: #7c3aed; transform: translateY(-2px); }
    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
        إدارة إرجاع البضاعة من المتاجر
    </h1>
</div>

<div class="tabs-container">
    <button class="tab-btn active" onclick="switchTab('all', this)">كل الطلبات</button>
    <button class="tab-btn" onclick="switchTab('pending', this)">قيد الانتظار</button>
    <button class="tab-btn" onclick="switchTab('approved', this)">موثق</button>
    <button class="tab-btn" onclick="switchTab('rejected', this)">مرفوض</button>
    <button class="tab-btn" onclick="switchTab('cancelled', this)">ملغي</button>
</div>

<div class="returns-list" id="returnsList">
    <div class="empty-state">جاري تحميل الطلبات...</div>
</div>

@include('shared.pagination')
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allReturns = [];
    let currentStatus = 'all';

    async function fetchReturns(page = 1) {
        try {
            const response = await fetch(`/api/warehouse/store-returns?page=${page}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            allReturns = result.data?.data || result.data || [];
            renderReturns();
            updatePagination(result.data);
        } catch (error) {
            document.getElementById('returnsList').innerHTML = '<div class="empty-state">⚠️ خطأ في تحميل البيانات</div>';
        }
    }

    function switchTab(status, btn) {
        currentStatus = status;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        fetchReturns(1);
    }

    function renderReturns() {
        const container = document.getElementById('returnsList');
        let filtered = allReturns.filter(ret => currentStatus === 'all' || ret.status === currentStatus);

        if (filtered.length === 0) {
            container.innerHTML = '<div class="empty-state">لا توجد طلبات</div>';
            return;
        }

        container.innerHTML = filtered.map(ret => {
            const statusMap = {
                'pending': { label: 'قيد الانتظار', class: 'status-pending', bg: 'rgba(245, 158, 11, 0.1)', color: '#f59e0b' },
                'approved': { label: 'موثق', class: 'status-approved', bg: 'rgba(16, 185, 129, 0.1)', color: '#10b981' },
                'rejected': { label: 'مرفوض', class: 'status-rejected', bg: 'rgba(239, 68, 68, 0.1)', color: '#ef4444' },
                'cancelled': { label: 'ملغي', class: 'status-cancelled', bg: 'rgba(100, 116, 139, 0.1)', color: '#64748b' }
            };
            const status = statusMap[ret.status];

            return `
                <div class="return-card">
                    <div class="return-icon" style="background: ${status.bg}; color: ${status.color};">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
                    </div>
                    <div class="return-info">
                        <div>
                            <div class="info-label">رقم الإرجاع</div>
                            <div class="info-value">${ret.return_number}</div>
                        </div>
                        <div>
                            <div class="info-label">المسوق</div>
                            <div class="info-value">${ret.marketer_name || '-'}</div>
                        </div>
                        <div>
                            <div class="info-label">المتجر</div>
                            <div class="info-value">${ret.store_name || '-'}</div>
                        </div>
                        <div>
                            <div class="info-label">المبلغ</div>
                            <div class="amount-display">${parseFloat(ret.total_amount).toFixed(2)} دينار</div>
                        </div>
                        <div>
                            <div class="info-label">الحالة</div>
                            <div class="status-badge ${status.class}">${status.label}</div>
                        </div>
                    </div>
                    <a href="/warehouse/store-returns/${ret.id}" class="btn-action">
                        عرض التفاصيل
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            `;
        }).join('');
    }

    fetchReturns();
</script>
@endpush
