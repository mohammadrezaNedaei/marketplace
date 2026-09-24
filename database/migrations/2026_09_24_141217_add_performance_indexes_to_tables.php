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
        // Orders table - optimize user lookups and status filtering
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });

        // Wallet transactions - optimize user history and order lookups
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('order_id');
            $table->index(['user_id', 'created_at']);
        });

        // Products - optimize listing queries and seller/category filtering
        Schema::table('products', function (Blueprint $table) {
            $table->index('seller_id');
            $table->index('category_id');
            $table->index('status');
            $table->index(['status', 'created_at']);
            $table->index(['seller_id', 'status']);
            $table->index(['category_id', 'status']);
        });

        // Reviews - optimize product reviews and user reviews
        Schema::table('reviews', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('user_id');
            $table->index(['product_id', 'approved']);
        });

        // Withdrawal requests - optimize admin queries
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
        });

        // Card transfer requests - optimize admin queries
        Schema::table('card_transfer_requests', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_user_id_index');
            $table->dropIndex('orders_user_id_status_index');
            $table->dropIndex('orders_created_at_index');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex('wallet_transactions_user_id_index');
            $table->dropIndex('wallet_transactions_order_id_index');
            $table->dropIndex('wallet_transactions_user_id_created_at_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_seller_id_index');
            $table->dropIndex('products_category_id_index');
            $table->dropIndex('products_status_index');
            $table->dropIndex('products_status_created_at_index');
            $table->dropIndex('products_seller_id_status_index');
            $table->dropIndex('products_category_id_status_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_product_id_index');
            $table->dropIndex('reviews_user_id_index');
            $table->dropIndex('reviews_product_id_approved_index');
        });

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropIndex('withdrawal_requests_user_id_index');
            $table->dropIndex('withdrawal_requests_status_index');
        });

        Schema::table('card_transfer_requests', function (Blueprint $table) {
            $table->dropIndex('card_transfer_requests_user_id_index');
            $table->dropIndex('card_transfer_requests_status_index');
        });
    }
};
