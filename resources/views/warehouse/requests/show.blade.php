<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div id="request-details"></div>
        <a href="/warehouse/requests" class="inline-block mt-6 bg-gray-500 text-white px-6 py-2 rounded">رجوع</a>
    </div>

    <script src="/js/config.js"></script>
    <script>
        let currentRequest = null;

        async function loadRequest() {
            const id = window.location.pathname.split('/').pop();
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE_URL}/warehouse/requests/${id}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            currentRequest = await response.json();
            renderRequest();
        }

        function renderRequest() {
            const req = currentRequest;
            document.getElementById('request-details').innerHTML = `
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold mb-4">طلب رقم: ${req.invoice_number}</h1>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div><strong>المسوق:</strong> ${req.marketer_name}</div>
                        <div><strong>الحالة:</strong> ${getStatusText(req.status)}</div>
                        <div><strong>التاريخ:</strong> ${req.created_at}</div>
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
                    
                    ${req.status === 'pending' ? `
                        <div class="flex gap-4">
                            <button onclick="approveRequest()" class="bg-green-500 text-white px-6 py-2 rounded">موافقة</button>
                            <button onclick="rejectRequest()" class="bg-red-500 text-white px-6 py-2 rounded">رفض</button>
                        </div>
                    ` : ''}
                    
                    ${req.status === 'approved' ? `
                        <div>
                            <h3 class="text-lg font-bold mb-2">رفع الفاتورة الموقعة</h3>
                            <input type="file" id="stamped_image" accept="image/*" class="mb-2">
                            <button onclick="documentRequest()" class="bg-blue-500 text-white px-6 py-2 rounded">توثيق</button>
                        </div>
                    ` : ''}
                    
                    ${req.stamped_image ? `
                        <div class="mt-4">
                            <strong>الفاتورة الموقعة:</strong>
                            <img src="/storage/${req.stamped_image}" class="mt-2 max-w-md border rounded">
                        </div>
                    ` : ''}
                </div>
            `;
        }

        async function approveRequest() {
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE_URL}/warehouse/requests/${currentRequest.id}/approve`, {
                method: 'PUT',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (response.ok) {
                alert('تمت الموافقة بنجاح');
                loadRequest();
            }
        }

        async function rejectRequest() {
            const notes = prompt('سبب الرفض:');
            if (!notes) return;
            
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE_URL}/warehouse/requests/${currentRequest.id}/reject`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notes })
            });
            if (response.ok) {
                alert('تم الرفض');
                loadRequest();
            }
        }

        async function documentRequest() {
            const fileInput = document.getElementById('stamped_image');
            if (!fileInput.files[0]) {
                alert('يرجى اختيار صورة الفاتورة');
                return;
            }
            
            const formData = new FormData();
            formData.append('stamped_image', fileInput.files[0]);
            
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE_URL}/warehouse/requests/${currentRequest.id}/document`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}` },
                body: formData
            });
            if (response.ok) {
                alert('تم التوثيق بنجاح');
                loadRequest();
            }
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
</body>
</html>
