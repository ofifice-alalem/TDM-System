@extends('layouts.app')

@section('title', 'طلب سحب جديد')

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-title { font-size: 28px; font-weight: 800; color: var(--text-light); margin-bottom: 8px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .page-subtitle { color: #64748b; font-size: 14px; }
    .form-card { background: var(--card-light); border-radius: 16px; padding: 32px; border: 1px solid var(--border-light); margin-bottom: 24px; }
    body.dark-mode .form-card { background: var(--card-dark); border-color: var(--border-dark); }
    .balance-display { background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border-radius: 12px; padding: 20px; color: white; margin-bottom: 24px; text-align: center; }
    .balance-label { font-size: 14px; opacity: 0.9; margin-bottom: 8px; }
    .balance-amount { font-size: 32px; font-weight: 800; }
    .form-group { margin-bottom: 24px; }
    .form-group label { display: block; font-size: 14px; font-weight: 700; color: var(--text-light); margin-bottom: 8px; }
    body.dark-mode .form-group label { color: var(--text-dark); }
    .form-group input { width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--border-light); background: var(--bg-light); color: var(--text-light); font-size: 16px; font-family: 'Tajawal', sans-serif; transition: all 0.3s ease; }
    body.dark-mode .form-group input { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .form-group input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
    .form-actions { display: flex; gap: 16px; justify-content: flex-end; margin-top: 32px; }
    .btn { padding: 12px 32px; border: none; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s ease; font-family: 'Tajawal', sans-serif; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #7c3aed; transform: translateY(-2px); }
    .btn-secondary { background: var(--card-light); color: var(--text-light); border: 1px solid var(--border-light); }
    body.dark-mode .btn-secondary { background: var(--card-dark); color: var(--text-dark); border-color: var(--border-dark); }
    .btn-secondary:hover { background: rgba(139, 92, 246, 0.1); }
    .alert { padding: 16px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; }
    .alert-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .alert-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">طلب سحب جديد</h1>
    <p class="page-subtitle">قم بتحديد المبلغ المطلوب سحبه</p>
</div>

<div id="alertContainer"></div>

<div class="form-card">
    <div class="balance-display">
        <div class="balance-label">الرصيد المتاح للسحب</div>
        <div class="balance-amount" id="balanceAmount">جاري التحميل...</div>
    </div>

    <div class="form-group">
        <label>المبلغ المطلوب سحبه (دينار)</label>
        <input type="number" id="requestedAmount" placeholder="أدخل المبلغ" min="0.01" step="0.01" oninput="validateAmount()">
    </div>
</div>

<div class="form-actions">
    <button type="button" class="btn btn-secondary" onclick="window.location.href='/marketer/withdrawals'">إلغاء</button>
    <button type="button" class="btn btn-primary" onclick="submitRequest()">إرسال الطلب</button>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let availableBalance = 0;

    async function fetchBalance() {
        try {
            const response = await fetch('/api/marketer/withdrawals/balance', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            availableBalance = parseFloat(result.data.available_balance);
            document.getElementById('balanceAmount').textContent = availableBalance.toFixed(2) + ' دينار';
            document.getElementById('requestedAmount').setAttribute('max', availableBalance);
        } catch (error) {
            showAlert('فشل تحميل الرصيد', 'error');
        }
    }

    function validateAmount() {
        const input = document.getElementById('requestedAmount');
        const value = parseFloat(input.value);
        if (value > availableBalance) {
            input.value = availableBalance;
        }
    }

    async function submitRequest() {
        const amount = parseFloat(document.getElementById('requestedAmount').value);

        if (!amount || amount <= 0) {
            showAlert('يرجى إدخال مبلغ صحيح', 'error');
            return;
        }

        if (amount > availableBalance) {
            showAlert(`المبلغ المطلوب أكبر من الرصيد المتاح (${availableBalance.toFixed(2)} دينار)`, 'error');
            return;
        }

        try {
            const response = await fetch('/api/marketer/withdrawals', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ requested_amount: amount })
            });

            const result = await response.json();
            
            if (response.ok) {
                showAlert('تم إنشاء طلب السحب بنجاح', 'success');
                setTimeout(() => window.location.href = '/marketer/withdrawals', 1500);
            } else {
                showAlert(result.message || 'حدث خطأ أثناء إنشاء الطلب', 'error');
            }
        } catch (error) {
            showAlert('حدث خطأ أثناء إرسال الطلب', 'error');
        }
    }

    function showAlert(message, type) {
        const container = document.getElementById('alertContainer');
        container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => container.innerHTML = '', 5000);
    }

    fetchBalance();
</script>
@endpush
