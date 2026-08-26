USE catering_db;

-- Add a role so the coupon-management page can be restricted to administrators.
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS role ENUM('customer', 'admin')
    NOT NULL DEFAULT 'customer';

-- The first existing user is used as the demonstration administrator.
UPDATE users
SET role = 'admin'
WHERE user_id = 1;

-- Review requires an event_id. This table is created only when it does not exist.
CREATE TABLE IF NOT EXISTS event (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(50) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(150) NOT NULL,
    total_cost DECIMAL(10, 2) NOT NULL DEFAULT 0,
    number_of_guests INT NOT NULL DEFAULT 1
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    payment_method ENUM('Cash', 'Card', 'Mobile Banking') NOT NULL,
    payment_status ENUM('Pending', 'Successful', 'Failed')
        NOT NULL DEFAULT 'Pending',
    CHECK (amount >= 0)
) ENGINE = InnoDB;

-- This relationship table connects Payment to the team's existing Order table.
CREATE TABLE IF NOT EXISTS order_payment (
    order_id INT NOT NULL,
    payment_id INT NOT NULL,
    PRIMARY KEY (order_id, payment_id),
    UNIQUE KEY one_payment_per_order (order_id),
    UNIQUE KEY one_order_per_payment (payment_id),
    CONSTRAINT fk_order_payment_order
        FOREIGN KEY (order_id)
        REFERENCES orders(order_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_order_payment_payment
        FOREIGN KEY (payment_id)
        REFERENCES payment(payment_id)
        ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    Customer_id INT NOT NULL,
    event_id INT NOT NULL,
    rating TINYINT NOT NULL,
    comment VARCHAR(500),
    review_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY one_review_per_customer_event (Customer_id, event_id),
    CONSTRAINT fk_review_customer
        FOREIGN KEY (Customer_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_review_event
        FOREIGN KEY (event_id)
        REFERENCES event(event_id)
        ON DELETE CASCADE,
    CHECK (rating BETWEEN 1 AND 5)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS discount_coupon (
    coupon_id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_code VARCHAR(30) NOT NULL,
    discount_amount DECIMAL(10, 2) NOT NULL,
    expiry_date DATE NOT NULL,
    coupon_status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    UNIQUE KEY unique_coupon_code (coupon_code),
    CHECK (discount_amount > 0)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS coupon_orders (
    coupon_id INT NOT NULL,
    order_id INT NOT NULL,
    PRIMARY KEY (coupon_id, order_id),
    UNIQUE KEY one_coupon_per_order (order_id),
    CONSTRAINT fk_coupon_orders_coupon
        FOREIGN KEY (coupon_id)
        REFERENCES discount_coupon(coupon_id),
    CONSTRAINT fk_coupon_orders_order
        FOREIGN KEY (order_id)
        REFERENCES orders(order_id)
        ON DELETE CASCADE
) ENGINE = InnoDB;

INSERT IGNORE INTO event (
    event_id,
    event_type,
    event_date,
    location,
    total_cost,
    number_of_guests
)
VALUES
    (1, 'Wedding', '2026-08-10', 'Dhaka Community Centre', 25000.00, 150),
    (2, 'Corporate', '2026-08-18', 'BRAC University Auditorium', 18000.00, 90);

INSERT IGNORE INTO discount_coupon (
    coupon_code,
    discount_amount,
    expiry_date,
    coupon_status
)
VALUES
    ('SAVE100', 100.00, '2027-12-31', 'Active'),
    ('OLD50', 50.00, '2025-01-01', 'Active'),
    ('OFF200', 200.00, '2027-12-31', 'Inactive');
