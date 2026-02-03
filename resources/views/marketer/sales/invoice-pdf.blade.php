<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>فاتورة بيع</title>
    <style>
        @font-face {
            font-family: 'Cairo';
            src: url('{{ public_path("fonts/Cairo-Regular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Cairo';
            src: url('{{ public_path("fonts/Cairo-ExtraBold.ttf") }}') format('truetype');
            font-weight: 900;
            font-style: normal;
        }
        @page { margin: 10px; }
        * { font-family: 'Cairo', 'DejaVu Sans', sans-serif; }
        body { font-family: 'Cairo', 'DejaVu Sans', sans-serif; color: #333; font-size: 13px; margin: 0; }
        .header { margin-bottom: 8px; background-color: #333; color: white; padding: 8px; border-radius: 4px; display: table; width: 100%; }
        .header-right { display: table-cell; text-align: right; width: 50%; vertical-align: middle; }
        .header-left { display: table-cell; text-align: left; width: 50%; vertical-align: middle; }
        .header h1 { margin: 0; font-size: 18px; font-weight: bold; }
        .header h2 { margin: 0; font-size: 20px; font-weight: 900; color: white; letter-spacing: 0.5px; }
        .info-box { background-color: #f8f9fa; padding: 6px 10px; border-radius: 4px; margin-bottom: 8px; border: 1px solid #333; text-align: right; }
        .info-row { display: inline-block; width: 48%; margin-bottom: 3px; font-size: 12px; text-align: right; font-weight: bold; }
        .label { font-weight: bold; color: #333; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { background-color: #333; color: white; padding: 6px; text-align: center; font-weight: bold; font-size: 13px; }
        td { border: 1px solid #333; padding: 5px; background-color: #ffffff; font-size: 12px; text-align: center; }
        td.product-name { text-align: right; padding-right: 8px; }
        td.quantity { font-family: 'DejaVu Sans', sans-serif; direction: ltr; }
        td.price { font-family: 'DejaVu Sans', sans-serif; direction: ltr; }
        tr:nth-child(even) td { background-color: #f5f5f5; }
        .summary-box { margin-top: 10px; background-color: #f8f9fa; padding: 8px; border-radius: 4px; border: 1px solid #333; }
        .summary-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 12px; font-weight: bold; }
        .summary-row.total { font-size: 14px; color: #333; border-top: 2px solid #333; padding-top: 6px; margin-top: 4px; }
        .signatures { position: fixed; bottom: 10px; left: 10px; right: 10px; }
        .signature-box { display: inline-block; width: 30%; text-align: center; border-top: 1px solid #000; padding-top: 15px; margin: 0 1.5%; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h2>#{{ $invoiceNumber }}</h2>
        </div>
        <div class="header-right">
            <h1>{{ $title }}</h1>
        </div>
    </div>

    <div class="info-box">
        <div class="info-row">
            {{ $marketerName }} :<span class="label">{{ $labels['marketer'] }}</span>
        </div>
        <div class="info-row">
            {{ $storeName }} :<span class="label">{{ $labels['store'] }}</span>
        </div>
        <div class="info-row">
            {{ $date }} :<span class="label">{{ $labels['date'] }}</span>
        </div>
        <div class="info-row">
            {{ $labels[$status] }} :<span class="label">{{ $labels['status'] }}</span>
        </div>
        @if(isset($approvedBy))
        <div class="info-row">
            {{ $approvedBy }} :<span class="label">{{ $labels['approvedBy'] }}</span>
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ $labels['total'] }}</th>
                <th>{{ $labels['price'] }}</th>
                <th>{{ $labels['discount'] }}</th>
                <th>{{ $labels['quantity'] }}</th>
                <th>{{ $labels['product'] }}</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="price">{{ number_format($item->total, 2) }}</td>
                <td class="price">{{ number_format($item->price, 2) }}</td>
                <td class="quantity" style="{{ $item->discount > 0 ? 'background-color: #d4edda; font-weight: bold;' : '' }}">{{ $item->discount > 0 ? $item->discount : '-' }}</td>
                <td class="quantity">{{ $item->totalQty }}</td>
                <td class="product-name">{{ $item->name }}</td>
                <td class="quantity">{{ $index + 1 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-row">
            <span>{{ $labels['subtotal'] }}:</span>
            <span class="price">{{ number_format($subtotal, 2) }} {{ $labels['currency'] }}</span>
        </div>
        @if($productDiscount > 0)
        <div class="summary-row">
            <span>{{ $labels['productDiscount'] }}:</span>
            <span class="price">{{ number_format($productDiscount, 2) }} {{ $labels['currency'] }}</span>
        </div>
        @endif
        @if($invoiceDiscount > 0)
        <div class="summary-row">
            <span>{{ $labels['invoiceDiscount'] }}:</span>
            <span class="price">{{ number_format($invoiceDiscount, 2) }} {{ $labels['currency'] }}</span>
        </div>
        @endif
        <div class="summary-row total">
            <span>{{ $labels['finalTotal'] }}:</span>
            <span class="price">{{ number_format($finalTotal, 2) }} {{ $labels['currency'] }}</span>
        </div>
    </div>

    @if($notes)
    <div style="margin-top: 10px; padding: 8px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 4px;">
        <div style="font-weight: bold; font-size: 12px; margin-bottom: 4px;">{{ $labels['notes'] }}:</div>
        <div style="font-size: 11px;">{{ $notes }}</div>
    </div>
    @endif

    <div class="signatures">
        <div class="signature-box">{{ $labels['marketer'] }}</div>
        <div class="signature-box">{{ $labels['keeper'] }}</div>
        <div class="signature-box">{{ $labels['storeOwner'] }}</div>
    </div>
</body>
</html>
