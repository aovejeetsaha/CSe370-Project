# CSE370 Catering Project - Mehraj's Features

This is a separate integration copy of the team project. It adds Payment, Review, and Discount Coupon without refactoring unrelated team features.

Payment is simulated for a classroom demonstration. No real money, card details, PIN, or external payment gateway is used.

## 1. Install with XAMPP

1. Open the XAMPP Control Panel.
2. Start **Apache** and **MySQL**.
3. Copy the `Catering` folder into `C:\xampp\htdocs\Catering`.
4. Open `http://localhost/phpmyadmin`.
5. Import `catering_db.sql` first. This is the team's original database dump.
6. Import `mehraj_features_migration.sql` second. This adds only the new feature tables and sample data.
7. Open `http://localhost/Catering/login.php`.

## 2. Demo accounts

The migration makes the existing user with `user_id = 1` the demonstration administrator. Use the login information already present in the original team database.

To demonstrate a normal customer, create another account through:

`http://localhost/Catering/signup.php`

## 3. Feature pages

- Coupon entry: `http://localhost/Catering/cart.php`
- Payment: opens automatically after an order; old receipts can be opened from Orders
- Reviews: `http://localhost/Catering/reviews.php`
- Coupon Admin: `http://localhost/Catering/coupon_admin.php`

## 4. Sample data

- Valid coupon: `SAVE100`
- Expired coupon: `OLD50`
- Inactive coupon: `OFF200`
- Two sample events are included for review demonstrations.

## 5. Recommended showcase

1. Log in and add menu items to the cart.
2. Apply `SAVE100` and explain subtotal, discount, and final total.
3. Try `OLD50` and `OFF200` to show validation.
4. Confirm the order and choose a simulated payment method.
5. Show the receipt and the `payment` and `order_payment` rows in phpMyAdmin.
6. Open Reviews and submit a rating and comment.
7. Submit another review for the same event to show duplicate prevention.
8. Log in as the first user and open Coupon Admin.
9. Create a new coupon and change its status.

## 6. How the feature flow works

The browser submits an HTML form to a PHP page. PHP validates the values, gets the logged-in user ID from the session, sends a prepared SQL query through `db.php`, receives the MariaDB result, and creates the updated HTML page.

## 7. Resetting the feature data

For a full clean reset, import `catering_db.sql` again and then import `mehraj_features_migration.sql` again. The original database dump may replace existing team records, so export anything important first.

## 8. Common problems

- **Database connection error:** Start MySQL and confirm that the database is named `catering_db`.
- **Page not found:** Confirm the folder is exactly `C:\xampp\htdocs\Catering`.
- **Feature table missing:** Import `mehraj_features_migration.sql` after the original SQL.
- **Coupon Admin denied:** Log in as the original user with `user_id = 1`.
- **Foreign-key error:** Import the files in the stated order and do not import individual table fragments.
