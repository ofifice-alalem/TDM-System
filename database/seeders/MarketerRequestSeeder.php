<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketerRequestSeeder extends Seeder
{
    public function run(): void
    {
        $requests = [
            ['invoice_number' => 'MR-2024-001', 'marketer_id' => 3, 'status' => 'pending', 'notes' => 'طلب بضاعة عاجل', 'created_at' => now()],
            ['invoice_number' => 'MR-2024-002', 'marketer_id' => 4, 'status' => 'approved', 'notes' => 'طلب شهري', 'approved_by' => 2, 'approved_at' => now(), 'created_at' => now()->subDays(1)],
            ['invoice_number' => 'MR-2024-003', 'marketer_id' => 3, 'status' => 'documented', 'notes' => null, 'approved_by' => 2, 'approved_at' => now()->subDays(2), 'documented_by' => 2, 'documented_at' => now()->subDays(1), 'created_at' => now()->subDays(3)],
            ['invoice_number' => 'MR-2024-004', 'marketer_id' => 4, 'status' => 'rejected', 'notes' => 'مخزون غير كافي', 'rejected_by' => 2, 'rejected_at' => now()->subDays(1), 'created_at' => now()->subDays(2)],
            ['invoice_number' => 'MR-2024-005', 'marketer_id' => 3, 'status' => 'pending', 'notes' => null, 'created_at' => now()],
            ['invoice_number' => 'MR-2024-006', 'marketer_id' => 4, 'status' => 'approved', 'notes' => 'طلب أسبوعي', 'approved_by' => 2, 'approved_at' => now(), 'created_at' => now()],
            ['invoice_number' => 'MR-2024-007', 'marketer_id' => 3, 'status' => 'documented', 'notes' => null, 'approved_by' => 2, 'approved_at' => now()->subDays(1), 'documented_by' => 2, 'documented_at' => now(), 'created_at' => now()->subDays(2)],
            ['invoice_number' => 'MR-2024-008', 'marketer_id' => 4, 'status' => 'cancelled', 'notes' => 'تم الإلغاء بطلب المسوق', 'created_at' => now()->subDays(1)],
        ];

        DB::table('marketer_requests')->insert($requests);

        $items = [
            ['request_id' => 1, 'product_id' => 1, 'quantity' => 50],
            ['request_id' => 1, 'product_id' => 2, 'quantity' => 30],
            ['request_id' => 2, 'product_id' => 3, 'quantity' => 100],
            ['request_id' => 2, 'product_id' => 4, 'quantity' => 75],
            ['request_id' => 3, 'product_id' => 1, 'quantity' => 40],
            ['request_id' => 4, 'product_id' => 5, 'quantity' => 200],
            ['request_id' => 5, 'product_id' => 2, 'quantity' => 60],
            ['request_id' => 6, 'product_id' => 3, 'quantity' => 80],
        ];

        DB::table('marketer_request_items')->insert($items);
    }
}
