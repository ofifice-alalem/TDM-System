@extends('layouts.app')

@section('title', 'طلبات إرجاع البضاعة')

@push('styles')
<style>
    :root {
        --card-radius: 20px;
        --accent-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        animation: fadeInDown 0.6s ease-out;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    body.dark-mode .page-title { color: var(--text-dark); }

    .btn-add-request {
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
        gap: 10px;
        text-decoration: none;
        font-family: 'Tajawal', sans-serif;
    }

    .btn-add-request:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .tabs-container {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        background: rgba(100, 116, 139, 0.05);
        padding: 6px;
        border-radius: 14px;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .tabs-container { border-color: var(--border-dark); }

    .tab-btn {
        flex: 1;
        padding: 10px;
        border: none;
        background: transparent;
        border-radius: 10px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Tajawal', sans-serif;
        font-size: 15px;
    }

    .tab-btn.active {
        background: var(--card-light);
        color: var(--primary);
        box-shadow: var(--shadow-sm);
    }

    body.dark-mode .tab-btn.active {
        background: var(--primary);
        color: white;
    }

    .filters-bar {
        background: var(--card-light);
        padding: 24px;
        border-radius: var(--card-radius);
        margin-bottom: 24px;
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
    }

    body.dark-mode .filters-bar {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 8px;
    }

    .search-input-premium {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        background: var(--bg-light);
        color: var(--text-light);
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: 'Tajawal', sans-serif;
    }

    body.dark-mode .search-input-premium {
        background: var(--bg-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .search-input-premium:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        outline: none;
    }

    .requests-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .request-card-premium {
        background: var(--card-light);
        border-radius: var(--card-radius);
        padding: 24px;
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
        animation: fadeInRight 0.6s ease-out;
    }

    body.dark-mode .request-card-premium {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .request-card-premium:hover {
        transform: scale(1.01);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }

    .request-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .request-info-group {
        flex: 1;
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
        align-items: center;
        gap: 20px;
    }

    .invoice-num {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
    }

    .date-info {
        display: flex;
        flex-direction: column;
    }

    .date-label {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 600;
    }

    .date-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-light);
    }
    body.dark-mode .date-value { color: var(--text-dark); }

    .status-badge-premium {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        justify-self: start;
    }

    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-documented { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; }

    .btn-action {
        padding: 10px 20px;
        background: var(--primary);
        color: white;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .empty-state-premium {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state-premium svg {
        width: 64px;
        height: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        طلبات إرجاع البضاعة
    </h1>
    <a href="/marketer/returns/create" class="btn-add-request">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        طلب إرجاع جديد
    </a>
</div>

<div class="tabs-container">
    <button class="tab-btn active" onclick="switchTab('all', this)">كل الطلبات</button>
    <button class="tab-btn" onclick="switchTab('pending', this)">قيد الانتظار</button>
    <button class="tab-btn" onclick="switchTab('approved', this)">موافق عليه</button>
    <button class="tab-btn" onclick="switchTab('documented', this)">موثق</button>
    <button class="tab-btn" onclick="switchTab('rejected', this)">مرفوض</button>
    <button class="tab-btn" onclick="switchTab('cancelled', this)">ملغي</button>
</div>

<div class="filters-bar">
    <div class="filter-group">
        <label>رقم الفاتورة</label>
        <input type="text" id="searchInput" class="search-input-premium" placeholder="ابحث برقم الفاتورة...">
    </div>
</div>

<div class="requests-list" id="requestsList">
    <div class="empty-state-premium">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <h3>جاري تحميل الطلبات...</h3>
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
            const response = await fetch('/api/marketer/returns', {
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
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        
        let filtered = allRequests.filter(req => {
            if (currentStatus !== 'all' && req.status !== currentStatus) return false;
            if (searchValue && !req.invoice_number.toLowerCase().includes(searchValue)) return false;
            return true;
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="empty-state-premium">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                    <h3>لا توجد طلبات تطابق بحثك</h3>
                </div>`;
            return;
        }

        container.innerHTML = filtered.map(req => {
            const statusMap = {
                'pending': { 
                    label: 'قيد الانتظار', 
                    class: 'status-pending', 
                    bg: 'rgba(245, 158, 11, 0.1)',
                    color: '#f59e0b',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>' 
                },
                'approved': { 
                    label: 'موافق عليه', 
                    class: 'status-approved', 
                    bg: 'rgba(16, 185, 129, 0.1)',
                    color: '#10b981',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' 
                },
                'documented': { 
                    label: 'موثق', 
                    class: 'status-documented', 
                    bg: 'rgba(59, 130, 246, 0.1)',
                    color: '#3b82f6',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>' 
                },
                'rejected': { 
                    label: 'مرفوض', 
                    class: 'status-rejected', 
                    bg: 'rgba(239, 68, 68, 0.1)',
                    color: '#ef4444',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>' 
                },
                'cancelled': { 
                    label: 'ملغي', 
                    class: 'status-cancelled', 
                    bg: 'rgba(100, 116, 139, 0.1)',
                    color: '#64748b',
                    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line></svg>' 
                }
            };
            const status = statusMap[req.status] || { label: req.status, class: '', icon: '', bg: 'rgba(139, 92, 246, 0.1)', color: 'var(--primary)' };
            const createdAtDate = new Date(req.created_at);
            const dateStr = createdAtDate.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
            const timeStr = createdAtDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });

            return `
                <div class="request-card-premium">
                    <div class="request-icon-box" style="background: ${status.bg}; color: ${status.color};">
                        ${status.icon}
                    </div>
                    <div class="request-info-group">
                        <div class="invoice-num">#${req.invoice_number}</div>
                        <div class="date-info">
                            <span class="date-label">التوقيت</span>
                            <span class="date-value">${dateStr} | ${timeStr}</span>
                        </div>
                        <div class="status-badge-premium ${status.class}">
                            ${status.icon.replace('width="24" height="24"', 'width="18" height="18"')}
                            ${status.label}
                        </div>
                    </div>
                    <a href="/marketer/returns/${req.id}" class="btn-action">
                        عرض التفاصيل
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            `;
        }).join('');
    }

    function showError() {
        document.getElementById('requestsList').innerHTML = '<div class="empty-state-premium">⚠️ <h3>خطأ في تحميل البيانات</h3></div>';
    }

    document.getElementById('searchInput').addEventListener('input', renderRequests);

    fetchRequests();
</script>
@endpush
