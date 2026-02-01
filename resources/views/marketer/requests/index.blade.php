@extends('layouts.app')

@section('title', 'طلبات المسوقين')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <h1>طلبات المسوقين</h1>
            <p>متابعة واعتماد طلبات سحب البضاعة</p>
        </div>
        <a href="/marketer/requests/create" class="icon-btn new-request-btn" style="background: var(--purple-gradient); color: white; padding: 0.8rem 2rem; border-radius: 14px; display: flex; align-items: center; gap: 0.75rem; text-decoration: none; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); font-weight: 700; box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3); border: none; width: auto; height: auto; white-space: nowrap;" onmouseover="this.style.transform='translateY(-3px) scale(1.02)'; this.style.boxShadow='0 8px 25px rgba(168, 85, 247, 0.4)'; this.style.filter='brightness(1.1)'; this.querySelector('i').style.transform='rotate(90deg)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 15px rgba(168, 85, 247, 0.3)'; this.style.filter=''; this.querySelector('i').style.transform='rotate(0deg)'">
            <i data-lucide="plus-circle" style="width: 24px; height: 24px; transition: transform 0.4s ease;"></i>
            <span>طلب بضاعة جديد</span>
        </a>
    </div>

    <div class="search-section" style="margin-bottom: 2rem; background: var(--card-bg); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <div class="search-bar" style="max-width: 100%;">
            <i data-lucide="search" class="search-icon" style="right: 1.5rem;"></i>
            <input type="text" id="search-input" class="search-input" placeholder="بحث برقم الطلب، اسم المسوق، أو التفاصيل..." style="padding: 14px 4rem 14px 1.5rem; width: 100%; border-radius: 16px; background: var(--dash-bg); border-color: transparent; font-size: 1rem;">
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="tabs-wrapper" style="margin-bottom: 2.5rem; position: relative;">
        <div class="tabs-container" id="status-tabs" style="gap: 12px; padding: 6px; background: var(--card-bg); border-radius: 18px; border: 1px solid var(--border-color); display: inline-flex;">
            <div class="tab-item active" data-status="pending" style="padding: 12px 24px; border: none; border-radius: 14px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                <i data-lucide="clock" style="width: 18px; height: 18px;"></i>
                <span>قيد الانتظار</span>
                <span class="tab-badge" id="count-pending" style="margin-right: 8px;">0</span>
            </div>
            <div class="tab-item" data-status="waiting_doc" style="padding: 12px 24px; border: none; border-radius: 14px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                <i data-lucide="file-check" style="width: 18px; height: 18px;"></i>
                <span>بانتظار التوثيق</span>
                <span class="tab-badge" id="count-waiting-doc" style="margin-right: 8px;">2</span>
            </div>
            <div class="tab-item" data-status="documented" style="padding: 12px 24px; border: none; border-radius: 14px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
                <span>تم التوثيق</span>
                <span class="tab-badge" id="count-documented" style="margin-right: 8px;">1</span>
            </div>
            <div class="tab-item" data-status="rejected" style="padding: 12px 24px; border: none; border-radius: 14px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                <i data-lucide="x-circle" style="width: 18px; height: 18px;"></i>
                <span>مرفوض / ملغى</span>
                <span class="tab-badge" id="count-rejected" style="margin-right: 8px;">1</span>
            </div>
        </div>
    </div>

    <!-- Requests Grid -->
    <div id="requests-grid" class="requests-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 1.5rem;">
        <!-- Requests will be loaded here -->
        <div style="grid-column: 1/-1; text-align: center; padding: 6rem; color: var(--text-muted); background: var(--card-bg); border-radius: 30px; border: 1px dashed var(--border-color);">
            <div class="loader-container" style="display: flex; flex-direction: column; align-items: center; gap: 1.5rem;">
                <div class="premium-loader" style="width: 50px; height: 50px; border: 4px solid var(--accent-purple-light); border-top-color: var(--accent-purple); border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="font-size: 1.1rem; font-weight: 500;">جاري مزامنة الطلبات من الخادم...</p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin { to { transform: rotate(360deg); } }
    
    .tab-item {
        color: var(--text-muted) !important;
        background: transparent !important;
    }
    
    .tab-item:hover {
        background: var(--accent-purple-light) !important;
        color: var(--accent-purple) !important;
    }
    
    .tab-item.active {
        background: var(--purple-gradient) !important;
        color: white !important;
        box-shadow: 0 8px 20px rgba(168, 85, 247, 0.25);
    }
    
    .tab-item.active .tab-badge {
        background: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }
    
    .request-card {
        border: 1px solid var(--border-color) !important;
        padding: 1.75rem !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 1.5rem !important;
        background: var(--card-bg) !important;
        border-radius: 24px !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    
    .request-card:hover {
        border-color: var(--accent-purple) !important;
        transform: translateY(-8px) scale(1.01) !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
    }
    
    .request-top {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .status-badge-premium {
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-review-premium {
        width: 100%;
        padding: 12px;
        border-radius: 14px;
        background: var(--dash-bg);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s;
        text-decoration: none;
    }
    
    .btn-review-premium:hover {
        background: var(--text-main);
        color: white;
        border-color: var(--text-main);
    }
</style>
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
                <div style="grid-column: 1/-1; text-align: center; padding: 4rem; color: #ef4444;">
                    <i data-lucide="alert-circle" style="width: 48px; height: 48px; margin-bottom: 1rem;"></i>
                    <p style="font-weight: 700;">حدث خطأ أثناء تحميل البيانات</p>
                    <button onclick="loadRequests()" style="margin-top: 1rem; padding: 8px 20px; border-radius: 10px; background: #ef4444; color: white; border: none; cursor: pointer;">إعادة المحاولة</button>
                </div>
            `;
            lucide.createIcons();
        }
    }

    function updateCounts() {
        const pending = allRequests.filter(r => r.status === 'pending').length;
        document.getElementById('count-pending').textContent = pending;
    }

    function renderRequests() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const filtered = allRequests.filter(req => {
            const matchesStatus = currentStatus === 'all' || req.status === currentStatus;
            const matchesSearch = req.invoice_number.toLowerCase().includes(searchTerm) || 
                                 (req.user?.full_name || '').toLowerCase().includes(searchTerm);
            return matchesStatus && matchesSearch;
        });

        const container = document.getElementById('requests-grid');
        if (filtered.length === 0) {
            container.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 6rem; color: var(--text-muted); background: var(--card-bg); border-radius: 30px; border: 1px dashed var(--border-color);">
                    <i data-lucide="inbox" style="width: 64px; height: 64px; margin-bottom: 1.5rem; opacity: 0.3;"></i>
                    <p style="font-size: 1.2rem;">لا توجد طلبات متوفرة حالياً</p>
                </div>
            `;
        } else {
            container.innerHTML = filtered.map(req => `
                <div class="request-card">
                    <div class="request-top">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="request-icon" style="width: 50px; height: 50px; background: var(--accent-purple-light); color: var(--accent-purple); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                                <i data-lucide="file-text"></i>
                            </div>
                            <div>
                                <div class="request-id" style="font-size: 1.1rem; font-weight: 800; color: var(--text-main);">${req.invoice_number}</div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">طلب سحب بضاعة</div>
                            </div>
                        </div>
                        <span class="status-badge-premium ${getStatusClass(req.status)}">
                            ${getStatusText(req.status)}
                        </span>
                    </div>
                    
                    <div class="request-middle" style="width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; padding: 1.25rem; background: var(--dash-bg); border-radius: 16px;">
                        <div class="detail-item">
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-bottom: 4px;">المسوق</span>
                            <span style="font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;">
                                <i data-lucide="user" style="width: 14px;"></i>
                                ${req.user?.full_name || 'غير معروف'}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-bottom: 4px;">التاريخ</span>
                            <span style="font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;">
                                <i data-lucide="calendar" style="width: 14px;"></i>
                                ${new Date(req.created_at).toLocaleDateString('ar-SA')}
                            </span>
                        </div>
                    </div>

                    <a href="/marketer/requests/${req.id}" class="btn-review-premium">
                        <i data-lucide="external-link" style="width: 18px;"></i>
                        تفاصيل ومعالجة الطلب
                    </a>
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
