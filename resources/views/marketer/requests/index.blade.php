@extends('layouts.app')

@section('title', 'طلبات البضاعة')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-light);
    }

    body.dark-mode .page-title { color: var(--text-dark); }

    .btn-primary {
        padding: 12px 24px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: #7c3aed;
        transform: translateY(-2px);
    }

    .filters-bar {
        background: var(--card-light);
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .filters-bar {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .filter-group {
        flex: 1;
    }

    .filter-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-light);
    }

    body.dark-mode .filter-group label { color: var(--text-dark); }

    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 10px 16px;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        background: var(--bg-light);
        color: var(--text-light);
        font-size: 14px;
        font-family: 'Tajawal', sans-serif;
    }

    body.dark-mode .filter-group select,
    body.dark-mode .filter-group input {
        background: var(--bg-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .tabs-container {
        background: rgba(100, 116, 139, 0.05);
        border-radius: 16px;
        padding: 6px;
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .tabs-container {
        border-color: var(--border-dark);
        background: rgba(255, 255, 255, 0.03);
    }

    .tab-btn {
        flex: 1;
        padding: 12px;
        border: none;
        background: transparent;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 15px;
        font-family: 'Tajawal', sans-serif;
    }

    .tab-btn.active {
        background: var(--card-light);
        color: var(--primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    body.dark-mode .tab-btn.active {
        background: var(--primary);
        color: white;
    }

    .requests-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .request-card {
        background: var(--card-light);
        border-radius: 16px;
        padding: 24px;
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
    }

    body.dark-mode .request-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .request-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .request-icon {
        width: 56px;
        height: 56px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .request-icon svg {
        width: 28px;
        height: 28px;
        color: var(--primary);
    }

    .request-content {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .request-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .request-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 4px;
    }

    body.dark-mode .request-title { color: var(--text-dark); }

    .request-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 14px;
        color: #64748b;
    }

    .request-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .request-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        display: inline-block;
    }

    .status-pending { background: rgba(251, 191, 36, 0.15); color: #f59e0b; }
    .status-approved { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
    .status-rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .status-cancelled { background: rgba(107, 114, 128, 0.15); color: #6b7280; }
    .status-documented { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }

    .btn-view {
        padding: 8px 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-view:hover {
        background: #7c3aed;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-art {
        font-size: 64px;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-light);
    }

    body.dark-mode .empty-state h3 { color: var(--text-dark); }

    .empty-state p {
        color: #64748b;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">طلبات البضاعة</h1>
    <a href="/marketer/requests/create" class="btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        طلب جديد
    </a>
</div>

<div class="tabs-container">
    <button class="tab-btn active" onclick="switchTab('all', this)">الكل</button>
    <button class="tab-btn" onclick="switchTab('pending', this)">قيد الانتظار</button>
    <button class="tab-btn" onclick="switchTab('approved', this)">موافق عليه</button>
    <button class="tab-btn" onclick="switchTab('documented', this)">موثق</button>
    <button class="tab-btn" onclick="switchTab('rejected', this)">مرفوض</button>
    <button class="tab-btn" onclick="switchTab('cancelled', this)">ملغي</button>
</div>

<div class="filters-bar">
    <div class="filter-group">
        <label>البحث برقم الفاتورة</label>
        <input type="text" id="searchInput" placeholder="ابحث برقم الفاتورة...">
    </div>
</div>

<div class="requests-list" id="requestsList">
    <div class="empty-state">
        <div class="empty-art">🔄</div>
        <h3>جاري تحميل البيانات</h3>
        <p>يرجى الانتظار قليلاً...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allRequests = [];
    let currentStatus = 'all';

    async function fetchRequests() {
        try {
            const response = await fetch('/api/marketer/requests', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            allRequests = result.data || [];
            renderRequests();
        } catch (error) {
            console.error('Error:', error);
            showError();
        }
    }

    function switchTab(status, btn) {
        currentStatus = status;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderRequests();
    }

    function renderRequests() {
        const container = document.getElementById('requestsList');
        const searchValue = document.getElementById('searchInput').value;
        
        let filtered = allRequests.filter(req => {
            if (currentStatus !== 'all' && req.status !== currentStatus) return false;
            if (searchValue && !req.invoice_number.toLowerCase().includes(searchValue.toLowerCase())) return false;
            return true;
        });

        if (filtered.length === 0) {
            container.innerHTML = `<div class="empty-state"><div class="empty-art">📦</div><h3>لا توجد طلبات</h3><p>لم يتم العثور على أي طلبات</p></div>`;
            return;
        }

        container.innerHTML = filtered.map(req => {
            const statusMap = {
                'pending': { label: 'قيد الانتظار', class: 'status-pending', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>' },
                'approved': { label: 'موافق عليه', class: 'status-approved', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>' },
                'documented': { label: 'موثق', class: 'status-documented', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>' },
                'rejected': { label: 'مرفوض', class: 'status-rejected', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>' },
                'cancelled': { label: 'ملغي', class: 'status-cancelled', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>' }
            };
            const status = statusMap[req.status] || { label: req.status, class: '', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>' };
            const date = new Date(req.created_at).toLocaleDateString('en-CA');
            const time = new Date(req.created_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });

            return `
                <div class="request-card">
                    <div class="request-icon">${status.icon}</div>
                    <div class="request-content">
                        <div class="request-info">
                            <div class="request-title">${req.invoice_number}</div>
                            <div class="request-meta">
                                <span class="request-meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    ${date}
                                </span>
                                <span class="request-meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    ${time}
                                </span>
                                <span class="status-badge ${status.class}">${status.label}</span>
                            </div>
                        </div>
                        <div class="request-actions">
                            <a href="/marketer/requests/${req.id}" class="btn-view">عرض التفاصيل</a>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function showError() {
        document.getElementById('requestsList').innerHTML = `<div class="empty-state"><div class="empty-art">⚠️</div><h3>حدث خطأ</h3><p>تعذر تحميل البيانات</p></div>`;
    }

    document.getElementById('searchInput').addEventListener('input', () => {
        renderRequests();
    });

    fetchRequests();
</script>
@endpush
