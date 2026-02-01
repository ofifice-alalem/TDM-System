@extends('layouts.app')

@section('title', 'طلب بضاعة جديد')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div class="page-title">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <a href="/marketer/requests" class="icon-btn" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: var(--text-main); text-decoration: none;">
                    <i data-lucide="arrow-right"></i>
                </a>
                <h1 style="margin: 0;">طلب بضاعة جديد</h1>
            </div>
            <p>قم باختيار المنتجات والكميات المطلوبة لسحبها من المخزن</p>
        </div>
    </div>

    <div class="form-card-premium" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 24px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
        <form id="requestForm">
            <div class="section-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px dashed var(--border-color);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 35px; height: 35px; background: var(--accent-purple-light); color: var(--accent-purple); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="package" style="width: 20px;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 700;">أصناف الطلب</h3>
                </div>
                <button type="button" onclick="addItem()" class="btn-add-item" style="background: var(--accent-purple-light); color: var(--accent-purple); border: none; padding: 10px 20px; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    <i data-lucide="plus" style="width: 18px;"></i>
                    إضافة منتج آخر
                </button>
            </div>

            <div id="items-container" style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Items will be injected here -->
            </div>
            
            <div class="notes-section" style="margin-top: 3rem;">
                <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; margin-bottom: 1rem; color: var(--text-main);">
                    <i data-lucide="message-square" style="width: 18px; color: var(--text-muted);"></i>
                    ملاحظات إضافية (اختياري)
                </label>
                <textarea id="notes" class="form-input" style="width: 100%; border-radius: 16px; padding: 1.25rem; min-height: 120px; background: var(--dash-bg); border: 1px solid transparent; transition: all 0.3s;" placeholder="اكتب أي تعليمات خاصة للشحن أو التعبئة هنا..."></textarea>
            </div>

            <div class="form-actions" style="margin-top: 3rem; display: flex; gap: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 2rem;">
                <button type="submit" class="btn-submit" style="flex: 2; background: var(--purple-gradient); color: white; border: none; padding: 16px; border-radius: 16px; font-weight: 800; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3); transition: all 0.3s;">
                    <i data-lucide="send" style="width: 20px;"></i>
                    إرسال الطلب الآن
                </button>
                <a href="/marketer/requests" class="btn-cancel" style="flex: 1; background: var(--dash-bg); color: var(--text-muted); text-decoration: none; padding: 16px; border-radius: 16px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s; border: 1px solid var(--border-color);">
                    <i data-lucide="x" style="width: 18px;"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .item-row {
        background: var(--dash-bg);
        padding: 1.5rem;
        border-radius: 18px;
        display: grid;
        grid-template-columns: 2fr 1fr auto;
        gap: 1.5rem;
        align-items: end;
        animation: slideUp 0.3s ease-out;
        border: 1px solid transparent;
        transition: all 0.3s;
    }

    .item-row:hover {
        border-color: var(--accent-purple-light);
        background: var(--card-bg);
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 8px;
        margin-right: 4px;
    }

    .form-select, .form-input-number {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-main);
        font-size: 1rem;
        font-weight: 600;
        outline: none;
        transition: all 0.3s;
    }

    .form-select:focus, .form-input-number:focus {
        border-color: var(--accent-purple);
        box-shadow: 0 0 0 4px var(--accent-purple-light);
    }

    .btn-remove {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: #fef2f2;
        color: #ef4444;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-remove:hover {
        background: #ef4444;
        color: white;
        transform: scale(1.05);
    }

    .btn-add-item:hover {
        background: var(--accent-purple) !important;
        color: white !important;
        transform: translateY(-2px);
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(168, 85, 247, 0.4);
        filter: brightness(1.1);
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: var(--text-main);
        border-color: var(--text-main);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); color: var(--accent-purple); }
        100% { transform: scale(1); }
    }
</style>
@endsection

