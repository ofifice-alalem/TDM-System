<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketerRequestController extends Controller
{
    /**
     * عرض جميع طلبات المسوق
     * GET /api/marketer/requests
     */
    public function index(Request $request)
    {
        $requests = DB::table('marketer_requests')
            ->where('marketer_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'قائمة طلبات المسوق',
            'data' => $requests
        ]);
    }

    /**
     * إنشاء طلب بضاعة جديد
     * POST /api/marketer/requests
     */
    public function store(Request $request)
    {
        // TODO: Implementation
        return response()->json([
            'message' => 'تم إنشاء الطلب بنجاح',
            'data' => []
        ], 201);
    }

    /**
     * عرض تفاصيل طلب محدد
     * GET /api/marketer/requests/{id}
     */
    public function show($id)
    {
        // TODO: Implementation
        return response()->json([
            'message' => 'تفاصيل الطلب',
            'data' => []
        ]);
    }

    /**
     * إلغاء طلب
     * PUT /api/marketer/requests/{id}/cancel
     */
    public function cancel($id)
    {
        // TODO: Implementation
        return response()->json([
            'message' => 'تم إلغاء الطلب بنجاح'
        ]);
    }
}
