@extends('layouts.app')

@section('title', 'تفاصيل طلب الإرجاع')

@push('styles')
<style>
    .page-header { margin-bottom: 40px; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    
    .page-layout { display: flex; flex-direction: row-reverse; gap: 24px; align-items: start; }
    .actions-sidebar { background: var(--card-light); border-radius: 20px; padding: 32px; border: 1px solid var(--border-light); position: sticky; top: 100px; width: 280px; flex-shrink: 0; }
    body.dark-mode .actions-sidebar { background: var(--card-dark); border-color: var(--border-dark); }
    
    .request-content { flex: 1; display: flex; flex-direction: column; gap: 32px; }
    .info-card { background: var(--card-light); border-radius: 20px; padding: 40px; border: 1px solid var(--border-light); }
    body.dark-mode .info-card { background: var(--card-dark); border-color: var(--border-dark); }
    
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }
    .info-label { font-size: 13px; color: #94a3b8; font-weight: 600; margin-bottom: 8px; }
    .info-value { font-size: 16px; font-weight: 700; color: var(--text-light); }
    body.dark-mode .info-value { color: var(--text-dark); }
    
    .status-badge { padding: 8px 16px; border-radius: 10px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-documented { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    
    .products-table { width: 100%; border-collapse: collapse; }
    .products-table thead th { background: #f8fafc; padding: 16px 24px; text-align: right; font-size: 14px; font-weight: 600; color: #64748b; }
    body.dark-mode .products-table thead th { background: #0f172a; }
    .products-table tbody td { padding: 20px 24px; font-weight: 600; color: var(--text-light); border-bottom: 1px solid #f1f5f9; }
    body.dark-mode .products-table tbody td { color: var(--text-dark); border-color: var(--border-dark); }
    
    .btn { width: 100%; padding: 14px 20px; border-radius: 14px; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s; border: none; cursor: pointer; font-family: 'Tajawal', sans-serif; }
    .btn-danger { background: rgba(239, 68, 68, 0.08); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15); }
    .btn-danger:hover { background: #ef4444; color: white; }
    .btn-secondary { background: var(--bg-light); color: #64748b; border: 1px solid var(--border-light); }
    body.dark-mode .btn-secondary { background: #1e293b; color: #94a3b8; border-color: #334155; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
        <span>تفاصيل طلب الإرجاع <span id="invoiceNumber" style="color: var(--primary);">-</span></span>
    </h1>
</div>

<div class="page-layout">
    <div class="actions-sidebar">
        <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 24px;">الإجراءات</h3>
        <div id="actionsContainer" style="display: flex; flex-direction: column; gap: 16px;"></div>
    </div>

    <div class="request-content">
        <div class="info-card">
            <div class="info-grid">
                <div>
                    <div class="info-label">المسوق</div>
                    <div class="info-value" id="marketerName">-</div>
                </div>
                <div>
                    <div class="info-label">تاريخ الطلب</div>
                    <div class="info-value" id="createdAt">-</div>
                </div>
                <div>
                    <div class="info-label">الحالة</div>
                    <div class="info-value"><span id="statusBadge" class="status-badge">-</span></div>
                </div>
            </div>
        </div>

        <div class="info-card">
            <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 28px;">المنتجات المرجعة</h2>
            <table class="products-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">الرقم</th>
                        <th>اسم المنتج</th>
                        <th style="width: 150px; text-align: center;">الكمية</th>
                    </tr>
                </thead>
                <tbody id="productsBody">
                    <tr><td colspan="3" style="text-align: center; padding: 40px;">جاري التحميل...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    const returnId = '{{ $returnId }}';

    async function fetchDetails() {
        try {
            const response = await fetch(`/api/marketer/returns/${returnId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            if (result.data) {
                displayDetails(result.data.return, result.data.items);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayDetails(returnData, items) {
        const statusMap = {
            'pending': { label: 'قيد الانتظار', class: 'status-pending' },
            'approved': { label: 'موافق عليه', class: 'status-approved' },
            'documented': { label: 'موثق', class: 'status-documented' },
            'rejected': { label: 'مرفوض', class: 'status-rejected' },
            'cancelled': { label: 'ملغي', class: 'status-cancelled' }
        };
        
        const status = statusMap[returnData.status] || { label: returnData.status, class: '' };
        const createdAtDate = new Date(returnData.created_at);
        const formattedDate = createdAtDate.toLocaleDateString('en-US').replace(/\//g, '-');
        
        document.getElementById('statusBadge').textContent = status.label;
        document.getElementById('statusBadge').className = `status-badge ${status.class}`;
        document.getElementById('invoiceNumber').textContent = `#${returnData.invoice_number}`;
        document.getElementById('marketerName').textContent = returnData.marketer_name || '---';
        document.getElementById('createdAt').textContent = formattedDate;

        const tbody = document.getElementById('productsBody');
        if (items && items.length > 0) {
            tbody.innerHTML = items.map((item, index) => `
                <tr>
                    <td style="color: #94a3b8;">${(index + 1).toString().padStart(2, '0')}</td>
                    <td>${item.product_name}</td>
                    <td style="text-align: center;"><span style="background: rgba(139, 92, 246, 0.1); color: var(--primary); padding: 6px 14px; border-radius: 99px; font-weight: 800;">${item.quantity}</span></td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; padding: 40px;">لا توجد منتجات</td></tr>';
        }

        renderActions(returnData.status);
    }

    function renderActions(status) {
        const container = document.getElementById('actionsContainer');
        let html = '';

        if (status === 'pending' || status === 'approved') {
            html += `
                <button class="btn btn-danger" onclick="cancelReturn()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    إلغاء الطلب
                </button>
            `;
        }

        html += `
            <button class="btn btn-secondary" onclick="window.history.back()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5"></path><polyline points="12 19 5 12 12 5"></polyline></svg>
                رجوع
            </button>
        `;

        container.innerHTML = html;
    }

    async function cancelReturn() {
        const confirmed = await showModal('إلغاء الطلب', 'هل أنت متأكد من إلغاء هذا الطلب؟');
        if (!confirmed) return;

        try {
            const response = await fetch(`/api/marketer/returns/${returnId}/cancel`, {
                method: 'PUT',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                await showModal('نجح', 'تم إلغاء الطلب بنجاح');
                window.location.reload();
            } else {
                await showModal('خطأ', 'فشل إلغاء الطلب');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ');
        }
    }

    fetchDetails();
</script>
@endpush