@push('scripts')
<script>
    let products = [];
    let itemCount = 0;

    async function loadProducts() {
        const token = localStorage.getItem('token');
        try {
            const response = await fetch(`${API_BASE_URL}/products`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await response.json();
            products = data.data;
            addItem();
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    function addItem() {
        const container = document.getElementById('items-container');
        const itemDiv = document.createElement('div');
        itemDiv.className = 'item-row';
        itemDiv.innerHTML = `
            <div class="form-group">
                <label>اختر المنتج</label>
                <select name="items[${itemCount}][product_id]" class="form-select" onchange="updateStockInfo(this)" required>
                    <option value="">-- اضغط للاختيار --</option>
                    ${products.map(p => `<option value="${p.id}" data-stock="${p.quantity || 0}">${p.name} (${p.price} ر.س)</option>`).join('')}
                </select>
                <div class="stock-display" style="margin-top: 8px; font-size: 0.8rem; color: var(--text-muted); display: none; align-items: center; gap: 5px; animation: fadeIn 0.3s ease;">
                    <i data-lucide="info" style="width: 14px;"></i>
                    <span>المتوفر في المخزن الرئيسي:</span>
                    <strong class="stock-value" style="color: var(--accent-purple); font-size: 0.9rem;">0</strong>
                </div>
            </div>
            <div class="form-group">
                <label>الكمية المطلوبة</label>
                <input type="number" name="items[${itemCount}][quantity]" class="form-input-number" min="1" placeholder="0" required>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="btn-remove" title="حذف الصنف">
                <i data-lucide="trash-2" style="width: 20px;"></i>
            </button>
        `;
        container.appendChild(itemDiv);
        lucide.createIcons();
        itemCount++;
    }

    function updateStockInfo(select) {
        const row = select.closest('.item-row');
        const selectedOption = select.options[select.selectedIndex];
        const stockDisplay = row.querySelector('.stock-display');
        const stockValue = row.querySelector('.stock-value');
        
        if (selectedOption.value) {
            const stock = selectedOption.getAttribute('data-stock');
            stockValue.textContent = stock;
            stockDisplay.style.display = 'flex';
            
            // Add a subtle animation to the quantity
            stockValue.style.animation = 'none';
            stockValue.offsetHeight; /* trigger reflow */
            stockValue.style.animation = 'pulse 0.5s ease';
        } else {
            stockDisplay.style.display = 'none';
        }
    }

    document.getElementById('requestForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Show loading state on button
        const btn = e.target.querySelector('.btn-submit');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" class="animate-spin" style="width: 20px;"></i> جاري المعالجة...';
        lucide.createIcons();

        const formData = new FormData(e.target);
        const items = [];
        
        // Use a more robust way to collect items since rows could be deleted
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(row => {
            const productId = row.querySelector('select').value;
            const quantity = row.querySelector('input[type="number"]').value;
            if (productId && quantity) {
                items.push({ product_id: parseInt(productId), quantity: parseInt(quantity) });
            }
        });

        if (items.length === 0) {
            alert('يرجى إضافة منتج واحد على الأقل');
            btn.disabled = false;
            btn.innerHTML = originalContent;
            lucide.createIcons();
            return;
        }

        const token = localStorage.getItem('token');
        try {
            const response = await fetch(`${API_BASE_URL}/marketer/requests`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    items: items,
                    notes: document.getElementById('notes').value
                })
            });

            if (response.ok) {
                window.location.href = '/marketer/requests';
            } else {
                const errorData = await response.json();
                alert('فشل إنشاء الطلب: ' + (errorData.message || 'خطأ غير معروف'));
                btn.disabled = false;
                btn.innerHTML = originalContent;
                lucide.createIcons();
            }
        } catch (error) {
            console.error('Error submitting request:', error);
            alert('حدث خطأ في الاتصال بالخادم');
            btn.disabled = false;
            btn.innerHTML = originalContent;
            lucide.createIcons();
        }
    });

    loadProducts();
</script>
@endpush
