-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 06, 2023 at 11:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `expense_name` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `paid_by` int(11) DEFAULT NULL,
  `owe_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_splits`
--

CREATE TABLE `expense_splits` (
  `split_id` int(11) NOT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `admin_id`, `created_at`) VALUES
(77, 'group1', 37, '2023-12-05 16:22:46'),
(78, 'walmart', 37, '2023-12-05 16:31:25'),
(79, 'walmart', 37, '2023-12-05 16:33:05'),
(80, 'walmart', 37, '2023-12-05 16:58:58'),
(81, 'chicago group', 37, '2023-12-05 17:00:28'),
(82, 'chicago group', 37, '2023-12-05 17:00:42'),
(83, 'walmart', 37, '2023-12-05 17:02:11'),
(84, 'group5', 37, '2023-12-05 17:10:54'),
(85, 'group7', 37, '2023-12-05 17:30:07'),
(86, 'rambharose', 38, '2023-12-05 19:48:16'),
(87, 'gimmmi', 38, '2023-12-05 20:34:32'),
(88, 'lllll', 38, '2023-12-05 21:05:29'),
(89, 'walmart', 39, '2023-12-05 21:41:15'),
(90, 'haridwar', 40, '2023-12-05 21:45:16'),
(91, 'kohl', 41, '2023-12-05 22:21:08'),
(92, 'saintlouise', 50, '2023-12-06 16:52:41'),
(93, 'chicago group', 50, '2023-12-06 16:52:45'),
(94, 'kohl', 50, '2023-12-06 16:53:11'),
(95, 'chicago group', 50, '2023-12-06 17:10:34'),
(96, 'walmart', 51, '2023-12-06 19:18:36'),
(97, 'new', 50, '2023-12-06 19:46:19'),
(98, 'newGroup', 50, '2023-12-06 19:46:55'),
(99, 'now_new', 50, '2023-12-06 19:47:53'),
(100, 'now_new', 50, '2023-12-06 19:50:08'),
(101, 'now_new', 50, '2023-12-06 19:50:50'),
(102, 'chicago group', 50, '2023-12-06 19:57:15'),
(103, 'walmart', 50, '2023-12-06 19:58:18'),
(104, 'walmart', 50, '2023-12-06 20:00:52'),
(105, 'walmart', 50, '2023-12-06 20:07:40'),
(106, 'walmart', 50, '2023-12-06 20:08:27'),
(107, 'walmart', 50, '2023-12-06 20:10:58'),
(108, 'ratata', 50, '2023-12-06 20:11:14'),
(116, 'chicago group', 50, '2023-12-06 20:43:10'),
(117, 'ratata', 50, '2023-12-06 20:48:36'),
(118, 'ratata', 50, '2023-12-06 21:01:46'),
(119, 'ratata', 50, '2023-12-06 21:07:34'),
(120, 'ratata', 50, '2023-12-06 21:08:52'),
(121, 'ratata', 50, '2023-12-06 21:12:23'),
(122, 'ratata', 50, '2023-12-06 21:13:56'),
(123, 'ratata', 50, '2023-12-06 21:16:31');

-- --------------------------------------------------------

--
-- Table structure for table `group_expenses`
--

