<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. الفواتير (الأكثر استخداماً)
        if (Schema::hasTable('sales_invoices')) {
            Schema::table('sales_invoices', function (Blueprint $table) {
                $table->index('created_at', 'idx_sales_created');
                $table->index('status', 'idx_sales_status');
                $table->index('marketer_id', 'idx_sales_marketer');
                $table->index('store_id', 'idx_sales_store');
                $table->index(['marketer_id', 'status'], 'idx_sales_marketer_status');
            });
        }

        // 2. طلبات المسوقين
        if (Schema::hasTable('marketer_requests')) {
            Schema::table('marketer_requests', function (Blueprint $table) {
                $table->index('created_at', 'idx_requests_created');
                $table->index('status', 'idx_requests_status');
                $table->index('marketer_id', 'idx_requests_marketer');
                $table->index(['marketer_id', 'status'], 'idx_requests_marketer_status');
            });
        }

        // 3. إرجاعات المسوقين
        if (Schema::hasTable('marketer_returns')) {
            Schema::table('marketer_returns', function (Blueprint $table) {
                $table->index('created_at', 'idx_returns_created');
                $table->index('status', 'idx_returns_status');
                $table->index('marketer_id', 'idx_returns_marketer');
            });
        }

        // 4. إيصالات القبض
        if (Schema::hasTable('store_payments')) {
            Schema::table('store_payments', function (Blueprint $table) {
                $table->index('created_at', 'idx_payments_created');
                $table->index('status', 'idx_payments_status');
                $table->index('marketer_id', 'idx_payments_marketer');
                $table->index('store_id', 'idx_payments_store');
            });
        }

        // 5. إرجاعات المتاجر
        if (Schema::hasTable('sales_returns')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->index('created_at', 'idx_store_returns_created');
                $table->index('status', 'idx_store_returns_status');
                $table->index('marketer_id', 'idx_store_returns_marketer');
                $table->index('store_id', 'idx_store_returns_store');
            });
        }

        // 6. العمولات
        if (Schema::hasTable('marketer_commissions')) {
            Schema::table('marketer_commissions', function (Blueprint $table) {
                $table->index('marketer_id', 'idx_commissions_marketer');
                $table->index('created_at', 'idx_commissions_created');
            });
        }

        // 7. سحب الأرباح
        if (Schema::hasTable('marketer_withdrawal_requests')) {
            Schema::table('marketer_withdrawal_requests', function (Blueprint $table) {
                $table->index('marketer_id', 'idx_withdrawals_marketer');
                $table->index('status', 'idx_withdrawals_status');
                $table->index('created_at', 'idx_withdrawals_created');
            });
        }

        // 8. ديون المتاجر
        if (Schema::hasTable('store_debt_ledger')) {
            Schema::table('store_debt_ledger', function (Blueprint $table) {
                $table->index('store_id', 'idx_debt_store');
                $table->index('entry_type', 'idx_debt_type');
                $table->index('created_at', 'idx_debt_created');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropIndex('idx_sales_created');
            $table->dropIndex('idx_sales_status');
            $table->dropIndex('idx_sales_marketer');
            $table->dropIndex('idx_sales_store');
            $table->dropIndex('idx_sales_marketer_status');
        });

        Schema::table('marketer_requests', function (Blueprint $table) {
            $table->dropIndex('idx_requests_created');
            $table->dropIndex('idx_requests_status');
            $table->dropIndex('idx_requests_marketer');
            $table->dropIndex('idx_requests_marketer_status');
        });

        Schema::table('marketer_returns', function (Blueprint $table) {
            $table->dropIndex('idx_returns_created');
            $table->dropIndex('idx_returns_status');
            $table->dropIndex('idx_returns_marketer');
        });

        Schema::table('store_payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_created');
            $table->dropIndex('idx_payments_status');
            $table->dropIndex('idx_payments_marketer');
            $table->dropIndex('idx_payments_store');
        });

        Schema::table('sales_returns', function (Blueprint $table) {
            $table->dropIndex('idx_store_returns_created');
            $table->dropIndex('idx_store_returns_status');
            $table->dropIndex('idx_store_returns_marketer');
            $table->dropIndex('idx_store_returns_store');
        });

        Schema::table('marketer_commissions', function (Blueprint $table) {
            $table->dropIndex('idx_commissions_marketer');
            $table->dropIndex('idx_commissions_created');
        });

        Schema::table('marketer_withdrawal_requests', function (Blueprint $table) {
            $table->dropIndex('idx_withdrawals_marketer');
            $table->dropIndex('idx_withdrawals_status');
            $table->dropIndex('idx_withdrawals_created');
        });

        Schema::table('store_debt_ledger', function (Blueprint $table) {
            $table->dropIndex('idx_debt_store');
            $table->dropIndex('idx_debt_type');
            $table->dropIndex('idx_debt_created');
        });
    }
};
