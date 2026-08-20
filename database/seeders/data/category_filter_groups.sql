-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 19, 2026 at 09:06 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hardware_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_filter_groups`
--

CREATE TABLE `category_filter_groups` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_filter_groups`
--

INSERT INTO `category_filter_groups` (`id`, `category_id`, `name`, `slug`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(6, 63, 'Connector', 'connector', 0, 1, '2026-07-25 17:48:12', '2026-07-25 17:48:12'),
(7, 63, 'Length', 'length', 1, 1, '2026-07-25 17:48:12', '2026-07-25 17:48:12'),
(8, 11, 'Core', 'cores', 0, 1, '2026-08-01 17:07:36', '2026-08-01 17:08:09'),
(9, 11, 'Generation', 'generation', 1, 1, '2026-08-01 17:07:37', '2026-08-01 17:07:37'),
(10, 11, 'Socket', 'socket', 2, 1, '2026-08-01 17:07:38', '2026-08-01 17:07:38'),
(11, 73, 'Type', 'type', 0, 1, '2026-08-01 17:14:26', '2026-08-01 17:14:26'),
(12, 15, 'PCIe', 'pcie', 0, 1, '2026-08-01 17:18:32', '2026-08-01 17:18:32'),
(13, 15, 'Power Requirement', 'power-requirement', 1, 1, '2026-08-01 17:18:32', '2026-08-01 17:18:32'),
(14, 15, 'Vram', 'vram', 2, 1, '2026-08-01 17:18:33', '2026-08-01 17:18:56'),
(15, 12, 'Capacity', 'capacity', 0, 1, '2026-08-01 17:23:16', '2026-08-01 17:23:54'),
(16, 12, 'Form Factor', 'form-factor', 1, 1, '2026-08-01 17:23:18', '2026-08-01 17:23:54'),
(17, 12, 'Speed', 'speed', 2, 1, '2026-08-01 17:23:19', '2026-08-01 17:23:19'),
(18, 12, 'Type', 'type', 3, 1, '2026-08-01 17:23:21', '2026-08-01 17:23:54'),
(23, 13, 'Chipset', 'chipset', 0, 1, '2026-08-01 17:30:16', '2026-08-01 17:30:16'),
(24, 13, 'Form Factor', 'form-factor', 1, 1, '2026-08-01 17:30:17', '2026-08-01 17:30:17'),
(25, 13, 'Socket', 'socket', 2, 1, '2026-08-01 17:30:18', '2026-08-01 17:30:46'),
(26, 13, 'Type', 'ram-type', 3, 1, '2026-08-01 17:30:19', '2026-08-01 17:30:46'),
(27, 2, 'Interface', 'interface', 0, 1, '2026-08-01 17:40:10', '2026-08-01 17:40:10'),
(29, 2, 'Ports', 'port', 2, 1, '2026-08-01 17:40:12', '2026-08-01 17:40:39'),
(30, 2, 'Speed', 'speed', 3, 1, '2026-08-01 17:40:13', '2026-08-01 17:40:13'),
(31, 2, 'Type', 'type', 4, 1, '2026-08-01 17:40:14', '2026-08-01 17:40:39'),
(32, 14, 'Certifications', 'certification', 0, 1, '2026-08-01 17:48:32', '2026-08-01 17:49:09'),
(33, 14, 'Form Factor', 'form-factor', 1, 1, '2026-08-01 17:48:32', '2026-08-01 17:48:32'),
(34, 14, 'Modular', 'modular', 2, 1, '2026-08-01 17:48:33', '2026-08-01 17:48:33'),
(35, 14, 'Wattage', 'wattage', 3, 1, '2026-08-01 17:48:33', '2026-08-01 17:48:33'),
(36, 80, 'Connectivity', 'connectivity', 0, 1, '2026-08-01 17:51:50', '2026-08-01 17:51:50'),
(37, 80, 'Technology', 'technology', 1, 1, '2026-08-01 17:51:50', '2026-08-01 17:51:50'),
(38, 80, 'Type', 'type', 2, 1, '2026-08-01 17:51:50', '2026-08-01 17:51:50'),
(39, 10, 'Cache', 'cache', 0, 1, '2026-08-18 12:58:41', '2026-08-18 12:58:41'),
(40, 10, 'Capacity', 'capacity', 1, 1, '2026-08-18 12:58:41', '2026-08-18 12:58:41'),
(41, 10, 'Form Factor', 'form-factor', 2, 1, '2026-08-18 12:58:41', '2026-08-18 12:58:41'),
(42, 10, 'Interface', 'interface', 3, 1, '2026-08-18 12:58:41', '2026-08-18 12:58:41'),
(43, 10, 'RPMS', 'rpms', 4, 1, '2026-08-18 12:58:41', '2026-08-18 12:58:41'),
(44, 10, 'Type', 'type', 5, 1, '2026-08-18 12:58:41', '2026-08-18 12:58:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_filter_groups`
--
ALTER TABLE `category_filter_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_filter_groups_category_id_slug_unique` (`category_id`,`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_filter_groups`
--
ALTER TABLE `category_filter_groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_filter_groups`
--
ALTER TABLE `category_filter_groups`
  ADD CONSTRAINT `category_filter_groups_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
