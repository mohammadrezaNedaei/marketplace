<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('verified_purchase')->default(false)->after('rating');
            $table->index(['product_id', 'user_id', 'verified_purchase']);
        });

        // Backfill existing reviews from authoritative purchase records.
        \DB::statement("UPDATE reviews SET verified_purchase = 1 WHERE EXISTS (SELECT 1 FROM orders WHERE orders.product_id = reviews.product_id AND orders.user_id = reviews.user_id AND orders.status IN ('paid', 'delivered'))");
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'user_id', 'verified_purchase']);
            $table->dropColumn('verified_purchase');
        });
    }
};
