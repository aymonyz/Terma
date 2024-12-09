-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 09 ديسمبر 2024 الساعة 19:28
-- إصدار الخادم: 9.0.1
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `terma`
--

-- --------------------------------------------------------

--
-- بنية الجدول `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'a@a', '$2y$10$nGXqJp52phZJrkpoZXx8tezXjJskkx5Ix3KdnCl7L1fPLGCi5Y.C.');

-- --------------------------------------------------------

--
-- بنية الجدول `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category_image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`, `category_image`) VALUES
(6, 'Medical Supplies', '2024-12-07 20:48:08', 'uploads/categories/Upcoming Projects.png'),
(7, 'Dental Solutions', '2024-12-07 20:49:04', 'uploads/categories/Web Designs.png'),
(8, 'Diagnostic Solutions', '2024-12-07 20:49:19', 'uploads/categories/Blockchain Services.png');

-- --------------------------------------------------------

--
-- بنية الجدول `devices`
--

CREATE TABLE `devices` (
  `id` int NOT NULL,
  `emp_id` int NOT NULL,
  `device_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `device_description` text COLLATE utf8mb4_general_ci,
  `category_id` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `devices`
--

INSERT INTO `devices` (`id`, `emp_id`, `device_name`, `device_description`, `category_id`, `price`, `stock_quantity`, `created_at`, `image_path`) VALUES
(5, 4, 'dsds', 'deqwdasdded\r\nd\r\ndsasd\r\nsad\r\nawd\r\nas\r\n', 6, 3.00, 23, '2024-12-08 13:18:55', 'uploads/devices/Screenshot 1446-05-23 at 12.57.31 PM.png'),
(6, 4, 'dsds', 'deqwdasdded\r\nd\r\ndsasd\r\nsad\r\nawd\r\nas\r\n', 6, 3.00, 23, '2024-12-08 13:19:06', 'uploads/devices/Screenshot 1446-05-23 at 12.57.31 PM.png'),
(7, 4, 'dsds', '3dewdfewf\r\nerf\r\nerw\r\nfer\r\nf\r\ner\r\nfergeroghejroigjbioprejbgui\\\r\ngpdjgio', 6, 10.00, 32, '2024-12-08 13:58:00', 'uploads/devices/‏لقطة الشاشة ١٤٤٦-٠٥-٠٢ في ١٢.٢٤.٠٨ م.png'),
(8, 4, 'لاتلبتبلت', 'yteryrtlkhjkrthjrt\r\nrt jrth jrtj \r\nrtj\r\nk \r\n[rtskjpo mrtopj mrst\r\nj \r\nmj pojpojoimtyjmoitymj odtyj\r\ndtyp\r\nom rtmjrst\r\njrs', 8, 34.00, 4, '2024-12-08 17:04:00', 'uploads/devices/‏لقطة الشاشة ١٤٤٦-٠٥-٠٢ في ١٢.٢٤.٠٨ م.png'),
(9, 4, 'مازن', 'lkjdfsghjkjdgh hg fgcvhkgf f uy ff kuyf kf kufg kghf khg v\r\n', 6, 700.00, 50, '2024-12-08 18:56:21', 'uploads/devices/‏لقطة الشاشة ١٤٤٦-٠٤-٢٥ في ٦.٢٥.٤٤ م.png'),
(10, 1, 'etawrgerg', 'sdsdsdsdssds\r\nds\r\ndsdsdsds\r\ndsdsds', 8, 10.00, 2, '2024-12-08 20:12:51', 'uploads/devices/Screenshot 1446-05-28 at 11.41.30 PM.png'),
(11, 1, 'etawrgerg', 'شسؤيشسؤسش', 6, 10.00, 81, '2024-12-09 14:10:06', 'uploads/devices/Screenshot 1446-06-06 at 6.12.59 PM.png');

-- --------------------------------------------------------

--
-- بنية الجدول `emp`
--

