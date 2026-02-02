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
    .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .btn-success:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
    .btn-danger { background: rgba(239, 68, 68, 0.08); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15); }
    .btn-danger:hover { background: #ef4444; color: white; }
    .btn-secondary { background: var(--bg-light); color: #64748b; border: 1px solid var(--border-light); }
    body.dark-mode .btn-secondary { background: #1e293b; color: #94a3b8; border-color: #334155; }
    
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); z-index: 9999; }
    .modal-overlay.active { display: flex; align-items: center; justify-content: center; }
    .modal-dialog { background: var(--card-light); border-radius: 20px; padding: 32px; max-width: 550px; width: 90%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); position: relative; }
    body.dark-mode .modal-dialog { background: var(--card-dark); }
    .modal-close { position: absolute; top: 16px; left: 16px; width: 32px; height: 32px; border: none; background: rgba(0, 0, 0, 0.05); border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; }
    .modal-close:hover { background: rgba(0, 0, 0, 0.1); }
    .modal-icon { width: 80px; height: 80px; margin: 0 auto 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(139, 92, 246, 0.1); }
    .modal-icon svg { color: #8b5cf6; width: 40px; height: 40px; }
    .upload-area { border: 2px dashed #d1d5db; border-radius: 12px; padding: 40px 20px; text-align: center; margin-bottom: 20px; background: #f9fafb; cursor: pointer; transition: all 0.3s ease; }
    .upload-area:hover { border-color: #8b5cf6; background: rgba(139, 92, 246, 0.02); }
    body.dark-mode .upload-area { background: rgba(255, 255, 255, 0.02); border-color: var(--border-dark); }
    .upload-text { font-size: 16px; font-weight: 600; color: var(--text-light); margin-top: 16px; }
    body.dark-mode .upload-text { color: var(--text-dark); }
    .upload-hint { font-size: 13px; color: #9ca3af; margin-top: 8px; }
    .warning-box { background: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.2); border-radius: 12px; padding: 16px; display: flex; gap: 12px; margin-bottom: 24px; font-size: 13px; color: #92400e; line-height: 1.6; }
    .warning-box svg { flex-shrink: 0; color: #f59e0b; }
    .modal-title { font-size: 22px; font-weight: 700; text-align: center; margin-bottom: 12px; color: var(--text-light); }
    body.dark-mode .modal-title { color: var(--text-dark); }
    .modal-message { text-align: center; color: #64748b; margin-bottom: 28px; line-height: 1.6; }
    .modal-input { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-light); background: var(--bg-light); color: var(--text-light); margin-bottom: 20px; font-family: 'Tajawal', sans-serif; font-size: 14px; }
    body.dark-mode .modal-input { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .modal-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
    .modal-actions { display: flex; gap: 12px; }
    .modal-btn { flex: 1; padding: 12px; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; font-family: 'Tajawal', sans-serif; font-size: 14px; transition: all 0.3s ease; }
    .modal-btn-primary { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; }
    .modal-btn-primary:hover { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); transform: translateY(-1px); }
    .modal-btn-secondary { background: var(--bg-light); color: #64748b; border: 1px solid var(--border-light); }
    body.dark-mode .modal-btn-secondary { background: var(--bg-dark); border-color: var(--border-dark); }
    .modal-btn-secondary:hover { background: #e2e8f0; }
    
    .image-modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.95); z-index: 10000; backdrop-filter: blur(4px); animation: fadeIn 0.3s ease; }
    .image-modal-overlay.active { display: flex; align-items: center; justify-content: center; flex-direction: column; }
    .image-modal-header { position: absolute; top: 20px; right: 20px; left: 20px; display: flex; justify-content: space-between; align-items: center; z-index: 10001; }
    .image-modal-title { color: white; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .image-modal-close { padding: 10px 20px; background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 14px; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; font-family: 'Tajawal', sans-serif; }
    .image-modal-close:hover { background: rgba(255, 255, 255, 0.2); transform: translateY(-1px); }
    .image-modal-content { max-width: 85%; max-height: 80vh; animation: slideUp 0.4s ease; }
    .image-modal-content img { max-width: 100%; max-height: 80vh; border-radius: 16px; box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6); object-fit: contain; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
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
        
        <div id="approvalSection" style="display: none; background: rgba(16, 185, 129, 0.05); padding: 16px; border-radius: 16px; border: 1px solid rgba(16, 185, 129, 0.1); margin-top: 20px;">
            <div style="font-size: 14px; font-weight: 700; color: #10b981; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                تمت الموافقة
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px; padding: 8px 0; border-bottom: 1px solid var(--border-light);">
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">بواسطة</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-light);" id="approvedBy">-</div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px; padding: 8px 0;">
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">بتاريخ</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-light);" id="approvedAt">-</div>
            </div>
        </div>
        
        <div id="documentSection" style="display: none; background: rgba(59, 130, 246, 0.05); padding: 16px; border-radius: 16px; border: 1px solid rgba(59, 130, 246, 0.1); margin-top: 20px;">
            <div style="font-size: 14px; font-weight: 700; color: #3b82f6; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                تم التوثيق
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px; padding: 8px 0; border-bottom: 1px solid var(--border-light);">
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">بواسطة</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-light);" id="documentedBy">-</div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 4px; padding: 8px 0;">
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">بتاريخ</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-light);" id="documentedAt">-</div>
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
                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">سبب الرفض</div>
                <div style="font-size: 14px; font-weight: 700; color: #ef4444; line-height: 1.6;" id="rejectionNotes">-</div>
            </div>
        </div>
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
        <button class="modal-close" onclick="closeModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <h3 class="modal-title" id="modalTitle">تأكيد</h3>
        <div id="modalContent"></div>
        <div class="modal-actions">
            <button class="modal-btn modal-btn-secondary" onclick="closeModal()">إلغاء</button>
            <button class="modal-btn modal-btn-primary" id="modalConfirmBtn" onclick="confirmAction()">تأكيد</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    const returnId = '{{ $returnId }}';
    let modalCallback = null;

    function showModal(title, message, needsInput = false, needsFile = false) {
        return new Promise((resolve) => {
            document.getElementById('modalTitle').textContent = title;
            const content = document.getElementById('modalContent');
            
            if (needsFile) {
                content.innerHTML = `
                    <div class="modal-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    </div>
                    <div class="upload-area" id="uploadArea">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        <div class="upload-text" id="uploadText">رفع صورة الفاتورة الموقعة</div>
                        <div class="upload-hint">PNG, JPG تصل إلى 10MB</div>
                        <input type="file" id="modalFileInput" accept="image/*" style="display:none;">
                    </div>
                    <div class="warning-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <div>يجب أن تكون الفاتورة موقعة من المسوق وتحتوي على ختم الاستلام. تحديث المخزون سيتم فوراً بعد الاعتماد.</div>
                    </div>
                `;
                document.querySelector('.upload-area').onclick = () => document.getElementById('modalFileInput').click();
                document.getElementById('modalFileInput').onchange = function(e) {
                    if (this.files && this.files[0]) {
                        document.getElementById('uploadText').innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" style="display:inline-block; vertical-align:middle; margin-left:6px;"><polyline points="20 6 9 17 4 12"></polyline></svg> تمت معالجة الصورة';
                        document.getElementById('uploadText').style.color = '#10b981';
                    }
                };
                document.getElementById('modalConfirmBtn').textContent = 'إتمام التوثيق';
            } else if (needsInput) {
                content.innerHTML = `
                    <p class="modal-message">${message}</p>
                    <textarea class="modal-input" id="modalInput" rows="3" placeholder="اكتب السبب..."></textarea>
                `;
                document.getElementById('modalConfirmBtn').textContent = 'تأكيد';
            } else {
                content.innerHTML = `<p class="modal-message">${message}</p>`;
                document.getElementById('modalConfirmBtn').textContent = 'تأكيد';
            }
            
            modalCallback = resolve;
            document.getElementById('confirmModal').classList.add('active');
        });
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.remove('active');
        if (modalCallback) modalCallback(null);
    }

    function confirmAction() {
        const input = document.getElementById('modalInput');
        const fileInput = document.getElementById('modalFileInput');
        let value = true;
        
        if (input) value = input.value;
        else if (fileInput && fileInput.files && fileInput.files[0]) value = fileInput.files[0];
        
        document.getElementById('confirmModal').classList.remove('active');
        if (modalCallback) modalCallback(value);
    }

    async function fetchDetails() {
        try {
            const response = await fetch(`/api/warehouse/returns/${returnId}`, {
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

        if (returnData.approver_name && returnData.approved_at) {
            document.getElementById('approvalSection').style.display = 'block';
            document.getElementById('approvedBy').textContent = returnData.approver_name;
            const appDate = new Date(returnData.approved_at);
            document.getElementById('approvedAt').textContent = appDate.toLocaleDateString('en-US').replace(/\//g, '-');
        }

        if (returnData.documenter_name && returnData.documented_at) {
            document.getElementById('documentSection').style.display = 'block';
            document.getElementById('documentedBy').textContent = returnData.documenter_name;
            const docDate = new Date(returnData.documented_at);
            document.getElementById('documentedAt').textContent = docDate.toLocaleDateString('en-US').replace(/\//g, '-');
        }

        if (returnData.status === 'rejected' && returnData.rejected_at) {
            document.getElementById('rejectionSection').style.display = 'block';
            document.getElementById('rejectedBy').textContent = returnData.rejecter_name || 'أمين المخزن';
            const rejDate = new Date(returnData.rejected_at);
            document.getElementById('rejectedAt').textContent = rejDate.toLocaleDateString('en-US').replace(/\//g, '-');
            document.getElementById('rejectionNotes').textContent = returnData.notes || 'لا يوجد سبب';
        }

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

        if (status === 'pending') {
            html += `
                <button class="btn btn-success" onclick="approveReturn()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    الموافقة
                </button>
                <button class="btn btn-danger" onclick="rejectReturn()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    رفض
                </button>
            `;
        }

        if (status === 'approved') {
            html += `
                <button class="btn btn-success" onclick="documentReturn()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
                    توثيق الاستلام
                </button>
                <button class="btn btn-danger" onclick="rejectReturn()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line></svg>
                    رفض
                </button>
            `;
        }

        if (status === 'documented') {
            html += `
                <button class="btn btn-success" onclick="viewDocumentation()" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    عرض التوثيق
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

    async function approveReturn() {
        const confirmed = await showModal('الموافقة', 'هل أنت متأكد من الموافقة على هذا الطلب؟');
        if (!confirmed) return;

        try {
            const response = await fetch(`/api/warehouse/returns/${returnId}/approve`, {
                method: 'PUT',
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });

            if (response.ok) {
                await showModal('نجح', 'تمت الموافقة بنجاح');
                window.location.reload();
            } else {
                await showModal('خطأ', 'فشلت الموافقة');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ');
        }
    }

    async function rejectReturn() {
        const notes = await showModal('رفض الطلب', 'يرجى إدخال سبب الرفض:', true);
        if (!notes) return;

        try {
            const response = await fetch(`/api/warehouse/returns/${returnId}/reject`, {
                method: 'PUT',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notes })
            });

            if (response.ok) {
                await showModal('نجح', 'تم الرفض بنجاح');
                window.location.reload();
            } else {
                await showModal('خطأ', 'فشل الرفض');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ');
        }
    }

    async function documentReturn() {
        const imageFile = await showModal('توثيق الاستلام', 'يرجى رفع صورة الفاتورة المختومة', false, true);
        if (!imageFile || imageFile === true) return;

        const formData = new FormData();
        formData.append('stamped_image', imageFile);

        try {
            const response = await fetch(`/api/warehouse/returns/${returnId}/document`, {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' },
                body: formData
            });

            if (response.ok) {
                await showModal('نجح', 'تم التوثيق بنجاح');
                window.location.reload();
            } else {
                await showModal('خطأ', 'فشل التوثيق');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ');
        }
    }

    async function viewDocumentation() {
        try {
            const response = await fetch(`/api/warehouse/returns/${returnId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            if (result.data && result.data.return.stamped_image) {
                document.getElementById('documentImage').src = result.data.return.stamped_image;
                document.getElementById('imageModal').classList.add('active');
            } else {
                await showModal('خطأ', 'لا توجد صورة توثيق متاحة');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ أثناء عرض التوثيق');
        }
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.remove('active');
    }

    fetchDetails();
</script>
@endpush
