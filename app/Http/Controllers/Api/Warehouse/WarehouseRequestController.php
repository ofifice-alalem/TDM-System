<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseRequestController extends Controller
{
    /**
     * عرض جميع طلبات المسوقين (للمخزن)
     * GET /api/warehouse/requests
     */
    public function index(Request $request)
    {
        $requests = DB::table('marketer_requests')
            ->join('users', 'marketer_requests.marketer_id', '=', 'users.id')
            ->select('marketer_requests.*', 'users.full_name as marketer_name')
            ->orderBy('marketer_requests.created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'قائمة طلبات المسوقين',
            'data' => $requests
        ]);
    }

    /**
     * عرض تفاصيل طلب محدد
     * GET /api/warehouse/requests/{id}
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
     * الموافقة على طلب
     * PUT /api/warehouse/requests/{id}/approve
     */
    public function approve($id)
    {
        // TODO: Implementation
        return response()->json([
            'message' => 'تمت الموافقة على الطلب بنجاح'
        ]);
    }

    /**
     * رفض طلب
     * PUT /api/warehouse/requests/{id}/reject
     */
    public function reject(Request $request, $id)
    {
        // TODO: Implementation
        return response()->json([
            'message' => 'تم رفض الطلب'
        ]);
    }

    /**
     * توثيق استلام البضاعة
     * POST /api/warehouse/requests/{id}/document
     */
    public function document(Request $request, $id)
    {
        // TODO: Implementation
        return response()->json([
            'message' => 'تم توثيق استلام البضاعة بنجاح'
        ]);
    }
}
