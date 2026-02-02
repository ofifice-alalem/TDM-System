<?php

namespace App\Http\Controllers\Web\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarketerRequest;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.requests.index', ['token' => $token]);
    }

    public function create(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.requests.create', ['token' => $token]);
    }

    public function show(Request $request, $id)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.requests.show', ['token' => $token, 'requestId' => $id]);
    }

    public function print($id)
    {
        $marketerRequest = MarketerRequest::with(['user', 'approver', 'items.product'])
            ->where('status', 'approved')
            ->findOrFail($id);

        $arabic = new \ArPHP\I18N\Arabic();
        
        $toEnglishNumbers = function($str) {
            $western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $eastern = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            return str_replace($eastern, $western, $str);
        };

        $data = [
            'invoiceNumber' => $marketerRequest->invoice_number,
            'date' => $marketerRequest->created_at->format('Y-m-d H:i'),
            'marketerName' => $arabic->utf8Glyphs($marketerRequest->user->full_name),
            'approvedBy' => $marketerRequest->approver ? $arabic->utf8Glyphs($marketerRequest->approver->full_name) : null,
            'items' => $marketerRequest->items->map(function($item) use ($arabic, $toEnglishNumbers) {
                return (object)[
                    'name' => $toEnglishNumbers($arabic->utf8Glyphs($item->product->name)),
                    'quantity' => $item->quantity
                ];
            }),
            'title' => $arabic->utf8Glyphs('طلب بضاعة'),
            'labels' => [
                'marketer' => $arabic->utf8Glyphs('المسوق'),
                'date' => $arabic->utf8Glyphs('التاريخ'),
                'status' => $arabic->utf8Glyphs('الحالة'),
                'approved' => $arabic->utf8Glyphs('تم الموافقة'),
                'approvedBy' => $arabic->utf8Glyphs('اعتمد بواسطة'),
                'keeper' => $arabic->utf8Glyphs('أمين المخزن'),
                'product' => $arabic->utf8Glyphs('المنتج'),
                'quantity' => $arabic->utf8Glyphs('الكمية'),
                'total' => $arabic->utf8Glyphs('الإجمالي'),
            ]
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('marketer.requests.invoice-pdf', $data)->setPaper('a4');
        return $pdf->download('request-' . $marketerRequest->invoice_number . '.pdf');
    }

    public function documentation($id)
    {
        $marketerRequest = MarketerRequest::where('status', 'documented')->findOrFail($id);
        
        if (!$marketerRequest->stamped_image) {
            abort(404, 'لا توجد صورة توثيق');
        }

        $imagePath = storage_path('app/public/' . $marketerRequest->stamped_image);
        
        if (!file_exists($imagePath)) {
            abort(404, 'الصورة غير موجودة');
        }

        return response()->file($imagePath);
    }
}
