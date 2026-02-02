<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketerWithdrawalRequest;
use App\Models\MarketerCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketerWithdrawalRequest::with(['marketer:id,full_name', 'approvedBy:id,full_name', 'rejectedBy:id,full_name'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->get();

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function show($id)
    {
        $withdrawal = MarketerWithdrawalRequest::with(['marketer:id,full_name', 'approvedBy:id,full_name', 'rejectedBy:id,full_name'])
            ->findOrFail($id);

        $availableBalance = $this->getAvailableBalance($withdrawal->marketer_id);

        return view('admin.withdrawals.show', compact('withdrawal', 'availableBalance'));
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
                ->firstOrFail();

            $availableBalance = $this->getAvailableBalance($withdrawal->marketer_id);

            if ($withdrawal->requested_amount > $availableBalance) {
                return back()->with('error', 'المبلغ المطلوب أكبر من الرصيد المتاح');
            }

            $imagePath = $request->file('signed_receipt_image')->store('receipts', 'public');

            $withdrawal->update([
                'status' => 'approved',
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
                'signed_receipt_image' => $imagePath
            ]);

            DB::commit();
            return redirect()->route('admin.withdrawals.index')->with('success', 'تمت الموافقة على طلب السحب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'فشل الموافقة على طلب السحب');
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
                ->firstOrFail();

            $withdrawal->update([
                'status' => 'rejected',
                'rejected_by' => $request->user()->id,
                'rejected_at' => now(),
                'notes' => $request->notes
            ]);

            DB::commit();
            return redirect()->route('admin.withdrawals.index')->with('success', 'تم رفض طلب السحب');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'فشل رفض طلب السحب');
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
