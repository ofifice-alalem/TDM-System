@extends('layouts.app')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div id="request-details"></div>
    <a href="/marketer/requests" class="inline-block mt-6 bg-gray-500 text-white px-6 py-2 rounded">رجوع</a>
</div>
@endsection

@push('scripts')
<script>
    async function loadRequest() {
        const id = window.location.pathname.split('/').pop();
        const token = localStorage.getItem('token');
        const response = await fetch(`${API_BASE_URL}/marketer/requests/${id}`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const req = await response.json();
        
        document.getElementById('request-details').innerHTML = `
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-4">طلب رقم: ${req.invoice_number}</h1>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div><strong>الحالة:</strong> ${getStatusText(req.status)}</div>
                    <div><strong>التاريخ:</strong> ${req.created_at}</div>
                    ${req.approved_at ? `<div><strong>تاريخ الموافقة:</strong> ${req.approved_at}</div>` : ''}
                    ${req.documented_at ? `<div><strong>تاريخ التوثيق:</strong> ${req.documented_at}</div>` : ''}
                </div>
                
                <h2 class="text-xl font-bold mb-4">المنتجات</h2>
                <table class="w-full mb-6">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-right">المنتج</th>
                            <th class="px-4 py-2 text-right">الكمية</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${req.items.map(item => `
                            <tr class="border-t">
                                <td class="px-4 py-2">${item.product_name}</td>
                                <td class="px-4 py-2">${item.quantity}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                
                ${req.stamped_image ? `
                    <div class="mb-4">
                        <strong>الفاتورة الموقعة:</strong>
                        <img src="/storage/${req.stamped_image}" class="mt-2 max-w-md border rounded">
                    </div>
                ` : ''}
            </div>
        `;
    }

    function getStatusText(status) {
        const texts = {
            pending: 'قيد الانتظار',
            approved: 'موافق عليه',
            documented: 'موثق',
            rejected: 'مرفوض',
            cancelled: 'ملغي'
        };
        return texts[status] || status;
    }

    loadRequest();
</script>
@endpush
