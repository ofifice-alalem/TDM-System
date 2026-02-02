@extends('layouts.app')

@section('title', 'تفاصيل الإيصال | ' . $paymentId)

@push('styles')
<style>
    :root { --card-radius: 24px; --accent-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); --glass-bg: rgba(255, 255, 255, 0.8); --glass-border: rgba(255, 255, 255, 0.4); }
    body.dark-mode { --glass-bg: rgba(30, 41, 59, 0.8); --glass-border: rgba(255, 255, 255, 0.1); }
    .page-header { margin-bottom: 40px; animation: fadeInDown 0.6s ease-out; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; letter-spacing: -0.5px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .page-title svg { color: var(--primary); filter: drop-shadow(0 0 8px rgba(139, 92, 246, 0.3)); }
    .page-layout { display: flex; flex-direction:row-reverse; gap: 24px; align-items: start; max-width: 1400px; margin: 0 auto; }
    .actions-sidebar { background: var(--glass-bg); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-radius: var(--card-radius); padding: 32px; border: 1px solid var(--glass-border); box-shadow: var(--shadow-lg); position: sticky; top: 100px; animation: fadeInLeft 0.6s ease-out; width: 280px; flex-shrink: 0; }
    .sidebar-section-title { font-size: 15px; font-weight: 700; color: var(--text-light); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; padding-bottom: 12px; border-bottom: 2px solid var(--border-light); }
    body.dark-mode .sidebar-section-title { color: var(--text-dark); border-color: var(--border-dark); }
    .actions-container { display: flex; flex-direction: column; gap: 16px; }
    .payment-content { display: flex; flex-direction: column; gap: 32px; animation: fadeInRight 0.6s ease-out; flex: 1; }
    .info-card { background: var(--card-light); border-radius: var(--card-radius); padding: 40px; border: 1px solid var(--border-light); box-shadow: var(--shadow-md); position: relative; overflow: hidden; }
    body.dark-mode .info-card { background: var(--card-dark); border-color: var(--border-dark); }
    .info-card::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: var(--accent-gradient); }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }
    .small-info-item { display: flex; flex-direction: column; gap: 4px; padding: 8px 0; border-bottom: 1px solid var(--border-light); }
    body.dark-mode .small-info-item { border-bottom-color: var(--border-dark); }
    .small-info-item:last-child { border-bottom: none; }
    .sidebar-info-card { background: rgba(16, 185, 129, 0.05); padding: 16px; border-radius: 16px; border: 1px solid rgba(16, 185, 129, 0.1); margin-top: 20px; animation: fadeInDown 0.4s ease-out; }
    body.dark-mode .sidebar-info-card { background: rgba(16, 185, 129, 0.1); }
    .sidebar-info-title { font-size: 14px; font-weight: 700; color: var(--success); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
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
    .amount-display { background: rgba(16, 185, 129, 0.1); border: 2px solid rgba(16, 185, 129, 0.2); border-radius: 16px; padding: 24px; text-align: center; margin-top: 20px; }
    .amount-label { font-size: 13px; color: #10b981; font-weight: 600; margin-bottom: 8px; }
    .amount-value { font-size: 32px; font-weight: 800; color: #10b981; }
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
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
        <span>بيانات الإيصال <span id="paymentNumber" style="color: var(--primary); opacity: 0.8;">-</span></span>
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

        <div class="amount-display">
            <div class="amount-label">المبلغ المسدد</div>
            <div class="amount-value" id="amountValue">0 ر.س</div>
        </div>

        <div class="sidebar-info-card" id="approvalSection" style="display: none;">
            <div class="sidebar-info-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                تم التوثيق
            </div>
            <div class="small-info-item">
                <div class="info-label">بواسطة</div>
                <div class="info-value" id="approvedBy">-</div>
            </div>
            <div class="small-info-item">
                <div class="info-label">بتاريخ</div>
                <div class="info-value" id="confirmedAt">-</div>
            </div>
        </div>

        <div class="sidebar-info-card" id="rejectionSection" style="display: none; background: rgba(239, 68, 68, 0.05); border-color: rgba(239, 68, 68, 0.1);">
            <div class="sidebar-info-title" style="color: #ef4444;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                تم الرفض
            </div>
            <div class="small-info-item">
                <div class="info-label">بواسطة</div>
                <div class="info-value" id="rejectedBy">-</div>
            </div>
            <div class="small-info-item">
                <div class="info-label">سبب الرفض</div>
                <div class="info-value" id="rejectionNotes" style="font-size: 14px; line-height: 1.6; color: #ef4444;">-</div>
            </div>
        </div>

        <div class="sidebar-info-card" id="commissionSection" style="display: none; background: rgba(139, 92, 246, 0.05); border-color: rgba(139, 92, 246, 0.1);">
            <div class="sidebar-info-title" style="color: var(--primary);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                العمولة
            </div>
            <div class="small-info-item">
                <div class="info-label">نسبة العمولة</div>
                <div class="info-value" id="commissionRate">-</div>
            </div>
            <div class="small-info-item">
                <div class="info-label">مبلغ العمولة</div>
                <div class="info-value" id="commissionAmount" style="color: var(--primary);">-</div>
            </div>
        </div>

        <div class="sidebar-info-card" id="rejectionSection" style="display: none; background: rgba(239, 68, 68, 0.05); border-color: rgba(239, 68, 68, 0.1);">
            <div class="sidebar-info-title" style="color: var(--danger);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                تم الرفض
            </div>
            <div class="small-info-item">
                <div class="info-label">سبب الرفض</div>
                <div class="info-value" id="rejectionNotes" style="font-size: 14px; line-height: 1.6; color: #ef4444;">-</div>
            </div>
        </div>
    </div>

    <div class="payment-content">
        <div class="info-card">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        المتجر
                    </div>
                    <div class="info-value" id="storeName">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        أمين المخزن
                    </div>
                    <div class="info-value" id="keeperName">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        تاريخ الإنشاء
                    </div>
                    <div class="info-value" id="createdAt">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                        طريقة الدفع
                    </div>
                    <div class="info-value" id="paymentMethod">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        حالة الإيصال
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
    const paymentId = '{{ $paymentId }}';

    async function fetchPaymentDetails() {
        try {
            const response = await fetch(`/api/marketer/payments/${paymentId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            if (result.data) {
                displayDetails(result.data.payment, result.data.commission);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayDetails(payment, commission) {
        const statusMap = {
            'pending': { label: 'قيد الانتظار', class: 'status-pending' },
            'approved': { label: 'موثق', class: 'status-approved' },
            'rejected': { label: 'مرفوض', class: 'status-rejected' },
            'cancelled': { label: 'ملغي', class: 'status-cancelled' }
        };
        
        const paymentMethodMap = {
            'cash': 'نقدي',
            'transfer': 'تحويل بنكي',
            'certified_check': 'شيك مصدق'
        };
        
        const status = statusMap[payment.status] || { label: payment.status, class: '' };
        const createdAtDate = new Date(payment.created_at);
        const formattedDate = createdAtDate.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
        const formattedTime = createdAtDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        
        document.getElementById('statusBadge').textContent = status.label;
        document.getElementById('statusBadge').className = `status-badge ${status.class}`;
        document.getElementById('paymentNumber').textContent = `#${payment.payment_number}`;
        document.getElementById('storeName').textContent = payment.store_name || '---';
        document.getElementById('keeperName').textContent = payment.keeper_name || '---';
        document.getElementById('createdAt').textContent = `${formattedDate} | ${formattedTime}`;
        document.getElementById('paymentMethod').textContent = paymentMethodMap[payment.payment_method] || payment.payment_method;
        document.getElementById('amountValue').textContent = `${parseFloat(payment.amount).toLocaleString()} دينار`;

        if (payment.status === 'approved' && payment.confirmed_at) {
            document.getElementById('approvalSection').style.display = 'block';
            document.getElementById('approvedBy').textContent = payment.keeper_name || 'أمين المخزن';
            const confDate = new Date(payment.confirmed_at);
            document.getElementById('confirmedAt').textContent = confDate.toLocaleDateString('en-US').replace(/\//g, '-');
        }

        if (payment.status === 'rejected' && payment.notes) {
            document.getElementById('rejectionSection').style.display = 'block';
            document.getElementById('rejectedBy').textContent = payment.keeper_name || 'أمين المخزن';
            document.getElementById('rejectionNotes').textContent = payment.notes;
        }

        if (commission) {
            document.getElementById('commissionSection').style.display = 'block';
            document.getElementById('commissionRate').textContent = `${parseFloat(commission.commission_rate)}%`;
            document.getElementById('commissionAmount').textContent = `${parseFloat(commission.commission_amount).toLocaleString()} دينار`;
        }

        if (payment.status === 'rejected' && payment.notes) {
            document.getElementById('rejectionSection').style.display = 'block';
            document.getElementById('rejectionNotes').textContent = payment.notes;
        }

        renderActions(payment.status);
    }

    function renderActions(status) {
        const container = document.getElementById('actionsContainer');
        let html = '';

        if (status === 'pending') {
            html += `
                <button class="btn btn-danger" onclick="cancelPayment()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    إلغاء الإيصال
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

    async function cancelPayment() {
        const modal = document.createElement('div');
        modal.id = 'cancelModal';
        modal.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;z-index:9999;';
        modal.innerHTML = `
            <div style="background:var(--card-light);padding:32px;border-radius:16px;max-width:450px;width:90%;">
                <h3 style="margin-bottom:16px;color:var(--text-light);font-size:20px;">تأكيد الإلغاء</h3>
                <p style="margin-bottom:24px;color:#64748b;font-size:15px;">هل أنت متأكد من إلغاء هذا الإيصال؟</p>
                <div style="display:flex;gap:12px;">
                    <button onclick="confirmCancel()" style="flex:1;padding:12px;background:#ef4444;color:white;border:none;border-radius:8px;font-weight:700;cursor:pointer;">نعم، إلغاء</button>
                    <button onclick="closeCancelModal()" style="flex:1;padding:12px;background:#e2e8f0;color:#64748b;border:none;border-radius:8px;font-weight:700;cursor:pointer;">لا، رجوع</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    function closeCancelModal() {
        const modal = document.getElementById('cancelModal');
        if (modal) modal.remove();
    }

    async function confirmCancel() {
        closeCancelModal();
        try {
            const response = await fetch(`/api/marketer/payments/${paymentId}/cancel`, {
                method: 'PUT',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    fetchPaymentDetails();
</script>
@endpush
