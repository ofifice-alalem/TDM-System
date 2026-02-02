<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MarketerCommission;
use App\Models\MarketerWithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMarketerController extends Controller
{
    public function index()
    {
        $marketers = User::whereHas('role', function($q) {
            $q->where('name', 'salesman');
        })->get()->map(function($marketer) {
            $totalCommissions = MarketerCommission::where('marketer_id', $marketer->id)->sum('commission_amount');
            $totalWithdrawals = MarketerWithdrawalRequest::where('marketer_id', $marketer->id)
                ->where('status', 'approved')
                ->sum('requested_amount');
            
            return [
                'id' => $marketer->id,
                'full_name' => $marketer->full_name,
                'commission_rate' => $marketer->commission_rate,
                'total_commissions' => $totalCommissions,
                'total_withdrawals' => $totalWithdrawals,
                'available_balance' => $totalCommissions - $totalWithdrawals
            ];
        });

        return response()->json(['message' => 'قائمة المسوقين', 'data' => $marketers]);
    }

    public function updateCommissionRate(Request $request, $id)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100'
        ]);

        $marketer = User::findOrFail($id);
        $marketer->update(['commission_rate' => $request->commission_rate]);

        return response()->json(['message' => 'تم تحديث نسبة العمولة بنجاح', 'data' => $marketer]);
    }
}
