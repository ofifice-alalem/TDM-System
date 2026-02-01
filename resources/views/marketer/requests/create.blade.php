@extends('layouts.app')

@section('title', 'طلب بضاعة جديد')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">طلب بضاعة جديد</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form id="requestForm">
            <div id="items-container"></div>
            
            <button type="button" onclick="addItem()" class="bg-green-500 text-white px-4 py-2 rounded mt-4">إضافة منتج</button>
            
            <div class="mt-6">
                <label class="block mb-2">ملاحظات</label>
                <textarea id="notes" class="w-full border rounded p-2" rows="3"></textarea>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">إنشاء الطلب</button>
                <a href="/marketer/requests" class="bg-gray-500 text-white px-6 py-2 rounded">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let products = [];
    let itemCount = 0;

    async function loadProducts() {
        const token = localStorage.getItem('token');
        const response = await fetch(`${API_BASE_URL}/products`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        products = data.data;
        addItem();
    }

    function addItem() {
        const container = document.getElementById('items-container');
        const itemDiv = document.createElement('div');
        itemDiv.className = 'flex gap-4 mb-4 items-end';
        itemDiv.innerHTML = `
            <div class="flex-1">
                <label class="block mb-2">المنتج</label>
                <select name="items[${itemCount}][product_id]" class="w-full border rounded p-2" required>
                    <option value="">اختر منتج</option>
                    ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                </select>
            </div>
            <div class="w-32">
                <label class="block mb-2">الكمية</label>
                <input type="number" name="items[${itemCount}][quantity]" class="w-full border rounded p-2" min="1" required>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-4 py-2 rounded">حذف</button>
        `;
        container.appendChild(itemDiv);
        itemCount++;
    }

    document.getElementById('requestForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const items = [];
        
        for (let i = 0; i < itemCount; i++) {
            const productId = formData.get(`items[${i}][product_id]`);
            const quantity = formData.get(`items[${i}][quantity]`);
            if (productId && quantity) {
                items.push({ product_id: parseInt(productId), quantity: parseInt(quantity) });
            }
        }

        const token = localStorage.getItem('token');
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
        }
    });

    loadProducts();
</script>
@endpush