CREATE TABLE `group_expenses` (
  `expense_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `expense_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `paid_by` varchar(25) DEFAULT NULL,
  `owe_to` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_expenses`
--

INSERT INTO `group_expenses` (`expense_id`, `group_id`, `expense_name`, `amount`, `date`, `paid_by`, `owe_to`) VALUES
(1, 94, 'groceries', 78687.00, '2023-00-00', NULL, NULL),
(2, 95, 'groceries', 78687.00, '2023-00-00', NULL, NULL),
(3, NULL, 'LOKER', 23.00, '2023-12-04', NULL, NULL),
(4, NULL, 'groceries', 67.00, '2023-12-06', '1', '1'),
(5, NULL, 'groceries', 67.00, '2023-12-06', '1', '1'),
(6, 108, 'groceries', 77.00, '2023-12-27', '1', '1'),
(7, 108, 'groceries', 77.00, '2023-12-27', '1', '1'),
(8, 108, 'groceries', 77.00, '2023-12-27', '1', '1'),
(9, 108, 'groceries', 77.00, '2023-12-27', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `member_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `user_id`, `created_at`, `member_name`) VALUES
(151, 80, NULL, '2023-12-05 17:10:00', 'vanshit'),
(164, 0, 37, '2023-12-05 18:09:05', 'hjgjh'),
(165, 0, 37, '2023-12-05 18:09:31', 'aaaaa'),
(166, 0, 37, '2023-12-05 18:15:31', 'vanshit'),
(167, 0, 37, '2023-12-05 19:45:51', 'vanshit'),
(168, 0, 38, '2023-12-05 19:48:24', 'bbb'),
(169, 0, 38, '2023-12-05 19:48:28', 'cccc'),
(170, 0, 38, '2023-12-05 19:55:14', 'cccc'),
(171, 0, 38, '2023-12-05 19:56:18', 'cccc'),
(172, 0, 38, '2023-12-05 19:56:40', 'cccc'),
(173, 0, 38, '2023-12-05 19:56:43', 'cccc'),
(174, 0, 38, '2023-12-05 19:57:05', 'cccc'),
(175, 0, 38, '2023-12-05 20:19:35', 'mmmmm'),
(176, 0, 38, '2023-12-05 20:26:37', 'mmmmm'),
(177, 86, NULL, '2023-12-05 20:33:47', 'vanshit'),
(178, 0, 38, '2023-12-05 20:34:40', 'nnnnnn'),
(179, 86, NULL, '2023-12-05 21:01:22', 'vanshit'),
(180, 86, NULL, '2023-12-05 21:10:59', 'seeta'),
(181, 87, NULL, '2023-12-05 21:15:23', 'anshit'),
(182, 87, NULL, '2023-12-05 21:17:28', 'anshit'),
(183, 87, NULL, '2023-12-05 21:29:25', 'anshit'),
(184, 89, NULL, '2023-12-05 21:41:32', 'ram'),
(185, 90, NULL, '2023-12-05 21:45:37', 'meenal'),
(186, 90, NULL, '2023-12-05 21:47:03', 'meenal'),
(187, 91, NULL, '2023-12-05 22:21:22', 'ram'),
(188, 77, NULL, '2023-12-06 17:35:44', ''),
(189, 77, NULL, '2023-12-06 17:36:58', ''),
(190, 79, NULL, '2023-12-06 17:39:37', ''),
(191, 77, NULL, '2023-12-06 17:39:55', ''),
(192, 94, NULL, '2023-12-06 17:49:44', 'vanshit'),
(193, 92, NULL, '2023-12-06 17:59:27', 'vanshit'),
(194, 84, NULL, '2023-12-06 18:04:20', ''),
(195, 86, NULL, '2023-12-06 18:16:41', ''),
(196, 86, NULL, '2023-12-06 18:36:32', ''),
(197, 80, NULL, '2023-12-06 18:51:44', ''),
(198, 96, NULL, '2023-12-06 19:18:42', ''),
(199, 90, NULL, '2023-12-06 19:32:48', ''),
(200, 95, NULL, '2023-12-06 19:46:28', ''),
(201, 97, NULL, '2023-12-06 19:46:37', ''),
(202, 98, NULL, '2023-12-06 19:52:32', ''),
(203, 84, NULL, '2023-12-06 19:53:49', ''),
(204, 98, NULL, '2023-12-06 20:11:04', ''),
(205, 80, NULL, '2023-12-06 20:42:59', ''),
(206, NULL, NULL, '2023-12-06 20:47:49', 'ram'),
(207, NULL, NULL, '2023-12-06 20:48:40', 'ram'),
(208, NULL, NULL, '2023-12-06 20:50:50', 'ram13'),
(209, NULL, NULL, '2023-12-06 20:53:54', 'ram13'),
(210, NULL, NULL, '2023-12-06 20:54:38', 'ram'),
(211, NULL, NULL, '2023-12-06 21:01:52', 'anshit'),
(212, NULL, NULL, '2023-12-06 21:07:42', 'anshit'),
(213, NULL, NULL, '2023-12-06 21:08:57', 'sxqs'),
(214, NULL, NULL, '2023-12-06 21:12:26', 'sxqs'),
(215, NULL, NULL, '2023-12-06 21:13:59', 'ahgjhhjs'),
(216, 108, NULL, '2023-12-06 21:16:35', 'anshit');

