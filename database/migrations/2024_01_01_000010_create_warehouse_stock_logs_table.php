<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('invoice_type', ['factory', 'marketer_request', 'marketer_return', 'sales_return']);
            $table->unsignedBigInteger('invoice_id');
            $table->foreignId('keeper_id')->constrained('users')->onDelete('cascade');
            $table->enum('action', ['add', 'withdraw', 'return']);
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['invoice_type', 'invoice_id']);
            $table->index('keeper_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_logs');
    }
};
