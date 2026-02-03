<?php

namespace App\Http\Controllers\Web\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesInvoice;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.sales.index', ['token' => $token]);
    }

    public function create(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.sales.create', ['token' => $token]);
    }

    public function show(Request $request, $id)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.sales.show', ['token' => $token, 'invoiceId' => $id]);
    }

    public function print($id)
    {
        $invoice = SalesInvoice::with(['marketer', 'store', 'keeper', 'items.product'])
            ->findOrFail($id);

        $arabic = new \ArPHP\I18N\Arabic();
        
        $toEnglishNumbers = function($str) {
            $western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $eastern = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            return str_replace($eastern, $western, $str);
        };

        $subtotal = $invoice->items->sum(function($item) {
            $freeQty = $item->free_quantity ?? 0;
            return ($item->quantity + $freeQty) * $item->unit_price;
        });

        $data = [
            'invoiceNumber' => $invoice->invoice_number,
            'date' => $invoice->created_at->format('Y-m-d H:i'),
            'marketerName' => $arabic->utf8Glyphs($invoice->marketer->full_name),
            'storeName' => $arabic->utf8Glyphs($invoice->store->name),
            'status' => $invoice->status,
            'approvedBy' => $invoice->keeper ? $arabic->utf8Glyphs($invoice->keeper->full_name) : null,
            'notes' => $invoice->notes ? $arabic->utf8Glyphs($invoice->notes) : null,
            'items' => $invoice->items->map(function($item) use ($arabic, $toEnglishNumbers) {
                $freeQty = $item->free_quantity ?? 0;
                $totalQty = $item->quantity + $freeQty;
                $totalWithFree = $totalQty * $item->unit_price;
                return (object)[
                    'name' => $toEnglishNumbers($arabic->utf8Glyphs($item->product->name)),
                    'totalQty' => $totalQty,
                    'discount' => $freeQty,
                    'price' => $item->unit_price,
                    'total' => $totalWithFree
                ];
            }),
            'subtotal' => $subtotal,
            'productDiscount' => $invoice->product_discount ?? 0,
            'invoiceDiscount' => $invoice->invoice_discount_amount ?? 0,
            'finalTotal' => $invoice->total_amount,
            'title' => $arabic->utf8Glyphs('فاتورة بيع'),
            'labels' => [
                'marketer' => $arabic->utf8Glyphs('المسوق'),
                'store' => $arabic->utf8Glyphs('المتجر'),
                'date' => $arabic->utf8Glyphs('التاريخ'),
                'status' => $arabic->utf8Glyphs('الحالة'),
                'pending' => $arabic->utf8Glyphs('قيد الانتظار'),
                'approved' => $arabic->utf8Glyphs('تم الموافقة'),
                'rejected' => $arabic->utf8Glyphs('مرفوض'),
                'approvedBy' => $arabic->utf8Glyphs('اعتمد بواسطة'),
                'keeper' => $arabic->utf8Glyphs('أمين المخزن'),
                'storeOwner' => $arabic->utf8Glyphs('صاحب المتجر'),
                'product' => $arabic->utf8Glyphs('المنتج'),
                'quantity' => $arabic->utf8Glyphs('الكمية'),
                'discount' => $arabic->utf8Glyphs('تخفيض'),
                'price' => $arabic->utf8Glyphs('السعر'),
                'total' => $arabic->utf8Glyphs('الإجمالي'),
                'subtotal' => $arabic->utf8Glyphs('المجموع الفرعي'),
                'productDiscount' => $arabic->utf8Glyphs('خصم المنتجات'),
                'invoiceDiscount' => $arabic->utf8Glyphs('خصم الفاتورة'),
                'finalTotal' => $arabic->utf8Glyphs('الإجمالي النهائي'),
                'currency' => $arabic->utf8Glyphs('د'),
                'notes' => $arabic->utf8Glyphs('ملاحظات'),
            ]
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('marketer.sales.invoice-pdf', $data)->setPaper('a4');
        return $pdf->download('sales-invoice-' . $invoice->invoice_number . '.pdf');
    }
}
