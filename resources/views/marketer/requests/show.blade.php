@extends('layouts.app')

@section('title', 'تفاصيل الطلب')

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

    .status-badge {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        display: inline-block;
    }

    .status-pending { background: rgba(251, 191, 36, 0.15); color: #f59e0b; }
    .status-approved { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
    .status-rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .status-cancelled { background: rgba(107, 114, 128, 0.15); color: #6b7280; }
    .status-documented { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }

    .info-card {
        background: var(--card-light);
        border-radius: 16px;
        padding: 24px;
        border: 1px solid var(--border-light);
        margin-bottom: 24px;
    }

    body.dark-mode .info-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .info-item label {
        display: block;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .info-item value {
        display: block;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-light);
    }

    body.dark-mode .info-item value { color: var(--text-dark); }

    .products-table {
        background: var(--card-light);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-light);
        margin-bottom: 24px;
    }

    body.dark-mode .products-table {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: rgba(139, 92, 246, 0.1);
    }

    th {
        padding: 16px;
        text-align: right;
        font-weight: 700;
        font-size: 14px;
        color: var(--text-light);
    }

    body.dark-mode th { color: var(--text-dark); }

    td {
        padding: 16px;
        border-top: 1px solid var(--border-light);
        color: var(--text-light);
    }

    body.dark-mode td {
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .btn-cancel {
        padding: 12px 24px;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: rgba(239, 68, 68, 0.2);
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">تفاصيل الطلب</h1>
    <span id="statusBadge" class="status-badge">جاري التحميل...</span>
</div>

<div class="info-card">
    <div class="info-grid">
        <div class="info-item">
            <label>رقم الفاتورة</label>
            <value id="invoiceNumber">-</value>
        </div>
        <div class="info-item">
            <label>تاريخ الإنشاء</label>
            <value id="createdAt">-</value>
        </div>
        <div class="info-item">
            <label>الحالة</label>
            <value id="statusText">-</value>
        </div>
    </div>
</div>

<div class="products-table">
    <table>
        <thead>
            <tr>
                <th>المنتج</th>
                <th>الكمية</th>
            </tr>
        </thead>
        <tbody id="productsBody">
            <tr>
                <td colspan="2" style="text-align: center;">جاري التحميل...</td>
            </tr>
        </tbody>
    </table>
</div>

<div id="actionsContainer"></div>
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
            'approved': { label: 'موافق عليه', class: 'status-approved' },
            'documented': { label: 'موثق', class: 'status-documented' },
            'rejected': { label: 'مرفوض', class: 'status-rejected' },
            'cancelled': { label: 'ملغي', class: 'status-cancelled' }
        };
        
        const status = statusMap[request.status] || { label: request.status, class: '' };
        
        document.getElementById('statusBadge').textContent = status.label;
        document.getElementById('statusBadge').className = `status-badge ${status.class}`;
        document.getElementById('invoiceNumber').textContent = request.invoice_number;
        document.getElementById('createdAt').textContent = new Date(request.created_at).toLocaleDateString('ar-EG');
        document.getElementById('statusText').textContent = status.label;

        const tbody = document.getElementById('productsBody');
        if (items && items.length > 0) {
            tbody.innerHTML = items.map(item => `
                <tr>
                    <td><strong>${item.product_name}</strong></td>
                    <td>${item.quantity} وحدة</td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="2" style="text-align: center;">لا توجد منتجات</td></tr>';
        }

        if (request.status === 'pending' || request.status === 'approved') {
            document.getElementById('actionsContainer').innerHTML = `
                <button class="btn-cancel" onclick="cancelRequest()">إلغاء الطلب</button>
            `;
        }
    }

    async function cancelRequest() {
        if (!confirm('هل أنت متأكد من إلغاء هذا الطلب؟')) return;

        try {
            const response = await fetch(`/api/marketer/requests/${requestId}/cancel`, {
                method: 'PUT',
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });

            if (response.ok) {
                alert('تم إلغاء الطلب بنجاح');
                window.location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إلغاء الطلب');
        }
    }

    fetchRequestDetails();
</script>
@endpush
