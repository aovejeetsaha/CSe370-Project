-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2026 at 04:34 PM
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
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favorite_id`, `user_id`, `item_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`item_id`, `item_name`, `description`, `price`) VALUES
(1, 'Chicken Biryani', 'Chicken biryani with salad', 180.00),
(2, 'Beef Burger', 'Beef burger with cheese', 250.00),
(3, 'Chicken Pizza', 'Chicken pizza with extra cheese', 500.00),
(4, 'Chicken Sandwich', 'Grilled chicken sandwich', 180.00),
(5, 'Mutton Kacchi', 'Traditional mutton kacchi biryani', 280.00),
(6, 'Chicken Pasta', 'Creamy chicken pasta', 220.00),
(7, 'Beef Steak', 'Grilled beef steak with sauce', 450.00),
(8, 'French Fries', 'Crispy golden french fries', 120.00),
(9, 'Chicken Shawarma', 'Chicken shawarma with garlic sauce', 180.00);

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
(36, 1, 180.00, '2026-08-21 14:30:49');

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
(12, 36, 1, 1, 180.00);

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
(2, 'y', 'y@y.com', '$2y$10$xLlrcDD9kqXV.WgrxVsfyuvhhDlugn/r8xhjZjyHEWSSRyGjNylY6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
