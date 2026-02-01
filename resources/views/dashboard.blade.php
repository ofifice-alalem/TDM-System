<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
</head>
<body>
    <h1>لوحة التحكم</h1>
    <p>Token: <code>{{ $token }}</code></p>
    <hr>
    <h3>اختبار API:</h3>
    <button onclick="testAPI()">اختبار /api/marketer/requests</button>
    <pre id="result"></pre>
    
    <script>
        const token = '{{ $token }}';
        
        async function testAPI() {
            try {
                const response = await fetch('/api/marketer/requests', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });
                
                const text = await response.text();
                document.getElementById('result').textContent = text;
                
                if (response.headers.get('content-type')?.includes('application/json')) {
                    const data = JSON.parse(text);
                    document.getElementById('result').textContent = JSON.stringify(data, null, 2);
                }
            } catch (error) {
                document.getElementById('result').textContent = 'Error: ' + error.message;
            }
        }
    </script>
</body>
</html>
