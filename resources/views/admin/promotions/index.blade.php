@extends('layouts.app')

@section('title', 'إدارة العروض الترويجية')

@push('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
    .page-title { font-size: 32px; font-weight: 800; color: var(--text-light); display: flex; align-items: center; gap: 16px; }
    body.dark-mode .page-title { color: var(--text-dark); }
    .btn-add { padding: 12px 24px; background: var(--primary); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 10px; }
    .btn-add:hover { background: #7c3aed; transform: translateY(-2px); }
    .promotions-list { display: flex; flex-direction: column; gap: 16px; }
    .promotion-card { background: var(--card-light); border-radius: 20px; padding: 24px; border: 1px solid var(--border-light); display: flex; align-items: center; gap: 20px; transition: all 0.3s; }
    body.dark-mode .promotion-card { background: var(--card-dark); border-color: var(--border-dark); }
    .promotion-card:hover { box-shadow: var(--shadow-md); border-color: var(--primary); }
    .promotion-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: rgba(139, 92, 246, 0.1); color: var(--primary); }
    .promotion-info { flex: 1; display: grid; grid-template-columns: 0.5fr 1.5fr 1fr 1fr 1fr 0.8fr; gap: 20px; align-items: center; }
    .info-label { font-size: 11px; color: #94a3b8; font-weight: 600; }
    .info-value { font-size: 14px; font-weight: 700; color: var(--text-light); }
    body.dark-mode .info-value { color: var(--text-dark); }
    .promotion-id { font-size: 18px; font-weight: 800; color: var(--primary); background: rgba(139, 92, 246, 0.1); padding: 8px 16px; border-radius: 10px; display: inline-block; }
    .promotion-text { font-size: 16px; font-weight: 800; color: var(--primary); }
    .status-badge { padding: 6px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
    .status-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-inactive { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .status-expired { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .promotion-actions { display: flex; gap: 8px; }
    .btn-toggle { padding: 10px 18px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; transition: all 0.3s; font-size: 13px; display: flex; align-items: center; gap: 6px; }
    .btn-toggle.active { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .btn-toggle.active:hover { background: rgba(239, 68, 68, 0.25); }
    .btn-toggle.inactive { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .btn-toggle.inactive:hover { background: rgba(16, 185, 129, 0.25); }
    .btn-delete { padding: 10px 18px; background: rgba(239, 68, 68, 0.15); color: #ef4444; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; transition: all 0.3s; font-size: 13px; display: flex; align-items: center; gap: 6px; }
    .btn-delete:hover { background: rgba(239, 68, 68, 0.25); }
    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .tabs-container { display: flex; gap: 8px; margin-bottom: 24px; background: rgba(100, 116, 139, 0.05); padding: 6px; border-radius: 14px; }
    .tab-btn { flex: 1; padding: 10px; border: none; background: transparent; border-radius: 10px; font-weight: 700; color: #64748b; cursor: pointer; transition: all 0.3s; font-family: 'Tajawal', sans-serif; }
    .tab-btn.active { background: var(--card-light); color: var(--primary); box-shadow: var(--shadow-sm); }
    body.dark-mode .tab-btn.active { background: var(--primary); color: white; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        إدارة العروض الترويجية
    </h1>
    <a href="/admin/promotions/create" class="btn-add">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        إضافة عرض ترويجي
    </a>
</div>

<div class="tabs-container">
    <button class="tab-btn active" onclick="switchTab(true, this)">المفعل</button>
    <button class="tab-btn" onclick="switchTab(false, this)">المعطل</button>
</div>

<div class="promotions-list" id="promotionsList">
    <div class="empty-state">جاري تحميل العروض الترويجية...</div>
</div>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allPromotions = [];
    let currentFilter = true;

    async function fetchPromotions() {
        try {
            const response = await fetch('/api/admin/promotions', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            allPromotions = result.data || [];
            renderPromotions();
        } catch (error) {
            document.getElementById('promotionsList').innerHTML = '<div class="empty-state">⚠️ خطأ في تحميل البيانات</div>';
        }
    }

    function switchTab(isActive, btn) {
        currentFilter = isActive;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderPromotions();
    }

    function renderPromotions() {
        const container = document.getElementById('promotionsList');
        const filtered = allPromotions.filter(p => p.is_active === currentFilter);

        if (filtered.length === 0) {
            container.innerHTML = '<div class="empty-state">لا توجد عروض ترويجية</div>';
            return;
        }

        container.innerHTML = filtered.map(promotion => {
            const isActive = promotion.is_active;
            const isExpired = new Date(promotion.end_date) < new Date();
            const statusClass = isActive ? (isExpired ? 'status-expired' : 'status-active') : 'status-inactive';
            const statusLabel = isActive ? (isExpired ? 'منتهي' : 'مفعل') : 'معطل';
            const toggleClass = isActive ? 'active' : 'inactive';
            const toggleLabel = isActive ? 'تعطيل' : 'تفعيل';

            return `
                <div class="promotion-card">
                    <div class="promotion-info">
                        <div>
                            <div class="promotion-id">${promotion.id}</div>
                        </div>
                        <div>
                            <div class="info-label">المنتج</div>
                            <div class="info-value">${promotion.product_name}</div>
                        </div>
                        <div>
                            <div class="info-label">العرض</div>
                            <div class="promotion-text">${promotion.promotion_text}</div>
                        </div>
                        <div>
                            <div class="info-label">الفترة</div>
                            <div class="info-value">${promotion.start_date} إلى ${promotion.end_date}</div>
                        </div>
                        <div>
                            <div class="info-label">المنشئ</div>
                            <div class="info-value">${promotion.creator_name || '-'}</div>
                        </div>
                        <div>
                            <div class="info-label">الحالة</div>
                            <div class="status-badge ${statusClass}">${statusLabel}</div>
                        </div>
                    </div>
                    <div class="promotion-actions">
                        <button class="btn-toggle ${toggleClass}" onclick="event.stopPropagation(); togglePromotion(${promotion.id})">
                            ${isActive ? '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' : '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>'}
                            ${toggleLabel}
                        </button>
                        <button class="btn-delete" onclick="event.stopPropagation(); deletePromotion(${promotion.id})">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            حذف
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    async function togglePromotion(id) {
        showConfirm('هل أنت متأكد من تغيير حالة هذا العرض؟', async () => {
            try {
                const response = await fetch(`/api/admin/promotions/${id}/toggle`, {
                    method: 'PUT',
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                });
                const result = await response.json();
                showModal('✅ نجاح', result.message, () => fetchPromotions());
            } catch (error) {
                showModal('⚠️ خطأ', 'حدث خطأ أثناء تغيير الحالة');
            }
        });
    }

    async function deletePromotion(id) {
        showConfirm('هل أنت متأكد من تعطيل هذا العرض نهائياً؟', async () => {
            try {
                const response = await fetch(`/api/admin/promotions/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                });
                const result = await response.json();
                showModal('✅ نجاح', result.message, () => fetchPromotions());
            } catch (error) {
                showModal('⚠️ خطأ', 'حدث خطأ أثناء الحذف');
            }
        });
    }

    fetchPromotions();
</script>
@endpush
