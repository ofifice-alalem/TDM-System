@extends('layouts.app')

@section('title', 'تفاصيل الطلب | ' . $requestId)

@push('styles')
<style>
    :root {
        --card-radius: 24px;
        --accent-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        --glass-bg: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(255, 255, 255, 0.4);
    }

    body.dark-mode {
        --glass-bg: rgba(30, 41, 59, 0.8);
        --glass-border: rgba(255, 255, 255, 0.1);
    }

    .page-header {
        margin-bottom: 40px;
        animation: fadeInDown 0.6s ease-out;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 16px;
        letter-spacing: -0.5px;
    }

    body.dark-mode .page-title { color: var(--text-dark); }

    .page-title svg {
        color: var(--primary);
        filter: drop-shadow(0 0 8px rgba(139, 92, 246, 0.3));
    }

    .page-layout {
        display: flex;
        flex-direction:row-reverse;
        gap: 24px;
        align-items: start;
        max-width: 1400px;
        margin: 0 auto;
    }

    .actions-sidebar {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: var(--card-radius);
        padding: 32px;
        border: 1px solid var(--glass-border);
        box-shadow: var(--shadow-lg);
        position: sticky;
        top: 100px;
        animation: fadeInLeft 0.6s ease-out;
        width: 280px;
        flex-shrink: 0;
    }

    .sidebar-section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border-light);
    }

    body.dark-mode .sidebar-section-title {
        color: var(--text-dark);
        border-color: var(--border-dark);
    }

    .actions-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .request-content {
        display: flex;
        flex-direction: column;
        gap: 32px;
        animation: fadeInRight 0.6s ease-out;
        flex: 1;
    }

    .info-card {
        background: var(--card-light);
        border-radius: var(--card-radius);
        padding: 40px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }

    body.dark-mode .info-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .info-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--accent-gradient);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 40px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
        padding: 12px 0;
        border-bottom: 2px dotted var(--border-light);
    }

    body.dark-mode .info-item {
        border-bottom-color: var(--border-dark);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-light);
    }

    body.dark-mode .info-value { color: var(--text-dark); }

    .small-info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 8px 0;
        border-bottom: 1px solid var(--border-light);
    }

    body.dark-mode .small-info-item {
        border-bottom-color: var(--border-dark);
    }

    .small-info-item:last-child {
        border-bottom: none;
    }

    .sidebar-info-card {
        background: rgba(16, 185, 129, 0.05);
        padding: 16px;
        border-radius: 16px;
        border: 1px solid rgba(16, 185, 129, 0.1);
        margin-top: 20px;
        animation: fadeInDown 0.4s ease-out;
    }

    body.dark-mode .sidebar-info-card {
        background: rgba(16, 185, 129, 0.1);
    }

    .sidebar-info-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--success);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .products-card {
        background: var(--card-light);
        border-radius: var(--card-radius);
        padding: 32px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-md);
    }

    body.dark-mode .products-card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
    }

    .section-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    body.dark-mode .section-title { color: var(--text-dark); }

    .table-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .table-container { border-color: var(--border-dark); }

    .products-table {
        width: 100%;
        border-collapse: collapse;
    }

    .products-table thead th {
        background: #f8fafc;
        padding: 16px 24px;
        text-align: right;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        border-bottom: 1px solid var(--border-light);
    }

    body.dark-mode .products-table thead th {
        background: #0f172a;
        border-color: var(--border-dark);
    }

    .products-table tbody td {
        padding: 20px 24px;
        font-weight: 600;
        color: var(--text-light);
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s;
    }

    body.dark-mode .products-table tbody td {
        color: var(--text-dark);
        border-color: var(--border-dark);
    }

    .products-table tbody tr:last-child td { border-bottom: none; }
    .products-table tbody tr:hover td { background: rgba(139, 92, 246, 0.02); }

    .status-badge {
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .status-approved { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-documented { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
    .status-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .status-cancelled { background: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.2); }

    .btn {
        width: 100%;
        padding: 14px 20px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
    }

    .btn:active { transform: scale(0.98); }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        transform: translateY(-2px);
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.15);
    }

    .btn-danger:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: var(--bg-light);
        color: #64748b;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .btn-secondary {
        background: #1e293b;
        color: #94a3b8;
        border-color: #334155;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .quantity-pill {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary);
        padding: 6px 14px;
        border-radius: 99px;
        font-size: 14px;
        font-weight: 800;
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @media (max-width: 1200px) {
        .page-layout { flex-direction: column; }
        .actions-sidebar { position: static; width: 100%; }
    }

    .badge {
        padding: 6px 12px;
        background: var(--primary);
        color: white;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        animation: fadeIn 0.2s ease;
    }

    .modal-overlay.active { display: flex; align-items: center; justify-content: center; }

    .modal-dialog {
        background: var(--card-light);
        border-radius: 20px;
        padding: 32px;
        max-width: 550px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
        position: relative;
    }

    body.dark-mode .modal-dialog { background: var(--card-dark); }

    .modal-close {
        position: absolute;
        top: 16px;
        left: 16px;
        width: 32px;
        height: 32px;
        border: none;
        background: rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .modal-close:hover { background: rgba(0, 0, 0, 0.1); }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 24px;
    }

    .modal-header svg { color: #8b5cf6; }

    .modal-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(139, 92, 246, 0.1);
    }

    .modal-icon svg { color: #8b5cf6; width: 40px; height: 40px; }

    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        margin-bottom: 20px;
        background: #f9fafb;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: #8b5cf6;
        background: rgba(139, 92, 246, 0.02);
    }

    body.dark-mode .upload-area {
        background: rgba(255, 255, 255, 0.02);
        border-color: var(--border-dark);
    }

    .upload-text {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-light);
        margin-top: 16px;
    }

    body.dark-mode .upload-text { color: var(--text-dark); }

    .upload-hint {
        font-size: 13px;
        color: #9ca3af;
        margin-top: 8px;
    }

    .warning-box {
        background: rgba(251, 191, 36, 0.1);
        border: 1px solid rgba(251, 191, 36, 0.2);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        font-size: 13px;
        color: #92400e;
        line-height: 1.6;
    }

    .warning-box svg { flex-shrink: 0; color: #f59e0b; }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 12px;
        color: var(--text-light);
    }

    body.dark-mode .modal-title { color: var(--text-dark); }

    .modal-message {
        text-align: center;
        color: #64748b;
        margin-bottom: 28px;
        line-height: 1.6;
    }

    .modal-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        background: var(--bg-light);
        color: var(--text-light);
        font-size: 14px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin-bottom: 20px;
    }

    body.dark-mode .modal-input {
        background: var(--bg-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .modal-actions {
        display: flex;
        gap: 12px;
    }

    .modal-btn {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .modal-btn-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .modal-btn-primary:hover { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); }

    .modal-btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .modal-btn-danger:hover { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }

    .modal-btn-secondary {
        background: var(--bg-light);
        color: #64748b;
        border: 1px solid var(--border-light);
    }

    body.dark-mode .modal-btn-secondary {
        background: var(--bg-dark);
        border-color: var(--border-dark);
    }

    .modal-btn-secondary:hover { background: #e2e8f0; }

    .image-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 10000;
        animation: fadeIn 0.2s ease;
    }

    .image-modal-overlay.active { display: flex; align-items: center; justify-content: center; flex-direction: column; }

    .image-modal-header {
        position: absolute;
        top: 20px;
        right: 20px;
        left: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 10001;
    }

    .image-modal-title {
        color: white;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .image-modal-actions {
        display: flex;
        gap: 12px;
    }

    .image-modal-btn {
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .image-modal-btn-download {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .image-modal-btn-download:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); }

    .image-modal-btn-close {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .image-modal-btn-close:hover { background: rgba(255, 255, 255, 0.2); }

    .image-modal-content {
        max-width: 90%;
        max-height: 85vh;
        animation: slideUp 0.3s ease;
    }

    .image-modal-content img {
        max-width: 100%;
        max-height: 85vh;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .image-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 10000;
        animation: fadeIn 0.2s ease;
    }

    .image-modal-overlay.active { display: flex; align-items: center; justify-content: center; flex-direction: column; }

    .image-modal-header {
        position: absolute;
        top: 20px;
        right: 20px;
        left: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 10001;
    }

    .image-modal-title {
        color: white;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .image-modal-actions {
        display: flex;
        gap: 12px;
    }

    .image-modal-btn {
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .image-modal-btn-download {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .image-modal-btn-download:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); }

    .image-modal-btn-close {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .image-modal-btn-close:hover { background: rgba(255, 255, 255, 0.2); }

    .image-modal-content {
        max-width: 90%;
        max-height: 85vh;
        animation: slideUp 0.3s ease;
    }

    .image-modal-content img {
        max-width: 100%;
        max-height: 85vh;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span>بيانات الطلب <span id="invoiceNumber" style="color: var(--primary); opacity: 0.8;">-</span></span>
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

        <!-- Approval Sidebar Section -->
        <div class="sidebar-info-card" id="approvalSidebarSection" style="display: none;">
            <div class="sidebar-info-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                تمت الموافقة
            </div>
            <div class="small-info-item">
                <div class="info-label">بواسطة</div>
                <div class="info-value" id="sidebarApprovedBy">-</div>
            </div>
            <div class="small-info-item">
                <div class="info-label">بتاريخ</div>
                <div class="info-value" id="sidebarApprovedAt">-</div>
            </div>
        </div>

        <!-- Documentation Sidebar Section -->
        <div class="sidebar-info-card" id="documentationSidebarSection" style="display: none; background: rgba(59, 130, 246, 0.05); border-color: rgba(59, 130, 246, 0.1);">
            <div class="sidebar-info-title" style="color: var(--info);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                تم التوثيق
            </div>
            <div class="small-info-item">
                <div class="info-label">بواسطة</div>
                <div class="info-value" id="sidebarDocumentedBy">-</div>
            </div>
            <div class="small-info-item">
                <div class="info-label">بتاريخ</div>
                <div class="info-value" id="sidebarDocumentedAt">-</div>
            </div>
        </div>

        <!-- Rejection Sidebar Section -->
        <div class="sidebar-info-card" id="rejectionSidebarSection" style="display: none; background: rgba(239, 68, 68, 0.05); border-color: rgba(239, 68, 68, 0.1);">
            <div class="sidebar-info-title" style="color: var(--danger);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                تم الرفض
            </div>
            <div class="small-info-item">
                <div class="info-label">بواسطة</div>
                <div class="info-value" id="sidebarRejectedBy">-</div>
            </div>
            <div class="small-info-item">
                <div class="info-label">بتاريخ</div>
                <div class="info-value" id="sidebarRejectedAt">-</div>
            </div>
            <div class="small-info-item">
                <div class="info-label">سبب الرفض</div>
                <div class="info-value" id="sidebarRejectionNotes" style="font-size: 14px; line-height: 1.6; color: #ef4444;">-</div>
            </div>
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

        <div class="products-card">
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    الأصناف المطلوبة
                </h2>
                <div class="badge" id="itemsCountBadge">0 أصناف</div>
            </div>

            <div class="table-container">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">الرقم</th>
                            <th>اسم المنتج ومواصفاته</th>
                            <th style="width: 150px; text-align: center;">الكمية</th>
                        </tr>
                    </thead>
                    <tbody id="productsBody">
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 40px;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 12px; color: #94a3b8;">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity: 0.5;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                                    <span>جاري تحميل البيانات...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="confirmModal">
    <div class="modal-dialog">
        <button class="modal-close" onclick="closeModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">تأكيد العملية</h3>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="12" cy="12" r="1"></circle></svg>
        </div>
        <div id="modalContentArea"></div>
        <div class="modal-actions">
            <button class="modal-btn modal-btn-secondary" onclick="closeModal()">إلغاء</button>
            <button class="modal-btn modal-btn-primary" id="modalConfirmBtn" onclick="confirmAction()">تأكيد</button>
        </div>
    </div>
</div>

<div class="image-modal-overlay" id="imageModal">
    <div class="image-modal-header">
        <div class="image-modal-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            صورة التوثيق
        </div>
        <div class="image-modal-actions">
            <button class="image-modal-btn image-modal-btn-download" id="downloadImageBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                تنزيل
            </button>
            <button class="image-modal-btn image-modal-btn-close" onclick="closeImageModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                إغلاق
            </button>
        </div>
    </div>
    <div class="image-modal-content">
        <img id="documentImage" src="" alt="صورة التوثيق">
    </div>
</div>

<style>
    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 0.3; }
        100% { opacity: 0.6; }
    }
</style>
@endsection

@push('scripts')
<script>
    const token = '{{ $token }}';
    const requestId = '{{ $requestId }}';
    let modalCallback = null;

    function showModal(title, message, type = 'success', needsInput = false, needsFile = false) {
        return new Promise((resolve) => {
            document.getElementById('modalTitle').textContent = title;
            
            const contentArea = document.getElementById('modalContentArea');
            const confirmBtn = document.getElementById('modalConfirmBtn');
            
            if (needsFile) {
                contentArea.innerHTML = `
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
                        <div>يجب أن تكون الفاتورة موقعة من المسوق وتحتوي على ختم الاستلام. تفقيش المحتوى وتحديث الكميات سيتم فوراً بعد الاعتماد.</div>
                    </div>
                `;
                document.querySelector('.upload-area').onclick = () => document.getElementById('modalFileInput').click();
                document.getElementById('modalFileInput').onchange = function(e) {
                    if (this.files && this.files[0]) {
                        document.getElementById('uploadText').innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" style="display:inline-block; vertical-align:middle; margin-left:6px;"><polyline points="20 6 9 17 4 12"></polyline></svg> تمت معالجة الصورة';
                        document.getElementById('uploadText').style.color = '#10b981';
                    }
                };
                confirmBtn.textContent = 'إتمام التوثيق';
                confirmBtn.className = 'modal-btn modal-btn-primary';
            } else if (needsInput) {
                contentArea.innerHTML = `
                    <p class="modal-message">${message}</p>
                    <textarea class="modal-input" id="modalInput" rows="3" placeholder="اكتب السبب هنا..."></textarea>
                `;
                confirmBtn.textContent = 'تأكيد';
                confirmBtn.className = type === 'danger' ? 'modal-btn modal-btn-danger' : 'modal-btn modal-btn-primary';
            } else {
                contentArea.innerHTML = `<p class="modal-message">${message}</p>`;
                confirmBtn.textContent = 'تأكيد';
                confirmBtn.className = type === 'danger' ? 'modal-btn modal-btn-danger' : 'modal-btn modal-btn-primary';
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
        
        if (input) {
            value = input.value;
        } else if (fileInput && fileInput.files && fileInput.files[0]) {
            value = fileInput.files[0];
        }
        
        document.getElementById('confirmModal').classList.remove('active');
        if (modalCallback) modalCallback(value);
    }

    async function fetchRequestDetails() {
        try {
            const response = await fetch(`/api/warehouse/requests/${requestId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            if (result.data) {
                displayDetails(result.data.request, result.data.items);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayDetails(request, items) {
        const statusMap = {
            'pending': { label: 'قيد الانتظار', class: 'status-pending' },
            'approved': { label: 'تم الموافقة', class: 'status-approved' },
            'documented': { label: 'تم التوثيق', class: 'status-documented' },
            'rejected': { label: 'مرفوض', class: 'status-rejected' },
            'cancelled': { label: 'ملغي', class: 'status-cancelled' }
        };
        
        const status = statusMap[request.status] || { label: request.status, class: '' };
        const createdAtDate = new Date(request.created_at);
        const formattedDate = createdAtDate.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
        const formattedTime = createdAtDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        
        document.getElementById('statusBadge').textContent = status.label;
        document.getElementById('statusBadge').className = `status-badge ${status.class}`;
        document.getElementById('invoiceNumber').textContent = `#${request.invoice_number}`;
        document.getElementById('marketerName').textContent = request.marketer_name || '---';
        document.getElementById('createdAt').textContent = `${formattedDate} | ${formattedTime}`;
        document.getElementById('itemsCountBadge').textContent = `${items.length} أصناف`;

        // Approved Info
        if (request.status === 'approved' || request.status === 'documented') {
            if (request.approved_by && request.approved_at) {
                document.getElementById('approvalSidebarSection').style.display = 'block';
                document.getElementById('sidebarApprovedBy').textContent = request.approver_name || 'المسؤول';
                const appDate = new Date(request.approved_at);
                document.getElementById('sidebarApprovedAt').textContent = appDate.toLocaleDateString('en-US').replace(/\//g, '-');
            }
        }

        // Documented Info
        if (request.status === 'documented') {
            if (request.documented_by && request.documented_at) {
                document.getElementById('documentationSidebarSection').style.display = 'block';
                document.getElementById('sidebarDocumentedBy').textContent = request.documenter_name || 'أمين المخزن';
                const docDate = new Date(request.documented_at);
                document.getElementById('sidebarDocumentedAt').textContent = docDate.toLocaleDateString('en-US').replace(/\//g, '-');
            }
        }

        // Rejected Info
        if (request.status === 'rejected') {
            if (request.rejected_by && request.rejected_at) {
                document.getElementById('rejectionSidebarSection').style.display = 'block';
                document.getElementById('sidebarRejectedBy').textContent = request.rejecter_name || 'أمين المخزن';
                const rejDate = new Date(request.rejected_at);
                document.getElementById('sidebarRejectedAt').textContent = rejDate.toLocaleDateString('en-US').replace(/\//g, '-');
                document.getElementById('sidebarRejectionNotes').textContent = request.notes || 'لا يوجد سبب';
            }
        }

        const tbody = document.getElementById('productsBody');
        if (items && items.length > 0) {
            tbody.innerHTML = items.map((item, index) => `
                <tr>
                    <td style="color: #94a3b8; font-weight: 700;">${(index + 1).toString().padStart(2, '0')}</td>
                    <td>
                        <div style="font-weight: 700; font-size: 16px;">${item.product_name}</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="quantity-pill">${item.quantity}</span>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; padding: 40px;">لا توجد منتجات</td></tr>';
        }

        renderActions(request.status);
    }

    function renderActions(status) {
        const container = document.getElementById('actionsContainer');
        let html = '';

        if (status === 'pending') {
            html += `
                <button class="btn btn-success" onclick="approveRequest()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    الموافقة على الطلب
                </button>
                <button class="btn btn-danger" onclick="rejectRequest()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    رفض الطلب
                </button>
            `;
        }

        if (status === 'approved') {
            html += `
                <button class="btn btn-success" onclick="documentRequest()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    توثيق الاستلام
                </button>
                <button class="btn btn-success" onclick="window.open('/marketer/requests/${requestId}/print', '_blank')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    طباعة الطلب
                </button>
                <button class="btn btn-danger" onclick="cancelRequest()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    إلغاء الطلب
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
                رجوع للقائمة
            </button>
        `;

        container.innerHTML = html;
    }

    async function approveRequest() {
        const confirmed = await showModal('الموافقة على الطلب', 'هل أنت متأكد من الموافقة على هذا الطلب؟', 'success');
        if (!confirmed) return;

        try {
            const response = await fetch(`/api/warehouse/requests/${requestId}/approve`, {
                method: 'PUT',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                await showModal('تم بنجاح', 'تمت الموافقة على الطلب بنجاح', 'success');
                window.location.reload();
            } else {
                await showModal('خطأ', 'فشلت الموافقة على الطلب', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ أثناء الموافقة على الطلب', 'danger');
        }
    }

    async function rejectRequest() {
        const notes = await showModal('رفض الطلب', 'يرجى إدخال سبب الرفض:', 'danger', true);
        if (!notes) return;

        try {
            const response = await fetch(`/api/warehouse/requests/${requestId}/reject`, {
                method: 'PUT',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notes })
            });

            if (response.ok) {
                await showModal('تم بنجاح', 'تم رفض الطلب بنجاح', 'success');
                window.location.reload();
            } else {
                await showModal('خطأ', 'فشل رفض الطلب', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ أثناء رفض الطلب', 'danger');
        }
    }

    async function cancelRequest() {
        const notes = await showModal('سبب الرفض', 'يرجى إدخال سبب الرفض:', 'danger', true);
        if (!notes) return;

        try {
            const response = await fetch(`/api/warehouse/requests/${requestId}/cancel`, {
                method: 'PUT',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notes })
            });

            if (response.ok) {
                await showModal('تم بنجاح', 'تم رفض الطلب بنجاح', 'success');
                window.location.reload();
            } else {
                await showModal('خطأ', 'فشل رفض الطلب', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ أثناء رفض الطلب', 'danger');
        }
    }

    async function documentRequest() {
        const imageFile = await showModal('توثيق الاستلام', 'يرجى رفع صورة الفاتورة الموقعة', 'success', false, true);
        if (!imageFile) return;
        
        if (imageFile === true) {
            await showModal('خطأ', 'يرجى اختيار صورة', 'danger');
            return;
        }

        const formData = new FormData();
        formData.append('stamped_image', imageFile);

        try {
            const response = await fetch(`/api/warehouse/requests/${requestId}/document`, {
                method: 'POST',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (response.ok) {
                await showModal('تم بنجاح', 'تم توثيق استلام البضاعة بنجاح', 'success');
                window.location.reload();
            } else {
                await showModal('خطأ', 'فشل توثيق الاستلام', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ أثناء التوثيق', 'danger');
        }
    }

    async function viewDocumentation() {
        try {
            const response = await fetch(`/api/warehouse/requests/${requestId}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            if (result.data && result.data.request.stamped_image) {
                const imageUrl = result.data.request.stamped_image;
                document.getElementById('documentImage').src = imageUrl;
                document.getElementById('imageModal').classList.add('active');
                
                document.getElementById('downloadImageBtn').onclick = function() {
                    const link = document.createElement('a');
                    link.href = imageUrl;
                    link.download = `توثيق-${result.data.request.invoice_number}.png`;
                    link.click();
                };
            } else {
                await showModal('خطأ', 'لا توجد صورة توثيق متاحة', 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            await showModal('خطأ', 'حدث خطأ أثناء عرض التوثيق', 'danger');
        }
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.remove('active');
    }

    fetchRequestDetails();
</script>
@endpush
