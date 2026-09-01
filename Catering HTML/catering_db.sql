-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2026 at 07:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catering_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$G46pswUMk.yVvNuaWaXfWO/5K2HujiU9E2U5I.XI15y.rHZ/VaU.S'),
(2, 'raiyan', '$2y$10$Ths2Xpk.F8ojEEipo3nYZetul0LgPGbc/tgQtHgr3Z6EmReQRJPga');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_orders`
--

CREATE TABLE `coupon_orders` (
  `coupon_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupon_orders`
--

INSERT INTO `coupon_orders` (`coupon_id`, `order_id`) VALUES
(1, 37);

-- --------------------------------------------------------

--
-- Table structure for table `discount_coupon`
--

CREATE TABLE `discount_coupon` (
  `coupon_id` int(11) NOT NULL,
  `coupon_code` varchar(30) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `expiry_date` date NOT NULL,
  `coupon_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ;

--
-- Dumping data for table `discount_coupon`
--

INSERT INTO `discount_coupon` (`coupon_id`, `coupon_code`, `discount_amount`, `expiry_date`, `coupon_status`) VALUES
(1, 'SAVE100', 100.00, '2027-12-31', 'Active'),
(2, 'OLD50', 50.00, '2025-01-01', 'Active'),
(3, 'OFF200', 200.00, '2027-12-31', 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `location` varchar(150) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `number_of_guests` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `event_type`, `event_date`, `location`, `total_cost`, `number_of_guests`) VALUES
(1, 'Wedding', '2026-08-10', 'Dhaka Community Centre', 25000.00, 150),
(2, 'Corporate', '2026-08-18', 'BRAC University Auditorium', 18000.00, 90);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `permanently_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`item_id`, `item_name`, `description`, `price`, `available`, `deleted`, `permanently_deleted`) VALUES
(1, 'Chicken Biryani', 'Chicken biryani with salad', 180.00, 1, 0, 0),
(2, 'Beef Burger', 'Beef burger with cheese', 250.00, 1, 0, 0),
(3, 'Chicken Pizza', 'Chicken pizza with extra cheese', 500.00, 1, 0, 0),
(4, 'Chicken Sandwich', 'Grilled chicken sandwich', 180.00, 1, 0, 0),
(5, 'Mutton Kacchi', 'Traditional mutton kacchi biryani', 280.00, 1, 0, 0),
(6, 'Chicken Pasta', 'Creamy chicken pasta', 220.00, 1, 0, 0),
(7, 'Beef Steak', 'Grilled beef steak with sauce', 450.00, 1, 0, 0),
(8, 'French Fries', 'Crispy golden french fries', 120.00, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `order_date`) VALUES
(1, 1, 2080.00, '2026-08-21 12:43:12'),
(2, 1, 360.00, '2026-08-21 12:45:56'),
(3, 1, 610.00, '2026-08-21 13:04:17'),
(4, 1, 2040.00, '2026-08-21 13:09:19'),
(5, 1, 250.00, '2026-07-22 18:00:00'),
(6, 1, 400.00, '2026-07-23 18:00:00'),
(7, 1, 320.00, '2026-07-24 18:00:00'),
(8, 1, 500.00, '2026-07-25 18:00:00'),
(9, 1, 280.00, '2026-07-26 18:00:00'),
(10, 1, 450.00, '2026-07-27 18:00:00'),
(11, 1, 350.00, '2026-07-28 18:00:00'),
(12, 1, 220.00, '2026-07-29 18:00:00'),
(13, 1, 390.00, '2026-07-30 18:00:00'),
(14, 1, 480.00, '2026-07-31 18:00:00'),
(15, 1, 300.00, '2026-08-01 18:00:00'),
(16, 1, 420.00, '2026-08-02 18:00:00'),
(17, 1, 270.00, '2026-08-03 18:00:00'),
(18, 1, 500.00, '2026-08-04 18:00:00'),
(19, 1, 340.00, '2026-08-05 18:00:00'),
(20, 1, 230.00, '2026-08-06 18:00:00'),
(21, 1, 460.00, '2026-08-07 18:00:00'),
(22, 1, 310.00, '2026-08-08 18:00:00'),
(23, 1, 400.00, '2026-08-09 18:00:00'),
(24, 1, 290.00, '2026-08-10 18:00:00'),
(25, 1, 350.00, '2026-08-11 18:00:00'),
(26, 1, 480.00, '2026-08-12 18:00:00'),
(27, 1, 260.00, '2026-08-13 18:00:00'),
(28, 1, 440.00, '2026-08-14 18:00:00'),
(29, 1, 300.00, '2026-08-15 18:00:00'),
(30, 1, 390.00, '2026-08-16 18:00:00'),
(31, 1, 250.00, '2026-08-17 18:00:00'),
(32, 1, 470.00, '2026-08-18 18:00:00'),
(33, 1, 330.00, '2026-08-19 18:00:00'),
(34, 1, 500.00, '2026-08-20 18:00:00'),
(35, 1, 540.00, '2026-08-21 14:28:55'),
(36, 1, 180.00, '2026-08-21 14:30:49'),
(37, 1, 980.00, '2026-08-26 19:40:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `item_id`, `quantity`, `price`) VALUES
(1, 1, 1, 6, 180.00),
(2, 1, 2, 2, 250.00),
(3, 1, 3, 1, 500.00),
(4, 2, 1, 2, 180.00),
(5, 3, 1, 1, 180.00),
(6, 3, 2, 1, 250.00),
(7, 3, 4, 1, 180.00),
(8, 4, 1, 3, 180.00),
(9, 4, 2, 2, 250.00),
(10, 4, 3, 2, 500.00),
(11, 35, 1, 3, 180.00),
(12, 36, 1, 1, 180.00),
(13, 37, 1, 6, 180.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_payment`
--

