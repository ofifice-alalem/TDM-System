@extends('layouts.app')

@section('title', 'تفاصيل طلب الإرجاع')

@push('styles')
<style>
    .page-header { margin-bottom: 40px; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .page-layout { display: flex; flex-direction: row-reverse; gap: 24px; max-width: 1400px; margin: 0 auto; }
    .actions-sidebar { background: var(--card-light); border-radius: 24px; padding: 32px; border: 1px solid var(--border-light); position: sticky; top: 100px; width: 280px; flex-shrink: 0; }
    body.dark-mode .actions-sidebar { background: var(--card-dark); border-color: var(--border-dark); }
    .sidebar-title { font-size: 15px; font-weight: 700; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid var(--border-light); }
    body.dark-mode .sidebar-title { border-color: var(--border-dark); }
    .actions-container { display: flex; flex-direction: column; gap: 16px; }
    .content { flex: 1; display: flex; flex-direction: column; gap: 32px; }
    .info-card { background: var(--card-light); border-radius: 24px; padding: 40px; border: 1px solid var(--border-light); position: relative; overflow: hidden; }
    body.dark-mode .info-card { background: var(--card-dark); border-color: var(--border-dark); }
    .info-card::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }
    .info-item { display: flex; flex-direction: column; gap: 8px; }
    .info-label { font-size: 13px; color: #94a3b8; font-weight: 600; }
    .info-value { font-size: 16px; font-weight: 700; color: var(--text-light); }
    body.dark-mode .info-value { color: var(--text-dark); }
    .status-badge { padding: 8px 16px; border-radius: 10px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    .amount-display { font-size: 32px; font-weight: 800; color: var(--primary); }
    .items-table { width: 100%; border-collapse: collapse; margin-top: 24px; }
    .items-table th { background: rgba(139, 92, 246, 0.05); padding: 12px; text-align: right; font-weight: 700; font-size: 13px; color: #64748b; }
    .items-table td { padding: 12px; border-bottom: 1px solid var(--border-light); }
    body.dark-mode .items-table td { border-color: var(--border-dark); }
    .btn { width: 100%; padding: 14px 20px; border-radius: 14px; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 10px; border: none; cursor: pointer; transition: all 0.3s; font-family: 'Tajawal', sans-serif; }
    .btn-danger { background: rgba(239, 68, 68, 0.08); color: #ef4444; }
    .btn-danger:hover { background: #ef4444; color: white; }
    .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .btn-success:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
    .btn-secondary { background: var(--bg-light); color: #64748b; }
    body.dark-mode .btn-secondary { background: #1e293b; color: #94a3b8; }
    .sidebar-info-card { background: rgba(16, 185, 129, 0.05); padding: 16px; border-radius: 16px; margin-top: 20px; }
    .sidebar-info-title { font-size: 14px; font-weight: 700; color: #10b981; margin-bottom: 12px; }
    .small-info-item { display: flex; flex-direction: column; gap: 4px; padding: 8px 0; border-bottom: 1px solid var(--border-light); }
    body.dark-mode .small-info-item { border-color: var(--border-dark); }
    .small-info-item:last-child { border-bottom: none; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
        تفاصيل طلب الإرجاع #<span id="returnNumber">-</span>
    </h1>
</div>

<div class="page-layout">
    <div class="actions-sidebar">
        <h3 class="sidebar-title">الإجراءات المتوفرة</h3>
        <div class="actions-container" id="actionsContainer">
            <div style="height: 50px; background: rgba(0,0,0,0.05); border-radius: 12px;"></div>
        </div>

        <div class="sidebar-info-card" id="approvalSection" style="display: none;">
            <div class="sidebar-info-title">تم التوثيق</div>
            <div class="small-info-item">
                <div class="info-label">بواسطة</div>
                <div class="info-value" id="keeperName">-</div>
            </div>
            <div class="small-info-item">
                <div class="info-label">بتاريخ</div>
                <div class="info-value" id="confirmedAt">-</div>
            </div>
        </div>

        <div class="sidebar-info-card" id="rejectionSection" style="display: none; background: rgba(239, 68, 68, 0.05);">
            <div class="sidebar-info-title" style="color: #ef4444;">تم الرفض</div>
            <div class="small-info-item">
                <div class="info-label">سبب الرفض</div>
                <div class="info-value" id="rejectionNotes" style="color: #ef4444; font-size: 14px;">-</div>
            </div>
        </div>

        <div class="sidebar-info-card" id="cancellationSection" style="display: none; background: rgba(100, 116, 139, 0.05);">
            <div class="sidebar-info-title" style="color: #64748b;">تم الإلغاء</div>
            <div class="small-info-item">
                <div class="info-label">سبب الإلغاء</div>
                <div class="info-value" id="cancellationNotes" style="color: #64748b; font-size: 14px;">-</div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="info-card">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">رقم الإرجاع</div>
                    <div class="info-value" id="returnNumberValue">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الفاتورة الأصلية</div>
                    <div class="info-value" id="invoiceNumber">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">المتجر</div>
                    <div class="info-value" id="storeName">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">المبلغ الإجمالي</div>
                    <div class="amount-display" id="totalAmount">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ الطلب</div>
                    <div class="info-value" id="createdAt">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الحالة</div>
                    <div><span id="statusBadge" class="status-badge">-</span></div>
                </div>
            </div>

            <h3 style="margin-top: 40px; margin-bottom: 16px; font-weight: 700;">المنتجات المرجعة</h3>
            <table class="items-table" id="itemsTable">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>المجموع</th>
                    </tr>
                </thead>
                <tbody id="itemsBody"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    const returnId = '{{ $id }}';

    async function fetchDetails() {
        try {
            const response = await fetch(`/api/marketer/store-returns/${returnId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            if (result.data) {
                displayDetails(result.data);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayDetails(data) {
        const statusMap = {
            'pending': { label: 'قيد الانتظار', class: 'status-pending' },
            'approved': { label: 'موثق', class: 'status-approved' },
            'rejected': { label: 'مرفوض', class: 'status-rejected' },
            'cancelled': { label: 'ملغي', class: 'status-cancelled' }
        };
        
        const status = statusMap[data.status];
        
        document.getElementById('returnNumber').textContent = data.return_number;
        document.getElementById('returnNumberValue').textContent = data.return_number;
        document.getElementById('invoiceNumber').textContent = data.sales_invoice_number || '-';
        document.getElementById('storeName').textContent = data.store_name || '-';
        document.getElementById('totalAmount').textContent = parseFloat(data.total_amount).toFixed(2) + ' ريال';
        document.getElementById('createdAt').textContent = new Date(data.created_at).toLocaleDateString('en-US').replace(/\//g, '-');
        document.getElementById('statusBadge').textContent = status.label;
        document.getElementById('statusBadge').className = `status-badge ${status.class}`;

        if (data.status === 'approved' && data.keeper_name) {
            document.getElementById('approvalSection').style.display = 'block';
            document.getElementById('keeperName').textContent = data.keeper_name;
            document.getElementById('confirmedAt').textContent = data.confirmed_at ? new Date(data.confirmed_at).toLocaleDateString('en-US').replace(/\//g, '-') : '-';
        }

        if (data.status === 'rejected' && data.notes) {
            document.getElementById('rejectionSection').style.display = 'block';
            document.getElementById('rejectionNotes').textContent = data.notes;
        }

        if (data.status === 'cancelled' && data.notes) {
            document.getElementById('cancellationSection').style.display = 'block';
            document.getElementById('cancellationNotes').textContent = data.notes;
        }

        if (data.items) {
            const tbody = document.getElementById('itemsBody');
            tbody.innerHTML = data.items.map(item => `
                <tr>
                    <td>${item.product_name}</td>
                    <td>${item.quantity}</td>
                    <td>${parseFloat(item.unit_price).toFixed(2)} ريال</td>
                    <td>${parseFloat(item.subtotal).toFixed(2)} ريال</td>
                </tr>
            `).join('');
        }

        renderActions(data.status, data.stamped_image);
    }

    function renderActions(status, stampedImage) {
        const container = document.getElementById('actionsContainer');
        let html = '';

        if (status === 'pending') {
            html += `
                <button class="btn btn-danger" onclick="cancelReturn()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    إلغاء الطلب
                </button>
            `;
        }

        if (status === 'approved' && stampedImage) {
            html += `
                <button class="btn btn-success" onclick="viewStampedImage('${stampedImage}')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    عرض الفاتورة المختومة
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

    async function cancelReturn() {
        const modal = document.createElement('div');
        modal.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 10000;';
        modal.innerHTML = `
            <div style="background: var(--card-light); border-radius: 20px; padding: 40px; max-width: 500px; width: 90%;">
                <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 20px; text-align: center;">إلغاء طلب الإرجاع</h3>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 700; margin-bottom: 8px;">سبب الإلغاء (اختياري)</label>
                    <textarea id="cancelNotes" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid var(--border-light); font-family: 'Tajawal', sans-serif; min-height: 100px; resize: vertical;" placeholder="أدخل سبب الإلغاء..."></textarea>
                </div>
                <div style="display: flex; gap: 16px; justify-content: flex-end;">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" style="padding: 12px 32px; background: var(--bg-light); color: #64748b; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif;">إلغاء</button>
                    <button onclick="confirmCancel()" style="padding: 12px 32px; background: #ef4444; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif;">تأكيد الإلغاء</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    async function confirmCancel() {
        const notes = document.getElementById('cancelNotes').value;
        document.querySelector('[style*="z-index: 10000"]').remove();

        try {
            const response = await fetch(`/api/marketer/store-returns/${returnId}/cancel`, {
                method: 'PUT',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notes })
            });

            if (response.ok) {
                const successModal = document.createElement('div');
                successModal.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 10000;';
                successModal.innerHTML = `
                    <div style="background: var(--card-light); border-radius: 20px; padding: 40px; max-width: 400px; text-align: center;">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" style="margin: 0 auto 20px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 12px;">تم إلغاء الطلب بنجاح</h3>
                        <button onclick="window.location.reload()" style="margin-top: 24px; padding: 12px 32px; background: var(--primary); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif;">حسناً</button>
                    </div>
                `;
                document.body.appendChild(successModal);
            } else {
                alert('فشل إلغاء الطلب');
            }
        } catch (error) {
            alert('حدث خطأ أثناء إلغاء الطلب');
        }
    }

    function viewStampedImage(imageUrl) {
        const modal = document.createElement('div');
        modal.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); display: flex; align-items: center; justify-content: center; z-index: 10000; padding: 20px;';
        modal.innerHTML = `
            <div style="position: relative; max-width: 90%; max-height: 90vh;">
                <button onclick="this.parentElement.parentElement.remove()" style="position: absolute; top: -40px; right: 0; background: white; border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer; font-size: 24px;">&times;</button>
                <img src="${imageUrl}" style="max-width: 100%; max-height: 85vh; border-radius: 12px;" alt="الفاتورة المختومة">
            </div>
        `;
        modal.onclick = (e) => { if (e.target === modal) modal.remove(); };
        document.body.appendChild(modal);
    }

    fetchDetails();
</script>
@endpush
