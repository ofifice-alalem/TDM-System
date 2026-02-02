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

        $data = [
            'invoiceNumber' => $marketerRequest->invoice_number,
            'date' => $marketerRequest->created_at->format('Y-m-d H:i'),
            'marketerName' => $arabic->utf8Glyphs($marketerRequest->user->full_name),
            'approvedBy' => $marketerRequest->approver ? $arabic->utf8Glyphs($marketerRequest->approver->full_name) : null,
            'items' => $marketerRequest->items->map(function($item) use ($arabic) {
                return (object)[
                    'name' => $arabic->utf8Glyphs($item->product->name),
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
            ]
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('marketer.requests.invoice-pdf', $data)->setPaper('a4');
        return $pdf->download('request-' . $marketerRequest->invoice_number . '.pdf');
    }
}
