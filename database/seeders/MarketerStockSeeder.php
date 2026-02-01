<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketerStockSeeder extends Seeder
{
    public function run(): void
    {
        $actualStock = [
            ['marketer_id' => 3, 'product_id' => 1, 'quantity' => 40],
            ['marketer_id' => 3, 'product_id' => 2, 'quantity' => 25],
            ['marketer_id' => 3, 'product_id' => 3, 'quantity' => 60],
            ['marketer_id' => 4, 'product_id' => 1, 'quantity' => 30],
            ['marketer_id' => 4, 'product_id' => 4, 'quantity' => 50],
            ['marketer_id' => 4, 'product_id' => 5, 'quantity' => 80],
            ['marketer_id' => 3, 'product_id' => 4, 'quantity' => 45],
            ['marketer_id' => 4, 'product_id' => 2, 'quantity' => 35],
        ];

        DB::table('marketer_actual_stock')->insert($actualStock);

        $reservedStock = [
            ['marketer_id' => 3, 'product_id' => 1, 'quantity' => 50, 'created_at' => now()],
            ['marketer_id' => 3, 'product_id' => 2, 'quantity' => 30, 'created_at' => now()],
            ['marketer_id' => 4, 'product_id' => 3, 'quantity' => 100, 'created_at' => now()],
            ['marketer_id' => 4, 'product_id' => 4, 'quantity' => 75, 'created_at' => now()],
            ['marketer_id' => 3, 'product_id' => 5, 'quantity' => 20, 'created_at' => now()],
            ['marketer_id' => 4, 'product_id' => 1, 'quantity' => 40, 'created_at' => now()],
            ['marketer_id' => 3, 'product_id' => 3, 'quantity' => 55, 'created_at' => now()],
            ['marketer_id' => 4, 'product_id' => 5, 'quantity' => 65, 'created_at' => now()],
        ];

        DB::table('marketer_reserved_stock')->insert($reservedStock);
    }
}
