@extends('layouts.app')

@section('title', 'تفاصيل الفاتورة')

@push('styles')
<style>
    .page-header { margin-bottom: 40px; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    
    .page-layout { display: flex; flex-direction: row-reverse; gap: 24px; align-items: start; }
    .actions-sidebar { background: var(--card-light); border-radius: 20px; padding: 32px; border: 1px solid var(--border-light); position: sticky; top: 100px; width: 280px; flex-shrink: 0; }
    body.dark-mode .actions-sidebar { background: var(--card-dark); border-color: var(--border-dark); }
    
    .invoice-content { flex: 1; display: flex; flex-direction: column; gap: 32px; }
    .info-card { background: var(--card-light); border-radius: 20px; padding: 40px; border: 1px solid var(--border-light); }
    body.dark-mode .info-card { background: var(--card-dark); border-color: var(--border-dark); }
    
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }
    .info-label { font-size: 13px; color: #94a3b8; font-weight: 600; margin-bottom: 8px; }
    .info-value { font-size: 16px; font-weight: 700; color: var(--text-light); }
    body.dark-mode .info-value { color: var(--text-dark); }
    
    .status-badge { padding: 8px 16px; border-radius: 10px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-approved { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .products-table { width: 100%; border-collapse: collapse; }
    .products-table thead th { background: #f8fafc; padding: 16px 24px; text-align: right; font-size: 14px; font-weight: 600; color: #64748b; }
    body.dark-mode .products-table thead th { background: #0f172a; }
    .products-table tbody td { padding: 20px 24px; font-weight: 600; color: var(--text-light); border-bottom: 1px solid #f1f5f9; }
    body.dark-mode .products-table tbody td { color: var(--text-dark); border-color: var(--border-dark); }
    
    .btn { width: 100%; padding: 14px 20px; border-radius: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 10px; border: none; cursor: pointer; font-family: 'Tajawal', sans-serif; }
    .btn-danger { background: rgba(239, 68, 68, 0.08); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15); }
    .btn-danger:hover { background: #ef4444; color: white; }
    .btn-secondary { background: var(--bg-light); color: #64748b; border: 1px solid var(--border-light); }
    body.dark-mode .btn-secondary { background: #1e293b; color: #94a3b8; border-color: #334155; }
    
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); z-index: 9999; }
    .modal-overlay.active { display: flex; align-items: center; justify-content: center; }
    .modal-dialog { background: var(--card-light); border-radius: 20px; padding: 32px; max-width: 500px; width: 90%; }
    body.dark-mode .modal-dialog { background: var(--card-dark); }
    .modal-title { font-size: 22px; font-weight: 700; text-align: center; margin-bottom: 12px; color: var(--text-light); }
    body.dark-mode .modal-title { color: var(--text-dark); }
    .modal-message { text-align: center; color: #64748b; margin-bottom: 28px; }
    .modal-actions { display: flex; gap: 12px; }
    .modal-btn { flex: 1; padding: 12px; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; font-family: 'Tajawal', sans-serif; }
    .modal-btn-primary { background: var(--primary); color: white; }
    .modal-btn-secondary { background: var(--bg-light); color: #64748b; border: 1px solid var(--border-light); }
    
    .image-modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.95); z-index: 10000; }
    .image-modal-overlay.active { display: flex; align-items: center; justify-content: center; flex-direction: column; }
    .image-modal-header { position: absolute; top: 20px; right: 20px; left: 20px; display: flex; justify-content: space-between; align-items: center; z-index: 10001; }
    .image-modal-title { color: white; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .image-modal-close { padding: 10px 20px; background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; font-family: 'Tajawal', sans-serif; }
    .image-modal-close:hover { background: rgba(255, 255, 255, 0.2); }
    .image-modal-content { max-width: 85%; max-height: 80vh; }
    .image-modal-content img { max-width: 100%; max-height: 80vh; border-radius: 16px; box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6); object-fit: contain; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
        <span>فاتورة <span id="invoiceNumber" style="color: var(--primary);">-</span></span>
    </h1>
</div>

<div class="page-layout">
    <div class="actions-sidebar">
        <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 24px;">الإجراءات</h3>
        <div id="actionsContainer" style="display: flex; flex-direction: column; gap: 16px;"></div>
        
        <div style="background: rgba(139, 92, 246, 0.05); padding: 20px; border-radius: 16px; border: 1px solid rgba(139, 92, 246, 0.1); margin-top: 20px;">
            <div style="font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 16px;">تفاصيل الفاتورة</div>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-light);">
                    <span style="font-size: 13px; color: #64748b; font-weight: 600;">المجموع الفرعي</span>
                    <span style="font-size: 14px; font-weight: 700; color: var(--text-light);" id="subtotalSidebar">-</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-light);">
                    <span style="font-size: 13px; color: #64748b; font-weight: 600;">خصم المنتجات</span>
                    <span style="font-size: 14px; font-weight: 700; color: #22c55e;" id="productDiscountSidebar">-</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-light);">
                    <span style="font-size: 13px; color: #64748b; font-weight: 600;">خصم الفاتورة</span>
                    <span style="font-size: 14px; font-weight: 700; color: #f59e0b;" id="invoiceDiscountSidebar">-</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 12px 0;">
                    <span style="font-size: 14px; color: var(--primary); font-weight: 700;">الإجمالي النهائي</span>
                    <span style="font-size: 16px; font-weight: 800; color: var(--primary);" id="totalSidebar">-</span>
                </div>
            </div>
        </div>
        
        <div id="approvalSection" style="display: none; background: rgba(59, 130, 246, 0.05); padding: 16px; border-radius: 16px; border: 1px solid rgba(59, 130, 246, 0.1); margin-top: 20px;">
            <div style="font-size: 14px; font-weight: 700; color: #3b82f6; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                تم التوثيق
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px; padding: 8px 0; border-bottom: 1px solid var(--border-light);">
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">بواسطة</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-light);" id="keeperName">-</div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px; padding: 8px 0;">
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">بتاريخ</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-light);" id="confirmedAt">-</div>
            </div>
        </div>
        
        <div id="rejectionSection" style="display: none; background: rgba(239, 68, 68, 0.05); padding: 16px; border-radius: 16px; border: 1px solid rgba(239, 68, 68, 0.1); margin-top: 20px;">
            <div style="font-size: 14px; font-weight: 700; color: #ef4444; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                تم الرفض
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px; padding: 8px 0; border-bottom: 1px solid var(--border-light);">
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">بواسطة</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-light);" id="rejectedBy">-</div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px; padding: 8px 0; border-bottom: 1px solid var(--border-light);">
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">بتاريخ</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-light);" id="rejectedAt">-</div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px; padding: 8px 0;">
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">السبب</div>
                <div style="font-size: 13px; font-weight: 600; color: var(--text-light); line-height: 1.6;" id="rejectionReason">-</div>
            </div>
        </div>
    </div>

    <div class="invoice-content">
        <div class="info-card">
            <div class="info-grid">
                <div><div class="info-label">المتجر</div><div class="info-value" id="storeName">-</div></div>
                <div><div class="info-label">التاريخ</div><div class="info-value" id="createdAt">-</div></div>
                <div><div class="info-label">الحالة</div><div class="info-value"><span id="statusBadge" class="status-badge">-</span></div></div>
                <div><div class="info-label">الإجمالي</div><div class="info-value" id="totalAmount">-</div></div>
            </div>
        </div>


        <div class="info-card">
            <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 28px;">المنتجات</h2>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th style="width: 100px; text-align: center;">الكمية</th>
                        <th style="width: 100px; text-align: center;">تخفيض</th>
                        <th style="width: 120px; text-align: center;">السعر</th>
                        <th style="width: 120px; text-align: center;">الإجمالي</th>
                    </tr>
                </thead>
                <tbody id="productsBody">
                    <tr><td colspan="5" style="text-align: center; padding: 40px;">جاري التحميل...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="image-modal-overlay" id="imageModal" onclick="if(event.target === this) closeImageModal()">
    <div class="image-modal-header">
        <div class="image-modal-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            صورة التوثيق
        </div>
        <button class="image-modal-close" onclick="closeImageModal()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            إغلاق
        </button>
    </div>
    <div class="image-modal-content">
        <img id="documentImage" src="" alt="صورة التوثيق">
    </div>
</div>

<div class="modal-overlay" id="confirmModal">
    <div class="modal-dialog">
        <h3 class="modal-title" id="modalTitle">تأكيد</h3>
        <p class="modal-message" id="modalMessage">-</p>
        <div class="modal-actions">
            <button class="modal-btn modal-btn-secondary" onclick="closeModal()">إلغاء</button>
            <button class="modal-btn modal-btn-primary" onclick="confirmAction()">تأكيد</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    const invoiceId = '{{ $invoiceId }}';
    let modalCallback = null;

    function showModal(title, message) {
        return new Promise((resolve) => {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalMessage').textContent = message;
            modalCallback = resolve;
            document.getElementById('confirmModal').classList.add('active');
        });
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.remove('active');
        if (modalCallback) modalCallback(null);
    }

    function confirmAction() {
        document.getElementById('confirmModal').classList.remove('active');
        if (modalCallback) modalCallback(true);
    }

    async function fetchDetails() {
        try {
            const response = await fetch(`/api/marketer/sales/${invoiceId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (result.data) displayDetails(result.data.invoice, result.data.items);
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayDetails(invoice, items) {
        const statusMap = {
            'pending': { label: 'قيد الانتظار', class: 'status-pending' },
            'approved': { label: 'موثقة', class: 'status-approved' },
            'cancelled': { label: 'ملغية', class: 'status-cancelled' },
            'rejected': { label: 'مرفوضة', class: 'status-rejected' }
        };
        const status = statusMap[invoice.status] || { label: invoice.status, class: '' };
        
        document.getElementById('statusBadge').textContent = status.label;
        document.getElementById('statusBadge').className = `status-badge ${status.class}`;
        document.getElementById('invoiceNumber').textContent = `#${invoice.invoice_number}`;
        document.getElementById('storeName').textContent = invoice.store_name || '---';
        document.getElementById('createdAt').textContent = new Date(invoice.created_at).toLocaleDateString('en-US').replace(/\//g, '-');
        document.getElementById('totalAmount').textContent = parseFloat(invoice.total_amount).toFixed(2) + ' دينار';
        
        // Update sidebar details
        document.getElementById('subtotalSidebar').textContent = parseFloat(invoice.subtotal || 0).toFixed(2) + ' دينار';
        document.getElementById('productDiscountSidebar').textContent = parseFloat(invoice.product_discount || 0).toFixed(2) + ' دينار';
        document.getElementById('invoiceDiscountSidebar').textContent = parseFloat(invoice.invoice_discount_amount || 0).toFixed(2) + ' دينار';
        document.getElementById('totalSidebar').textContent = parseFloat(invoice.total_amount).toFixed(2) + ' دينار';

        if (invoice.status === 'approved' && invoice.keeper_name) {
            document.getElementById('approvalSection').style.display = 'block';
            document.getElementById('keeperName').textContent = invoice.keeper_name;
            document.getElementById('confirmedAt').textContent = invoice.confirmed_at ? new Date(invoice.confirmed_at).toLocaleDateString('en-US').replace(/\//g, '-') : '-';
        }
        
        if (invoice.status === 'rejected') {
            fetchRejectionDetails();
        }

        const tbody = document.getElementById('productsBody');
        if (items && items.length > 0) {
            tbody.innerHTML = items.map(item => {
                const totalQty = item.quantity + (item.free_quantity || 0);
                return `
                <tr>
                    <td>${item.product_name}</td>
                    <td style="text-align: center;"><span style="background: rgba(139, 92, 246, 0.1); color: var(--primary); padding: 6px 14px; border-radius: 99px; font-weight: 800;">${totalQty}</span></td>
                    <td style="text-align: center;">${item.free_quantity > 0 ? `<span style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 6px 14px; border-radius: 99px; font-weight: 800;">${item.free_quantity}</span>` : '-'}</td>
                    <td style="text-align: center;">${parseFloat(item.unit_price).toFixed(2)} دينار</td>
                    <td style="text-align: center; font-weight: 800;">${parseFloat(item.total_price).toFixed(2)} دينار</td>
                </tr>
            `}).join('');
        }

        renderActions(invoice.status, invoice.stamped_invoice_image);
    }

    function renderActions(status, stampedImage) {
        const container = document.getElementById('actionsContainer');
        let html = '';

        if (status === 'pending') {
            html += `<button class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;" onclick="printInvoice()"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>طباعة الفاتورة</button>`;
            html += `<button class="btn btn-danger" onclick="cancelInvoice()"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>إلغاء الفاتورة</button>`;
        }

        if (status === 'approved' && stampedImage) {
            html += `<button class="btn" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;" onclick="viewDocument(event)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>عرض التوثيق</button>`;
        }

        html += `<button class="btn btn-secondary" onclick="window.history.back()"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5"></path><polyline points="12 19 5 12 12 5"></polyline></svg>رجوع</button>`;
        container.innerHTML = html;
    }

    function printInvoice() {
        window.open(`/marketer/sales/${invoiceId}/print`, '_blank');
    }

    async function cancelInvoice() {
        const confirmed = await showModal('إلغاء الفاتورة', 'هل أنت متأكد من إلغاء هذه الفاتورة؟');
        if (!confirmed) return;

        try {
            const response = await fetch(`/api/marketer/sales/${invoiceId}/cancel`, {
                method: 'PUT',
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });

            if (response.ok) {
                await showModal('نجح', 'تم إلغاء الفاتورة بنجاح');
                window.location.reload();
            } else {
                await showModal('خطأ', 'فشل إلغاء الفاتورة');
            }
        } catch (error) {
            await showModal('خطأ', 'حدث خطأ');
        }
    }

    async function viewDocument(event) {
        if (event) event.preventDefault();
        try {
            const response = await fetch(`/api/marketer/sales/${invoiceId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (result.data && result.data.invoice.stamped_invoice_image) {
                document.getElementById('documentImage').src = result.data.invoice.stamped_invoice_image;
                document.getElementById('imageModal').classList.add('active');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.remove('active');
    }
    
    async function fetchRejectionDetails() {
        try {
            const response = await fetch(`/api/marketer/sales/${invoiceId}/rejection`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (result.data) {
                document.getElementById('rejectionSection').style.display = 'block';
                document.getElementById('rejectedBy').textContent = result.data.rejected_by_name || '---';
                document.getElementById('rejectedAt').textContent = result.data.rejected_at ? new Date(result.data.rejected_at).toLocaleDateString('en-US').replace(/\//g, '-') : '-';
                document.getElementById('rejectionReason').textContent = result.data.rejection_reason || '---';
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    fetchDetails();
</script>
@endpush
