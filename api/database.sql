-- Skema Database untuk Aplikasi Gafood

CREATE DATABASE IF NOT EXISTS gafood;
USE gafood;

-- Tabel untuk Admin
-- Kita akan menyimpan password yang sudah di-hash
CREATE TABLE `admins` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Masukkan satu admin default agar bisa login
-- Passwordnya adalah "admin123" (sudah di-hash menggunakan BCRYPT)
INSERT INTO `admins` (`username`, `password`) VALUES
('admin', '$2y$10$I/hP.pC9.A8FjB5h.sSCp.djsA7uK6Dk.Yj/b.xG2a.gY3gI2bBqG');

-- Tabel untuk produk (menu makanan)
CREATE TABLE `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10, 2) NOT NULL,
  `image_url` VARCHAR(255),
  `is_available` BOOLEAN NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk pesanan
CREATE TABLE `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `customer_address` TEXT NOT NULL,
  `total_amount` DECIMAL(10, 2) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Pesanan Diterima',
  `order_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk item-item dalam satu pesanan
CREATE TABLE `order_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Informasi Kontak
CREATE TABLE `contact_info` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `address` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `map_url` TEXT,
  `opening_hours` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Masukkan informasi kontak default
INSERT INTO `contact_info` (`address`, `phone`, `email`, `map_url`, `opening_hours`) VALUES
('Jl. Raya Gafood, No. 123, Jakarta', '(021) 123-4567', 'info@gafood.com', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576673953335!2d106.8271528147689!3d-6.18822559552099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f4236b6b5c5b%3A0x7f4f1b3b0e9b1a2b!2sMonumen%20Nasional!5e0!3m2!1sid!2sid', 'Senin - Jumat: 08:00 - 22:00, Sabtu - Minggu: 10:00 - 23:00');

-- Tabel untuk Pesan Kontak
CREATE TABLE `contact_messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `message` TEXT NOT NULL,
  `sent_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
