<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            [
                'name' => 'متجر الشمال',
                'owner_name' => 'أحمد محمد',
                'phone' => '0501234567',
                'location' => 'الرياض - حي النخيل',
                'address' => 'شارع الملك فهد، مبنى رقم 123',
                'is_active' => true,
            ],
            [
                'name' => 'متجر الجنوب',
                'owner_name' => 'خالد علي',
                'phone' => '0507654321',
                'location' => 'جدة - حي الروضة',
                'address' => 'شارع التحلية، مبنى رقم 456',
                'is_active' => true,
            ],
            [
                'name' => 'متجر الشرق',
                'owner_name' => 'سعيد عبدالله',
                'phone' => '0509876543',
                'location' => 'الدمام - حي الفيصلية',
                'address' => 'شارع الأمير محمد، مبنى رقم 789',
                'is_active' => true,
            ],
            [
                'name' => 'متجر الغرب',
                'owner_name' => 'فهد سالم',
                'phone' => '0503456789',
                'location' => 'مكة - حي العزيزية',
                'address' => 'شارع إبراهيم الخليل، مبنى رقم 321',
                'is_active' => true,
            ],
            [
                'name' => 'متجر الوسط',
                'owner_name' => 'عبدالرحمن حسن',
                'phone' => '0506543210',
                'location' => 'الرياض - حي العليا',
                'address' => 'شارع العليا العام، مبنى رقم 654',
                'is_active' => true,
            ],
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}
