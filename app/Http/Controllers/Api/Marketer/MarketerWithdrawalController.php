<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use App\Models\MarketerWithdrawalRequest;
use App\Models\MarketerCommission;
use App\Http\Resources\MarketerWithdrawalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketerWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketerWithdrawalRequest::where('marketer_id', $request->user()->id)
            ->with(['approvedBy:id,full_name', 'rejectedBy:id,full_name']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'قائمة طلبات السحب',
            'data' => MarketerWithdrawalResource::collection($withdrawals)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'requested_amount' => 'required|numeric|min:0.01'
        ]);

        DB::beginTransaction();
        try {
            $availableBalance = $this->getAvailableBalance($request->user()->id);

            if ($request->requested_amount > $availableBalance) {
                return response()->json([
                    'message' => 'المبلغ المطلوب أكبر من الرصيد المتاح',
                    'available_balance' => $availableBalance
                ], 400);
            }

            $withdrawal = MarketerWithdrawalRequest::create([
                'marketer_id' => $request->user()->id,
                'requested_amount' => $request->requested_amount,
                'status' => 'pending'
            ]);

            DB::commit();
            return response()->json([
                'message' => 'تم إنشاء طلب السحب بنجاح',
                'data' => new MarketerWithdrawalResource($withdrawal)
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل إنشاء طلب السحب', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $withdrawal = MarketerWithdrawalRequest::where('id', $id)
            ->where('marketer_id', $request->user()->id)
            ->with(['approvedBy:id,full_name', 'rejectedBy:id,full_name'])
            ->first();

        if (!$withdrawal) {
            return response()->json(['message' => 'طلب السحب غير موجود'], 404);
        }

        if ($withdrawal->signed_receipt_image) {
            $withdrawal->signed_receipt_image = asset('storage/' . $withdrawal->signed_receipt_image);
        }

        return response()->json([
            'message' => 'تفاصيل طلب السحب',
            'data' => new MarketerWithdrawalResource($withdrawal)
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $request->validate(['notes' => 'nullable|string']);

        DB::beginTransaction();
        try {
            $withdrawal = MarketerWithdrawalRequest::where('id', $id)
                ->where('marketer_id', $request->user()->id)
                ->where('status', 'pending')
                ->first();

            if (!$withdrawal) {
                return response()->json(['message' => 'طلب السحب غير موجود أو لا يمكن إلغاؤه'], 404);
            }

            $withdrawal->update([
                'status' => 'cancelled',
                'notes' => $request->notes
            ]);

            DB::commit();
            return response()->json(['message' => 'تم إلغاء طلب السحب بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء إلغاء طلب السحب'], 500);
        }
    }

    public function balance(Request $request)
    {
        $marketerId = $request->user()->id;
        
        $totalCommissions = MarketerCommission::where('marketer_id', $marketerId)->sum('commission_amount');
        
        $totalWithdrawals = MarketerWithdrawalRequest::where('marketer_id', $marketerId)
            ->where('status', 'approved')
            ->sum('requested_amount');
        
        $availableBalance = $totalCommissions - $totalWithdrawals;

        return response()->json([
            'message' => 'رصيد العمولات',
            'data' => [
                'total_commissions' => $totalCommissions,
                'total_withdrawals' => $totalWithdrawals,
                'available_balance' => $availableBalance
            ]
        ]);
    }

    private function getAvailableBalance($marketerId)
    {
        $totalCommissions = MarketerCommission::where('marketer_id', $marketerId)->sum('commission_amount');
        
        $totalWithdrawals = MarketerWithdrawalRequest::where('marketer_id', $marketerId)
            ->where('status', 'approved')
            ->sum('requested_amount');
        
        return $totalCommissions - $totalWithdrawals;
    }
}
