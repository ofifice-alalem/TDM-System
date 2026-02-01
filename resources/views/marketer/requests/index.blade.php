<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات البضاعة</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">طلبات البضاعة</h1>
            <a href="/marketer/requests/create" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">طلب جديد</a>
        </div>

        <div id="requests-container" class="bg-white rounded-lg shadow"></div>
    </div>

    <script src="/js/config.js"></script>
    <script>
        async function loadRequests() {
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE_URL}/marketer/requests`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await response.json();
            
            const container = document.getElementById('requests-container');
            container.innerHTML = `
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right">رقم الفاتورة</th>
                            <th class="px-6 py-3 text-right">الحالة</th>
                            <th class="px-6 py-3 text-right">التاريخ</th>
                            <th class="px-6 py-3 text-right">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.map(req => `
                            <tr class="border-t">
                                <td class="px-6 py-4">${req.invoice_number}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-sm ${getStatusClass(req.status)}">
                                        ${getStatusText(req.status)}
                                    </span>
                                </td>
                                <td class="px-6 py-4">${req.created_at}</td>
                                <td class="px-6 py-4">
                                    <a href="/marketer/requests/${req.id}" class="text-blue-500">عرض</a>
                                    ${req.status === 'pending' || req.status === 'approved' ? 
                                        `<button onclick="cancelRequest(${req.id})" class="text-red-500 mr-2">إلغاء</button>` : ''}
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

        async function cancelRequest(id) {
            if (!confirm('هل تريد إلغاء هذا الطلب؟')) return;
            
            const token = localStorage.getItem('token');
            await fetch(`${API_BASE_URL}/marketer/requests/${id}/cancel`, {
                method: 'PUT',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            loadRequests();
        }

        loadRequests();
    </script>
</body>
</html>
