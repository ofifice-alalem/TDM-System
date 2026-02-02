@extends('layouts.app')

@section('title', 'إيصال قبض جديد')

@push('styles')
<style>
    .page-header { margin-bottom: 32px; animation: fadeInDown 0.6s ease-out; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); margin-bottom: 8px; display: flex; align-items: center; gap: 12px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .page-subtitle { color: #64748b; font-size: 15px; }

    .form-card { 
        background: var(--card-light); 
        border-radius: 24px; 
        padding: 40px; 
        border: 1px solid var(--border-light); 
        margin-bottom: 24px; 
        box-shadow: var(--shadow-sm);
        animation: fadeInUp 0.6s ease-out;
    }
    body.dark-mode .form-card { background: var(--card-dark); border-color: var(--border-dark); }

    .form-section-title { 
        font-size: 20px; 
        font-weight: 800; 
        color: var(--text-light); 
        margin-bottom: 32px; 
        padding-bottom: 16px; 
        border-bottom: 2px solid rgba(139, 92, 246, 0.1); 
        display: flex; 
        align-items: center; 
        gap: 12px; 
    }
    body.dark-mode .form-section-title { color: var(--text-dark); border-color: rgba(139, 92, 246, 0.2); }

    .form-group { margin-bottom: 28px; }
    .form-group label { display: block; font-size: 14px; font-weight: 700; color: #64748b; margin-bottom: 10px; padding-right: 4px; }
    
    .input-premium { 
        width: 100%; 
        padding: 14px 20px; 
        border-radius: 14px; 
        border: 1px solid var(--border-light); 
        background: var(--bg-light); 
        color: var(--text-light); 
        font-size: 15px; 
        font-family: 'Tajawal', sans-serif; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    body.dark-mode .input-premium { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .input-premium:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1); transform: translateY(-1px); }

    .debt-info-banner { 
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%); 
        border: 1px solid rgba(16, 185, 129, 0.2); 
        border-radius: 18px; 
        padding: 24px; 
        margin-bottom: 32px; 
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: scaleIn 0.4s ease-out;
    }
    .debt-label-group { display: flex; flex-direction: column; gap: 4px; }
    .debt-label { font-size: 14px; color: #059669; font-weight: 700; }
    .debt-value { font-size: 32px; font-weight: 900; color: #10b981; }

    /* Payment Methods Grid */
    .payment-methods-grid { 
        display: grid; 
        grid-template-columns: repeat(3, 1fr); 
        gap: 16px; 
        margin-top: 12px;
    }
    .payment-method-card {
        background: var(--bg-light);
        border: 2px solid var(--border-light);
        border-radius: 18px;
        padding: 24px 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
    }
    body.dark-mode .payment-method-card { background: var(--bg-dark); border-color: var(--border-dark); }
    
    .payment-method-card:hover { 
        border-color: var(--primary); 
        background: rgba(139, 92, 246, 0.05); 
        transform: translateY(-4px);
    }
    
    .payment-method-card.active { 
        border-color: var(--primary); 
        background: rgba(139, 92, 246, 0.1); 
        box-shadow: 0 8px 24px rgba(139, 92, 246, 0.15);
    }

    .method-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    body.dark-mode .method-icon { background: var(--card-dark); }
    
    .payment-method-card.active .method-icon { background: var(--primary); color: white; }
    
    .method-name { font-weight: 700; font-size: 15px; color: #64748b; }
    .payment-method-card.active .method-name { color: var(--primary); }

    .form-actions { display: flex; gap: 20px; justify-content: flex-end; margin-top: 40px; }
    .btn { padding: 14px 40px; border-radius: 16px; font-weight: 800; font-size: 16px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); font-family: 'Tajawal', sans-serif; }
    
    .btn-primary { background: var(--primary); color: white; border: none; box-shadow: 0 8px 16px rgba(139, 92, 246, 0.2); }
    .btn-primary:hover { background: #7c3aed; transform: translateY(-2px); box-shadow: 0 12px 20px rgba(139, 92, 246, 0.3); }
    
    .btn-secondary { background: transparent; color: #64748b; border: 2px solid var(--border-light); }
    .btn-secondary:hover { background: rgba(100, 116, 139, 0.05); color: var(--text-light); border-color: #64748b; }
    body.dark-mode .btn-secondary:hover { color: var(--text-dark); }

    .alert { padding: 16px 24px; border-radius: 14px; margin-bottom: 24px; font-weight: 600; font-size: 15px; animation: slideDown 0.3s ease-out; }
    .alert-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .alert-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }

    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        إيصال قبض جديد
    </h1>
    <p class="page-subtitle">تسجيل عملية تحصيل مالي من المتاجر وتحديث الأرصدة</p>
</div>

<div id="alertContainer"></div>

<div class="form-card">
    <h2 class="form-section-title" style="justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
            معلومات التحصيل
        </div>
        <div style="display: flex; align-items: center; gap: 8px; background: rgba(139, 92, 246, 0.1); padding: 6px 16px; border-radius: 12px; border: 1px solid rgba(139, 92, 246, 0.2);">
            <span style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">نسبتك المقدرة</span>
            <span style="font-size: 16px; font-weight: 800; color: var(--primary);" id="commissionRateDisplay">0.00%</span>
        </div>
    </h2>
    
    <div class="form-group">
        <label>اختيار المتجر</label>
        <select id="storeSelect" class="input-premium" onchange="loadStoreDebt()">
            <option value="">اختر المتجر الذي قام بالتسديد</option>
        </select>
    </div>

    <div id="debtInfoContainer"></div>

    <div class="form-group">
        <label>المبلغ المستلم</label>
        <div style="position: relative;">
            <input type="number" id="amountInput" class="input-premium" placeholder="0.00" min="0.01" step="0.01" style="padding-left: 60px;" oninput="updateCalculations()">
            <span style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); font-weight: 800; color: #94a3b8;">دينار</span>
        </div>
        <div id="marketerShareContainer" style="margin-top: 12px; display: none; animation: fadeIn 0.3s ease;">
            <div style="background: rgba(139, 92, 246, 0.03); border: 1px dashed rgba(139, 92, 246, 0.3); border-radius: 12px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    <span style="font-size: 13px; font-weight: 700; color: #64748b;">حصتك المقدرة من هذا المبلغ:</span>
                </div>
                <span id="marketerShareDisplay" style="font-size: 16px; font-weight: 800; color: var(--primary);">0.00 دينار</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>طريقة الدفع</label>
        <input type="hidden" id="paymentMethodValue" value="">
        <div class="payment-methods-grid">
            <div class="payment-method-card" onclick="selectPaymentMethod('cash', this)">
                <div class="method-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path><path d="M15 12h7v4h-7z"></path></svg>
                </div>
                <div class="method-name">نقدي</div>
            </div>
            <div class="payment-method-card" onclick="selectPaymentMethod('transfer', this)">
                <div class="method-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M13 10v11M17 10v11M12 2L2 7h20L12 2z"></path></svg>
                </div>
                <div class="method-name">تحويل بنكي</div>
            </div>
            <div class="payment-method-card" onclick="selectPaymentMethod('certified_check', this)">
                <div class="method-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="m9 15 2 2 4-4"></path></svg>
                </div>
                <div class="method-name">شيك مصدق</div>
            </div>
        </div>
    </div>

    <div class="form-group" style="margin-bottom: 0;">
        <label>ملاحظات إضافية</label>
        <textarea id="notesInput" class="input-premium" placeholder="اكتب أي تفاصيل إضافية متعلقة بهذا الإيصال..."></textarea>
    </div>
</div>

<div class="form-actions">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='/marketer/payments'">إلغاء العملية</button>
    <button type="button" class="btn btn-primary" id="submitBtn" onclick="submitPayment()">إصدار الإيصال الآن</button>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let currentDebt = 0;
    const commissionRate = parseFloat('{{ $commission_rate }}') || 0;

    function selectPaymentMethod(method, element) {
        document.querySelectorAll('.payment-method-card').forEach(card => card.classList.remove('active'));
        element.classList.add('active');
        document.getElementById('paymentMethodValue').value = method;
    }

    function updateCalculations() {
        const amountInput = document.getElementById('amountInput');
        const amount = parseFloat(amountInput.value) || 0;
        
        // Validate against debt
        if (amount > currentDebt) {
            amountInput.value = currentDebt;
        }

        const validAmount = parseFloat(amountInput.value) || 0;

        // Calculate share
        const share = (validAmount * commissionRate) / 100;
        const shareDisplay = document.getElementById('marketerShareDisplay');
        const shareContainer = document.getElementById('marketerShareContainer');

        if (validAmount > 0) {
            shareContainer.style.display = 'block';
            shareDisplay.textContent = share.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' دينار';
        } else {
            shareContainer.style.display = 'none';
        }
    }

    // Initialize display
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('commissionRateDisplay').textContent = commissionRate.toFixed(2) + '%';
    });

    async function fetchData() {
        try {
            const response = await fetch('/api/stores', { 
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' } 
            });
            
            const stores = await response.json();
            const storeSelect = document.getElementById('storeSelect');
            (stores.data || stores || []).forEach(s => {
                const option = document.createElement('option');
                option.value = s.id;
                option.textContent = s.name;
                storeSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error:', error);
            showAlert('فشل تحميل قائمة المتاجر', 'error');
        }
    }

    async function loadStoreDebt() {
        const storeId = document.getElementById('storeSelect').value;
        const container = document.getElementById('debtInfoContainer');
        const amountInput = document.getElementById('amountInput');
        
        if (!storeId) {
            container.innerHTML = '';
            currentDebt = 0;
            amountInput.removeAttribute('max');
            return;
        }

        try {
            const response = await fetch(`/api/stores/${storeId}/debt`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            currentDebt = parseFloat(result.debt || 0);
            
            amountInput.setAttribute('max', currentDebt);
            
            container.innerHTML = `
                <div class="debt-info-banner">
                    <div class="debt-label-group">
                        <span class="debt-label">رصيد الدين المستحق على المتجر</span>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                            <span class="debt-value">${currentDebt.toLocaleString('en-US', {minimumFractionDigits: 2})} دينار</span>
                        </div>
                    </div>
                </div>
            `;
        } catch (error) {
            console.error('Error:', error);
            showAlert('فشل تحميل معلومات مديونية المتجر', 'error');
        }
    }

    async function submitPayment() {
        const storeId = document.getElementById('storeSelect').value;
        const amount = parseFloat(document.getElementById('amountInput').value);
        const paymentMethod = document.getElementById('paymentMethodValue').value;
        const notes = document.getElementById('notesInput').value;
        const submitBtn = document.getElementById('submitBtn');

        if (!storeId) {
            showAlert('يرجى اختيار المتجر أولاً', 'error');
            return;
        }

        if (isNaN(amount) || amount <= 0) {
            showAlert('يرجى إدخال مبلغ دفع صحيح', 'error');
            return;
        }

        if (amount > currentDebt) {
            showAlert(`المبلغ المدخل أكبر من المديونية الحالية (${currentDebt.toLocaleString()} دينار)`, 'error');
            return;
        }

        if (!paymentMethod) {
            showAlert('يرجى اختيار طريقة الدفع المتبعة', 'error');
            return;
        }

        try {
            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري المعالجة...';

            const response = await fetch('/api/marketer/payments', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ store_id: storeId, amount, payment_method: paymentMethod, notes })
            });

            const result = await response.json();
            
            if (response.ok) {
                showAlert('تم تسجيل إيصال القبض بنجاح وتحديث الحساب', 'success');
                setTimeout(() => window.location.href = '/marketer/payments', 1500);
            } else {
                showAlert(result.message || 'حدث خطأ غير متوقع أثناء الحفظ', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'إصدار الإيصال الآن';
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('فشل الاتصال بالخادم، يرجى المحاولة لاحقاً', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'إصدار الإيصال الآن';
        }
    }

    function showAlert(message, type) {
        const container = document.getElementById('alertContainer');
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        container.innerHTML = '';
        container.appendChild(alertDiv);
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => alertDiv.remove(), 5000);
    }

    fetchData();
</script>
@endpush
