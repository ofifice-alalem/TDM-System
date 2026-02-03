@extends('layouts.app')

@section('title', 'ديون المتاجر')

@push('styles')
<style>
    .page-header-premium { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; animation: fadeInDown 0.6s ease-out; }
    .header-title-group { display: flex; align-items: center; gap: 16px; }
    .header-icon { width: 56px; height: 56px; background: rgba(139, 92, 246, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: var(--primary); }
    .header-text h1 { font-size: 28px; font-weight: 800; margin-bottom: 4px; color: var(--text-light); }
    body.dark-mode .header-text h1 { color: var(--text-dark); }
    .header-text p { font-size: 14px; color: #64748b; }
    
    .stores-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    .store-card { background: var(--card-light); border-radius: 24px; padding: 28px; border: 1px solid var(--border-light); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative; display: flex; flex-direction: column; animation: fadeInUp 0.6s ease-out; }
    body.dark-mode .store-card { background: var(--card-dark); border-color: var(--border-dark); }
    .store-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); border-color: var(--primary); }
    
    .store-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
    .store-icon-box { width: 48px; height: 48px; background: rgba(139, 92, 246, 0.1); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: var(--primary); transition: all 0.3s ease; }
    .store-card:hover .store-icon-box { background: var(--primary); color: white; transform: scale(1.1) rotate(5deg); }
    
    .toggle-switch { position: relative; width: 50px; height: 26px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; border-radius: 26px; transition: 0.3s; }
    .toggle-slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: 0.3s; }
    input:checked + .toggle-slider { background-color: #10b981; }
    input:checked + .toggle-slider:before { transform: translateX(24px); }
    .toggle-switch.disabled { opacity: 0.5; pointer-events: none; }
    
    .store-main { text-align: right; margin-bottom: 24px; }
    .store-name { font-size: 22px; font-weight: 800; margin-bottom: 6px; color: var(--text-light); }
    body.dark-mode .store-name { color: var(--text-dark); }
    .store-owner { font-size: 13px; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
    
    .card-divider { height: 1px; background: radial-gradient(circle, var(--border-light) 0%, transparent 100%); margin-bottom: 24px; }
    body.dark-mode .card-divider { background: radial-gradient(circle, var(--border-dark) 0%, transparent 100%); }
    
    .store-stats { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0px; margin-bottom: 24px; }
    .stat-item { display: flex; flex-direction: column; gap: 6px; padding: 0 12px; }
    .stat-item:first-child { padding-right: 0; }
    .stat-item:not(:first-child) { border-right: 2px dotted rgba(148, 163, 184, 0.3); }
    body.dark-mode .stat-item:not(:first-child) { border-color: rgba(255, 255, 255, 0.1); }
    .stat-label { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; }
    .stat-value { font-size: 18px; font-weight: 800; }
    
    .remaining-debt-card { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 18px; padding: 16px 20px; color: white; margin-bottom: 24px; position: relative; overflow: hidden; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2); }
    .remaining-debt-card.positive { background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2); }
    .remaining-debt-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; }
    .debt-info { display: flex; flex-direction: column; gap: 2px; z-index: 1; }
    .debt-label { font-size: 11px; font-weight: 700; text-transform: uppercase; opacity: 0.8; letter-spacing: 0.5px; }
    .debt-amount { font-size: 22px; font-weight: 800; }
    .card-chip-mini { width: 32px; height: 24px; background: rgba(255, 255, 255, 0.2); border-radius: 4px; border: 1px solid rgba(255, 255, 255, 0.3); z-index: 1; }
    
    .btn-details-premium { width: 100%; padding: 12px; background: var(--bg-light); color: var(--text-light); border: 1px solid var(--border-light); border-radius: 14px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 8px; font-family: 'Tajawal', sans-serif; text-decoration: none; }
    body.dark-mode .btn-details-premium { background: var(--bg-dark); border-color: var(--border-dark); color: var(--text-dark); }
    .btn-details-premium:hover { background: var(--primary); color: white; border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2); }
    
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @media (max-width: 1200px) { .stores-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .stores-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="page-header-premium">
    <div class="header-title-group">
        <div class="header-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        </div>
        <div class="header-text">
            <h1>ديون المتاجر</h1>
            <p>متابعة حسابات المتاجر والديون المستحقة</p>
        </div>
    </div>
</div>

<div class="stores-grid" id="storesGrid">
    <div style="grid-column: 1/-1; text-align: center; padding: 100px; color: #94a3b8;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 16px; opacity: 0.5;"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg>
        <h3 style="font-size: 20px; font-weight: 700;">جاري تحميل المتاجر...</h3>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    const isAdmin = {{ auth()->user()->role->name === 'admin' ? 'true' : 'false' }};

    async function fetchStores() {
        try {
            const response = await fetch('/api/stores/debts', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            renderStores(result.data || []);
        } catch (error) {
            document.getElementById('storesGrid').innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 100px; color: #ef4444;">⚠️ خطأ في تحميل البيانات</div>';
        }
    }

    function renderStores(stores) {
        const grid = document.getElementById('storesGrid');
        if (stores.length === 0) {
            grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 100px; color: #94a3b8;">📭 لا يوجد متاجر مسجلة حالياً</div>';
            return;
        }

        grid.innerHTML = stores.map(s => {
            const remaining = parseFloat(s.remaining_debt);
            const cardClass = remaining <= 0 ? 'positive' : '';
            const debtLabel = remaining <= 0 ? 'لا يوجد ديون' : 'المتبقي';
            
            return `
            <div class="store-card">
                <div class="store-top">
                    <div class="store-icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    </div>
                    <label class="toggle-switch ${!isAdmin ? 'disabled' : ''}">
                        <input type="checkbox" ${s.is_active ? 'checked' : ''} onchange="toggleActive(${s.id}, this.checked)" ${!isAdmin ? 'disabled' : ''}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="store-main">
                    <h3 class="store-name">${s.name}</h3>
                    <div class="store-owner">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        ${s.owner_name || 'غير محدد'}
                    </div>
                </div>
                <div class="card-divider"></div>
                <div class="store-stats">
                    <div class="stat-item">
                        <span class="stat-label">إجمالي الفواتير</span>
                        <span class="stat-value" style="color: #3b82f6;">${parseFloat(s.total_sales).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">مجموع الإرجاع</span>
                        <span class="stat-value" style="color: #f59e0b;">${parseFloat(s.total_returns).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                    </div>
                    <div class="stat-item" style="text-align: left;">
                        <span class="stat-label">ما تم تسديده</span>
                        <span class="stat-value" style="color: #10b981;">${parseFloat(s.total_payments).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                    </div>
                </div>
                <div class="remaining-debt-card ${cardClass}">
                    <div class="debt-info">
                        <span class="debt-label">${debtLabel}</span>
                        <span class="debt-amount">${Math.abs(remaining).toLocaleString('en-US', {minimumFractionDigits: 2})} دينار</span>
                    </div>
                    <div class="card-chip-mini"></div>
                </div>
                <a href="/stores/${s.id}" class="btn-details-premium">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                    التفاصيل
                </a>
            </div>
        `}).join('');
    }

    async function toggleActive(id, isActive) {
        if (!isAdmin) return;
        
        try {
            await fetch(`/api/stores/${id}/toggle-active`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ is_active: isActive })
            });
        } catch (error) {
            alert('فشل تحديث الحالة');
            fetchStores();
        }
    }

    fetchStores();
</script>
@endpush
