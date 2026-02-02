@extends('layouts.app')

@section('title', 'إدارة المسوقين')

@push('styles')
<style>
    .page-header-premium {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        animation: fadeInDown 0.6s ease-out;
    }

    .header-title-group {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .header-icon {
        width: 56px;
        height: 56px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: var(--primary);
    }

    .header-text h1 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 4px;
        color: var(--text-light);
    }

    body.dark-mode .header-text h1 { color: var(--text-dark); }

    .header-text p {
        font-size: 14px;
        color: #64748b;
    }

    .marketers-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .marketer-card {
        background: var(--card-light);
        border-radius: 24px;
        padding: 28px;
        border: 1px solid var(--border-light);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        display: flex;
        flex-direction: column;
        animation: fadeInUp 0.6s ease-out;
    }

    body.dark-mode .marketer-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .marketer-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }

    .marketer-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .badge-commission {
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary);
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .user-icon-box {
        width: 48px;
        height: 48px;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .marketer-card:hover .user-icon-box {
        background: var(--primary);
        color: white;
        transform: scale(1.1) rotate(5deg);
    }

    .marketer-main {
        text-align: right;
        margin-bottom: 24px;
    }

    .marketer-name {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 6px;
        color: var(--text-light);
    }

    body.dark-mode .marketer-name { color: var(--text-dark); }

    .marketer-role {
        font-size: 13px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .card-divider {
        height: 1px;
        background: radial-gradient(circle, var(--border-light) 0%, transparent 100%);
        margin-bottom: 24px;
    }

    body.dark-mode .card-divider {
        background: radial-gradient(circle, var(--border-dark) 0%, transparent 100%);
    }

    .marketer-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0px; /* Remove gap to allow border to act as divider */
        margin-bottom: 24px;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 0 16px;
    }

    .stat-item:first-child {
        padding-right: 0;
    }

    .stat-item:last-child {
        padding-left: 0;
        border-right: 2px dotted rgba(148, 163, 184, 0.3);
        padding-right: 16px;
    }

    body.dark-mode .stat-item:last-child {
        border-color: rgba(255, 255, 255, 0.1);
    }

    .available-balance-card {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        border-radius: 18px;
        padding: 16px 20px;
        color: white;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.2);
    }

    .available-balance-card::after {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .balance-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        z-index: 1;
    }

    .balance-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        opacity: 0.8;
        letter-spacing: 0.5px;
    }

    .balance-amount {
        font-size: 22px;
        font-weight: 800;
    }

    .card-chip-mini {
        width: 32px;
        height: 24px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        z-index: 1;
    }

    .btn-edit-premium {
        width: 100%;
        padding: 12px;
        background: var(--bg-light);
        color: var(--text-light);
        border: 1px solid var(--border-light);
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: 'Tajawal', sans-serif;
    }

    body.dark-mode .btn-edit-premium {
        background: var(--bg-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .btn-edit-premium:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
    }

    /* Modal Styles */
    .modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); z-index: 10000; align-items: center; justify-content: center; }
    .modal.active { display: flex; animation: fadeIn 0.3s ease; }
    .modal-content { background: var(--card-light); border-radius: 28px; padding: 36px; max-width: 440px; width: 90%; border: 1px solid var(--border-light); box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
    body.dark-mode .modal-content { background: var(--card-dark); border-color: var(--border-dark); }
    .modal-title { font-size: 24px; font-weight: 800; color: var(--text-light); margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
    body.dark-mode .modal-title { color: var(--text-dark); }
    
    .input-premium {
        width: 100%;
        padding: 14px 18px;
        border-radius: 14px;
        border: 1px solid var(--border-light);
        background: var(--bg-light);
        color: var(--text-light);
        font-size: 15px;
        font-family: 'Tajawal', sans-serif;
        transition: all 0.3s ease;
    }
    body.dark-mode .input-premium { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .input-premium:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1); }

    .modal-actions { display: flex; gap: 16px; margin-top: 32px; }
    .btn { flex: 1; padding: 14px; border: none; border-radius: 14px; font-weight: 800; font-size: 16px; cursor: pointer; transition: all 0.3s ease; font-family: 'Tajawal', sans-serif; }
    .btn-submit { background: var(--primary); color: white; }
    .btn-submit:hover { background: #7c3aed; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); }
    .btn-cancel { background: var(--bg-light); color: var(--text-light); border: 1px solid var(--border-light); }
    body.dark-mode .btn-cancel { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }

    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    @media (max-width: 1024px) { .marketers-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .marketers-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="page-header-premium">
    <div class="header-title-group">
        <div class="header-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="header-text">
            <h1>إدارة المسوقين</h1>
            <p>متابعة أداء المسوقين وتعديل نسب العمولات والأرصدة</p>
        </div>
    </div>
</div>

<div class="marketers-grid" id="marketersGrid">
    <div style="grid-column: 1/-1; text-align: center; padding: 100px; color: #94a3b8;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 16px; opacity: 0.5;"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg>
        <h3 style="font-size: 20px; font-weight: 700;">جاري تحميل المسوقين...</h3>
    </div>
</div>

<!-- Edit Commission Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <h3 class="modal-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            تعديل نسبة العمولة
        </h3>
        <div style="margin-bottom: 24px; padding: 16px; background: rgba(139, 92, 246, 0.05); border-radius: 16px; border: 1px solid rgba(139, 92, 246, 0.1);">
            <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">المسوق المختار</div>
            <div id="marketerNameDisplay" style="font-size: 18px; font-weight: 800; color: var(--primary);">---</div>
        </div>
        <div class="form-group">
            <label>نسبة العمولة الجديدة (%)</label>
            <input type="number" id="commissionRate" class="input-premium" min="0" max="100" step="0.01" placeholder="مثال: 5.50">
        </div>
        <div class="modal-actions">
            <button class="btn btn-cancel" onclick="closeModal()">إلغاء</button>
            <button class="btn btn-submit" id="saveBtn" onclick="saveCommissionRate()">حفظ التغييرات</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let currentMarketerId = null;

    async function fetchMarketers() {
        try {
            const response = await fetch('/api/admin/marketers', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            renderMarketers(result.data || []);
        } catch (error) {
            document.getElementById('marketersGrid').innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 100px; color: #ef4444;">⚠️ خطأ في تحميل البيانات</div>';
        }
    }

    function renderMarketers(marketers) {
        const grid = document.getElementById('marketersGrid');
        if (marketers.length === 0) {
            grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 100px; color: #94a3b8;">📭 لا يوجد مسوقين مسجلين حالياً</div>';
            return;
        }

        grid.innerHTML = marketers.map(m => `
            <div class="marketer-card">
                <div class="marketer-top">
                    <div class="user-icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <div class="badge-commission">${parseFloat(m.commission_rate).toFixed(2)}% عمولة</div>
                </div>
                <div class="marketer-main">
                    <h3 class="marketer-name">${m.full_name}</h3>
                </div>
                <div class="card-divider"></div>
                <div class="marketer-stats">
                    <div class="stat-item">
                        <span class="stat-label">إجمالي الأرباح</span>
                        <span class="stat-value" style="color: #10b981;">${parseFloat(m.total_commissions).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                    </div>
                    <div class="stat-item" style="text-align: left;">
                        <span class="stat-label">إجمالي المسحوب</span>
                        <span class="stat-value" style="color: #ef4444;">${parseFloat(m.total_withdrawals).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                    </div>
                </div>
                <div class="available-balance-card">
                    <div class="balance-info">
                        <span class="balance-label">الرصيد المتاح للسحب</span>
                        <span class="balance-amount">${parseFloat(m.available_balance).toLocaleString('en-US', {minimumFractionDigits: 2})} دينار</span>
                    </div>
                    <div class="card-chip-mini"></div>
                </div>
                <button class="btn-edit-premium" onclick="openEditModal(${m.id}, '${m.full_name}', ${m.commission_rate})">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    تعديل نسبة العمولة
                </button>
            </div>
        `).join('');
    }

    function openEditModal(id, name, rate) {
        currentMarketerId = id;
        document.getElementById('marketerNameDisplay').textContent = name;
        document.getElementById('commissionRate').value = parseFloat(rate).toFixed(2);
        document.getElementById('editModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('editModal').classList.remove('active');
        currentMarketerId = null;
    }

    async function saveCommissionRate() {
        const rate = document.getElementById('commissionRate').value;
        const btn = document.getElementById('saveBtn');
        
        if (!rate || rate < 0 || rate > 100) {
            alert('يرجى إدخال نسبة صحيحة بين 0 و 100');
            return;
        }

        try {
            btn.disabled = true;
            btn.textContent = 'جاري الحفظ...';
            
            const response = await fetch(`/api/admin/marketers/${currentMarketerId}/commission-rate`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ commission_rate: rate })
            });

            if (response.ok) {
                closeModal();
                fetchMarketers();
            } else {
                alert('فشل تحديث النسبة');
            }
        } catch (error) {
            alert('حدث خطأ أثناء التحديث');
        } finally {
            btn.disabled = false;
            btn.textContent = 'حفظ التغييرات';
        }
    }

    fetchMarketers();
</script>
@endpush