CREATE TABLE `emp` (
  `id` int NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` int NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `job_title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `emp`
--

INSERT INTO `emp` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `city`, `job_title`, `created_by`, `created_at`) VALUES
(1, 'asssssssssssssssssssssss', 'saeed', 'dd@dd', '$2y$10$7P/mH7Pg/c3zp6dzIhUZOO/hUDhy3InQi92ZmOfnaM9B1c74b5vqa', 537146467, '2222', 'ewfef', 1, '2024-12-08 19:36:02'),
(3, 'ayman', 'abdelbagi', 'aa@aa.com', '$2y$10$cjMeJfbYLuVQYb1PTJk.OeGe7AE3q.G.PAZf0Q47Ys6wSFeAPyKD2', 531907793, 'المدينة المنورة', 'aymonyz', 1, '2024-12-08 12:25:01'),
(4, 'ayman', 'abdelbagi', 'm@m', '$2y$10$NPfj78j78s5Gq59rqsEQEu5iTJ9iNG.QIHEBJ03gqFm96uPodcyAy', 531907793, 'المدينة المنورة', 'جامعي', 1, '2024-12-08 18:19:00'),
(5, 'ayman', 'abdelbagi', 'Wdalzho@gmail.com', '$2y$10$8aDFhFttuJt7xwTqNBsjuu21HTwTNiYxQjjSl83vD1EPVVOi6/z/C', 531907793, 'لباتلتلا', 'جامعي', 1, '2024-12-08 18:24:33'),
(6, 'dsdssd', 'dssddsdsd', 'asdsd@a', '$2y$10$XlVOLCrPoZLGv6.8lTOD5.6jXZnJgIFY4NE.yljwPO2B/x.OVQ88G', 531907793, 'المدينة المنورة', 'جامعي', 1, '2024-12-08 19:23:23'),
(7, 'يبلبيل', 'abdelbagi', 'azqqa@aza', '$2y$10$7P4qNURIzpjPxVkyvN7a3eGMfpw2dWSe22UmwWpOSe/TDEFV2hw2a', 531907793, 'المدينة المنورة', 'بيلبي', 1, '2024-12-09 14:04:19');

-- --------------------------------------------------------

--
-- بنية الجدول `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` int NOT NULL,
  `device_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `issue_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_contact` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `device_name`, `issue_description`, `user_name`, `user_contact`, `status`, `created_at`) VALUES
(1, 'etawrgerg', 'بيبيرقاقرسقفترق', 'قفقفقفقففققف', '٦٥٤٦٨١٤٥٦١٨١٦', 'قيد التنفيذ', '2024-12-09 16:46:55'),
(2, 'etawrgerg', 'fcfsds fd df\\r\\n \\r\\ndfkjsdflg a\\r\\n \\r\\na kjfahk;a', 'fdfdfdfd', '٦٥٤٦٨١٤٥٦١٨١٦', NULL, '2024-12-09 18:18:07');

-- --------------------------------------------------------

--
-- بنية الجدول `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `device_id` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `created_at` datetime NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `device_id`, `quantity`, `total_price`, `status`, `created_at`, `user_id`) VALUES
(1, 'ahmed', 10, 2, 912912.00, 'approved', '2024-12-08 19:16:13', 12),
(2, 'ssssssssssssssss', 10, 1, 456456.00, 'rejected', '2024-12-08 20:02:51', 0),
(3, 'aqqqqqqqqqqqqqqqqqq', 10, 1, 456456.00, 'approved', '2024-12-08 20:05:13', 12),
(4, 'بلا', 8, 1, 34.00, 'pending', '2024-12-08 20:54:59', 1),
(5, 'dssddssdsdsdsdsdsd', 4, 1, 456456.00, 'approved', '2024-12-09 14:58:38', 1),
(6, 'dssddssdsdsdsdsdsd', 8, 1, 34.00, 'pending', '2024-12-09 14:58:38', 1),
(7, 'ايمن', 4, 1, 456456.00, 'rejected', '2024-12-09 15:02:09', 1),
(8, 'ايمن', 5, 1, 3.00, 'rejected', '2024-12-09 15:02:09', 1),
(9, 'ايمن', 6, 1, 3.00, '0', '2024-12-09 15:02:09', 1),
(10, 'ايمن', 9, 1, 700.00, 'approved', '2024-12-09 15:02:09', 1),
(11, 'ايمن', 8, 1, 34.00, '0', '2024-12-09 15:02:09', 1),
(12, 'ايمن', 7, 1, 10.00, '0', '2024-12-09 15:02:09', 1),
(13, 'ايمن', 10, 1, 10.00, '0', '2024-12-09 15:02:09', 1),
(14, 'محمد', 4, 3, 1369368.00, 'approved', '2024-12-09 15:03:30', 1),
(15, 'سسسسسسسسس', 5, 1, 3.00, '2', '2024-12-09 16:08:38', 20),
(16, 'سسسسسسسسس', 6, 1, 3.00, '0', '2024-12-09 16:08:38', 20),
(17, 'سسسسسسسسس', 7, 1, 10.00, '0', '2024-12-09 16:08:38', 20);

