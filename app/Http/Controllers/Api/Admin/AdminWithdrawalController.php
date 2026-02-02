<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketerWithdrawalRequest;
use App\Models\MarketerCommission;
use App\Http\Resources\MarketerWithdrawalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketerWithdrawalRequest::with(['marketer:id,full_name', 'approvedBy:id,full_name', 'rejectedBy:id,full_name'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('marketer_id')) {
            $query->where('marketer_id', $request->marketer_id);
        }

        $withdrawals = $query->get();

        return response()->json([
            'message' => 'قائمة طلبات السحب',
            'data' => MarketerWithdrawalResource::collection($withdrawals)
        ]);
    }

    public function show($id)
    {
        $withdrawal = MarketerWithdrawalRequest::with(['marketer:id,full_name', 'approvedBy:id,full_name', 'rejectedBy:id,full_name'])
            ->find($id);

        if (!$withdrawal) {
            return response()->json(['message' => 'طلب السحب غير موجود'], 404);
        }

        if ($withdrawal->signed_receipt_image) {
            $withdrawal->signed_receipt_image = asset('storage/' . $withdrawal->signed_receipt_image);
        }

        $availableBalance = $this->getAvailableBalance($withdrawal->marketer_id);

        return response()->json([
            'message' => 'تفاصيل طلب السحب',
            'data' => [
                'withdrawal' => new MarketerWithdrawalResource($withdrawal),
                'available_balance' => $availableBalance
            ]
        ]);
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'signed_receipt_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $withdrawal = MarketerWithdrawalRequest::where('id', $id)
                ->where('status', 'pending')
                ->first();

            if (!$withdrawal) {
                return response()->json(['message' => 'طلب السحب غير موجود أو تمت معالجته بالفعل'], 404);
            }

            $availableBalance = $this->getAvailableBalance($withdrawal->marketer_id);

            if ($withdrawal->requested_amount > $availableBalance) {
                return response()->json([
                    'message' => 'المبلغ المطلوب أكبر من الرصيد المتاح',
                    'available_balance' => $availableBalance
                ], 400);
            }

            $imagePath = $request->file('signed_receipt_image')->store('receipts', 'public');

            $withdrawal->update([
                'status' => 'approved',
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
                'signed_receipt_image' => $imagePath
            ]);

            DB::commit();
            return response()->json([
                'message' => 'تمت الموافقة على طلب السحب بنجاح',
                'data' => new MarketerWithdrawalResource($withdrawal)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل الموافقة على طلب السحب', 'error' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $withdrawal = MarketerWithdrawalRequest::where('id', $id)
                ->where('status', 'pending')
                ->first();

            if (!$withdrawal) {
                return response()->json(['message' => 'طلب السحب غير موجود أو تمت معالجته بالفعل'], 404);
            }

            $withdrawal->update([
                'status' => 'rejected',
                'rejected_by' => $request->user()->id,
                'rejected_at' => now(),
                'notes' => $request->notes
            ]);

            DB::commit();
            return response()->json([
                'message' => 'تم رفض طلب السحب',
                'data' => new MarketerWithdrawalResource($withdrawal)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل رفض طلب السحب', 'error' => $e->getMessage()], 500);
        }
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
