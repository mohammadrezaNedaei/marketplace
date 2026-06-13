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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                    ->constrained('products')
                    ->onDelete('cascade');

            $table->foreignId('user_id')
                    ->constrained('users')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('answer_to_id')->nullable();
            $table->foreign('answer_to_id')
                    ->references('id')
                    ->on('reviews')
                    ->onDelete('set null');

            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('approved')->default(false);

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