-- --------------------------------------------------------

--
-- بنية الجدول `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` int NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `device_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `birth_date` date NOT NULL,
  `phone` int NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `job_title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `password`, `gender`, `birth_date`, `phone`, `city`, `job_title`, `created_at`) VALUES
(15, 'ayman', 'abdelbagi', 'fff@example.com', '$2y$10$RcaSU9eifQodmsrSYx1Iceh3eQspYIYhnXWnCcCsfMraYvlfulqbu', 'Female', '2024-12-04', 531907793, '22222', 'aymonyz', '2024-12-08 19:36:11'),
(16, 'ayman', 'abdelbagi', 'a@a', '$2y$10$qRln5ryZuKUnmKALUmGbEOFwyG0Uf/1bO6z1A7S9/GTN.jjXdL8WS', 'Female', '2024-12-11', 531907793, 'المدينة المنورة', 'مبرمج', '2024-12-09 09:45:57'),
(17, 'ayman', 'abdelbagi', 'a223@a', '$2y$10$H./pPDhZ0szzAdqKFwHRNeYM.lSscLSfMuTFA1Yx4HBstNpn/5YFG', 'أنثى', '2024-12-26', 531907793, 'المدينة المنورة', 'مشرفه', '2024-12-09 09:46:42'),
(18, 'ayman', 'abdelbagi', 'a332@a', '$2y$10$LQr/x8.GjCU53lycmqP2DuLtfYcsqj5pB9XkYQbGKDnqMazSd5uVe', 'أنثى', '2024-12-17', 531907793, 'المدينة المنورة', 'مشرفه', '2024-12-09 09:46:56'),
(19, 'ayman', 'abdelbagi', 'aza@aza', '$2y$10$86unjI0xwRxQav2kxVKvy.SXm0Qja26k3PWIjjKIYsoHSB0z3Hw6a', 'أنثى', '2024-12-18', 531907793, 'المدينة المنورة', 'قفغقفغ', '2024-12-09 13:56:53'),
(20, '2222khald', 'alazher', 'azqqa@aza', '$2y$10$vsgGhWxJnymc4EkBfX65NOuypbmRw4s7mT38g2sV3tFDMGBV4f8uK', 'أنثى', '2024-12-18', 531907793, 'al madinah al munawwarah', 'aymonyz', '2024-12-09 13:57:41'),
(22, 'ayman', 'abdelbagi', 'azdssdsdsdsqqa@aza', '$2y$10$GitwzbSOCvpZgVMDZnvZG.ewhtogC/fqyKDT1sALt/zU9RiLkwFjG', 'Female', '2024-12-13', 531907793, 'المدينة المنورة', 'dsds', '2024-12-09 12:58:36'),
(23, 'ayman', 'abdelbagi', 'azqqssa@aza', '$2y$10$RKA6Y/sCDoxj.maDSf//femN40vZiXfkYLgNsh2kygMPiFI1anBZW', 'Male', '2024-12-10', 531907793, 'المدينة المنورة', 'سيسسي', '2024-12-09 13:14:33'),
(24, 'ayman', 'abdelbagi', 'azqq564a@aza', '$2y$10$w55Soc4uNldtbmYDmjICdu/YND7UyQHIWhode1yCK5CJSb5gTUGvC', 'Female', '2024-12-17', 531907793, 'المدينة المنورة', 'fht', '2024-12-09 13:34:48'),
(25, 'ayman', 'abdelbagi', 'azqsssqssa@aza', '$2y$10$IwtxjniXqqpNS7be8XG82eKeDD.V741K5SNfufM.lNDTpQvIdA6H2', 'Female', '2024-12-18', 531907793, 'المدينة المنورة', 'sddssd', '2024-12-09 13:53:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `emp`
--
ALTER TABLE `emp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `emp`
--
ALTER TABLE `emp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- قيود الجداول `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD CONSTRAINT `purchase_requests_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
