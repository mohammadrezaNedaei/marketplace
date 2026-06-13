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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('seller_id')
                    ->constrained('users')
                    ->onDelete('cascade');

            $table->foreignId('category_id')
                    ->constrained('categories')
                    ->onDelete('cascade');

            $table->string('picture_url', 500);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('file_url', 500)->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('sales_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
