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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                    ->constrained('users')
                    ->onDelete('cascade');

            $table->foreignId('product_id')
                    ->constrained('products')
                    ->onDelete('cascade');
            
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('amount', 10, 2);

            $table->enum('status', [
                'pending',
                'paid',
                'delivered',
                'cancelled'
            ])->default('pending');
            
            $table->string('payment_gateway', 50)->nullable();
            $table->string('transaction_id', 100)->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
