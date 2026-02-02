<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE sales_invoices MODIFY COLUMN status ENUM('pending', 'approved', 'cancelled', 'rejected') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE sales_invoices MODIFY COLUMN status ENUM('pending', 'approved', 'cancelled') DEFAULT 'pending'");
    }
};
