<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketerStockSeeder extends Seeder
{
    public function run(): void
    {
        $actualStock = [
            ['marketer_id' => 1, 'product_id' => 1, 'quantity' => 120],
            ['marketer_id' => 1, 'product_id' => 2, 'quantity' => 70],
            ['marketer_id' => 1, 'product_id' => 3, 'quantity' => 95],
            ['marketer_id' => 1, 'product_id' => 4, 'quantity' => 30],
            ['marketer_id' => 1, 'product_id' => 5, 'quantity' => 50],
        ];

        DB::table('marketer_actual_stock')->insert($actualStock);

        $reservedStock = [
            ['marketer_id' => 1, 'product_id' => 1, 'quantity' => 90, 'created_at' => now(), 'updated_at' => now()],
            ['marketer_id' => 1, 'product_id' => 2, 'quantity' => 85, 'created_at' => now(), 'updated_at' => now()],
            ['marketer_id' => 1, 'product_id' => 3, 'quantity' => 165, 'created_at' => now(), 'updated_at' => now()],
            ['marketer_id' => 1, 'product_id' => 4, 'quantity' => 75, 'created_at' => now(), 'updated_at' => now()],
            ['marketer_id' => 1, 'product_id' => 5, 'quantity' => 20, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('marketer_reserved_stock')->insert($reservedStock);
    }
}
