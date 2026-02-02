<?php

namespace App\Http\Controllers\Web\Marketer;

use App\Http\Controllers\Controller;
use App\Models\MarketerWithdrawalRequest;
use App\Models\MarketerCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $withdrawals = MarketerWithdrawalRequest::where('marketer_id', $request->user()->id)
            ->with(['approvedBy:id,full_name', 'rejectedBy:id,full_name'])
            ->orderBy('created_at', 'desc')
            ->get();

        $availableBalance = $this->getAvailableBalance($request->user()->id);
        $token = $request->user()->createToken('web-token')->plainTextToken;

        return view('marketer.withdrawals.index', compact('withdrawals', 'availableBalance', 'token'));
    }

    public function create(Request $request)
    {
        $availableBalance = $this->getAvailableBalance($request->user()->id);
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.withdrawals.create', compact('availableBalance', 'token'));
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
                return back()->with('error', 'المبلغ المطلوب أكبر من الرصيد المتاح');
            }

            MarketerWithdrawalRequest::create([
                'marketer_id' => $request->user()->id,
                'requested_amount' => $request->requested_amount,
                'status' => 'pending'
            ]);

            DB::commit();
            return redirect()->route('marketer.withdrawals.index')->with('success', 'تم إنشاء طلب السحب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'فشل إنشاء طلب السحب');
        }
    }

    public function show(Request $request, $id)
    {
        $withdrawal = MarketerWithdrawalRequest::where('id', $id)
            ->where('marketer_id', $request->user()->id)
            ->with(['approvedBy:id,full_name', 'rejectedBy:id,full_name'])
            ->firstOrFail();

        $token = $request->user()->createToken('web-token')->plainTextToken;

        return view('marketer.withdrawals.show', compact('withdrawal', 'id', 'token'));
    }

    public function cancel(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $withdrawal = MarketerWithdrawalRequest::where('id', $id)
                ->where('marketer_id', $request->user()->id)
                ->where('status', 'pending')
                ->firstOrFail();

            $withdrawal->update([
                'status' => 'cancelled',
                'notes' => $request->notes
            ]);

            DB::commit();
            return redirect()->route('marketer.withdrawals.index')->with('success', 'تم إلغاء طلب السحب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إلغاء طلب السحب');
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
