-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 02:56 AM
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
-- Table structure for table `advance_payment`
--

CREATE TABLE `advance_payment` (
  `student_id` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `program` varchar(20) DEFAULT NULL,
  `batch` varchar(30) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `payment_purpose` varchar(30) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `remark` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('kcmit1001', 400000.00, 0.00, 400000.00);

-- --------------------------------------------------------

--
-- Table structure for table `individual_payment`
--

CREATE TABLE `individual_payment` (
  `payment_id` int(11) NOT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `student_email` varchar(40) DEFAULT NULL,
  `batch` varchar(30) DEFAULT NULL,
  `program` varchar(30) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `payment_purpose` varchar(40) DEFAULT NULL,
  `comment` varchar(400) DEFAULT NULL,
  `entered_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `message` varchar(255) NOT NULL,
  `payment_field` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_fee_details`
--

CREATE TABLE `other_fee_details` (
  `student_id` varchar(50) DEFAULT NULL,
  `paid_amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `other_fee_details`
--

INSERT INTO `other_fee_details` (`student_id`, `paid_amount`) VALUES
('kcmit1001', 0);

-- --------------------------------------------------------

--
-- Table structure for table `other_payment`
--

CREATE TABLE `other_payment` (
  `id` int(11) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `program` varchar(12) DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `payment_field` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_transaction_details`
--

CREATE TABLE `other_transaction_details` (
  `transaction_id` int(11) NOT NULL,
  `amount` double DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `batch` varchar(30) DEFAULT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `program` varchar(30) DEFAULT NULL,
  `payment_purpose` varchar(30) DEFAULT NULL
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
  `program` varchar(20) DEFAULT NULL,
  `payment_purpose` varchar(30) DEFAULT NULL,
  `amount` double DEFAULT NULL
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
  `batch` varchar(50) DEFAULT NULL,
  `program` varchar(30) DEFAULT NULL
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
('kcmit1001', 'Ayush Bajagain', '9712345678', 'ayushbajagain@gmail.com', '674c6789a5fd5-Screenshot 2024-12-01 192450.png', 'Kathmandu', 'Kaver', 'bca', 'HP Bajagain', 'K Bajagian', '2022');

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
  `program` varchar(30) DEFAULT NULL,
  `payment_purpose` varchar(30) DEFAULT NULL
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
('Ayush', 'ayushbajagain@gmail.com', 'Ayush1212', 'student', 'kcmit1001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fee_details`
--
ALTER TABLE `fee_details`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `individual_payment`
--
ALTER TABLE `individual_payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `makepayment`
--
ALTER TABLE `makepayment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_fee_details`
--
ALTER TABLE `other_fee_details`
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `other_payment`
--
ALTER TABLE `other_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_transaction_details`
--
ALTER TABLE `other_transaction_details`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`transaction_id`),
  ADD UNIQUE KEY `unique_transaction_id` (`transaction_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `individual_payment`
--
ALTER TABLE `individual_payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100003;

--
-- AUTO_INCREMENT for table `makepayment`
--
ALTER TABLE `makepayment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `other_payment`
--
ALTER TABLE `other_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fee_details`
--
ALTER TABLE `fee_details`
  ADD CONSTRAINT `fee_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `other_fee_details`
--
ALTER TABLE `other_fee_details`
  ADD CONSTRAINT `other_fee_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
