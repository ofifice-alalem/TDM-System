@extends('layouts.app')

@section('title', 'طلبات البضاعة - أمين المخزن')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">طلبات البضاعة</h1>
    <div id="requests-container" class="bg-white rounded-lg shadow"></div>
</div>
@endsection

@push('scripts')
<script>
    async function loadRequests() {
        const token = localStorage.getItem('token');
        const response = await fetch(`${API_BASE_URL}/warehouse/requests`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        
        const container = document.getElementById('requests-container');
        container.innerHTML = `
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right">رقم الفاتورة</th>
                        <th class="px-6 py-3 text-right">المسوق</th>
                        <th class="px-6 py-3 text-right">الحالة</th>
                        <th class="px-6 py-3 text-right">التاريخ</th>
                        <th class="px-6 py-3 text-right">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.data.map(req => `
                        <tr class="border-t">
                            <td class="px-6 py-4">${req.invoice_number}</td>
                            <td class="px-6 py-4">${req.marketer_name}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-sm ${getStatusClass(req.status)}">
                                    ${getStatusText(req.status)}
                                </span>
                            </td>
                            <td class="px-6 py-4">${req.created_at}</td>
                            <td class="px-6 py-4">
                                <a href="/warehouse/requests/${req.id}" class="text-blue-500">عرض</a>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    }

    function getStatusClass(status) {
        const classes = {
            pending: 'bg-yellow-100 text-yellow-800',
            approved: 'bg-green-100 text-green-800',
            documented: 'bg-blue-100 text-blue-800',
            rejected: 'bg-red-100 text-red-800',
            cancelled: 'bg-gray-100 text-gray-800'
        };
        return classes[status] || '';
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

    loadRequests();
</script>
@endpush
