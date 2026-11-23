<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'is_bestseller'], 'idx_products_active_bestseller');
            $table->index(['is_active', 'is_featured'], 'idx_products_active_featured');
            $table->index(['is_active', 'deal_of_week'], 'idx_products_active_deal');
            $table->index('price', 'idx_products_price');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'idx_orders_status');
            $table->index('payment_status', 'idx_orders_payment_status');
            $table->index('return_status', 'idx_orders_return_status');
            $table->index('created_at', 'idx_orders_created_at');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('is_active', 'idx_categories_is_active');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_active_bestseller');
            $table->dropIndex('idx_products_active_featured');
            $table->dropIndex('idx_products_active_deal');
            $table->dropIndex('idx_products_price');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_payment_status');
            $table->dropIndex('idx_orders_return_status');
            $table->dropIndex('idx_orders_created_at');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_is_active');
        });
    }
};
