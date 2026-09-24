<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip for SQLite as it doesn't support MODIFY COLUMN ENUM
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('deposit', 'purchase', 'income', 'withdrawal') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip for SQLite as it doesn't support MODIFY COLUMN ENUM
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('deposit', 'purchase', 'income') NOT NULL");
    }
};
