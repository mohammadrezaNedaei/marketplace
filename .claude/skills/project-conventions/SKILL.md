---
name: project-conventions
description: Marketplace code conventions — Persian/Jalali date handling, wallet transaction rules, role middleware, enum conventions. Background knowledge for all work in this Laravel marketplace.
user-invocable: false
---

# Project Conventions

Laravel 12 marketplace (PHP 8.2), Blade + Tailwind CSS 4 + Alpine.js + Vite. Persian/Farsi UI (`APP_LOCALE=fa`), Jalali calendar display via `morilog/jalali`. MySQL database.

## Architecture

- **All routes live in a single `routes/web.php`** (~88 routes), grouped by role with middleware `['auth', 'role:admin']` / `role:seller` and `->name('admin.xxx.')` / `->name('seller.xxx.')` prefixes. Add new routes to the matching group — do not create new route files.
- **Roles**: `admin`, `seller`, `buyer` stored as a string column on `users`. Enforced by `App\Http\Middleware\CheckRole` (registered as `role:xxx`). Some controllers additionally check `Auth::user()->role` inline (e.g. `OrderController::store` aborts 403 unless buyer).
- **User activation**: `App\Http\Middleware\CheckActive` blocks inactive users (`status` column).
- **Controllers**: no form requests — validation is inline `$request->validate([...], [Persian messages])` with Farsi error/success flash messages (`->with('error', '...')` / `->with('success', '...')`).
- **No policies/permissions layer**: authorization is direct `Auth::id()` comparison or role middleware.

## Money & wallet rules (critical)

- `users.wallet_balance` is the source of truth. All balance mutations and the matching `WalletTransaction` rows MUST be wrapped in `DB::transaction(...)`.
- `wallet_transactions.type` is a MySQL ENUM: `'deposit' | 'purchase' | 'income' | 'withdrawal'`. Never invent new types without an enum migration (see `2026_08_01_120703_update_wallet_transactions_type_enum.php`).
- Buying doubles the entries: a `purchase` row for the buyer AND an `income` row for the seller, both linked via `order_id`.
- Withdrawals and card-transfer deposits are request-based with admin approval (`withdrawal_requests`, `card_transfer_requests` tables, status `pending` → `approved`/`rejected`). Never credit a balance before admin approval.
- `OrderController` marks matching reviews `verified_purchase = true` after payment.

## Dates & locale

- Display dates in views are Jalali. User-facing date inputs arrive as `Y/m/d` Jalali strings and are converted via a `jalaliToGregorian()` helper before querying (see `WalletController::index`).
- Validation messages, flash messages, and view text are in Persian. Keep new strings Persian.

## Migrations & schema

- Enum columns are changed with raw `DB::statement("ALTER TABLE ... ENUM(...)")` migrations (MySQL-specific — this project does not target SQLite/Postgres).
- `activity_log_view` is a hand-written SQL VIEW (`DB::statement("CREATE VIEW ...")` in migration `2026_08_10_195832_create_activity_log_view.php`), unioning users/orders/products/etc. Query it read-only; it has no model.

## Frontend

- Tailwind CSS 4 (via `@tailwindcss/vite` plugin) — v4 syntax (`@import "tailwindcss"` in `resources/css/app.css`), no `tailwind.config.js`.
- Alpine.js for interactivity in Blade templates. Axios for the few API endpoints (e.g. `api.products`).
- Views organized `resources/views/{admin,buyer,seller,wallet,tickets,...}/` with layouts in `resources/views/layouts/`.

## Commands

- Run tests: `composer test` or `php artisan test` (PHPUnit 11, tests in `tests/Feature`, `tests/Unit`).
- Format: `vendor/bin/pint` (Laravel Pint; runs automatically as a PostToolUse hook after Claude edits).
- Dev server: `composer dev` (concurrently runs serve + queue + vite).
