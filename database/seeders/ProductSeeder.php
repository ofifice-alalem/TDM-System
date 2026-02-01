<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\MainStock;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'منتج تجريبي 1',
                'barcode' => 'PRD001',
                'description' => 'وصف المنتج التجريبي الأول',
                'current_price' => 100.00,
                'is_active' => true,
                'stock' => 500,
            ],
            [
                'name' => 'منتج تجريبي 2',
                'barcode' => 'PRD002',
                'description' => 'وصف المنتج التجريبي الثاني',
                'current_price' => 150.00,
                'is_active' => true,
                'stock' => 300,
            ],
            [
                'name' => 'منتج تجريبي 3',
                'barcode' => 'PRD003',
                'description' => 'وصف المنتج التجريبي الثالث',
                'current_price' => 200.00,
                'is_active' => true,
                'stock' => 200,
            ],
            [
                'name' => 'منتج تجريبي 4',
                'barcode' => 'PRD004',
                'description' => 'وصف المنتج التجريبي الرابع',
                'current_price' => 75.00,
                'is_active' => true,
                'stock' => 1000,
            ],
            [
                'name' => 'منتج تجريبي 5',
                'barcode' => 'PRD005',
                'description' => 'وصف المنتج التجريبي الخامس',
                'current_price' => 250.00,
                'is_active' => true,
                'stock' => 150,
            ],
        ];

        foreach ($products as $productData) {
            $stock = $productData['stock'];
            unset($productData['stock']);

            $product = Product::create($productData);

            MainStock::create([
                'product_id' => $product->id,
                'quantity' => $stock,
            ]);
        }
    }
}
