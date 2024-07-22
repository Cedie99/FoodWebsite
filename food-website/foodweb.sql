-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2024 at 11:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '123');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `firstname`, `lastname`, `email`, `address`, `phoneNumber`, `status`, `order_date`, `payment_method`, `comments`, `deleted`) VALUES
(1, 1, 550.00, 'Jhon Cedrick66', 'Ignacio', 'johncedrickignacio965@gmail.com', 'Garden, SMB', '09569984190', 'Pending', '2024-07-21 11:00:43', 'Cash on Delivery', '', 0),
(2, 1, 119.00, 'Jhon Cedrick66', 'Ignacio', 'johncedrickignacio965@gmail.com', 'Garden, SMB', '09569984190', 'Pending', '2024-07-21 16:43:25', 'Cash on Delivery', '', 0),
(3, 3, 218.00, 'Juan', 'Dela Cruz', 'j@gmail.com', '123 pulo', '09569984190', 'Pending', '2024-07-21 16:54:56', 'Cash on Delivery', '', 0),
(4, 1, 527.00, 'Jhon Cedrick66', 'Ignacio', 'johncedrickignacio965@gmail.com', 'Garden, SMB', '09569984190', 'Pending', '2024-07-22 05:46:32', 'Cash on Delivery', '', 0),
(5, 1, 79.00, 'Jhon Cedrick66', 'Ignacio', 'johncedrickignacio965@gmail.com', 'Garden, SMB', '09569984190', 'Pending', '2024-07-22 05:46:42', 'Cash on Delivery', '', 1),
(6, 1, 119.00, 'Jhon Cedrick66', 'Ignacio', 'johncedrickignacio965@gmail.com', 'Garden, SMB', '09569984190', 'Pending', '2024-07-22 06:25:34', 'Cash on Delivery', '', 1),
(7, 4, 515.00, 'Jay M', 'Merilo', 'jaymarccmerilo@iskolarngbayan.pup.edu.ph', 'tindahan', '09951388922', 'Pending', '2024-07-22 07:25:53', 'GCash', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `quantity`, `price`, `deleted`) VALUES
(1, 1, 'Malay Chicken Biryani', 1, 100.00, 0),
(2, 1, 'Special Bombay Biryani', 1, 150.00, 0),
(3, 1, 'Chicken Masala', 3, 100.00, 0),
(4, 2, 'Kebab Rice', 1, 119.00, 0),
(5, 3, 'Kebab Rice', 1, 119.00, 0),
(6, 3, 'Shawarma Rice', 1, 99.00, 0),
(7, 4, 'Shawarma Rice', 2, 99.00, 0),
(8, 4, 'Shawarma', 1, 79.00, 0),
(9, 4, 'Chicken Masala', 1, 100.00, 0),
(10, 4, 'Special Bombay Biryani', 1, 150.00, 0),
(11, 5, 'Shawarma', 1, 79.00, 1),
(12, 6, 'Kebab Rice', 1, 119.00, 1),
(13, 7, 'Shawarma Rice', 2, 99.00, 1),
(14, 7, 'Kebab Rice', 2, 119.00, 1),
(15, 7, 'Shawarma', 1, 79.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `recovery_phrase` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `address`, `phoneNumber`, `email`, `password`, `created_at`, `reset_token`, `reset_token_expiry`, `recovery_phrase`) VALUES
(1, 'Jhon Cedrick66', 'Ignacio', 'Ceddie', 'Garden, SMB', '09569984190', 'johncedrickignacio965@gmail.com', '$2y$10$jTeSmbhvYC3o/m4THXKzF.0ehY9qOt..WYpqy9vbCGWkl1QLPmNSK', '2024-07-21 10:00:58', '16bdde68fee47d3612b73f8bc1c8cdf19961e74ca142332c484b1a9013008f34', '2024-07-21 18:06:43', '185983382a9db93c'),
(2, 'Arvin', 'De Vera', 'Arvin', 'Caypombo', '09053474562', 'arvin@iskolarngbayan.pup.edu.ph', '$2y$10$JbIEp.eIJXgL3yj5cBoHJeTMheijdqb718mcol19a3amCiC1e5UaK', '2024-07-21 12:09:31', '898d7fa52b4d3f6c11e8523c583432f12d1d4464cc6e2edae054cf68151b7a43', '2024-07-21 15:19:37', '25642cf66a9e05f5'),
(3, 'Juan', 'Dela Cruz', 'Juanny', '123 pulo', '09569984190', 'j@gmail.com', '$2y$10$3qKlstZlH2dYRmMso6WzNuaAiXyi/e3egHRnALhOX1UU9blECLTWy', '2024-07-21 16:52:52', NULL, NULL, '07f47c74cc152889'),
(4, 'Jay M', 'Merilo', 'jay', 'bahay n', '09951388922', 'jaymarccmerilo@iskolarngbayan.pup.edu.ph', '$2y$10$/HeYkEoanavXlPWeeNzXcuJx71jjq7BnwPK4l6ReUMI3V6ubCjrcG', '2024-07-22 07:20:41', 'd835b17974456ae99db1413abd0d2aae8974bc413ef3bd552dbf8595cee40dc8', '2024-07-22 10:21:44', '2d82b054b2c44fe9');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
