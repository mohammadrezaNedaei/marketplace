# مارکت‌پلیس — Instagram + Store

A full-stack Laravel marketplace combining Instagram-style product posting with e-commerce functionality. Sellers post products, buyers browse/search/save/purchase, and admins manage users, products, and support tickets.

Persian-language app with RTL layout, built with Laravel, Blade, Tailwind CSS, and Alpine.js.

---

## Requirements

| Tool     | Version |
|----------|---------|
| PHP      | >= 8.2  |
| Composer | >= 2.x  |
| MySQL    | >= 8.0  |
| Node.js  | >= 18.x |

---

## Setup

```bash
# 1. Clone / navigate to the project
cd marketplace

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Configure .env with your database credentials
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=marketplace
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Create the database
mysql -u root -p -e "CREATE DATABASE marketplace;"

# 8. Run migrations
php artisan migrate

# 9. Link storage (for uploaded product images/files)
php artisan storage:link

# 10. Seed the database (creates admin/seller/buyer test accounts + categories)
php artisan db:seed
```

### Running the app

```bash
composer run dev
```

Visit `http://127.0.0.1:8000`.

### Seeded accounts

`php artisan db:seed` creates these accounts for you:

| Role   | Username      | Password    |
|--------|---------------|-------------|
| Admin  | `ادمین تست`   | `admin123`  |
| Seller | `فروشنده تست` | `seller123` |
| Buyer  | `خریدار تست`  | `buyer123`  |


⚠️ Change or remove these credentials before deploying anywhere public.

---

## User Roles

### Seller (فروشنده)
- Create/edit/delete products (image, title, description, price, discount price, optional digital file)
- Toggle product status (active/inactive)
- View per-product views and sales count
- Analytics dashboard: total views, sales, this-month orders, per-product chart

### Buyer (خریدار)
- Explore products with live search, category filter, and sort
- Save/unsave products
- Purchase products (fake payment flow — instant "paid" status)
- Download digital files after purchase
- Buyer dashboard: purchase history + saved products
- Leave reviews (rating + comment) with reply support

### Admin (ادمین)
- Dashboard: user/product/ticket stats + recent activity feed
- Manage all users: search, filter by role, edit, delete
- Manage all products: search, filter by status, edit, delete
- Support ticket system: view all tickets, filter by status, reply, change status

---

## Database Schema

| Table              | Purpose |
|---------------------|---------|
| `users`             | username, phone, password, role (buyer/seller/admin) |
| `categories`        | product categories |
| `products`          | seller's listings — price, discount, image, file, views, sales |
| `orders`            | purchases — quantity, amount, status, payment gateway, transaction id |
| `reviews`           | ratings + comments, supports nested replies via `answer_to_id` |
| `likes`             | unique per (product, user) |
| `saves`             | unique per (product, user) |
| `support_tickets`   | subject + status (open/answered/closed) |
| `ticket_messages`   | conversation thread per ticket, any admin can reply |
| `product_views`     | tracks logged-in user views to prevent duplicate view counts |
