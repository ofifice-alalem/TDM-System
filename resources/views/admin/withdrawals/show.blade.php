@extends('layouts.app')

@section('title', 'تفاصيل طلب السحب | ' . $id)

@push('styles')
<style>
    :root { --card-radius: 24px; --accent-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); --glass-bg: rgba(255, 255, 255, 0.8); --glass-border: rgba(255, 255, 255, 0.4); }
    body.dark-mode { --glass-bg: rgba(30, 41, 59, 0.8); --glass-border: rgba(255, 255, 255, 0.1); }
    .page-header { margin-bottom: 40px; animation: fadeInDown 0.6s ease-out; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; letter-spacing: -0.5px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .page-layout { display: flex; flex-direction: row-reverse; gap: 24px; align-items: start; max-width: 1400px; margin: 0 auto; }
    .actions-sidebar { background: var(--glass-bg); backdrop-filter: blur(12px); border-radius: var(--card-radius); padding: 32px; border: 1px solid var(--glass-border); box-shadow: var(--shadow-lg); position: sticky; top: 100px; animation: fadeInLeft 0.6s ease-out; width: 320px; flex-shrink: 0; }
    .sidebar-section-title { font-size: 15px; font-weight: 700; color: var(--text-light); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; padding-bottom: 12px; border-bottom: 2px solid var(--border-light); }
    body.dark-mode .sidebar-section-title { color: var(--text-dark); border-color: var(--border-dark); }
    .actions-container { display: flex; flex-direction: column; gap: 16px; }
    .request-content { display: flex; flex-direction: column; gap: 32px; animation: fadeInRight 0.6s ease-out; flex: 1; }
    .info-card { background: var(--card-light); border-radius: var(--card-radius); padding: 40px; border: 1px solid var(--border-light); box-shadow: var(--shadow-md); position: relative; overflow: hidden; }
    body.dark-mode .info-card { background: var(--card-dark); border-color: var(--border-dark); }
    .info-card::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: var(--accent-gradient); }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }
    .small-info-item { display: flex; flex-direction: column; gap: 4px; padding: 8px 0; border-bottom: 1px solid var(--border-light); }
    body.dark-mode .small-info-item { border-bottom-color: var(--border-dark); }
    .small-info-item:last-child { border-bottom: none; }
    .balance-card { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; padding: 20px; color: white; margin-top: 20px; }
    .balance-label { font-size: 13px; opacity: 0.9; margin-bottom: 8px; }
    .balance-amount { font-size: 28px; font-weight: 800; }
    .info-label { font-size: 13px; color: #94a3b8; font-weight: 600; display: flex; align-items: center; gap: 6px; }
    .info-value { font-size: 16px; font-weight: 700; color: var(--text-light); }
    body.dark-mode .info-value { color: var(--text-dark); }
    .status-badge { padding: 8px 16px; border-radius: 10px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .status-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.2); }
    .btn { width: 100%; padding: 14px 20px; border-radius: 14px; font-weight: 700; font-size: 14px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: none; cursor: pointer; }
    .btn:active { transform: scale(0.98); }
    .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
    .btn-success:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); transform: translateY(-2px); }
    .btn-danger { background: rgba(239, 68, 68, 0.08); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15); }
    .btn-danger:hover { background: #ef4444; color: white; transform: translateY(-2px); }
    .btn-secondary { background: var(--bg-light); color: #64748b; border: 1px solid var(--border-light); }
    body.dark-mode .btn-secondary { background: #1e293b; color: #94a3b8; border-color: #334155; }
    .btn-secondary:hover { background: #e2e8f0; color: #334155; }
    .amount-display { font-size: 32px; font-weight: 800; color: var(--primary); }
    .form-group { margin-top: 16px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--text-light); margin-bottom: 8px; }
    body.dark-mode .form-group label { color: var(--text-dark); }
    .form-group input, .form-group textarea { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid var(--border-light); background: var(--bg-light); color: var(--text-light); font-family: 'Tajawal', sans-serif; }
    body.dark-mode .form-group input, body.dark-mode .form-group textarea { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .form-group textarea { resize: vertical; min-height: 80px; }
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes fadeInRight { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes pulse { 0% { opacity: 0.6; } 50% { opacity: 0.3; } 100% { opacity: 0.6; } }
    @media (max-width: 1200px) { .page-layout { flex-direction: column; } .actions-sidebar { position: static; width: 100%; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        <span>تفاصيل طلب السحب <span id="withdrawalId" style="color: var(--primary); opacity: 0.8;">#{{ $id }}</span></span>
    </h1>
</div>

<div class="page-layout">
    <div class="actions-sidebar">
        <h3 class="sidebar-section-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20v-6M9 17.63l3-2.63 3 2.63M12 4v6m3-2.63l-3 2.63-3-2.63"></path></svg>
            الإجراءات المتوفرة
        </h3>
        <div class="actions-container" id="actionsContainer">
            <div style="height: 50px; background: rgba(0,0,0,0.05); border-radius: 12px; animation: pulse 1.5s infinite;"></div>
        </div>

        <div class="balance-card" id="balanceCard" style="display: none;">
            <div class="balance-label">الرصيد المتاح للمسوق</div>
            <div class="balance-amount" id="availableBalance">-</div>
        </div>

        <div class="balance-card" id="rejectionCard" style="display: none; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <div class="balance-label">تم الرفض بواسطة</div>
            <div class="balance-amount" id="rejectedBy" style="font-size: 20px; margin-bottom: 12px;">-</div>
            <div class="balance-label">سبب الرفض</div>
            <div style="font-size: 14px; line-height: 1.6; margin-top: 8px;" id="rejectionReason">-</div>
        </div>

        <div class="balance-card" id="approvalCard" style="display: none; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="balance-label">تمت الموافقة بواسطة</div>
            <div class="balance-amount" id="approvedBy" style="font-size: 20px;">-</div>
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
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        المبلغ المطلوب
                    </div>
                    <div class="info-value"><span class="amount-display" id="requestedAmount">-</span></div>
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    const withdrawalId = '{{ $id }}';
    let currentWithdrawal = null;

    async function fetchDetails() {
        try {
            const response = await fetch(`/api/admin/withdrawals/${withdrawalId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            if (result.data) {
                currentWithdrawal = result.data.withdrawal;
                displayDetails(result.data.withdrawal, result.data.available_balance);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayDetails(withdrawal, availableBalance) {
        const statusMap = {
            'pending': { label: 'قيد الانتظار', class: 'status-pending' },
            'approved': { label: 'معتمد', class: 'status-approved' },
            'rejected': { label: 'مرفوض', class: 'status-rejected' },
            'cancelled': { label: 'ملغي', class: 'status-cancelled' }
        };
        
        const status = statusMap[withdrawal.status] || { label: withdrawal.status, class: '' };
        const createdAtDate = new Date(withdrawal.created_at);
        const formattedDate = createdAtDate.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
        const formattedTime = createdAtDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        
        document.getElementById('statusBadge').textContent = status.label;
        document.getElementById('statusBadge').className = `status-badge ${status.class}`;
        document.getElementById('marketerName').textContent = withdrawal.marketer_name || 'غير محدد';
        document.getElementById('requestedAmount').textContent = parseFloat(withdrawal.requested_amount).toFixed(2) + ' دينار';
        document.getElementById('createdAt').textContent = `${formattedDate} | ${formattedTime}`;

        if (withdrawal.status === 'pending') {
            document.getElementById('balanceCard').style.display = 'block';
            document.getElementById('availableBalance').textContent = parseFloat(availableBalance).toFixed(2) + ' دينار';
        }

        if (withdrawal.status === 'rejected') {
            document.getElementById('rejectionCard').style.display = 'block';
            document.getElementById('rejectedBy').textContent = withdrawal.rejected_by_name || 'غير محدد';
            document.getElementById('rejectionReason').textContent = withdrawal.notes || 'لا يوجد سبب';
        }

        if (withdrawal.status === 'approved') {
            document.getElementById('approvalCard').style.display = 'block';
            document.getElementById('approvedBy').textContent = withdrawal.approved_by_name || 'غير محدد';
        }

        renderActions(withdrawal.status, withdrawal.signed_receipt_image);
    }

    function renderActions(status, receiptImage) {
        const container = document.getElementById('actionsContainer');
        let html = '';

        if (status === 'pending') {
            html += `
                <button class="btn btn-success" onclick="showApproveForm()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    الموافقة على الطلب
                </button>
                <button class="btn btn-danger" onclick="showRejectForm()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    رفض الطلب
                </button>
            `;
        }

        if (status === 'approved' && receiptImage) {
            html += `
                <button class="btn btn-success" onclick="viewReceipt('${receiptImage}')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    عرض إيصال الاستلام
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

    function showApproveForm() {
        const html = `
            <div class="form-group">
                <label>صورة إيصال الاستلام الموقع *</label>
                <input type="file" id="receiptImage" accept="image/*" required>
            </div>
            <button class="btn btn-success" onclick="approveRequest()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                تأكيد الموافقة
            </button>
            <button class="btn btn-secondary" onclick="fetchDetails()">إلغاء</button>
        `;
        document.getElementById('actionsContainer').innerHTML = html;
    }

    function showRejectForm() {
        const html = `
            <div class="form-group">
                <label>سبب الرفض *</label>
                <textarea id="rejectNotes" placeholder="أدخل سبب الرفض..." required></textarea>
            </div>
            <button class="btn btn-danger" onclick="rejectRequest()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                تأكيد الرفض
            </button>
            <button class="btn btn-secondary" onclick="fetchDetails()">إلغاء</button>
        `;
        document.getElementById('actionsContainer').innerHTML = html;
    }

    async function approveRequest() {
        const fileInput = document.getElementById('receiptImage');
        if (!fileInput.files[0]) {
            alert('يرجى رفع صورة إيصال الاستلام');
            return;
        }

        const formData = new FormData();
        formData.append('signed_receipt_image', fileInput.files[0]);

        try {
            const response = await fetch(`/api/admin/withdrawals/${withdrawalId}/approve`, {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' },
                body: formData
            });

            const result = await response.json();
            if (response.ok) {
                showSuccessModal('تمت الموافقة على الطلب بنجاح');
            } else {
                alert(result.message || 'فشلت الموافقة على الطلب');
            }
        } catch (error) {
            alert('حدث خطأ أثناء الموافقة على الطلب');
        }
    }

    async function rejectRequest() {
        const notes = document.getElementById('rejectNotes').value.trim();
        if (!notes) {
            alert('يرجى إدخال سبب الرفض');
            return;
        }

        try {
            const response = await fetch(`/api/admin/withdrawals/${withdrawalId}/reject`, {
                method: 'POST',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notes })
            });

            const result = await response.json();
            if (response.ok) {
                showSuccessModal('تم رفض الطلب بنجاح');
            } else {
                alert(result.message || 'فشل رفض الطلب');
            }
        } catch (error) {
            alert('حدث خطأ أثناء رفض الطلب');
        }
    }

    function showSuccessModal(message) {
        const modal = document.createElement('div');
        modal.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 10000;';
        modal.innerHTML = `
            <div style="background: var(--card-light); border-radius: 20px; padding: 40px; max-width: 400px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" style="margin: 0 auto 20px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <h3 style="font-size: 24px; font-weight: 800; color: var(--text-light); margin-bottom: 12px;">${message}</h3>
                <button onclick="window.location.reload()" style="margin-top: 24px; padding: 12px 32px; background: var(--primary); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">حسناً</button>
            </div>
        `;
        document.body.appendChild(modal);
    }

    function viewReceipt(imageUrl) {
        const modal = document.createElement('div');
        modal.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); display: flex; align-items: center; justify-content: center; z-index: 10000; padding: 20px;';
        modal.innerHTML = `
            <div style="position: relative; max-width: 90%; max-height: 90vh;">
                <button onclick="this.parentElement.parentElement.remove()" style="position: absolute; top: -40px; right: 0; background: white; border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer; font-size: 24px; display: flex; align-items: center; justify-content: center;">&times;</button>
                <img src="${imageUrl}" style="max-width: 100%; max-height: 85vh; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);" alt="إيصال الاستلام">
            </div>
        `;
        modal.onclick = (e) => { if (e.target === modal) modal.remove(); };
        document.body.appendChild(modal);
    }

    fetchDetails();
</script>
@endpush
