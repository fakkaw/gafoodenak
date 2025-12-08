-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 07:51 AM
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
-- Database: `gafood`
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
(1, 'fakka', '$2y$10$VKdiXG5b1LH/FrcbzepJi.PafnYC8fqPvLMiXZBAvB9ZlvcI3Alem');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `order_id`, `sender`, `message`, `timestamp`) VALUES
(1, '12', 'admin', 'jj', '2025-10-15 01:32:11'),
(2, '12', 'user', 'halo', '2025-10-15 01:36:49'),
(3, '13', 'user', 'pp', '2025-10-15 01:50:27');

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `map_url` text DEFAULT NULL,
  `opening_hours` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`id`, `address`, `phone`, `email`, `map_url`, `opening_hours`) VALUES
(1, 'Jl. Tentara Pelajar, No. 123, Banjarnegara', '0813-9194-1736', 'gafood@gmail.com', 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d265.96732594409616!2d109.72219431818692!3d-7.391220466731655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1sid!2sid!4v1760415056627!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '09.00-21.00');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `sent_at`) VALUES
(1, 'Ghani Rindra', 'ghanirindra63@gmail.com', 'bagus', '2025-10-14 04:06:55'),
(2, 'Ghani Rindra', 'ghanirindra63@gmail.com', 'bagus', '2025-10-14 04:10:17'),
(3, 'Ghani Rindra', 'ghanirindra63@gmail.com', 'bagus', '2025-10-14 04:11:10'),
(4, 'Ghani Rindra', 'ghanirindra63@gmail.com', 'bagus', '2025-10-14 04:12:35'),
(5, 'Ghani Rindra', 'ghanirindra63@gmail.com', 'bagus', '2025-10-14 04:12:55'),
(6, 'fakk', 'gafood@gmail.com', 'bagus mantap', '2025-10-14 04:13:31'),
(7, 'fakk', 'gafood@gmail.com', 'bagus mantap', '2025-10-14 04:13:47');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pesanan Diterima',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_phone`, `customer_address`, `total_amount`, `payment_method`, `status`, `order_date`) VALUES
(1, 'Ghani Rindra', '081391941736', 'Sokanandi', 40000.00, 'E-Wallet', 'Tiba di Tujuan', '2025-10-11 14:31:04'),
(2, 'Ghani Rindra', '081391941736', 'Sokanandi', 40000.00, 'Transfer Bank', 'Tiba di Tujuan', '2025-10-11 14:48:43'),
(3, 'Ghani Rindra', '081391941736', 'Sokanandi', 30000.00, 'COD (Cash on Delivery)', 'Tiba di Tujuan', '2025-10-11 14:49:38'),
(4, 'Ghani Rindra', '081391941736', 'Sokanandi', 105000.00, 'COD (Cash on Delivery)', 'Pesanan Diterima', '2025-10-11 14:51:53'),
(5, 'Ghani Rindra', '081391941736', 'Sokanandi', 20000.00, 'COD (Cash on Delivery)', 'Pesanan Diterima', '2025-10-11 15:32:23'),
(6, 'Ghani Rindra', '081391941736', 'Sokanandi', 136000.00, 'COD (Cash on Delivery)', 'Pesanan Diterima', '2025-10-11 23:11:36'),
(7, 'Ghani Rindra', '081391941736', 'Sokanandi', 50000.00, 'E-Wallet', 'Pesanan Diterima', '2025-10-12 00:39:42'),
(8, 'Ghani Rindra', '081391941736', 'Sokanandi', 210000.00, 'E-Wallet', 'Pesanan Diterima', '2025-10-12 00:47:05'),
(9, 'Ghani Rindra', '081391941736', 'Sokanandi', 92000.00, 'E-Wallet', 'Tiba di Tujuan', '2025-10-12 10:23:55'),
(10, 'Ghani Rindra', '081391941736', 'Sokanandi', 66000.00, 'Transfer Bank', 'Tiba di Tujuan', '2025-10-13 16:05:19'),
(11, 'Ghani Rindra', '081391941736', 'Sokanandi', 20000.00, 'E-Wallet', 'Dalam Perjalanan', '2025-10-14 03:08:15'),
(12, 'Ghani Rindra', '081391941736', 'Sokanandi', 12000.00, 'Transfer Bank', 'Dalam Perjalanan', '2025-10-15 01:31:36'),
(13, 'Ghani Rindra', '081391941736', 'Sokanandi', 13000.00, 'Transfer Bank', 'Tiba di Tujuan', '2025-10-15 01:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 6, 1, 35000.00),
(2, 1, 7, 1, 5000.00),
(3, 2, 7, 1, 5000.00),
(4, 2, 6, 1, 35000.00),
(5, 3, 3, 1, 30000.00),
(6, 4, 6, 3, 35000.00),
(7, 5, 8, 1, 15000.00),
(8, 5, 7, 1, 5000.00),
(9, 6, 1, 4, 25000.00),
(10, 6, 4, 2, 18000.00),
(11, 7, 8, 3, 15000.00),
(12, 7, 7, 1, 5000.00),
(13, 8, 3, 3, 30000.00),
(14, 8, 6, 3, 35000.00),
(15, 8, 7, 3, 5000.00),
(16, 9, 4, 2, 18000.00),
(17, 9, 3, 1, 16000.00),
(18, 9, 11, 1, 35000.00),
(19, 9, 7, 1, 5000.00),
(20, 10, 5, 1, 28000.00),
(21, 10, 4, 1, 18000.00),
(22, 10, 2, 1, 20000.00),
(23, 11, 2, 1, 20000.00),
(24, 12, 22, 1, 12000.00),
(25, 13, 21, 1, 13000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `is_available`) VALUES
(1, 'Nasi Goreng Spesial', 'Nasi goreng dengan telur, ayam suwir, dan bakso.', 25000.00, 'assets/nasgor spesial.jpg', 1),
(2, 'Mie Ayam Bakso', 'Mie ayam lezat disajikan dengan bakso sapi asli.', 20000.00, 'assets/mie ayam bakso.jpg', 1),
(3, 'Sate Ayam (10 tusuk)', 'Sate ayam dengan bumbu kacang yang kaya rasa.', 16000.00, 'assets/sate ayam.jpg', 1),
(4, 'Gado-Gado', 'Salad sayuran segar dengan saus kacang spesial.', 18000.00, 'assets/gado gado.jpg', 1),
(5, 'Ayam Bakar Madu', 'Ayam bakar empuk dengan olesan madu manis.', 28000.00, 'assets/ayam bakar madu.jpg', 1),
(6, 'Soto Betawi', 'Soto daging sapi dengan kuah santan khas Betawi.', 35000.00, 'assets/soto betawi.jpg', 1),
(7, 'Es Teh Manis', 'Minuman teh manis dingin yang menyegarkan.', 5000.00, 'assets/es teh.jpg', 1),
(8, 'Jus Alpukat', 'Jus alpukat segar dengan susu kental manis.', 15000.00, 'assets/jus alpukat1.jpg', 1),
(10, 'Sop Iga', 'Terdapat 2-3 Potong Iga Ukuran Sedang.', 40000.00, 'assets/uploads/menu_68eb0cd92ec1e.jpg', 1),
(11, 'Sapo Tahu Seafood', 'Berisikan Udang, cumi, Tofu, dan aneka Macam Sayuran.', 35000.00, 'assets/uploads/menu_68eb0d71e9366.jpg', 1),
(12, 'Cah Kangkung', '', 12000.00, 'assets/uploads/menu_68eb0dd845e8e.jpg', 1),
(13, 'Teh Anget', '', 5000.00, 'assets/uploads/menu_68ed1e8aa7e5c.jpg', 1),
(14, 'Bakso', 'Dibuat dengan Daging Sapi asli.\r\n', 15000.00, 'assets/uploads/menu_68ed1ed1df911.jpg', 1),
(15, 'Dimsum Mentai', '1 porsi berisikan 6pcs', 25000.00, 'assets/uploads/menu_68ed1f37dfa90.jpg', 1),
(16, 'Es Jeruk', '', 5000.00, 'assets/uploads/menu_68ed1fe3602ca.jpg', 1),
(17, 'Nasi Tim Ayam', 'Isinya ada Nasi dan Ayam.', 20000.00, 'assets/uploads/menu_68ed2039771f7.jpg', 1),
(18, 'Sate Kambing', 'Sate ayam dengan bumbu kecap yang kaya rasa.', 30000.00, 'assets/uploads/menu_68ed20a1c3b0d.jpg', 1),
(19, 'Nasi Goreng Babat', 'Nasi goreng dengan Babat.', 25000.00, 'assets/uploads/menu_68ed2134b0b13.jpg', 1),
(20, 'Mie Goreng', 'Mie goreng yang berisikan bakso + telur', 12000.00, 'assets/uploads/menu_68ed21c302cbb.jpg', 1),
(21, 'Mie Rebus', 'Mie Rebus dengan isian Suiran daging Ayam', 13000.00, 'assets/uploads/menu_68ed222b68ecc.jpg', 1),
(22, 'Ketoprak', 'Kupat + Bihun + Telor dadar + Toge', 12000.00, 'assets/uploads/menu_68ed22b3ebf57.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
