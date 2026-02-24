-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 11:44 AM
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
-- Database: `ches_db`
--
CREATE DATABASE IF NOT EXISTS `ches_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ches_db`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(8, 'Processor'),
(9, 'Mouse'),
(10, 'Keyboard'),
(11, 'Monitor'),
(12, 'CPU CASE'),
(13, 'Graphic Cards'),
(14, 'RAM'),
(15, 'Storage'),
(16, 'PSU'),
(17, 'Headset'),
(19, 'Controller');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'COD',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `transaction_uuid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `payment_method`, `created_at`, `transaction_uuid`) VALUES
(1, 12, 216000.00, 'delivered', 'COD', '2026-01-14 09:36:13', NULL),
(2, 12, 20000.00, 'rejected', 'COD', '2026-01-14 10:48:10', NULL),
(3, 12, 196600.00, 'delivered', 'COD', '2026-01-14 10:48:30', NULL),
(4, 13, 3999.00, 'rejected', 'COD', '2026-02-05 12:06:08', NULL),
(8, 12, 3999.00, 'delivered', 'COD', '2026-02-11 16:07:38', NULL),
(9, 12, 26997.00, 'dispatched', 'COD', '2026-02-13 06:25:32', NULL),
(10, 11, 3999.00, 'rejected', 'COD', '2026-02-13 08:55:35', NULL),
(11, 12, 3999.00, 'cancelled', 'COD', '2026-02-13 09:42:53', NULL),
(12, 12, 7998.00, 'cancelled', 'COD', '2026-02-14 11:17:10', NULL),
(13, 12, 8999.00, 'accepted', 'COD', '2026-02-14 11:50:32', NULL),
(14, 12, 24275.00, 'rejected', 'COD', '2026-02-14 12:03:03', NULL),
(22, 12, 26250.00, 'rejected', 'COD', '2026-02-14 12:51:20', NULL),
(23, 12, 10000.00, 'dispatched', 'COD', '2026-02-14 12:52:56', NULL),
(24, 12, 5000.00, 'accepted', 'COD', '2026-02-14 12:53:47', NULL),
(25, 12, 10000.00, 'cancelled', 'COD', '2026-02-17 05:22:30', NULL),
(26, 12, 17998.00, 'delivered', 'COD', '2026-02-17 07:33:41', NULL),
(27, 13, 12000.00, 'pending', 'esewa', '2026-02-19 21:31:45', NULL),
(28, 13, 500.00, 'pending', 'COD', '2026-02-19 21:36:15', NULL),
(29, 13, 500.00, 'cancelled', 'esewa', '2026-02-19 21:36:39', NULL),
(30, 13, 500.00, 'pending', 'esewa', '2026-02-19 21:42:22', NULL),
(31, 14, 500.00, 'pending', 'esewa', '2026-02-19 21:43:46', NULL),
(32, 14, 500.00, 'cancelled', 'esewa', '2026-02-19 21:46:15', NULL),
(33, 14, 500.00, 'pending', 'esewa', '2026-02-19 21:56:21', NULL),
(34, 14, 500.00, 'cancelled', 'esewa', '2026-02-19 21:59:21', NULL),
(35, 13, 500.00, 'pending', 'esewa', '2026-02-19 22:22:45', NULL),
(36, 13, 500.00, 'cancelled', 'esewa', '2026-02-19 22:23:56', NULL),
(37, 13, 3999.00, 'cancelled', 'esewa', '2026-02-24 07:30:56', NULL),
(38, 13, 500.00, 'pending_payment', 'esewa', '2026-02-24 07:58:02', 'ORDER-1771919882-9136'),
(39, 13, 500.00, 'pending', 'COD', '2026-02-24 08:00:05', NULL),
(40, 13, 500.00, 'paid', 'esewa', '2026-02-24 08:13:15', 'ORDER-1771920795-2379'),
(41, 13, 500.00, 'pending', 'COD', '2026-02-24 08:18:54', NULL),
(42, 13, 500.00, 'pending', 'COD', '2026-02-24 08:26:26', NULL),
(43, 13, 500.00, 'cancelled', 'COD', '2026-02-24 08:30:30', NULL),
(44, 13, 500.00, 'accepted', 'COD', '2026-02-24 08:31:05', NULL),
(45, 13, 500.00, 'paid', 'esewa', '2026-02-24 08:32:29', 'ORDER-1771921949-7575'),
(46, 13, 500.00, 'pending_payment', 'esewa', '2026-02-24 08:33:50', 'ORDER-1771922030-6740'),
(47, 13, 500.00, 'paid', 'esewa', '2026-02-24 08:48:07', 'ORDER-1771922887-7207'),
(48, 13, 500.00, 'pending', 'esewa', '2026-02-24 08:50:57', 'ORDER-1771923057-5543'),
(49, 13, 189800.00, 'rejected', 'esewa', '2026-02-24 08:53:16', 'ORDER-1771923196-8637'),
(50, 13, 29673.00, 'pending', 'COD', '2026-02-24 09:02:34', NULL);

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
(1, 1, 5, 3, 72000.00),
(2, 2, 37, 1, 20000.00),
(3, 3, 26, 2, 98300.00),
(4, 4, 47, 1, 3999.00),
(5, 8, 47, 1, 3999.00),
(6, 9, 46, 3, 8999.00),
(7, 10, 47, 1, 3999.00),
(8, 11, 47, 1, 3999.00),
(9, 12, 47, 2, 3999.00),
(10, 13, 46, 1, 8999.00),
(11, 14, 43, 3, 4200.00),
(12, 14, 45, 1, 11675.00),
(13, 22, 36, 3, 8750.00),
(14, 23, 41, 1, 10000.00),
(15, 24, 9, 10, 500.00),
(16, 25, 41, 1, 10000.00),
(17, 26, 46, 2, 8999.00),
(18, 27, 38, 2, 6000.00),
(19, 28, 9, 1, 500.00),
(20, 29, 9, 1, 500.00),
(21, 30, 9, 1, 500.00),
(22, 31, 9, 1, 500.00),
(23, 32, 9, 1, 500.00),
(24, 33, 9, 1, 500.00),
(25, 34, 9, 1, 500.00),
(26, 35, 9, 1, 500.00),
(27, 36, 9, 1, 500.00),
(28, 37, 47, 1, 3999.00),
(29, 38, 9, 1, 500.00),
(30, 39, 9, 1, 500.00),
(31, 40, 9, 1, 500.00),
(32, 41, 9, 1, 500.00),
(33, 42, 9, 1, 500.00),
(34, 43, 9, 1, 500.00),
(35, 44, 9, 1, 500.00),
(36, 45, 9, 1, 500.00),
(37, 46, 9, 1, 500.00),
(38, 47, 9, 1, 500.00),
(39, 48, 9, 1, 500.00),
(40, 49, 21, 1, 189800.00),
(41, 50, 46, 2, 8999.00),
(42, 50, 45, 1, 11675.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `stock`, `description`, `image`, `created_at`, `status`) VALUES
(3, 'AMD Ryzen 5 3600', 8, 25000.00, 10, 'AMD RYZEN 5 3600 6-Core 3.6 GHz (4.2 GHz Max Boost) \r\n\r\nSocket AM4 Desktop Processor\r\n\r\n3rd Gen Ryzen\r\n\r\nDDR4 memory Support \r\n\r\n6-Core\r\n\r\nThreads 12 \r\n\r\nL2 Cache 3MB\r\n\r\nL3 Cache 32MB\r\n\r\nThermal Design Power 65W\r\n\r\nwith Wraith Stealth cooler', '1768382501_AMD Ryzen 5 3600.jpg', '2026-01-14 09:21:41', 1),
(4, 'AMD Ryzen 5 5600X', 8, 30000.00, 8, 'AMD’s fastest 6 core processor for mainstream desktop, with 12 processing threads\r\nCan deliver elite 100 plus FPS performance in the world’s most popular games\r\nBundled with the quiet, capable AMD Wraith Stealth cooler. System Memory Type: DDR4\r\n4.6 GHz Max Boost, unlocked for overclocking, 35 MB of cache, DDR-3200 support\r\nFor the advanced Socket AM4 platform, can support PCIe 4.0 on X570 and B550 motherboards . OS Support: Windows 11 – 64-Bit Edition, Windows 10 – 64-Bit Edition, RHEL x86 64-Bit, Ubuntu x86 64-Bit', '1768382607_AMD Ryzen 5 5600X.jpg', '2026-01-14 09:23:27', 1),
(5, 'AMD Ryzen 9 7950X', 8, 72000.00, 2, 'Architecture: Zen 5 (4nm process)\r\nCores/Threads: 16/32\r\nBase Clock: 4.2 GHz\r\nBoost Clock: Up to 5.8 GHz\r\nCache: 80 MB (64 MB L3 + 16 MB L2)\r\nTDP: 170W\r\nSocket Compatibility: AM5', '1768382705_AMD Ryzen 9 7950X.jpg', '2026-01-14 09:25:05', 1),
(6, 'Intel 12th Gen Core i5-12600K Alder Lake Processor', 8, 39000.00, 10, 'Key Features\r\nModel: Core i5-12600K\r\nBase Clock: P-Core: 3.7 GHz, E-Core: 2.8 GHz\r\nMax. Boost: P-Core: 4.9 GHz, E-Core: 3.6 GHz\r\nCache: 20MB, Socket: LGA 1700\r\nCPU Cores: 10, CPU Threads: 16\r\nMax Resolution (HDMI): 4096 x 2160 @ 60Hz\r\nMax Resolution (DP): 7680 x 4320 @ 60Hz', '1768383785_Intel-Core-i5-12600K-in-Nepal.jpg', '2026-01-14 09:43:05', 1),
(7, 'Intel 12th Gen Core i9-12900K Alder Lake Processor', 8, 65000.00, 6, 'Intel 12th Gen Core i9-12900K Alder Lake Processor\r\nKey Features\r\nModel: Core i9-12900K\r\nClock Speed: 3.70 GHz Up to 5.20 GHz\r\nCache: 30 MB, Socket: LGA 1700\r\nCPU Cores: 16, CPU Threads: 24\r\nGPU name: Intel UHD Graphics 770', '1768383889_Intel 12th Gen Core i9-12900K Alder Lake Processor.jpg', '2026-01-14 09:44:49', 1),
(8, 'Rapoo N300 Gaming Mouse', 9, 750.00, 15, '1. Rapoo brand is the High Configuration 2000DPI Business office and USB Wired Gaming Mouse Gamer for Computer / pc / Laptop.\r\n\r\n2. High quality 1.65M USB Wired Gaming and Business office Mouse Computer , Strong USB cable.\r\n\r\n3. Ergonomic Mouse Gamer PC Gaming and Business office, a better user experience improve operational performance .\r\n\r\n4. 3 Adjustable DPI Gaming computer Mouse 1000 , 1600 , 2000DPI.\r\n\r\n5. 6 Button Gamer Mouse Computer ( Left , Right , DPI Switch , Scroll wheel , forward , Backword )1. Rapoo brand is the High Configuration 2000DPI Business office and USB Wired Gaming Mouse Gamer for Computer / pc / Laptop.\r\n\r\n2. High quality 1.65M USB Wired Gaming and Business office Mouse Computer , Strong USB cable.\r\n\r\n3. Ergonomic Mouse Gamer PC Gaming and Business office, a better user experience improve operational performance .\r\n\r\n4. 3 Adjustable DPI Gaming computer Mouse 1000 , 1600 , 2000DPI.\r\n\r\n5. 6 Button Gamer Mouse Computer ( Left , Right , DPI Switch , Scroll wheel , forward , Backword )1. Rapoo brand is the High Configuration 2000DPI Business office and USB Wired Gaming Mouse Gamer for Computer / pc / Laptop.\r\n\r\n2. High quality 1.65M USB Wired Gaming and Business office Mouse Computer , Strong USB cable.\r\n\r\n3. Ergonomic Mouse Gamer PC Gaming and Business office, a better user experience improve operational performance .\r\n\r\n4. 3 Adjustable DPI Gaming computer Mouse 1000 , 1600 , 2000DPI.\r\n\r\n5. 6 Button Gamer Mouse Computer ( Left , Right , DPI Switch , Scroll wheel , forward , Backword )', '1768384007_Rapoo N300 Gaming Mouse.jpg', '2026-01-14 09:46:47', 1),
(9, 'R8 1605 Magic RGB Wired Gaming Mouse', 9, 500.00, 17, 'Product Type:R8 1605 Magic RGB Wired Gaming Mouse.\r\nBrand:R8.\r\nModel:1605.\r\nInterface: USB.\r\nColor : Black.\r\nDPI : 2400.\r\nNumber of button: 3D.\r\nThere&amp;#039;s an eye-catching RGB LED.\r\nCable length:125cm.', '1768384068_R8 1605 Magic RGB Wired Gaming Mouse.jpg', '2026-01-14 09:47:48', 1),
(10, 'Logitech M190 Wireless Mouse', 9, 1500.00, 15, 'Logitech M190 is a full-size wireless mouse with a comfortable, contoured design that follows the natural curve of medium to large hands, allowin you to work wirelessly and move freely with virtually no delays or dropouts.\r\n\r\nWireless, good brand, more comfort and flexibility.', '1768384117_Logitech M190 Wireless Mouse.jpg', '2026-01-14 09:48:37', 1),
(11, 'Corsair M65 RGB Elite Gaming Mouse', 9, 12500.00, 10, 'Weight In Grams\r\n147\r\nConnectivity\r\nWired\r\nWeight Tuning\r\nYes\r\nHand Size\r\nMedium\r\nGrip Type\r\nClaw\r\nReport Rate\r\nSelectable 1000Hz/500Hz/250Hz/125Hz\r\nGame Type\r\nAny\r\nMouse Button Durability\r\n50M L/R Click\r\nMouse Button Type\r\nOmron', '1768384180_Corsair M65 RGB Elite Gaming Mouse.png', '2026-01-14 09:49:40', 1),
(12, 'Logitech G502 Hero Optical 16000dpi', 9, 9000.00, 10, 'Key Features\r\nMPN: 910-005469\r\nModel: G502 Hero\r\nSensor: HERO 25K (Resolution: 100-25,600 DPI)\r\nButtons: 15 Programmable Controls\r\nMax. acceleration: &gt;1.41 oz (40 g) \r\nMicroprocessor: 32-bit ARM\r\nWarranty: 1 Year Warranty', '1768384300_Logitech G502 Hero Optical 16000dpi.png', '2026-01-14 09:51:40', 1),
(13, 'Ajazz AK680 Mechanical Keyboard (Red Switch)', 10, 2999.00, 15, 'Layout\r\n\r\n68-key, 65% layout\r\n\r\nMounting Structure\r\n\r\nThree-layer silencer gasket mount\r\n\r\nTyping Experience\r\n\r\nEVA sandwich filling for harmonious typing and crisp sound\r\n\r\nBacklighting\r\n\r\nCustomizable rainbow light effects with 20 pre-set modes\r\n\r\nSwitch Type\r\n\r\nHigh-quality sleeve switch with metal contacts\r\n\r\nConnectivity\r\n\r\nUSB Wired', '1768384389_Ajazz AK680 Mechanical Keyboard (Red Switch).png', '2026-01-14 09:53:09', 1),
(14, 'Leobog Hi75 Aluminum Keyboard', 10, 10999.00, 8, 'Aluminum build, 75% layout, PBT keycaps, hot-swappable, knob control → nicer materials &amp; features.75% Gaming Keyboard\r\n81-Key Layout\r\n5 Pin Hot-Swap\r\nGASKET Soft Elastic Structure\r\nAluminum Alloy Material – Spraying Process\r\nPlug And Play', '1768384459_Leobog Hi75 Aluminum Keyboard.jpg', '2026-01-14 09:54:19', 1),
(16, 'Logitech G512 Carbon RGB Mechanical Gaming Keyboard', 10, 17000.00, 5, 'Key Features\r\nModel: Logitech G512 Carbon Lightsync\r\nLightning: Per Key RGB Lighting System\r\nWeight: 1130 g\r\nPassthrough: USB 2.0\r\nSwitch: GX Mechanical Switches \r\nCable length: 6ft\r\nWarranty: 1 Year Warranty', '1768384897_Logitech G512 Carbon RGB Mechanical Gaming Keyboard - Per-Key RGB, LightSync, Mechanical Switches.jpg', '2026-01-14 10:01:37', 1),
(17, 'Fantech Atom 63 MK874V2 Mori Edition', 10, 2799.00, 10, 'Layout: 60% (63 keys)\r\n\r\nSwitch Type: Mechanical (available in Red Linear and Blue Clicky)\r\n\r\nHot-Swappable: Yes, compatible with 3-pin switches\r\n\r\nAnti-Ghosting: Supports up to 26 keys simultaneously\r\n\r\nConnectivity: Wired (USB-C)\r\n\r\nLighting: 17 RGB lighting modes\r\n\r\nKeycaps: Double-injection ABS with a 3-color combination\r\n\r\nBuild Material: ABS plastic', '1768384997_Fantech Atom 63 MK874V2 Mori Edition.jpg', '2026-01-14 10:03:17', 1),
(18, 'Atom HE68 Wired Magnetic Switch Keyboard', 10, 5999.00, 5, 'Model Number: MK811\r\n\r\nConnectivity: Wired\r\n\r\nInterface: USB-C\r\n\r\nOnboard Memory: Yes\r\n\r\nNumber of Keys: 68 keys\r\n\r\nAnti-ghosting: Full-keys anti-ghosting\r\n\r\nSwitch Type: Magnetic Switch\r\n\r\nSwitch Accuracy: 0.01 mm\r\n\r\nPolling Rate: 8000 Hz', '1768385068_Atom HE68 Wired Magnetic Switch Keyboard.jpg', '2026-01-14 10:04:28', 1),
(19, 'AFOX GT610 2GB DDR3', 13, 8999.00, 10, '*Graphics Engine	NVIDIA Geforce GT 610\r\n*Bus Standard	PCI Express 2.0\r\n*Video Memory	DDR3 2048MB/1024MB\r\n*Base Clock	810 MHz\r\n*Memory Clock	1333 MHz\r\n*RAMDAC	400MHz\r\n*Memory Interface	64-Bit\r\n*D-Sub Max Resolution	2048 x 1536\r\n*DVI Max Resolution	2560 x 1600', '1768385149_AFOX GT610 2GB DDR3.jpg', '2026-01-14 10:05:49', 1),
(20, 'Inno3D GeForce RTX 2060 Twin X2 6GB GDDR6', 13, 39000.00, 5, 'Solid for 1080p high settings gaming, real-time ray tracing, good cooling. Great middle choice.\r\nRTX 2060 TWIN X2 I 6GB GDDR6 192-bit\r\nCUDA Cores : 1920 I Boost Clock (MHz) : 1680\r\nMemory Clock : 14Gbps I Memory Interface Width : 192-bit\r\nBus Support : PCI-E 3.0 X16 I Maxmium Digital Resolution : 7680×4320\r\nStandard Display Connectors : HDMI 2.0b, 3x DisplayPort 1.4', '1768385204_Inno3D GeForce RTX 2060 Twin X2 6GB GDDR6.jpg', '2026-01-14 10:06:44', 1),
(21, 'PNY GeForce RTX 4080 16GB XLR8', 13, 189800.00, 5, 'PNY Part Number\r\nVCG408016TFXXPB1\r\nUPC Code\r\n751492756400\r\nCUDA Cores\r\n9728\r\nClock Speed\r\n2205 MHz\r\nBoost Speed\r\n2505 MHz\r\nMemory Speed (Gbps)\r\n22.4\r\nMemory Size\r\n16GB GDDR6X\r\nMemory Interface\r\n256-bit\r\nMemory Bandwidth (GB/sec)\r\n716.8\r\nTDP\r\n320 W\r\nOutputs\r\nDisplayPort 1.4a (x3), HDMI® 2.1\r\nMulti-Screen\r\n4\r\nResolution\r\n7680 x 4320 @120Hz (Digital)3Outputs\r\nDisplayPort 1.4a (x3), HDMI® 2.1\r\nMulti-Screen\r\n4\r\nResolution\r\n7680 x 4320 @120Hz (Digital)3Outputs\r\nDisplayPort 1.4a (x3), HDMI® 2.1\r\nMulti-Screen\r\n4\r\nResolution\r\n7680 x 4320 @120Hz (Digital)3', '1768385290_PNY GeForce RTX 4080 16GB XLR8.jpg', '2026-01-14 10:08:10', 1),
(22, 'Zotac Gaming GeForce GTX 1650 AMP 4GB GDDR6 Graphics Card', 13, 30500.00, 8, 'Zotac Gaming GeForce GTX 1650 AMP 4GB GDDR6 Graphics Card\r\nKey Features\r\nModel: Zotac Gaming GeForce GTX 1650 Super\r\nSuper Compact\r\n4K and HDR Ready\r\nDual 90mm Fan Design\r\nFireStorm Utility\r\nType	GDDR6	 \r\nSize	4GB	 \r\nResolution	7680 X 4320	 \r\nCore Clock	Boost: 1590 MHz	 \r\nMemory Clock	12 Gbps	 \r\nBUS Type	128-bit	 \r\nCUDA Cores	1280', '1768385450_Zotac Gaming GeForce GTX 1650 AMP 4GB GDDR6 Graphics Card.jpg', '2026-01-14 10:09:59', 1),
(23, 'Gigabyte GeForce RTX 5050 GAMING OC 8G - Graphics Card', 13, 51000.00, 5, 'Key Features\r\nPowered by the NVIDIA Blackwell architecture and DLSS 4\r\nPowered by GeForce RTX™ 5050\r\nIntegrated with 8GB GDDR6 128bit memory interface\r\nWINDFORCE cooling system /Hawk fan\r\nServer-grade Thermal conductive gel\r\nReinforced structure', '1768385535_Gigabyte GeForce RTX 5050 GAMING OC 8G - Graphics Card.jpg', '2026-01-14 10:12:15', 1),
(24, 'Dell E2422H (24″ IPS, Full HD, 60Hz)', 11, 19500.00, 10, 'PRODUCT THAT IS BUILT TO LAST: Product Weight 3.62 Kg\r\nNATIVE RESOLUTION: 1920 x 1080 at 60 Hz\r\nPANEL TYPE: IPS (In-Plane Switching)\r\nASPECT RATIO: 16:9\r\nBRIGHTNESS: 300 cd/m² (typical)\r\nRESPONSE TIME: 8 ms typical (Normal) 5 ms typical (Fast) (gray to gray)', '1768385632_Dell E2422H (24″ IPS, Full HD, 60Hz).jpg', '2026-01-14 10:13:52', 1),
(25, 'MSI G24C4 24″ Curved Gaming Monitor 144Hz', 11, 39500.00, 8, 'Brand\r\nMSI\r\n\r\nColor\r\nBlack\r\n\r\nModel\r\nG24C4\r\n\r\nDisplay Type\r\nLED\r\n\r\nScreen Size\r\n23.6 inch\r\n\r\nResolution\r\n1920×1080\r\n\r\nRefresh Rate\r\n144Hz\r\n\r\nResponse Time\r\n1ms (MPRT)\r\n\r\nBrightness\r\n250 cd/m²\r\n\r\nContrast Ratio\r\n3000:1\r\n\r\nAspect Ratio\r\n16:9\r\n\r\nColor Support\r\n16.78 million colors\r\n\r\nHDR Support\r\nNO', '1768385686_MSI G24C4 24″ Curved Gaming Monitor 144Hz.jpg', '2026-01-14 10:14:46', 1),
(26, 'Gigabyte M28U 28″ 4K Gaming Monitor 144Hz', 11, 98300.00, 3, 'Key Specification:\r\n28-inch UHD (3840 x 2160) SS IPS Display\r\n144Hz Display | 1ms Response Time\r\n94% DCI-P3/120% sRGB Color Gamut | 16.7M Display Colors\r\nHDMI 2.1, Display port 1.4, USB 3.2 Gen 1, USB 3.2 Gen 1, USB Type-C\r\n1 Year Warranty', '1768385726_Gigabyte M28U 28″ 4K Gaming Monitor 144Hz.jpg', '2026-01-14 10:15:26', 1),
(27, 'Asus VP228NE Eye Care Monitor – 21.5” FHD display, 100% sRGB, 1ms Response Time', 11, 18000.00, 10, 'General Description\r\nBrand	Asus\r\nModel	VP228NE\r\nType	Normal Monitor\r\nDisplay\r\nPanel Size (inch)	21.5-inch\r\nAspect Ratio	16:9\r\nDisplay Viewing Area (H x V)	476.64 x 268.11 mm\r\nDisplay Surface	Non-Glare\r\nBacklight Type	LED\r\nPanel Type	TN\r\nResolution	Full-HD (1920 x 1080 pixels)', '1768385858_image_1024.jpg', '2026-01-14 10:17:38', 1),
(28, 'Samsung 34&quot; Odyssey G55T WQHD 165Hz 1ms(MPRT) HDR Curved Gaming Monitor', 11, 144900.00, 5, 'Key Features\r\nMPN: LC34G55TWWNXZA\r\nModel: Samsung 34&quot; Odyssey G55T WQHD 165Hz\r\nResolution: WUQHD (3440x1440)\r\nDisplay: VA Panel, 165Hz, 1ms, HDR 10\r\nPorts: 1 x HDMI, 1 x DP, 1 x PC, 4 x USB, 1x3.5mm audio\r\nFeatures: Flicker-free, Eco light saver, Eye saving mode', '1768385996_samsung.jpg', '2026-01-14 10:19:56', 1),
(29, 'White Mid Tower PC Case with 3 RGB Fans', 12, 8500.00, 10, 'White Mid Tower Gaming Case with 3  RGB Fans\r\nTempered Glass Door that can be opened and closed easily\r\nDual Magnetic Dust Filter \r\nFront Mesh Filter\r\nMultiple Ventilation System for easy air circulation\r\nUnique Design complemented with White Color \r\n\r\nBasic steel frame, RGB fans, no big brand, simple features — good entry level.', '1768386066_White Mid Tower PC Case with 3 RGB Fans.jpg', '2026-01-14 10:21:06', 1),
(30, 'DeepCool MATREXX 50 MESH 4FS', 12, 9900.00, 8, 'MATREXX 50 MESH 4FS is a high airflow case that comes with four tri-color LED fans, a solid tempered glass panel, and supports up to E-ATX motherboards.\r\nFour 120mm fans come pre-installed in the MATREXX 50 MESH 4FS with three at front and one at back. The fans are lit up with a preset tri-color LED combination, simply plug in the power and enjoy the visual experience.\r\nMASSIVE COOLING CAPABILITY\r\nWith support for up to six 120mm or five 140mm cooling fans and also radiators up to 360mm installed in front or 240mm on top, the MATREXX 50 MESH 4FS provides ample cooling configurations.\r\nHIGH AIRFLOW DESIGN', '1768386111_DeepCool MATREXX 50 MESH 4FS.jpg', '2026-01-14 10:21:51', 1),
(31, 'NZXT H9 Flow Case', 12, 30500.00, 5, 'The H9 Flow is a spacious mid-tower ATX case built for high-performance builds, balancing airflow and aesthetics. Its dual-chamber design improves cooling and cable management, while perforated panels, angled fans, and ample fan and radiator support deliver optimal thermal performance.\r\n\r\nDual-Chamber Design: Separates main components from the PSU and drives for improved thermal performance and cable management.\r\nOptimized Airflow: Perforated steel panels and angled front-right fans ensure efficient cooling for high-performance builds.\r\nPre-Installed Fans: Includes three F140Q (CV) fans in the front-right and one F120Q (CV) fan in the rear. CV = Case Version (3-pin DC)', '1768386157_NZXT H9 Flow Case.jpg', '2026-01-14 10:22:37', 1),
(32, 'Armaggeddon Aquaron ATX Gaming PC Case', 12, 4500.00, 15, 'Key Features\r\nPanoramic tempered glass on front and side for showcase builds\r\nFish tank design offers advanced cooling performance\r\nSupports Micro-ATX motherboards with SSD integration\r\nStealth cable management system for clean routing\r\nFront I/O includes USB 3.0, USB 2.0 and HD audio port', '1768386260_Armaggeddon Aquaron ATX Gaming PC Case.jpg', '2026-01-14 10:24:20', 1),
(33, 'Thermaltake S100 Tempered Glass Snow Edition Micro Casing', 12, 7000.00, 8, 'Key Features\r\nModel: Thermaltake S100 Tempered Glass Snow\r\nType: Micro-ATX Casing\r\nMaterial: SPCC + Tempered glass\r\nPreinstalled Fans: 1x 200mm fan front &amp; 1x 120mm rear Fans\r\nTempered Glass x 1\r\nBrand	Thermaltake\r\nP/N	CA-1Q9-00S6WN-00\r\nSERIES	S Series\r\nMODEL	S100 TG Snow\r\nCASE TYPE	Micro Case\r\nDIMENSION (H X W X D)	441 x 220 x 411 mm\r\n(17.36 x 8.66 x 16.18 inch)\r\nNET WEIGHT	6.1 kg / 13.45 lbs.\r\nSIDE PANEL	Tempered Glass x 1\r\nCOLOR	Exterior &amp; Interior: White\r\nMATERIAL	SPCC', '1768386354_Thermaltake S100 Tempered Glass Snow Edition Micro Casing.jpg', '2026-01-14 10:25:54', 1),
(34, 'Corsair Vengeance LPX 16GB DDR4 3200MHz', 14, 6500.00, 10, 'Capacity: 16GB (1×16GB)\r\nType: DDR4 Desktop RAM\r\nSpeed: 3200MHz\r\nVoltage: 1.35V\r\nLow-profile aluminum heat spreader\r\nSupports Intel XMP 2.0\r\nIdeal for gaming and multitasking', '1768386531_Corsair Vengeance LPX 16GB DDR4 3200MHz.jpg', '2026-01-14 10:28:04', 1),
(35, 'Kingston Fury Beast 8GB DDR4 2666MHz', 14, 3200.00, 10, 'Capacity: 8GB\r\nSpeed: 2666MHz\r\nPlug-and-play performance\r\nLow power consumption\r\nCompatible with Intel &amp; AMD systems\r\nBest for office and budget PCs', '1768386524_Kingston Fury Beast 8GB DDR4 2666MHz.jpg', '2026-01-14 10:28:44', 1),
(36, 'GAMING Desktop RAM with Heat Sink - HyperX Fury -8GB 2933Mhz', 14, 8750.00, 7, 'GAMING Desktop RAM with Heat Sink - HyperX Fury -8GB 2933Mhz\r\nHyperX FURY DDR4 auto-overclocks itself to the highest published frequency, up to 3466MHz, providing a Plug N Play boost for gaming, video editing, and rendering. It&#039;s available in 2400MHz-3466MHz speeds.\r\nBrand	HyperX\r\nMemory Speed	2933 MHz\r\nItem model number	HX429C17FR2/8\r\nItem Weight	1.34 ounces\r\nProduct Dimensions	5.25 x 0.28 x 1.34 inches\r\nItem Dimensions LxWxH	5.25 x 0.28 x 1.34 inches\r\nColor	Red', '1768386858_GAMING Desktop RAM with Heat Sink - HyperX Fury -8GB 2933Mhz.jpg', '2026-01-14 10:34:18', 1),
(37, 'GAMING Desktop RAM HyperX Predator -16GB 3000Mhz', 14, 20000.00, 4, 'GAMING Desktop RAM HyperX Predator -16GB 3000Mhz\r\nHyperX Predator DDR4 memory has a fierce new heat spreader design in black aluminum for greater heat dissipation to optimize reliability. The heat spreader and PCB complement the look and design of the latest PC hardware, so you can dominate in HyperX style. \r\nBrand	HyperX\r\nMemory Speed	3000 MHz\r\nItem model number	HX430C15PB3K2/16\r\nItem Weight	2.88 ounces\r\nProduct Dimensions	5.25 x 1.23 x 0.13 inches\r\nItem Dimensions LxWxH	5.25 x 1.23 x 0.13 inches\r\nColor	Black', '1768386988_GAMING Desktop RAM HyperX Predator -16GB 3000Mhz.jpg', '2026-01-14 10:36:28', 1),
(38, 'Cooler Master MWE 550W 80+ Bronze', 16, 6000.00, 8, 'Cooler Master MWE 550W 80+ Bronze\r\nWattage: 550W\r\nEfficiency: 80+ Bronze Certified\r\nFan: 120mm Silent Fan\r\nOver-voltage &amp; short-circuit protection\r\nSuitable for mid-range gaming PC', '1768387061_Cooler Master MWE 550W 80+ Bronze.jpg', '2026-01-14 10:37:41', 1),
(39, 'Corsair CV650 650W 80+ Bronze', 16, 7200.00, 5, 'Corsair CV650 650W 80+ Bronze\r\n\r\nWattage: 650W\r\nNon-modular design\r\nStable and efficient power delivery\r\nSupports dedicated graphics cards\r\nIdeal for gaming and workstation PCs', '1768387094_Corsair CV650 650W 80+ Bronze.jpg', '2026-01-14 10:38:14', 1),
(40, 'Hunkey GS550 HK550-36PP Power Supply', 16, 7500.00, 8, 'Key Specification:\r\nModel: Huntkey Power Supply GS550\r\nConnectors: (4xSATA / 1xIDE power / 20+4 Pin / 2x4PIN / 2x6+2Pin(PCI-E))\r\nType: Intel ATX12V V2.2 / EPSS12V V.291\r\nMax Power: 550W\r\nNumber of Fans: 1\r\nNvidia SLI: Yes\r\nWarranty: 1 Years Warranty', '1768387164_Hunkey GS550 HK550-36PP Power Supply.jpg', '2026-01-14 10:39:24', 1),
(41, 'HUNTKEY POWER SUPPLY GX750 PRO', 16, 10000.00, 4, 'HUNTKEY POWER SUPPLY GX750 PRO\r\nKey Features:\r\nRated Power	750W\r\nPFC Type	A-PFC/180-264V~\r\n Energy Rating	80 PLUS Bronze(EU)\r\nTopology Structure	LLC+DC-DC\r\nCooling Fan	Dual ball bearing fan\r\n Mainboard Connector	20+4P\r\n CPU Connector	(4+4-4+4)P*1\r\n GPU Connector(1): (6+2-6+2)P*1	(1): (6+2-6+2)P*1\r\n GPU Connector(2)	(6+2)P*1\r\nDimensions	150*86*140mm', '1768387240_huntkey-gx750pro-power-supply-pack.jpg', '2026-01-14 10:40:40', 1),
(42, 'Samsung 970 EVO Plus 1TB NVMe SSD', 15, 14500.00, 10, 'Samsung 970 EVO Plus 1TB NVMe SSD\r\nCapacity: 1TB\r\nInterface: NVMe M.2 PCIe Gen 3\r\nRead Speed: Up to 3500MB/s\r\nWrite Speed: Up to 3300MB/s\r\nExcellent for gaming and video editing\r\nFast boot and load times', '1768387340_Samsung 970 EVO Plus 1TB NVMe SSD.webp', '2026-01-14 10:42:20', 1),
(43, 'WD Green 480GB SATA SSD', 15, 4200.00, 12, 'WD Green 480GB SATA SSD\r\n\r\nCapacity: 480GB\r\nInterface: SATA III\r\nRead Speed: Up to 545MB/s\r\nSilent and shock resistant\r\nIdeal for OS and daily applications', '1768387383_WD Green 480GB SATA SSD.jpg', '2026-01-14 10:43:03', 1),
(44, 'Seagate Barracuda 1TB HDD', 15, 3800.00, 8, 'Seagate Barracuda 1TB HDD\r\n\r\nCapacity: 1TB\r\nSpeed: 7200 RPM\r\nInterface: SATA III\r\nReliable long-term storage\r\nSuitable for backups and media files', '1768387421_Seagate Barracuda 1TB HDD.jpg', '2026-01-14 10:43:41', 1),
(45, 'PNY CS1031 500GB M.2 NVMe SSD', 15, 11675.00, 6, 'PNY CS1031 500GB M.2 NVMe SSD\r\nKey Features\r\nMPN: M280CS1031-500-CL\r\nModel: CS1031\r\nFaster boot-up\r\nRead: up to 2,200 MB/s\r\nWrite: up to 1,200 MB/s\r\nBetter System Performance', '1768387501_Key Features.jpg', '2026-01-14 10:45:01', 1),
(46, 'FANTECH WHG02P HARMONY PRO WIRELESS GAMING HEADSET', 17, 8999.00, 2, 'FANTECH WHG02P HARMONY PRO WIRELESS GAMING HEADSET\r\nImmersive Gaming Audio Experience \r\nMultiplatform Compatiblity \r\nLong Lasting Battery Life \r\nRGB Lighting', '1768387583_FANTECH WHG02P HARMONY PRO WIRELESS GAMING HEADSET.jpg', '2026-01-14 10:46:23', 1),
(47, 'EOS Vega GP15 Gaming Controller Wired', 19, 3999.00, 12, 'EOS Vega GP15 Gaming Controller Wired\r\nGP15 gaming controller \r\nConnectivity: Wired\r\nButtons: 21 keys, 12 programmable buttons\r\nSensors: 6-axis gyroscope\r\nRumble: 3-level adjustable rumble\r\nHeadset port: 3.5 mm\r\nCompatibility: PC/Steam, Switch, PS3, Android, Tesla Vehicles, Cloud Gaming/Game Pass\r\nWeight: 261 g', '1771062571_EOS Vega GP15 Gaming Controller Wired.jpg', '2026-01-14 10:47:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `created_at`) VALUES
(11, 'Admin User', 'admin@ches.com', '$2y$10$0hHGrR5We8/v.EIoW48IU.c6Q7KY22drJ5Kqizw4ZXE/IXfOL7EGy', NULL, NULL, 'admin', '2026-01-14 09:04:25'),
(12, 'Prem Adhikari', 'premadh7@gmail.com', '$2y$10$c.re6msDpp0kGCNmNQQ/8up7IhsItz6LPYtr24CAiMMqnpKDxSyGy', '9840883886', 'Balambu', 'customer', '2026-01-14 09:04:53'),
(13, 'Pushpa Adhikari', 'pushpaadh90@gmail.com', '$2y$10$XuwvfqMeNr8nXES34d23Ree5Z7spkLrEMcRQez3T2jOEvRa/Dih8.', '9761612988', 'Balambu', 'customer', '2026-02-05 12:05:58'),
(14, 'Goma adh', 'goma@gmail.com', '$2y$10$KVfp7Ei2f1S6fUAU36x2mOsMTo7jK/ROyuWCY15/o1nrcqG0jOcne', '9761612988', 'Balambu', 'customer', '2026-02-13 09:10:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
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
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
