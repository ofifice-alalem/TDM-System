@extends('layouts.app')

@section('title', 'قائمة المنتجات')

@section('content')
<div class="shop-container">
    <div class="shop-header">
        <div class="title-area">
            <h1>قائمة المنتجات</h1>
            <p>إدارة بيانات وأسعار المنتجات في النظام</p>
        </div>
        <button class="add-btn" onclick="openAddModal()">
            <span>+</span> إضافة منتج جديد
        </button>
    </div>

    <div class="shop-card">
        <div class="card-toolbar">
            <div class="search-box">
                <input type="text" placeholder="بحث سريع عن منتج..." onkeyup="filterProducts(this.value)">
            </div>
            <div class="badge-total" id="totalProducts">0 منتج</div>
        </div>

        <div class="table-wrap">
            <table class="clean-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>اسم المنتج</th>
                        <th>السعر المعتمد</th>
                        <th style="width: 100px;">الإجراء</th>
                    </tr>
                </thead>
                <tbody id="productsList">
                    <tr>
                        <td colspan="4" class="empty-row">جاري المزامنة...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="productModal" class="clean-modal">
    <div class="modal-box">
        <div class="modal-top">
            <h2 id="modalTitle">إضافة منتج</h2>
            <button onclick="closeModal()">&times;</button>
        </div>
        <form id="productForm">
            <div class="form-grid">
                <div class="field">
                    <label>اسم الصنف</label>
                    <input type="text" id="productName" required>
                </div>
                <div class="field">
                    <label>سعر البيع (دينار)</label>
                    <input type="number" id="productPrice" step="0.01" min="0" required>
                </div>
                <div class="field">
                    <label>الوصف</label>
                    <input type="text" id="productDescription" placeholder="وصف المنتج (اختياري)">
                </div>
                <div class="field">
                    <label>الباركود</label>
                    <input type="text" id="productBarcode" placeholder="رقم الباركود (اختياري)">
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
    .shop-container { max-width: 1100px; margin: 0 auto; padding: 20px 0; }
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

    .price-cell { font-family: 'Inter', sans-serif; font-weight: 800; color: var(--primary); }
    .action-link { color: #94a3b8; text-decoration: none; font-size: 13px; font-weight: 700; cursor: pointer; }
    .action-link:hover { color: var(--primary); }

    /* Modal */
    .clean-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
    .clean-modal.active { display: flex; }
    .modal-box { background: white; width: 400px; border-radius: 12px; padding: 30px; box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
    body.dark-mode .modal-box { background: #1a202c; color: white; }
    .modal-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .modal-top h2 { font-size: 20px; font-weight: 800; margin: 0; }
    .modal-top button { background: none; border: none; font-size: 24px; color: #cbd5e0; cursor: pointer; }

    .field { margin-bottom: 20px; }
    .field label { display: block; font-size: 13px; font-weight: 700; color: #718096; margin-bottom: 8px; }
    .field input { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-family: inherit; font-size: 15px; }
    body.dark-mode .field input { background: #111827; border-color: #2d3748; color: white; }

    .modal-btns { display: flex; gap: 12px; justify-content: flex-end; margin-top: 10px; }
    .btn-ghost { background: none; border: 1px solid #e2e8f0; padding: 10px 20px; border-radius: 6px; font-weight: 700; color: #718096; cursor: pointer; }
    .btn-solid { background: var(--primary); color: white; border: none; padding: 10px 24px; border-radius: 6px; font-weight: 700; cursor: pointer; }

    .empty-row { text-align: center; padding: 80px; color: #94a3b8; }
</style>
@endpush

@push('scripts')
<script>
    const token = '{{ $token }}';
    let allProducts = [];
    let editingId = null;

    async function loadData() {
        try {
            const r = await fetch('/api/products', { headers: { 'Authorization': 'Bearer ' + token }});
            const d = await r.json();
            allProducts = d.data?.data || d.data || [];
            displayProducts(allProducts);
        } catch (e) {
            document.getElementById('productsList').innerHTML = '<tr><td colspan="4" class="empty-row">⚠️ فشل التحميل</td></tr>';
        }
    }

    function displayProducts(list) {
        document.getElementById('totalProducts').textContent = list.length + ' منتج';
        document.getElementById('productsList').innerHTML = list.map(p => `
            <tr>
                <td><small>#${p.id}</small></td>
                <td>${p.name}</td>
                <td class="price-cell">${parseFloat(p.current_price || 0).toFixed(2)} دينار</td>
                <td><span class="action-link" onclick="editProduct(${p.id}, '${p.name}', ${p.current_price || 0})">تحرير</span></td>
            </tr>
        `).join('') || '<tr><td colspan="4" class="empty-row">لا يوجد بيانات</td></tr>';
    }

    function filterProducts(q) {
        displayProducts(allProducts.filter(p => p.name.toLowerCase().includes(q.toLowerCase())));
    }

    function openAddModal() {
        editingId = null;
        document.getElementById('modalTitle').textContent = 'إضافة منتج';
        document.getElementById('productName').value = '';
        document.getElementById('productPrice').value = '';
        document.getElementById('productDescription').value = '';
        document.getElementById('productBarcode').value = '';
        document.getElementById('productModal').classList.add('active');
    }

    function editProduct(id, name, price) {
        editingId = id;
        document.getElementById('modalTitle').textContent = 'تحرير المنتج';
        document.getElementById('productName').value = name;
        document.getElementById('productPrice').value = price;
        document.getElementById('productDescription').value = '';
        document.getElementById('productBarcode').value = '';
        document.getElementById('productModal').classList.add('active');
    }

    function closeModal() { document.getElementById('productModal').classList.remove('active'); }

    document.getElementById('productForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        
        const data = {
            name: document.getElementById('productName').value,
            current_price: document.getElementById('productPrice').value,
            description: document.getElementById('productDescription').value || null,
            barcode: document.getElementById('productBarcode').value || null
        };

        try {
            const url = editingId ? `/api/products/${editingId}` : '/api/products';
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