@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="shop-container">
    <div class="shop-header">
        <div class="title-area">
            <h1>إدارة المستخدمين</h1>
            <p>إضافة وتعديل المستخدمين والصلاحيات</p>
        </div>
        <button class="add-btn" onclick="openAddModal()">إضافة مستخدم</button>
    </div>

    <div class="shop-card">
        <div class="card-toolbar">
            <div class="search-box">
                <input type="text" placeholder="بحث سريع عن مستخدم..." onkeyup="filterUsers(this.value)">
            </div>
            <div class="badge-total" id="totalUsers">0 مستخدم</div>
        </div>

        <div class="table-wrap">
            <table class="clean-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>اسم المستخدم</th>
                        <th>الاسم الكامل</th>
                        <th>الدور</th>
                        <th>العمولة %</th>
                        <th>الحالة</th>
                        <th style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="usersList">
                    <tr>
                        <td colspan="7" class="empty-row">جاري المزامنة...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="userModal" class="clean-modal">
    <div class="modal-box" style="width: 500px;">
        <div class="modal-top">
            <h2 id="modalTitle">إضافة مستخدم</h2>
            <button onclick="closeModal()">&times;</button>
        </div>
        <form id="userForm">
            <div class="form-grid">
                <div class="field">
                    <label>اسم المستخدم</label>
                    <input type="text" id="username" required>
                </div>
                <div class="field">
                    <label>الاسم الكامل</label>
                    <input type="text" id="fullName" required>
                </div>
                <div class="field">
                    <label>كلمة المرور</label>
                    <input type="password" id="password" required>
                </div>
                <div class="field">
                    <label>الدور</label>
                    <select id="roleId" required>
                        <option value="">اختر الدور</option>
                    </select>
                </div>
                <div class="field">
                    <label>نسبة العمولة %</label>
                    <input type="number" id="commissionRate" step="0.01" min="0" max="100" placeholder="0.00">
                </div>
            </div>
            <div class="modal-btns">
                <button type="button" class="btn-ghost" onclick="closeModal()">إلغاء</button>
                <button type="submit" class="btn-solid" id="submitBtn">حفظ البيانات</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .shop-container { max-width: 1200px; margin: 0 auto; padding: 20px 0; }
    .shop-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; }
    .title-area h1 { font-size: 30px; font-weight: 800; color: var(--text-light); margin: 0; }
    body.dark-mode .title-area h1 { color: var(--text-dark); }
    .title-area p { color: #8e9aaf; margin: 4px 0 0; font-size: 15px; }

    .add-btn { background: var(--text-light); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; }
    body.dark-mode .add-btn { background: var(--primary); }
    .add-btn:hover { opacity: 0.9; transform: translateY(-1px); }

    .shop-card { background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    body.dark-mode .shop-card { background: #1a1f2e; border-color: #2d3748; }

    .card-toolbar { padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #edf2f7; }
    body.dark-mode .card-toolbar { border-color: #2d3748; }
    
    .search-box input { border: 1px solid #e2e8f0; padding: 10px 16px; border-radius: 6px; width: 300px; font-family: inherit; font-size: 14px; }
    body.dark-mode .search-box input { background: #111827; border-color: #2d3748; color: white; }

    .badge-total { background: #f7fafc; padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 700; color: #4a5568; }
    body.dark-mode .badge-total { background: #111827; color: #a0aec0; }

    .clean-table { width: 100%; border-collapse: collapse; }
    .clean-table th { text-align: right; padding: 16px 24px; background: #f8fafc; color: #64748b; font-size: 12px; font-weight: 800; border-bottom: 1px solid #edf2f7; }
    body.dark-mode .clean-table th { background: #111827; border-color: #2d3748; color: #718096; }
    .clean-table td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; font-weight: 600; color: var(--text-light); }
    body.dark-mode .clean-table td { border-color: #2d3748; color: var(--text-dark); }
    .clean-table tr:hover { background: #f8fafc; }
    body.dark-mode .clean-table tr:hover { background: #222a3b; }

    .status-badge { padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #fef2f2; color: #dc2626; }
    body.dark-mode .status-active { background: #064e3b; color: #34d399; }
    body.dark-mode .status-inactive { background: #7f1d1d; color: #f87171; }

    .action-link { color: #94a3b8; text-decoration: none; font-size: 13px; font-weight: 700; cursor: pointer; margin-left: 8px; }
    .action-link:hover { color: var(--primary); }

    /* Modal */
    .clean-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
    .clean-modal.active { display: flex; }
    .modal-box { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
    body.dark-mode .modal-box { background: #1a202c; color: white; }
    .modal-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .modal-top h2 { font-size: 20px; font-weight: 800; margin: 0; }
    .modal-top button { background: none; border: none; font-size: 24px; color: #cbd5e0; cursor: pointer; }

    .field { margin-bottom: 20px; }
    .field label { display: block; font-size: 13px; font-weight: 700; color: #718096; margin-bottom: 8px; }
    .field input, .field select { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-family: inherit; font-size: 15px; }
    body.dark-mode .field input, body.dark-mode .field select { background: #111827; border-color: #2d3748; color: white; }

    .modal-btns { display: flex; gap: 12px; justify-content: flex-end; margin-top: 10px; }
    .btn-ghost { background: none; border: 1px solid #e2e8f0; padding: 10px 20px; border-radius: 6px; font-weight: 700; color: #718096; cursor: pointer; }
    .btn-solid { background: var(--primary); color: white; border: none; padding: 10px 24px; border-radius: 6px; font-weight: 700; cursor: pointer; }

    .empty-row { text-align: center; padding: 80px; color: #94a3b8; }
</style>
@endpush

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allUsers = [];
    let allRoles = [];
    let editingId = null;

    async function loadData() {
        try {
            const [usersRes, rolesRes] = await Promise.all([
                fetch('/api/users', { headers: { 'Authorization': 'Bearer ' + token }}),
                fetch('/api/roles', { headers: { 'Authorization': 'Bearer ' + token }})
            ]);
            
            const usersData = await usersRes.json();
            const rolesData = await rolesRes.json();
            
            allUsers = usersData.data?.data || usersData.data || [];
            allRoles = rolesData.data?.data || rolesData.data || [];
            
            displayUsers(allUsers);
            loadRoles();
        } catch (e) {
            document.getElementById('usersList').innerHTML = '<tr><td colspan="7" class="empty-row">⚠️ فشل التحميل</td></tr>';
        }
    }

    function loadRoles() {
        const select = document.getElementById('roleId');
        select.innerHTML = '<option value="">اختر الدور</option>' + 
            allRoles.map(r => `<option value="${r.id}">${r.display_name}</option>`).join('');
    }

    function displayUsers(list) {
        document.getElementById('totalUsers').textContent = list.length + ' مستخدم';
        document.getElementById('usersList').innerHTML = list.map(u => `
            <tr>
                <td><small>#${u.id}</small></td>
                <td>${u.username}</td>
                <td>${u.full_name}</td>
                <td>${u.role_name}</td>
                <td>${parseFloat(u.commission_rate).toFixed(2)}%</td>
                <td><span class="status-badge ${u.is_active ? 'status-active' : 'status-inactive'}">${u.is_active ? 'نشط' : 'معطل'}</span></td>
                <td>
                    <span class="action-link" onclick="editUser(${u.id})">تحرير</span>
                    <span class="action-link" onclick="toggleActive(${u.id})">${u.is_active ? 'تعطيل' : 'تفعيل'}</span>
                </td>
            </tr>
        `).join('') || '<tr><td colspan="7" class="empty-row">لا يوجد بيانات</td></tr>';
    }

    function filterUsers(q) {
        displayUsers(allUsers.filter(u => 
            u.username.toLowerCase().includes(q.toLowerCase()) || 
            u.full_name.toLowerCase().includes(q.toLowerCase())
        ));
    }

    function openAddModal() {
        editingId = null;
        document.getElementById('modalTitle').textContent = 'إضافة مستخدم';
        document.getElementById('userForm').reset();
        document.getElementById('password').required = true;
        document.getElementById('userModal').classList.add('active');
    }

    function editUser(id) {
        const user = allUsers.find(u => u.id === id);
        if (!user) return;
        
        editingId = id;
        document.getElementById('modalTitle').textContent = 'تحرير المستخدم';
        document.getElementById('username').value = user.username;
        document.getElementById('fullName').value = user.full_name;
        document.getElementById('password').value = '';
        document.getElementById('password').required = false;
        document.getElementById('commissionRate').value = user.commission_rate;
        document.getElementById('userModal').classList.add('active');
    }

    async function toggleActive(id) {
        try {
            const r = await fetch(`/api/users/${id}/toggle-active`, {
                method: 'PUT',
                headers: { 'Authorization': 'Bearer ' + token }
            });
            if (r.ok) loadData();
        } catch (e) { alert('خطأ'); }
    }

    function closeModal() { document.getElementById('userModal').classList.remove('active'); }

    document.getElementById('userForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        
        const data = {
            username: document.getElementById('username').value,
            full_name: document.getElementById('fullName').value,
            role_id: document.getElementById('roleId').value,
            commission_rate: document.getElementById('commissionRate').value || 0
        };

        if (document.getElementById('password').value) {
            data.password = document.getElementById('password').value;
        }

        try {
            const url = editingId ? `/api/users/${editingId}` : '/api/users';
            const method = editingId ? 'PUT' : 'POST';
            const r = await fetch(url, {
                method,
                headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (r.ok) { closeModal(); loadData(); }
        } catch (e) { alert('خطأ'); }
        btn.disabled = false;
    });

    loadData();
</script>
@endpush
@endsection