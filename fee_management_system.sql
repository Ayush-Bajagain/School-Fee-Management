-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 04:59 PM
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
-- Database: `fee management system`
--

-- --------------------------------------------------------

--
-- Table structure for table `fee_details`
--

CREATE TABLE `fee_details` (
  `student_id` varchar(20) NOT NULL,
  `total_fee` decimal(10,2) NOT NULL,
  `paid_fee` decimal(10,2) NOT NULL,
  `due_fee` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fee_details`
--

INSERT INTO `fee_details` (`student_id`, `total_fee`, `paid_fee`, `due_fee`) VALUES
('kcmit1001', 400000.00, 0.00, 400000.00),
('kcmit1002', 400000.00, 0.00, 400000.00);

-- --------------------------------------------------------

--
-- Table structure for table `makepayment`
--

CREATE TABLE `makepayment` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `program` varchar(12) NOT NULL,
  `batch` varchar(12) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `transaction_id` int(11) DEFAULT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `remark` varchar(300) DEFAULT NULL,
  `program` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` varchar(30) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(40) NOT NULL,
  `username` varchar(255) NOT NULL,
  `batch` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `contact_number` varchar(10) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `photo` varchar(512) NOT NULL,
  `current_address` varchar(255) NOT NULL,
  `permanent_address` varchar(255) NOT NULL,
  `program` varchar(20) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `mother_name` varchar(255) NOT NULL,
  `batch` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `full_name`, `contact_number`, `email`, `photo`, `current_address`, `permanent_address`, `program`, `father_name`, `mother_name`, `batch`) VALUES
('kcmit1001', 'Ayush Bajagain', '9749334997', 'ayushbajagain0007@gmail.com', '66e706f479cf2-ayush.jpg', 'Koteshwor, Kathmandu', 'Mandandeupur-3, Kavre', 'bca', 'HP Bajagain', 'Kamala Bajagian', '2022'),
('kcmit1002', 'Sahjat Ansari', '9819937397', 'sahzadansri3@gmail.com', '66e96026d6bae-Screenshot 2024-09-17 163542.png', 'Old-Baneshwor, Kathmandu', 'Siraha', 'bca', 'MD Ali', 'Rabiya Khatun', '2020');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `transaction_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `remark` varchar(300) NOT NULL,
  `photo` varchar(300) NOT NULL,
  `batch` varchar(30) DEFAULT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `program` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL,
  `user_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `email`, `password`, `role`, `user_id`) VALUES
('Admin', 'admin@admin.com', 'admin', 'admin', 'kcmit_admin'),
('Ayush', 'ayushbajagain0007@gmail.com', 'Ayush1212', 'student', 'kcmit1001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fee_details`
--
ALTER TABLE `fee_details`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `makepayment`
--
ALTER TABLE `makepayment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`transaction_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `makepayment`
--
ALTER TABLE `makepayment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fee_details`
--
ALTER TABLE `fee_details`
  ADD CONSTRAINT `fee_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