CREATE TABLE `order_payment` (
  `order_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_payment`
--

INSERT INTO `order_payment` (`order_id`, `payment_id`) VALUES
(1, 9),
(2, 10),
(30, 8),
(31, 7),
(32, 6),
(33, 5),
(34, 4),
(35, 3),
(36, 2),
(37, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_reviews`
--

CREATE TABLE `order_reviews` (
  `review_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `Customer_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('Cash','Card','Mobile Banking') NOT NULL,
  `payment_status` enum('Pending','Successful','Failed') NOT NULL DEFAULT 'Pending'
) ;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `amount`, `payment_date`, `payment_method`, `payment_status`) VALUES
(1, 980.00, '2026-08-26 19:40:30', 'Cash', 'Successful'),
(2, 180.00, '2026-08-26 19:40:41', 'Cash', 'Successful'),
(3, 540.00, '2026-08-26 19:40:46', 'Cash', 'Successful'),
(4, 500.00, '2026-08-26 19:40:50', 'Cash', 'Successful'),
(5, 330.00, '2026-08-26 19:40:54', 'Cash', 'Successful'),
(6, 470.00, '2026-08-26 19:40:58', 'Cash', 'Successful'),
(7, 250.00, '2026-08-26 19:41:01', 'Cash', 'Successful'),
(8, 390.00, '2026-08-26 19:41:06', 'Cash', 'Successful'),
(9, 2080.00, '2026-08-26 19:41:11', 'Card', 'Successful'),
(10, 360.00, '2026-08-26 19:41:18', 'Card', 'Successful');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(11) NOT NULL,
  `Customer_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`) VALUES
(1, 'raiyan', 'raiyan@raiyan.com', '$2y$10$Xh7lM6blGRdeAHqZua/3Re.H23h/6.uCh46v59JjywTxjdTOTAqUO'),
(8, 'y', 'y@y.com', '$2y$10$dpZIcCWDaIfXsqjtmQpPFu2460El4t3FGmCNBilTFQZS88/Us4mbC'),
(9, 'z', 'z@z.com', '$2y$10$SY3bWInuG8MfyPaynrm48utq9A2XfKcjs/2pRLpt4hjLWTMlb86va');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `coupon_orders`
--
ALTER TABLE `coupon_orders`
  ADD PRIMARY KEY (`coupon_id`,`order_id`),
  ADD UNIQUE KEY `one_coupon_per_order` (`order_id`);

--
-- Indexes for table `discount_coupon`
--
ALTER TABLE `discount_coupon`
  ADD PRIMARY KEY (`coupon_id`),
  ADD UNIQUE KEY `unique_coupon_code` (`coupon_code`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `order_payment`
--
ALTER TABLE `order_payment`
  ADD PRIMARY KEY (`order_id`,`payment_id`),
  ADD UNIQUE KEY `one_payment_per_order` (`order_id`),
  ADD UNIQUE KEY `one_order_per_payment` (`payment_id`);

--
-- Indexes for table `order_reviews`
--
ALTER TABLE `order_reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `one_review_per_customer_event` (`Customer_id`,`event_id`),
  ADD KEY `fk_review_event` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `discount_coupon`
--
ALTER TABLE `discount_coupon`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_reviews`
--
ALTER TABLE `order_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`);

--
-- Constraints for table `coupon_orders`
--
ALTER TABLE `coupon_orders`
  ADD CONSTRAINT `fk_coupon_orders_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `discount_coupon` (`coupon_id`),
  ADD CONSTRAINT `fk_coupon_orders_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`);

--
-- Constraints for table `order_payment`
--
ALTER TABLE `order_payment`
  ADD CONSTRAINT `fk_order_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_payment_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_customer` FOREIGN KEY (`Customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_event` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
