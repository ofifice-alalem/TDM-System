@extends('layouts.app')

@section('title', 'طلبات المسوقين')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <h1>طلبات المسوقين</h1>
            <p>متابعة واعتماد طلبات سحب البضاعة</p>
        </div>
        <div class="search-bar">
            <i data-lucide="search" class="search-icon"></i>
            <input type="text" id="search-input" class="search-input" placeholder="بحث برقم الطلب أو اسم المسوق...">
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="tabs-container" id="status-tabs">
        <div class="tab-item active" data-status="pending">
            <span>قيد الانتظار</span>
            <span class="tab-badge" id="count-pending">0</span>
        </div>
        <div class="tab-item" data-status="waiting_doc">
            <span>بانتظار التوثيق</span>
            <span class="tab-badge" id="count-waiting-doc">2</span>
        </div>
        <div class="tab-item" data-status="documented">
            <span>تم التوثيق</span>
            <span class="tab-badge" id="count-documented">1</span>
        </div>
        <div class="tab-item" data-status="rejected">
            <span>مرفوض / ملغى</span>
            <span class="tab-badge" id="count-rejected">1</span>
        </div>
    </div>

    <!-- Requests Grid -->
    <div id="requests-grid" class="requests-grid">
        <!-- Requests will be loaded here -->
        <div style="text-align: center; padding: 4rem; color: var(--text-muted);">
            <i data-lucide="loader" class="animate-spin" style="width: 40px; height: 40px; margin-bottom: 1rem;"></i>
            <p>جاري تحميل الطلبات...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let allRequests = [];
    let currentStatus = 'pending';

    async function loadRequests() {
        const token = localStorage.getItem('token');
        try {
            const response = await fetch(`${API_BASE_URL}/marketer/requests`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await response.json();
            allRequests = data.data || [];
            
            updateCounts();
            renderRequests();
        } catch (error) {
            console.error('Error loading requests:', error);
            document.getElementById('requests-grid').innerHTML = `
                <div style="text-align: center; padding: 4rem; color: #ef4444;">
                    <i data-lucide="alert-circle" style="width: 40px; height: 40px; margin-bottom: 1rem;"></i>
                    <p>فشل في تحميل البيانات. يرجى المحاولة مرة أخرى.</p>
                </div>
            `;
            lucide.createIcons();
        }
    }

    function updateCounts() {
        const pending = allRequests.filter(r => r.status === 'pending').length;
        document.getElementById('count-pending').textContent = pending;
        // Other counts are static for now or can be calculated if API supports them
    }

    function renderRequests() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const filtered = allRequests.filter(req => {
            const matchesStatus = currentStatus === 'all' || req.status === currentStatus;
            const matchesSearch = req.invoice_number.toLowerCase().includes(searchTerm); // Can add more fields
            return matchesStatus && matchesSearch;
        });

        const container = document.getElementById('requests-grid');
        if (filtered.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 4rem; color: var(--text-muted); background: var(--card-bg); border-radius: 20px; border: 1px dashed var(--border-color);">
                    <i data-lucide="inbox" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p>لا توجد طلبات تطابق المعايير الحالية</p>
                </div>
            `;
        } else {
            container.innerHTML = filtered.map(req => `
                <div class="request-card">
                    <div class="request-info">
                        <div class="request-icon">
                            <i data-lucide="clipboard-list"></i>
                        </div>
                        <div class="request-meta">
                            <div class="request-id">${req.invoice_number}</div>
                            <div class="request-details">
                                <span class="detail-item">
                                    <i data-lucide="user" style="width: 14px;"></i>
                                    ${req.user?.full_name || 'مسوق غير معروف'}
                                </span>
                                <span class="detail-item">
                                    <i data-lucide="calendar" style="width: 14px;"></i>
                                    ${new Date(req.created_at).toLocaleString('ar-SA')}
                                </span>
                                <span class="detail-item">
                                    <i data-lucide="package" style="width: 14px;"></i>
                                    2 أصناف
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="request-actions">
                        <span class="status-tag ${getStatusClass(req.status)}">
                            ${getStatusText(req.status)}
                        </span>
                        <a href="/marketer/requests/${req.id}" class="btn-review">
                            <i data-lucide="eye" style="width: 18px;"></i>
                            مراجعة الطلب
                        </a>
                        <i data-lucide="chevron-left" style="color: var(--text-muted); cursor: pointer;"></i>
                    </div>
                </div>
            `).join('');
        }
        lucide.createIcons();
    }

    function getStatusClass(status) {
        return `status-${status}`;
    }

    function getStatusText(status) {
        const texts = {
            pending: 'قيد الانتظار',
            approved: 'موافق عليه',
            documented: 'موثق',
            rejected: 'مرفوض',
            cancelled: 'ملغي'
        };
        return texts[status] || status;
    }

    // Event Listeners
    document.getElementById('search-input').addEventListener('input', renderRequests);

    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            currentStatus = tab.dataset.status;
            renderRequests();
        });
    });

    loadRequests();
</script>
@endpush
