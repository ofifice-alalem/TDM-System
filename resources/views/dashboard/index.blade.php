@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">إجمالي المنتجات</h3>
            <p id="total-products" class="text-3xl font-bold text-blue-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">المنتجات النشطة</h3>
            <p id="active-products" class="text-3xl font-bold text-green-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">المخزون الرئيسي</h3>
            <p id="main-stock" class="text-3xl font-bold text-purple-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm">المتاجر</h3>
            <p id="total-stores" class="text-3xl font-bold text-orange-600">0</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-bold">قائمة المنتجات</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">الرقم</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">اسم المنتج</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">الباركود</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">السعر</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">المخزون</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">الحالة</th>
                    </tr>
                </thead>
                <tbody id="products-table"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function loadDashboard() {
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user'));
        
        if (!token || !user) {
            window.location.href = '/';
            return;
        }

        document.getElementById('user-name').textContent = user.full_name;

        try {
            const [productsRes, storesRes] = await Promise.all([
                fetch(`${API_BASE_URL}/products`, {
                    headers: { 
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }),
                fetch(`${API_BASE_URL}/stores`, {
                    headers: { 
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
            ]);

            if (!productsRes.ok || !storesRes.ok) {
                console.error('API Error:', productsRes.status, storesRes.status);
                return;
            }

            const productsData = await productsRes.json();
            const storesData = await storesRes.json();

            const products = productsData.data || [];
            const stores = storesData.data || [];
            const activeProducts = products.filter(p => p.is_active);
            const totalStock = products.reduce((sum, p) => sum + (p.main_stock?.quantity || 0), 0);

            document.getElementById('total-products').textContent = products.length;
            document.getElementById('active-products').textContent = activeProducts.length;
            document.getElementById('main-stock').textContent = totalStock;
            document.getElementById('total-stores').textContent = stores.length;

            const tbody = document.getElementById('products-table');
            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">لا توجد منتجات</td></tr>';
            } else {
                tbody.innerHTML = products.map((product, index) => `
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4">${index + 1}</td>
                        <td class="px-6 py-4 font-medium">${product.name}</td>
                        <td class="px-6 py-4 text-gray-600">${product.barcode || '-'}</td>
                        <td class="px-6 py-4 text-green-600 font-semibold">${product.current_price} ريال</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm ${
                                (product.main_stock?.quantity || 0) > 100 ? 'bg-green-100 text-green-800' :
                                (product.main_stock?.quantity || 0) > 50 ? 'bg-yellow-100 text-yellow-800' :
                                'bg-red-100 text-red-800'
                            }">
                                ${product.main_stock?.quantity || 0}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm ${
                                product.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                            }">
                                ${product.is_active ? 'نشط' : 'غير نشط'}
                            </span>
                        </td>
                    </tr>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading dashboard:', error);
            alert('حدث خطأ في تحميل البيانات');
        }
    }

    loadDashboard();
</script>
@endpush
