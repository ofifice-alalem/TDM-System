<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'password_hash' => Hash::make('admin123'),
                'full_name' => 'مدير النظام',
                'role_id' => 1, // admin
                'commission_rate' => null,
                'phone' => '0501234567',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'keeper1',
                'password_hash' => Hash::make('keeper123'),
                'full_name' => 'أحمد محمد - أمين المخزن',
                'role_id' => 2, // warehouse_keeper
                'commission_rate' => null,
                'phone' => '0507654321',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'salesman1',
                'password_hash' => Hash::make('sales123'),
                'full_name' => 'محمد علي - مسوق',
                'role_id' => 3, // salesman
                'commission_rate' => 2.50, // 2.5%
                'phone' => '0509876543',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'salesman2',
                'password_hash' => Hash::make('sales123'),
                'full_name' => 'خالد أحمد - مسوق',
                'role_id' => 3, // salesman
                'commission_rate' => 3.00, // 3%
                'phone' => '0501122334',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}