<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketerRequestSeeder extends Seeder
{
    public function run(): void
    {
        $requests = [
            ['invoice_number' => 'MR-2024-001', 'marketer_id' => 1, 'status' => 'pending', 'notes' => 'طلب بضاعة عاجل', 'approved_by' => null, 'approved_at' => null, 'rejected_by' => null, 'rejected_at' => null, 'documented_by' => null, 'documented_at' => null, 'stamped_image' => null, 'created_at' => now(), 'updated_at' => now()],
            ['invoice_number' => 'MR-2024-002', 'marketer_id' => 1, 'status' => 'approved', 'notes' => 'طلب شهري', 'approved_by' => 2, 'approved_at' => now(), 'rejected_by' => null, 'rejected_at' => null, 'documented_by' => null, 'documented_at' => null, 'stamped_image' => null, 'created_at' => now()->subDays(1), 'updated_at' => now()],
            ['invoice_number' => 'MR-2024-003', 'marketer_id' => 1, 'status' => 'documented', 'notes' => null, 'approved_by' => 2, 'approved_at' => now()->subDays(2), 'rejected_by' => null, 'rejected_at' => null, 'documented_by' => 2, 'documented_at' => now()->subDays(1), 'stamped_image' => null, 'created_at' => now()->subDays(3), 'updated_at' => now()],
            ['invoice_number' => 'MR-2024-004', 'marketer_id' => 1, 'status' => 'rejected', 'notes' => 'مخزون غير كافي', 'approved_by' => null, 'approved_at' => null, 'rejected_by' => 2, 'rejected_at' => now()->subDays(1), 'documented_by' => null, 'documented_at' => null, 'stamped_image' => null, 'created_at' => now()->subDays(2), 'updated_at' => now()],
            ['invoice_number' => 'MR-2024-005', 'marketer_id' => 1, 'status' => 'pending', 'notes' => null, 'approved_by' => null, 'approved_at' => null, 'rejected_by' => null, 'rejected_at' => null, 'documented_by' => null, 'documented_at' => null, 'stamped_image' => null, 'created_at' => now(), 'updated_at' => now()],
            ['invoice_number' => 'MR-2024-006', 'marketer_id' => 1, 'status' => 'approved', 'notes' => 'طلب أسبوعي', 'approved_by' => 2, 'approved_at' => now(), 'rejected_by' => null, 'rejected_at' => null, 'documented_by' => null, 'documented_at' => null, 'stamped_image' => null, 'created_at' => now(), 'updated_at' => now()],
            ['invoice_number' => 'MR-2024-007', 'marketer_id' => 1, 'status' => 'documented', 'notes' => null, 'approved_by' => 2, 'approved_at' => now()->subDays(1), 'rejected_by' => null, 'rejected_at' => null, 'documented_by' => 2, 'documented_at' => now(), 'stamped_image' => null, 'created_at' => now()->subDays(2), 'updated_at' => now()],
            ['invoice_number' => 'MR-2024-008', 'marketer_id' => 1, 'status' => 'cancelled', 'notes' => 'تم الإلغاء بطلب المسوق', 'approved_by' => null, 'approved_at' => null, 'rejected_by' => null, 'rejected_at' => null, 'documented_by' => null, 'documented_at' => null, 'stamped_image' => null, 'created_at' => now()->subDays(1), 'updated_at' => now()],
        ];

        foreach ($requests as $request) {
            $id = DB::table('marketer_requests')->insertGetId($request);
            
            if ($id <= 8) {
                DB::table('marketer_request_items')->insert([
                    ['request_id' => $id, 'product_id' => 1, 'quantity' => 50],
                    ['request_id' => $id, 'product_id' => 2, 'quantity' => 30],
                ]);
            }
        }
    }
}
