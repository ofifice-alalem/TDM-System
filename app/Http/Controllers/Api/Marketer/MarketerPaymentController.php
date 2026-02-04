<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use App\Models\StoreDebtLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketerPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('store_payments')
            ->join('stores', 'store_payments.store_id', '=', 'stores.id')
            ->where('store_payments.marketer_id', $request->user()->id)
            ->select('store_payments.*', 'stores.name as store_name');

        // Filter by status
        if ($request->has('status')) {
            $query->where('store_payments.status', $request->status);
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

        $payments = $query->orderBy('store_payments.created_at', 'desc')->get();

        return response()->json(['message' => 'قائمة إيصالات القبض', 'data' => $payments]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,transfer,certified_check',
            'notes' => 'nullable|string'
        ]);

        // Check if store is active
        $store = DB::table('stores')->where('id', $request->store_id)->first();
        if (!$store || !$store->is_active) {
            return response()->json(['message' => 'المتجر غير نشط أو غير موجود'], 400);
        }

        DB::beginTransaction();
        try {
            $currentDebt = StoreDebtLedger::where('store_id', $request->store_id)->sum('amount');

            if ($currentDebt <= 0) {
                return response()->json(['message' => 'لا يوجد دين على هذا المتجر'], 400);
            }

            if ($request->amount > $currentDebt) {
                return response()->json(['message' => 'المبلغ المسدد أكبر من الدين الحالي'], 400);
            }

            $paymentNumber = 'PAY-' . date('Ymd') . '-' . str_pad(DB::table('store_payments')->count() + 1, 4, '0', STR_PAD_LEFT);

            $paymentId = DB::table('store_payments')->insertGetId([
                'payment_number' => $paymentNumber,
                'store_id' => $request->store_id,
                'marketer_id' => $request->user()->id,
                'keeper_id' => 1,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'notes' => $request->notes,
                'created_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم إنشاء إيصال القبض بنجاح', 'data' => ['id' => $paymentId, 'payment_number' => $paymentNumber]], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل إنشاء إيصال القبض', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $payment = DB::table('store_payments')->where('id', $id)->first();
        
        if (!$payment) {
            return response()->json(['message' => 'إيصال القبض غير موجود'], 404);
        }

        if ($payment->marketer_id != $request->user()->id) {
            return response()->json(['message' => 'ليس لديك صلاحية الوصول لهذا الإيصال'], 403);
        }

        $payment = DB::table('store_payments')
            ->join('stores', 'store_payments.store_id', '=', 'stores.id')
            ->join('users as keeper', 'store_payments.keeper_id', '=', 'keeper.id')
            ->where('store_payments.id', $id)
            ->select('store_payments.*', 'stores.name as store_name', 'keeper.full_name as keeper_name')
            ->first();

        if ($payment->receipt_image) {
            $payment->receipt_image = asset('storage/' . $payment->receipt_image);
        }

        $commission = DB::table('marketer_commissions')
            ->where('payment_id', $id)
            ->first();

        return response()->json(['message' => 'تفاصيل إيصال القبض', 'data' => ['payment' => $payment, 'commission' => $commission]]);
    }

    public function cancel(Request $request, $id)
    {
        $request->validate(['notes' => 'nullable|string']);

        $payment = DB::table('store_payments')->where('id', $id)->first();
        
        if (!$payment) {
            return response()->json(['message' => 'إيصال القبض غير موجود'], 404);
        }

        if ($payment->marketer_id != $request->user()->id) {
            return response()->json(['message' => 'ليس لديك صلاحية إلغاء هذا الإيصال'], 403);
        }

        if ($payment->status != 'pending') {
            return response()->json(['message' => 'يمكن إلغاء الإيصالات في حالة pending فقط'], 400);
        }

        DB::beginTransaction();
        try {

            DB::table('store_payments')->where('id', $id)->update([
                'status' => 'cancelled',
                'notes' => $request->notes
            ]);

            DB::commit();
            return response()->json(['message' => 'تم إلغاء إيصال القبض بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء إلغاء إيصال القبض'], 500);
        }
    }
}
