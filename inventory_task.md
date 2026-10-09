# **E-Commerce Order & Inventory Management System** 

**Objective:-** Build a complete full-stack web application. The objective is to evaluate backend development, frontend development, database design, API development, authentication, validation, security, JavaScript, and overall application architecture. The application should have both: 

1. Customer-facing application 

2. Admin panel 

## **1. Technology Requirements** 

- PHP 8+ 

- Laravel 

- MySQL 

- HTML5 

- CSS3 

- JavaScript 

- Bootstrap or Tailwind CSS 

- AJAX/Fetch/jQuery as required 

## **2. Authentication** 

   - Implement proper authentication and authorization. 

   - Admin pages must not be accessible to customers. 

- Implement authentication for two types of users: 

### a. Admin 

   - Admin can: 

      - Login 

      - Logout 

      - Manage products 

      - Manage categories 

      - Manage users 

      - View orders 

      - Update order status 

      - View dashboard 

      - View reports 

- b. Customer 

   - Customer can: 

      - Register 

      - Login 

      - Logout 

      - View products 

      - Search products 

      - Filter products 

      - Add products to cart 

      - Update cart 

      - Place orders 

      - View order history 

      - View order details 

      - Update profile 

## **2. Database Design** 

- Create appropriate migrations and relationships. 

- Use proper foreign keys and indexes. 

- Minimum tables: users categories products product_images carts cart_items orders order_items payments 

- Candidate can add additional tables if required. 

### * Products 

- Minimum fields: id category_id name slug description price stock status created_at updated_at 

### * Orders 

- Minimum fields: id order_number user_id total_amount status payment_status shipping_address created_at updated_at 

## **3. Admin Panel** 

- Create an admin dashboard. 

- Dashboard should display: 

   - Total users 

   - Total products 

   - Total orders 

   - Pending orders 

   - Successful orders 

   - Failed orders 

   - Total sales 

- Also display a sales report/chart based on order data. 

## **4. Category Management** 

- Admin should be able to: 

   - Create category 

   - Edit category 

   - Delete category 

   - Activate/deactivate category 

   - View category list 

   - Search categories 

- Implement validation. 

- Category slug should be unique. 

## **5. Product Management** 

- Admin should be able to: 

   - Create product 

   - Edit product 

   - Delete product 

   - Activate/deactivate product 

   - Upload product image 

   - Remove product image 

   - Manage stock 

   - Search products 

   - Filter by category/status 

- Product fields: 

Name Category Description Price Stock Status Image 

- Use proper server-side validation. 

## **6. Customer Product Listing** 

- Create a customer-facing product page. 

- Features: 

   - Product listing 

   - Product details 

- Search - Category filter 

- Price filter - Sorting - Pagination - Example: Search: iPhone Category: Electronics 

Price: Min: 10000 Max: 100000 Sort: Price Low →High Price High →Low 

Newest 

- Filtering should happen without unnecessarily reloading the entire page where practical. 

## **7. Shopping Cart** 

- Implement a complete shopping cart. 

- Customer should be able to: 

   - Add product 

   - Remove product 

   - Increase quantity 

   - Decrease quantity 

   - View cart 

   - View subtotal 

   - View total 

- Example: 

Product Qty Price Total Laptop 2 50000 100000 Mouse 1 1000 1000 Subtotal: 101000 

- Important: Do not allow the customer to purchase more quantity than available stock. 

## **8. Checkout** 

- Create checkout page. 

- Customer should enter: Name Mobile Email Address City State Pincode Payment Method 

- Payment methods: COD ONLINE 

- Validate all required fields. 

## **9. Order Creation** 

- When customer places an order: 

   1. Validate cart. 

   2. Verify product availability. 

   3. Calculate total amount on the server. 

   4. Create order. 

   5. Create order items. 

   6. Reduce product stock. 

   7. Clear cart. 

   8. Create payment record. 

   9. Return order confirmation. 

- The complete order creation process must use a database transaction. 

- If any operation fails, the complete transaction should rollback. 

## **10. Payment Simulation** 

- No real payment gateway is required. 

- Create a simulated payment API: 

POST /api/payment/process Request: json { "order_id": 101, "amount": 50000 } - Response should simulate: SUCCESS FAILED - Payment status: PENDING SUCCESS FAILED REFUNDED 

- Implement proper handling for payment failure. 

## **11. Order Management** 

- Customer can view: 

- My Orders Display: Order Number Order Date Amount Payment Status Order Status 

- Customer can open an order and view: Products Quantity Price Total Shipping Address Payment Status Order Status - Admin can update order status: PLACED CONFIRMED PROCESSING SHIPPED DELIVERED CANCELLED 

## **12. Order Cancellation** 

- Customer can cancel an order only when allowed. 

- For example: PLACED CONFIRMED - Cancellation should not be allowed after: SHIPPED DELIVERED - When an order is cancelled: 

- Restore product stock. 

- Update order status. 

- Update payment status where applicable. 

- This operation must be handled safely using a database transaction. 

