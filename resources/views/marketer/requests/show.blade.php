@extends('layouts.app')

@section('title', 'تفاصيل الطلب | ' . $requestId)

@push('styles')
<style>
    :root {
        --card-radius: 24px;
        --accent-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        --glass-bg: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(255, 255, 255, 0.4);
    }

    body.dark-mode {
        --glass-bg: rgba(30, 41, 59, 0.8);
        --glass-border: rgba(255, 255, 255, 0.1);
    }

    .page-header {
        margin-bottom: 40px;
        animation: fadeInDown 0.6s ease-out;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 16px;
        letter-spacing: -0.5px;
    }

    body.dark-mode .page-title { color: var(--text-dark); }

    .page-title svg {
        color: var(--primary);
        filter: drop-shadow(0 0 8px rgba(139, 92, 246, 0.3));
    }

    .page-layout {
        display: flex;
        flex-direction:row-reverse;
        gap: 24px;
        align-items: start;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Sidebar Styling (Now on the Left in LTR logic, but in RTL layout it means Left) */
    .actions-sidebar {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: var(--card-radius);
        padding: 32px;
        border: 1px solid var(--glass-border);
        box-shadow: var(--shadow-lg);
        position: sticky;
        top: 100px;
        animation: fadeInLeft 0.6s ease-out;
        width: 280px;
        flex-shrink: 0;
    }

    .sidebar-section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border-light);
    }

    body.dark-mode .sidebar-section-title {
        color: var(--text-dark);
        border-color: var(--border-dark);
    }

    .actions-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Main Content Styling */
    .request-content {
        display: flex;
        flex-direction: column;
        gap: 32px;
        animation: fadeInRight 0.6s ease-out;
        flex: 1;
    }

    .info-card {
        background: var(--card-light);
        border-radius: var(--card-radius);
        padding: 40px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }

    body.dark-mode .info-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .info-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--accent-gradient);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 40px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .info-label {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-light);
    }

    body.dark-mode .info-value { color: var(--text-dark); }

    /* Products Table Styling */
    .products-card {
        background: var(--card-light);
        border-radius: var(--card-radius);
        padding: 32px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-md);
    }

    body.dark-mode .products-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
    }

    .section-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    body.dark-mode .section-title { color: var(--text-dark); }

    .table-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .table-container { border-color: var(--border-dark); }

    .products-table {
        width: 100%;
        border-collapse: collapse;
    }

    .products-table thead th {
        background: #f8fafc;
        padding: 16px 24px;
        text-align: right;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        border-bottom: 1px solid var(--border-light);
    }

    body.dark-mode .products-table thead th {
        background: #0f172a;
        border-color: var(--border-dark);
    }

    .products-table tbody td {
        padding: 20px 24px;
        font-weight: 600;
        color: var(--text-light);
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s;
    }

    body.dark-mode .products-table tbody td {
        color: var(--text-dark);
        border-color: var(--border-dark);
    }

    .products-table tbody tr:last-child td { border-bottom: none; }
    .products-table tbody tr:hover td { background: rgba(139, 92, 246, 0.02); }

    /* Status Badges */
    .status-badge {
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .status-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-documented { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.2); }

    /* Buttons */
    .btn {
        width: 100%;
        padding: 14px 20px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
    }

    .btn:active { transform: scale(0.98); }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        transform: translateY(-2px);
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.15);
    }

    .btn-danger:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: var(--bg-light);
        color: #64748b;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .btn-secondary {
        background: #1e293b;
        color: #94a3b8;
        border-color: #334155;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .quantity-pill {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary);
        padding: 6px 14px;
        border-radius: 99px;
        font-size: 14px;
        font-weight: 800;
    }

    /* Animations */
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @media (max-width: 1200px) {
        .page-layout { flex-direction: column; }
        .actions-sidebar { position: static; width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span>بيانات الطلب <span id="invoiceNumber" style="color: var(--primary); opacity: 0.8;">-</span></span>
    </h1>
</div>

<div class="page-layout">
    <div class="actions-sidebar">
        <h3 class="sidebar-section-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20v-6M9 17.63l3-2.63 3 2.63M12 4v6m3-2.63l-3 2.63-3-2.63"></path></svg>
            الإجراءات المتوفرة
        </h3>
        <div class="actions-container" id="actionsContainer">
            <!-- Shimmer loading state or placeholder -->
            <div style="height: 50px; background: rgba(0,0,0,0.05); border-radius: 12px; animation: pulse 1.5s infinite;"></div>
        </div>
    </div>

    <div class="request-content">
        <div class="info-card">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        المسوق
                    </div>
                    <div class="info-value" id="marketerName">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        تاريخ الطلب
                    </div>
                    <div class="info-value" id="createdAt">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        حالة الطلب
                    </div>
                    <div class="info-value"><span id="statusBadge" class="status-badge">-</span></div>
                </div>
                <div class="info-item" id="approvedInfo" style="display: none;">
                    <div class="info-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                        اعتمد بواسطة
                    </div>
                    <div class="info-value" id="approvedBy">-</div>
                </div>
                <div class="info-item" id="approvedAtInfo" style="display: none;">
                    <div class="info-label">تاريخ الاعتماد</div>
                    <div class="info-value" id="approvedAt">-</div>
                </div>
                <div class="info-item" id="documentedInfo" style="display: none;">
                    <div class="info-label">وثق بواسطة</div>
                    <div class="info-value" id="documentedBy">-</div>
                </div>
                <div class="info-item" id="documentedAtInfo" style="display: none;">
                    <div class="info-label">تاريخ التوثيق</div>
                    <div class="info-value" id="documentedAt">-</div>
                </div>
            </div>
        </div>

        <div class="products-card">
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    الأصناف المطلوبة
                </h2>
                <div class="badge" id="itemsCountBadge">0 أصناف</div>
            </div>

            <div class="table-container">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">الرقم</th>
                            <th>اسم المنتج ومواصفاته</th>
                            <th style="width: 150px; text-align: center;">الكمية</th>
                        </tr>
                    </thead>
                    <tbody id="productsBody">
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 40px;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 12px; color: #94a3b8;">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity: 0.5;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                                    <span>جاري تحميل البيانات...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 0.3; }
        100% { opacity: 0.6; }
    }
    .badge {
        padding: 6px 12px;
        background: var(--primary);
        color: white;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
    }
</style>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    const requestId = '{{ $requestId }}';

    async function fetchRequestDetails() {
        try {
            const response = await fetch(`/api/marketer/requests/${requestId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            if (result.data) {
                displayDetails(result.data.request, result.data.items);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayDetails(request, items) {
        const statusMap = {
            'pending': { label: 'قيد الانتظار', class: 'status-pending' },
            'approved': { label: 'تم الموافقة', class: 'status-approved' },
            'documented': { label: 'تم التوثيق', class: 'status-documented' },
            'rejected': { label: 'مرفوض', class: 'status-rejected' },
            'cancelled': { label: 'ملغي', class: 'status-cancelled' }
        };
        
        const status = statusMap[request.status] || { label: request.status, class: '' };
        const createdAtDate = new Date(request.created_at);
        const formattedDate = createdAtDate.toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' });
        const formattedTime = createdAtDate.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
        
        document.getElementById('statusBadge').textContent = status.label;
        document.getElementById('statusBadge').className = `status-badge ${status.class}`;
        document.getElementById('invoiceNumber').textContent = `#${request.invoice_number}`;
        document.getElementById('marketerName').textContent = request.user?.name || '---';
        document.getElementById('createdAt').textContent = `${formattedDate} | ${formattedTime}`;
        document.getElementById('itemsCountBadge').textContent = `${items.length} أصناف`;

        // Approved Info
        if (request.approved_by && request.approved_at) {
            document.getElementById('approvedInfo').style.display = 'flex';
            document.getElementById('approvedAtInfo').style.display = 'flex';
            document.getElementById('approvedBy').textContent = request.approver?.name || 'المسؤول';
            const appDate = new Date(request.approved_at);
            document.getElementById('approvedAt').textContent = appDate.toLocaleDateString('ar-EG');
        }

        // Documented Info
        if (request.documented_by && request.documented_at) {
            document.getElementById('documentedInfo').style.display = 'flex';
            document.getElementById('documentedAtInfo').style.display = 'flex';
            document.getElementById('documentedBy').textContent = request.documenter?.name || 'أمين المخزن';
            const docDate = new Date(request.documented_at);
            document.getElementById('documentedAt').textContent = docDate.toLocaleDateString('ar-EG');
        }

        const tbody = document.getElementById('productsBody');
        if (items && items.length > 0) {
            tbody.innerHTML = items.map((item, index) => `
                <tr>
                    <td style="color: #94a3b8; font-weight: 700;">${(index + 1).toString().padStart(2, '0')}</td>
                    <td>
                        <div style="font-weight: 700; font-size: 16px;">${item.product_name}</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="quantity-pill">${item.quantity}</span>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; padding: 40px;">لا توجد منتجات</td></tr>';
        }

        renderActions(request.status);
    }

    function renderActions(status) {
        const container = document.getElementById('actionsContainer');
        let html = '';

        if (status === 'pending') {
            html += `
                <button class="btn btn-danger" onclick="cancelRequest()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    إلغاء الطلب
                </button>
            `;
        }

        if (status === 'approved') {
            html += `
                <button class="btn btn-success" onclick="window.open('/marketer/requests/${requestId}/print', '_blank')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    طباعة الطلب
                </button>
            `;
        }

        html += `
            <button class="btn btn-secondary" onclick="window.history.back()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5"></path><polyline points="12 19 5 12 12 5"></polyline></svg>
                رجوع للقائمة
            </button>
        `;

        container.innerHTML = html;
    }

    async function cancelRequest() {
        if (!confirm('هل أنت متأكد من إلغاء هذا الطلب؟')) return;

        try {
            const response = await fetch(`/api/marketer/requests/${requestId}/cancel`, {
                method: 'PUT',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                alert('تم إلغاء الطلب بنجاح');
                window.location.reload();
            } else {
                alert('فشل إلغاء الطلب');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إلغاء الطلب');
        }
    }

    fetchRequestDetails();
</script>
@endpush
