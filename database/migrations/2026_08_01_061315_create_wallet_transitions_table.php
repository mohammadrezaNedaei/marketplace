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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                    ->constrained('users')
                    ->onDelete('cascade');

            $table->enum('type', ['deposit', 'purchase', 'income']);
            $table->decimal('amount', 12);
            $table->string('gateway' ,50)->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->foreignId('order_id')
                    ->nullable()
                    ->constrained('orders')
                    ->onDelete('set null');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transitions');
    }
};
