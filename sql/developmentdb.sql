-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Gegenereerd op: 14 apr 2024 om 20:55
-- Serverversie: 11.3.2-MariaDB-1:11.3.2+maria~ubu2204
-- PHP-versie: 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Brands`
--

CREATE TABLE `Brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Brands`
--

INSERT INTO `Brands` (`brand_id`, `brand_name`, `description`) VALUES
(1, 'Nike', 'Just do it.'),
(2, 'Levi\'s', 'Built to Last'),
(3, 'Under Armour', 'Ready to take on a challenge'),
(5, 'Asics', 'Sound Mind, Sound Body'),
(6, 'Adidas', 'Impossible is Nothing'),
(7, 'Puma', 'Forever Better');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Categories`
--

CREATE TABLE `Categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Categories`
--

INSERT INTO `Categories` (`category_id`, `category_name`) VALUES
(2, 'Pants'),
(9, 'Shirts'),
(1, 'Shoes'),
(10, 'Shorts'),
(11, 'Underwear');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Products`
--

CREATE TABLE `Products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Products`
--

INSERT INTO `Products` (`product_id`, `category_id`, `brand_id`, `name`, `price`, `image_url`, `created_at`) VALUES
(6, 1, 1, 'Air Max', 125.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRatg1US8AGcVoxxMs59w4QA9O0uJ79YbRsIA&s', '2024-04-08 00:00:00'),
(7, 2, 2, '501 Original Jeans', 89.99, 'https://img01.ztat.net/article/spp-media-p1/eaa75d3773e24dbea9398b3ddb2d6f4b/931d18ee70144c16a08b1f89a9563e5a.jpg?imwidth=762', '2024-04-02 00:00:00'),
(8, 9, 3, 'Tech Tee', 27.95, 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQQVbd7JSP2MZqgRVjLX4q1NK4swkf_OE-GUf38PPjaF_5SKcI_wSQ0pj79aA92fuySDXvL4n7CQDgmuFGjuEaogBUEZN2yokFjvNeim9pBybZ2HA4M3heIUAyFEZnnCGbAskrKsQ&usqp=CAc', '2024-04-14 12:39:46'),
(12, 2, 6, 'Originals SS Track Pants', 45.00, 'https://image-resizing.booztcdn.com/adidas-originals/adign8453_cblackwhite_2.webp?has_grey=1&has_webp=0&dpr=2.5&size=w400', '2024-04-14 13:34:33');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ProductSizes`
--

CREATE TABLE `ProductSizes` (
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `ProductSizes`
--

INSERT INTO `ProductSizes` (`product_id`, `size_id`, `stock_quantity`) VALUES
(6, 1, 60),
(7, 3, 15),
(7, 4, 90),
(8, 9, 35),
(12, 5, 35);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Sizes`
--

CREATE TABLE `Sizes` (
  `size_id` int(11) NOT NULL,
  `size_name` varchar(50) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Sizes`
--

INSERT INTO `Sizes` (`size_id`, `size_name`, `category_id`) VALUES
(1, '9', 1),
(2, '10', 1),
(3, 'M', 2),
(4, 'L', 2),
(5, 'XL', 2),
(8, 'S', 2),
(9, 'M', 9),
(10, 'L', 9);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `StockHistory`
--

CREATE TABLE `StockHistory` (
  `history_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `quantity_changed` int(11) DEFAULT NULL,
  `change_date` timestamp NULL DEFAULT current_timestamp(),
  `reason` varchar(255) DEFAULT NULL,
  `stock_movement` enum('IN','OUT') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `StockHistory`
--

INSERT INTO `StockHistory` (`history_id`, `product_id`, `size_id`, `quantity_changed`, `change_date`, `reason`, `stock_movement`) VALUES
(3, 6, 1, 10, '2024-04-05 20:09:44', 'New stock arrival', 'IN'),
(4, 7, 3, 15, '2024-04-05 20:09:44', 'New stock arrival', 'IN'),
(5, 8, 9, 10, '2024-04-14 18:18:43', 'New stock arrival', 'IN'),
(7, 8, 9, 30, '2024-04-14 18:21:26', 'Second stock arrival', 'IN'),
(8, 8, 9, 10, '2024-04-14 18:36:10', 'Second stock arrival', 'IN'),
(9, 8, 9, 10, '2024-04-14 18:37:25', 'Damaged Stock', 'OUT'),
(10, 12, 5, 35, '2024-04-14 18:54:46', 'New arrival', 'IN'),
(11, 6, 1, 5, '2024-04-14 18:56:25', 'Sold on market', 'OUT'),
(12, 7, 4, 55, '2024-04-14 18:58:40', '13', 'OUT'),
(13, 6, 1, 55, '2024-04-14 19:01:27', 'New arrival', 'IN'),
(14, 7, 4, 50, '2024-04-14 19:02:58', 'New arrival', 'IN'),
(15, 7, 4, 15, '2024-04-14 19:03:26', 'New arrival', 'OUT');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `created_at`, `status`) VALUES
(69, 'mdinle', '$2y$10$wimifKF.9pP5STH02.8.UeBei3CIend/PiUS/xqqE9c.akl9XQXki', '700152@student.inholland.nl', '2024-04-12 17:56:43', 'active'),
(95, 'ssmith', '$2y$10$AlGBs4xLXYkR7PeKB9sx3uSa6QICTIhmNFz6mNFUk3lHSoG1InpQa', 'ssmith@example.com', '2024-04-13 23:39:36', 'active'),
(96, 'ss', '$2y$10$MrmW2FkXLvaBYmFEW1mrAON2rUVGOlsayjFZAp14PrW9QFLQK3E3W', 'ssmith@example.coms', '2024-04-14 14:13:43', 'active'),
(97, 'TEST', '$2y$10$Gv9ipraNkt5quR0QYDEkqesrTmmnutU5GQ81QopnCp8HiUGmy4FNK', 'test@live.nl', '2024-04-14 20:50:17', 'active');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `Brands`
--
ALTER TABLE `Brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexen voor tabel `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexen voor tabel `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexen voor tabel `ProductSizes`
--
ALTER TABLE `ProductSizes`
  ADD PRIMARY KEY (`product_id`,`size_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Indexen voor tabel `Sizes`
--
ALTER TABLE `Sizes`
  ADD PRIMARY KEY (`size_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexen voor tabel `StockHistory`
--
ALTER TABLE `StockHistory`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `Brands`
--
ALTER TABLE `Brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `Categories`
--
ALTER TABLE `Categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT voor een tabel `Products`
--
ALTER TABLE `Products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT voor een tabel `Sizes`
--
ALTER TABLE `Sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `StockHistory`
--
ALTER TABLE `StockHistory`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `Products`
--
ALTER TABLE `Products`
  ADD CONSTRAINT `Products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `Brands` (`brand_id`);

--
-- Beperkingen voor tabel `ProductSizes`
--
ALTER TABLE `ProductSizes`
  ADD CONSTRAINT `ProductSizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`),
  ADD CONSTRAINT `ProductSizes_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `Sizes` (`size_id`);

--
-- Beperkingen voor tabel `Sizes`
--
ALTER TABLE `Sizes`
  ADD CONSTRAINT `Sizes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Categories` (`category_id`);

--
-- Beperkingen voor tabel `StockHistory`
--
ALTER TABLE `StockHistory`
  ADD CONSTRAINT `StockHistory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`),
  ADD CONSTRAINT `StockHistory_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `Sizes` (`size_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