-- --------------------------------------------------------

--
-- Table structure for table `individual_expenses`
--

CREATE TABLE `individual_expenses` (
  `expense_id` int(11) NOT NULL,
  `ID` int(11) DEFAULT NULL,
  `expense_amount` decimal(10,2) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `deductible` tinyint(1) DEFAULT NULL,
  `expense_name` varchar(255) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `individual_expenses`
--

INSERT INTO `individual_expenses` (`expense_id`, `ID`, `expense_amount`, `expense_date`, `deductible`, `expense_name`, `group_id`) VALUES
(14, 31, 78687.00, '2023-12-14', 1, 'pooja', NULL),
(15, 33, 72828.00, '2023-12-14', 1, 'groceries', NULL),
(16, 40, 0.00, '0000-00-00', 0, '', NULL),
(17, 40, 57657.00, '2023-12-06', 1, 'groceries', NULL),
(18, NULL, 76868.00, '2023-12-15', 1, 'moneymarket', NULL),
(19, NULL, 10.00, '2023-12-06', 1, 'groceries', NULL),
(20, NULL, 20.00, '2023-12-06', 1, 'new', NULL),
(21, NULL, 30.00, '2023-12-06', 1, 'LOKER', NULL),
(22, NULL, 23.00, '2023-12-05', 1, 'groceries', NULL),
(23, NULL, 12.00, '2023-12-07', 1, 'LOKER', NULL),
(24, NULL, 12.00, '2023-12-06', 1, 'pooja', NULL),
(25, NULL, 12.00, '2023-12-06', 1, 'LOKER', NULL),
(26, NULL, 12.00, '2023-12-06', 1, '2weqw', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

CREATE TABLE `user_form` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`user_id`, `name`, `email`, `password`, `user_type`) VALUES
(32, 'dane', 'dane@gmail.com', '$2y$10$Vf2Ob62ugKlB5LXY3YM6xOM3nU8ZT6cNOhTwIBDU/6kWivgN4r5eq', 'admin'),
(33, 'tom', 'tom@gmail.com', '$2y$10$6csiKa7G8DCb7DVoHGzm7OZRmg4E0ZJILbpQ7vZsdyi6sS47rkMvG', 'admin'),
(34, 'gyan', 'gyan@gmail.com', '$2y$10$YPHm32HBJ5nZONvvLMuQ0ujx5KhCne6OJ8xn445KZAoDoh5Svkgo6', 'admin'),
(35, 'addu', 'addu@gmail.com', '$2y$10$RWKnUnEpQtY7pyDPtekvleumnmI4x6SIkl0sOQEeBvFq5NmrIsq7a', 'user'),
(36, 'mishka', 'mishka@gmail.com', '$2y$10$PjAZ30u4CryIgQHazEFKGOLOsvmVZW5ce3CsMOQaYc8vtxFpbhkpS', 'user'),
(37, 'abc', 'abcd@gmail.com', '$2y$10$PNgyhgxKXtkankTLJx4S9OX0s0Ix.DX3qFQedXzdBPDexIkXXrKdi', 'admin'),
(38, 'gyan2', 'gyan2@gmail.com', '$2y$10$L.uDhBwyKg04nRrmS6EM0OSOVvkb48sKNC7HAMxOyqxW7lUh5xR8u', 'admin'),
(39, 'hari1', 'hari1@gmail.com', '$2y$10$OTwhrcg1kluU9wrLC8THD.hp6IYJpj8utmK2Rvk8cckZ4tplgB/Du', 'admin'),
(40, 'hari3', 'hari3@gmail.com', '$2y$10$1mc8Nw7FpWoQwZXlZFs7ku8Eyi2Pwl2ZCIhQ1L1gkcHlEW6kstcNC', 'admin'),
(41, 'LOVE', 'LOVE@GMAIL.COM', '$2y$10$dLgS9PNuk/WqjnZNAItNhOGR6oEYhq9HQA8Gm.OvEmtc0KSita6fy', 'admin'),
(42, 'naman9', 'naman9@gmail.com', '020058ee7e27fb66b292c2b48361ba92', 'user'),
(43, 'Jyotsna Gaurav Agrawal', 'jg@gmail.com', '020058ee7e27fb66b292c2b48361ba92', 'user'),
(44, 'Gaurav', 'grv@grv.com', '$2y$10$9BUcyRvt5twUpOxbmEIj0Oh86BcXI5ay6DVK35M7SO2AJJIeHYH9S', 'admin'),
(45, 'Gaurav', 'abc@abc.com', '202cb962ac59075b964b07152d234b70', 'user'),
(46, 'Gaurav', 'def@def.com', '202cb962ac59075b964b07152d234b70', 'admin'),
(47, 'manu', 'manu@gmail.com', '020058ee7e27fb66b292c2b48361ba92', 'admin'),
(48, 'addu', 'addu@addu.com', '202cb962ac59075b964b07152d234b70', 'user'),
(49, 'jyyyyy', 'jyyyy@ju.com', '$2y$10$2lOcxyw9j05vKjbVRyv2reRiOf9PE61imhjujKybZ/CP.HDJoWPAC', 'admin'),
(50, 'ban', 'ban@gmail.com', '$2y$10$ovzlqTgQhu86AEoNZjZud.Ob39LITwT6hwDggWXcMS4eDlP.nwvfe', 'user'),
(51, 'pooo', 'PO@gmail.com', '$2y$10$bkNLa7izxk3NDV4fbsosreMRDxUxgbQpjPuAZP6PaW0ql1YZ8ctcW', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `fk_paid_by` (`paid_by`),
  ADD KEY `fk_owe_to` (`owe_to`);

--
-- Indexes for table `expense_splits`
--
ALTER TABLE `expense_splits`
  ADD PRIMARY KEY (`split_id`),
  ADD KEY `expense_id` (`expense_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `group_expenses`
--
ALTER TABLE `group_expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_group_members_groups` (`group_id`);

--
-- Indexes for table `individual_expenses`
--
ALTER TABLE `individual_expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `ID` (`ID`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `expense_splits`
--
ALTER TABLE `expense_splits`
  MODIFY `split_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `group_expenses`
--
ALTER TABLE `group_expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT for table `individual_expenses`
--
ALTER TABLE `individual_expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  ADD CONSTRAINT `fk_owe_to` FOREIGN KEY (`owe_to`) REFERENCES `group_members` (`user_id`),
  ADD CONSTRAINT `fk_paid_by` FOREIGN KEY (`paid_by`) REFERENCES `group_members` (`user_id`);

--
-- Constraints for table `expense_splits`
--
ALTER TABLE `expense_splits`
  ADD CONSTRAINT `expense_splits_ibfk_1` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`expense_id`),
  ADD CONSTRAINT `expense_splits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `group_members` (`user_id`);

--
-- Constraints for table `group_expenses`
--
ALTER TABLE `group_expenses`
  ADD CONSTRAINT `group_expenses_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`);

--
-- Constraints for table `individual_expenses`
--
ALTER TABLE `individual_expenses`
  ADD CONSTRAINT `individual_expenses_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
