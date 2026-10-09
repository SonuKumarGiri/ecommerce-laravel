# E-Commerce Order & Inventory Management System

## Project Description
A complete full-stack web application built with Laravel 12. It features a complete customer-facing application for browsing products, managing a shopping cart, and checking out, alongside a robust Admin panel for managing products, categories, orders, and users. 

## Requirements
- PHP 8.2
- Laravel 12
- MySQL
- Node.js & NPM (for Vite assets)

## Application Modules

### 1. Admin Panel
- **Dashboard:** Overview of total users, products, orders, sales, and a 7-day revenue chart.
- **Categories Management:** Create, read, update, delete, and toggle activation status for categories.
- **Products Management:** Complete CRUD with Dropify image upload/removal, stock management, status toggling, and category/status filtering via Select2.
- **Order Management:** View orders, update order status (Placed, Confirmed, Processing, Shipped, Delivered, Cancelled), and process refunds.
- **Users Management:** View registered customers, edit their details, or remove them.
- **Reports:** Detailed system reports and analytics.
- **Notifications:** Real-time system notifications for new orders and low stock.
- **Settings:** Manage global application settings.

### 2. Customer Application
- **Authentication:** Secure Registration, Login, and Logout functionality.
- **Product Catalog:** Browse products, search by name, filter by category/price, and sort (price/newest) dynamically without page reloads (AJAX/Fetch).
- **Product Details:** View detailed information, stock status, and add items to the cart.
- **Shopping Cart:** Add/remove items, adjust quantities, guest-to-user cart migration upon login, and dynamically view subtotal/total calculation without exceeding available stock limits.
- **Checkout:** Validate shipping details, choose payment method (COD or Online). Order creation handles stock reduction and transactions safely with strictly server-side authoritative price calculation (immune to client-side price tampering).
- **Order History:** View past orders, check order status, and view detailed invoices.
- **Order Cancellation & Stock Restoration:** Customers and Admins can safely cancel orders (prior to shipment). Powered by a centralized `OrderService` with database transactions and pessimistic row-locking (`lockForUpdate`), preventing race conditions, blocking double-cancellations, automatically restoring inventory, and triggering payment refunds.
- **Profile Management:** Update personal information.

## Installation Steps
1. Clone the repository
2. Run `composer install`
3. Run `npm install`
4. Run `npm run build`

## Environment Configuration
Copy `.env.example` to `.env` and configure your database and queue connection settings.
```bash
cp .env.example .env
php artisan key:generate
```
Set `QUEUE_CONNECTION=database` in your `.env`.

## Database Setup
1. Create a MySQL database matching your `.env` configuration.
2. Run migrations:
```bash
php artisan migrate
```

## Seeder Command
To populate the database with an admin user, categories, and sample products:
```bash
php artisan db:seed
```

## Queue Setup
The application uses background jobs for sending order confirmations. Start the queue worker using:
```bash
php artisan queue:work
```

## How to run the application
Start the local development server:
```bash
php artisan serve
```
If you are developing frontend assets, you can run:
```bash
npm run dev
```

## Test Command
To execute the automated tests (feature and unit tests for authentication, products, cart, checkout, and API endpoints):
```bash
php artisan test --filter EcommerceAssessmentTest
```

## API Testing (Postman)
A fully configured Postman collection has been included in the root directory: `postman_collection.json`.
1. Open Postman.
2. Click **Import** and select `postman_collection.json`.
3. Set your `base_url` variable to `http://localhost:8000` or your Laragon URL.
4. Run the **Login** request to receive an authentication token.
5. Copy the token into the collection's `token` variable to test all protected routes (Cart, Orders, Payment).

## Demo User Credentials
After running the seeders (`php artisan db:seed`), the following test accounts are readily available:

### 1. Admin Account
- **Role:** Administrator (full access to Admin Dashboard, Categories, Products, Orders, Users, Reports, Settings)
- **Email:** `admin@example.com`
- **Password:** `password`
- **Access URL:** `/admin` or `/login`

### 2. Customer Account
- **Role:** Customer (browse products, cart management, checkout, order history, profile)
- **Email:** `customer@example.com`
- **Password:** `password`
- **Access URL:** `/login`

*Note: New customers can also register directly at `/register`.*

## API Documentation
The application provides RESTful APIs utilizing Laravel Sanctum for authentication.

**Public APIs:**
- `POST /api/login` - Authenticate a user
- `POST /api/register` - Register a new user
- `GET /api/categories` (or `/api/get-categories`) - List all categories
- `GET /api/categories/{category}` (or `/api/get-category-details/{category}`) - Get category details
- `GET /api/products` (or `/api/get-products`) - List all products
- `GET /api/products/{product}` (or `/api/get-product-details/{product}`) - Get product details

**Protected APIs (Requires Bearer Token):**
- `GET /api/user` - Get authenticated user
- `POST /api/logout` - Logout user
- `GET /api/cart` (or `/api/get-cart`) - View current user's cart
- `POST /api/cart` (or `/api/add-to-cart`) - Add an item to cart
- `PUT /api/cart/{id}` (or `/api/update-cart-item/{id}`) - Update cart item quantity
- `DELETE /api/cart/{id}` (or `/api/remove-cart-item/{id}`) - Remove item from cart
- `GET /api/orders` (or `/api/my-orders`) - View user's order history
- `GET /api/orders/{order}` (or `/api/my-orders/{order}`) - View specific order
- `POST /api/orders` (or `/api/place-order`) - Place an order from current cart
- `POST /api/orders/{order}/cancel` (or `/api/cancel-order/{order}`) - Cancel an order
- `POST /api/payment/process` - Process simulated payment for an order

Note: Pass the authentication token in the request headers:
`Authorization: Bearer <your_token>`

## Database Optimization & Scaling Strategy

### Selected Indexes
Frequently queried columns have been properly indexed to ensure fast query execution:
- `users.email`: Indexed (unique) for fast login/authentication lookups.
- `categories.slug`: Indexed (unique) for rapid frontend route resolution.
- `products.category_id`: Foreign key indexed for fast JOINs and category filtering.
- `products.status`: Indexed for rapid frontend filtering (where `status = active`).
- `orders.user_id`: Foreign key indexed to quickly fetch a customer's order history.
- `orders.order_number`: Indexed (unique) for rapid order tracking lookups.
- `orders.status` & `orders.created_at`: Indexed for the Admin dashboard reporting queries and charts.

### Scaling to Millions of Records
If the database scales to **10 million products, 50 million orders, and 100 million order_items**, the following optimizations would be required:
1. **Database Sharding/Partitioning:** Partition the `orders` and `order_items` tables by `created_at` (e.g., monthly partitions) so that historical queries do not scan the entire dataset.
2. **Read/Write Replicas:** Implement MySQL Master-Slave replication. Direct heavy reporting and catalog read queries to the slave nodes, leaving the master node dedicated solely to writes (like checkout/payments).
3. **Caching Layer (Redis):** Cache heavily accessed data like the Product Catalog and Category list. Caching the results of `Product::with('category')->where('status', 'active')->paginate()` would drastically reduce database I/O.
4. **Elasticsearch / Meilisearch:** Offload text-based `LIKE '%search%'` queries for products to a dedicated search engine instead of using slow SQL full-table scans.
5. **Background Analytics:** Instead of calculating dashboard metrics (`SUM(total_amount)`, `COUNT(*)`) dynamically on page load, dispatch scheduled background jobs (Cron/Queues) to calculate and store these aggregates into a dedicated `daily_reports` table.
