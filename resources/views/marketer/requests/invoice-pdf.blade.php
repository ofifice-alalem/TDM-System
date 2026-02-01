<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة طلب بضاعة</title>
    <style>
        body { font-family: 'Tajawal', Arial, sans-serif; direction: rtl; }
        .header { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: right; }
        th { background: #f5f5f5; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>فاتورة طلب بضاعة</h1>
        <p>رقم الفاتورة: <span id="invoice-number"></span></p>
        <p>التاريخ: <span id="date"></span></p>
    </div>

    <div>
        <p><strong>المسوق:</strong> <span id="marketer-name"></span></p>
        <p><strong>الحالة:</strong> <span id="status"></span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>المنتج</th>
                <th>الكمية</th>
            </tr>
        </thead>
        <tbody id="items-table"></tbody>
    </table>

    <div class="footer">
        <p>نظام إدارة التوزيع - تقنية © 2024</p>
    </div>

    <script>
        const API_BASE_URL = 'http://127.0.0.1:8000/api';
        
        async function loadInvoice() {
            const id = new URLSearchParams(window.location.search).get('id');
            const token = localStorage.getItem('token');
            
            const response = await fetch(`${API_BASE_URL}/marketer/requests/${id}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const req = await response.json();
            
            document.getElementById('invoice-number').textContent = req.invoice_number;
            document.getElementById('date').textContent = req.created_at;
            document.getElementById('marketer-name').textContent = req.marketer_name;
            document.getElementById('status').textContent = req.status;
            
            const tbody = document.getElementById('items-table');
            tbody.innerHTML = req.items.map(item => `
                <tr>
                    <td>${item.product_name}</td>
                    <td>${item.quantity}</td>
                </tr>
            `).join('');
            
            setTimeout(() => window.print(), 500);
        }
        
        loadInvoice();
    </script>
</body>
</html>
