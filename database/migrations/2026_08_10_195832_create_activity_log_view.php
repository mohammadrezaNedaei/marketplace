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
        DB::statement("
            CREATE VIEW activity_log_view AS
            SELECT
                'user' AS type,
                id AS source_id,
                username AS actor,
                NULL AS subject,
                created_at
            FROM users

            UNION ALL

            SELECT
                'order' AS type,
                orders.id AS source_id,
                users.username AS actor,
                products.title AS subject,
                orders.created_at
            FROM orders
            JOIN users ON users.id = orders.user_id
            JOIN products ON products.id = orders.product_id

            UNION ALL

            SELECT
                'product' AS type,
                products.id AS source_id,
                users.username AS actor,
                products.title AS subject,
                products.created_at
            FROM products
            JOIN users ON users.id = products.seller_id

            UNION ALL

            SELECT
                'ticket' AS type,
                support_tickets.id AS source_id,
                users.username AS actor,
                support_tickets.subject AS subject,
                support_tickets.created_at
            FROM support_tickets
            JOIN users ON users.id = support_tickets.user_id

            UNION ALL

            SELECT
                'review' AS type,
                reviews.id AS source_id,
                users.username AS actor,
                products.title AS subject,
                reviews.created_at
            FROM reviews
            JOIN users ON users.id = reviews.user_id
            JOIN products ON products.id = reviews.product_id

            UNION ALL

            SELECT
                'withdrawal' AS type,
                withdrawal_requests.id AS source_id,
                users.username AS actor,
                CONCAT(withdrawal_requests.amount, ' تومان') AS subject,
                withdrawal_requests.created_at
            FROM withdrawal_requests
            JOIN users ON users.id = withdrawal_requests.user_id

            UNION ALL

            SELECT
                'deposit' AS type,
                wallet_transactions.id AS source_id,
                users.username AS actor,
                CONCAT(wallet_transactions.amount, ' تومان') AS subject,
                wallet_transactions.created_at
            FROM wallet_transactions
            JOIN users ON users.id = wallet_transactions.user_id
            WHERE wallet_transactions.type = 'deposit'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS activity_log_view");
    }
};
