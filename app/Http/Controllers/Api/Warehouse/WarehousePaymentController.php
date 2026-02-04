<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\StoreDebtLedger;
use App\Models\MarketerCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehousePaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('store_payments')
            ->join('stores', 'store_payments.store_id', '=', 'stores.id')
            ->join('users as marketer', 'store_payments.marketer_id', '=', 'marketer.id')
            ->join('users as keeper', 'store_payments.keeper_id', '=', 'keeper.id')
            ->select('store_payments.*', 'stores.name as store_name', 'marketer.full_name as marketer_name', 'keeper.full_name as keeper_name');

        // Filter by status
        if ($request->has('status')) {
            $query->where('store_payments.status', $request->status);
        }

        // Filter by marketer
        if ($request->has('marketer_id')) {
            $query->where('store_payments.marketer_id', $request->marketer_id);
        }

        // Filter by store
        if ($request->has('store_id')) {
            $query->where('store_payments.store_id', $request->store_id);
        }

        // Filter by payment method
        if ($request->has('payment_method')) {
            $query->where('store_payments.payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('store_payments.created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('store_payments.created_at', '<=', $request->to_date);
        }

        $payments = $query->orderBy('store_payments.created_at', 'desc')->paginate(20);

        return response()->json(['message' => 'قائمة إيصالات القبض', 'data' => $payments]);
    }

    public function show($id)
    {
        $payment = DB::table('store_payments')
            ->join('stores', 'store_payments.store_id', '=', 'stores.id')
            ->join('users as marketer', 'store_payments.marketer_id', '=', 'marketer.id')
            ->join('users as keeper', 'store_payments.keeper_id', '=', 'keeper.id')
            ->where('store_payments.id', $id)
            ->select('store_payments.*', 'stores.name as store_name', 'marketer.full_name as marketer_name', 'keeper.full_name as keeper_name')
            ->first();

        if (!$payment) {
            return response()->json(['message' => 'إيصال القبض غير موجود'], 404);
        }

        if ($payment->receipt_image) {
            $payment->receipt_image = asset('storage/' . $payment->receipt_image);
        }

        $commission = DB::table('marketer_commissions')
            ->where('payment_id', $id)
            ->first();

        return response()->json(['message' => 'تفاصيل إيصال القبض', 'data' => ['payment' => $payment, 'commission' => $commission]]);
    }

    public function approve(Request $request, $id)
    {
        $request->validate(['receipt_image' => 'required|image|max:10240']);

        DB::beginTransaction();
        try {
            $payment = DB::table('store_payments')->where('id', $id)->where('status', 'pending')->first();

            if (!$payment) {
                return response()->json(['message' => 'إيصال القبض غير موجود أو تم معالجته مسبقاً'], 404);
            }

            $imagePath = null;
            if ($request->hasFile('receipt_image')) {
                $directory = "receipts/{$payment->payment_number}";
                $imagePath = $request->file('receipt_image')->store($directory, 'public');
            }

            StoreDebtLedger::create([
                'store_id' => $payment->store_id,
                'entry_type' => 'payment',
                'payment_id' => $id,
                'amount' => -$payment->amount
            ]);

            $marketer = DB::table('users')->where('id', $payment->marketer_id)->first();

            if ($marketer->commission_rate > 0) {
                $commissionAmount = $payment->amount * ($marketer->commission_rate / 100);

                MarketerCommission::create([
                    'marketer_id' => $payment->marketer_id,
                    'store_id' => $payment->store_id,
                    'keeper_id' => auth()->id(),
                    'payment_amount' => $payment->amount,
                    'payment_id' => $id,
                    'commission_rate' => $marketer->commission_rate,
                    'commission_amount' => $commissionAmount
                ]);
            }

            DB::table('store_payments')->where('id', $id)->update([
                'status' => 'approved',
                'receipt_image' => $imagePath,
                'confirmed_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم توثيق إيصال القبض بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء التوثيق', 'error' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string|max:1000']);

        DB::beginTransaction();
        try {
            $payment = DB::table('store_payments')->where('id', $id)->where('status', 'pending')->first();

            if (!$payment) {
                return response()->json(['message' => 'إيصال القبض غير موجود أو تم معالجته مسبقاً'], 404);
            }

            DB::table('store_payments')->where('id', $id)->update([
                'status' => 'rejected',
                'notes' => $request->notes
            ]);

            DB::commit();
            return response()->json(['message' => 'تم رفض إيصال القبض بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء الرفض'], 500);
        }
    }
}