## **13. AJAX / JavaScript Requirements** 

- Use JavaScript/AJAX/Fetch for selected operations. 

- At minimum: 

Cart quantity update When quantity changes: User changes quantity ↓ 

AJAX request ↓ 

Server validates stock ↓ Updated total returned ↓ UI updates without full page reload 

- Product search/filter 

- Implement dynamic filtering using AJAX/Fetch where appropriate. 

- Display a loading indicator while the request is processing. 

- Handle validation/error messages on the frontend. 

## **14. REST API** 

- Create APIs for important functionality. 

- Minimum: 

POST /api/login POST /api/register GET /api/products GET /api/products/{id} POST /api/cart GET /api/cart PUT /api/cart/{id} DELETE /api/cart/{id} POST /api/orders GET /api/orders GET /api/orders/{id} POST /api/payment/process - Use Laravel API Resources where appropriate. 

- Return proper JSON responses and HTTP status codes. 

## **15. API Authentication** 

- Use Laravel Sanctum or another appropriate token-based authentication mechanism. 

- Protected APIs should reject unauthenticated requests. 

- Implement appropriate authorization so that: 

- Customer cannot access another customer's orders. 

- Customer cannot access admin APIs. 

- Admin-only APIs are protected. 

## **16. Validation** 

- Use Laravel Form Request classes where appropriate. 

- Examples: - Product name →required price →numeric, greater than 0 stock →integer, minimum 0 category →valid category 

- Registration name email password password_confirmation - Checkout 

- Validate all customer and shipping information. 

- Validation errors should be returned/displayed properly. 

## **17. Security Requirements** 

- Candidate should implement appropriate security practices. 

- Consider: 

   - CSRF protection 

   - XSS prevention 

   - SQL injection prevention 

   - Authentication 

   - Authorization 

   - Password hashing 

   - Input validation 

   - File upload validation 

   - Secure API responses 

- Do not trust price/amount values coming from the frontend. 

- The final order amount must always be calculated on the server. 

## **18. Database Optimization** 

- Candidate should properly index frequently queried columns. 

- For example: 

users.email products.slug products.category_id products.status orders.user_id orders.order_number orders.status orders.created_at 

- Candidate should explain the indexes selected. 

- Also explain how they would optimize the application if the database contained: 10 million products 

   - 50 million orders 

100 million order_items 

## **19. Laravel Architecture** 

- Business logic should not be unnecessarily placed inside controllers. 

- Candidate should demonstrate appropriate use of: 

   - Controllers 

   - Models 

   - Form Requests 

   - API Resources 

   - Services 

   - Jobs/Queues 

   - Events/Listeners 

   - Middleware 

   - Policies/Gates 

   - Notifications 

- The candidate should explain their architectural decisions. 

## **20. Queue / Background Processing** 

- Implement at least one background job. 

- Example: 

After successful order creation: 

Order Created 

↓ 

Dispatch Job 

↓ 

Send Order Confirmation 

- Create: 

SendOrderConfirmationJob 

- The job can log/send an email notification. 

- Candidate should explain: 

   - Queue configuration 

   - Retry 

   - Failed jobs 

   - Job timeout 

## **21. Notifications** 

- After successful order creation, send a notification to the customer. 

- Notification can be: 

Email or Laravel's notification system. 

- Example: 

Subject: Order ORD-10001 Confirmed 

## **22. Error Handling & Logging** 

- Implement proper exception handling. 

- Important operations should be logged. 

- Example: 

Order creation failed Payment failed Stock update failed 

Webhook/API failure 

- Do not expose sensitive internal errors to the customer. 

- Return user-friendly error messages. 

## **23. Testing** 

- Write tests for important functionality. 

- Minimum tests: 

User registration User login Product creation Product validation Product listing Add to cart Cart quantity update Order creation Insufficient stock Order cancellation Payment success Payment failure Authorization 

- Use Laravel Feature/Unit tests appropriately. 

## **24. UI Requirements** 

- The application should be responsive. 

- Minimum screens: 

Customer 

Login Register Home Product Listing Product Details Cart Checkout Order Success My Orders Order Details Profile Admin Login Dashboard Categories Products Users Orders Order Details Reports 

- The UI does not need to be highly artistic, but it should be clean, usable and responsive. 

## **26. README** 

- Provide a README containing: 

   - Project description 

   - Requirements 

   - Installation steps 

   - Environment configuration 

   - Database setup 

   - Migration command 

   - Seeder command 

   - Queue setup 

   - How to run the application 

   - Test command 

   - Admin credentials 

   - API documentation 

- Submit: 

   1. Complete Laravel project 

   2. Database migrations 

   3. Seeders/factories 

   4. Frontend implementation 

   5. REST APIs 

   6. Authentication 

   7. Admin panel 

   8. Customer panel 

   9. Automated tests 

   10. Postman collection/API documentation 

   11. README 

   12. Git repository 

- Time Limit 

   - Recommended timeline: 2–4 days 

