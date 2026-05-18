-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 02:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrm`
--
CREATE DATABASE IF NOT EXISTS `hrm` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `hrm`;

-- --------------------------------------------------------

--
-- Table structure for table `phphr_attendance`
--
-- Creation: Feb 05, 2026 at 12:57 AM
--

DROP TABLE IF EXISTS `phphr_attendance`;
CREATE TABLE IF NOT EXISTS `phphr_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('present','absent','late','half_day') DEFAULT 'present',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_date` (`employee_id`,`attendance_date`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `phphr_attendance`:
--
--
-- Dumping data for table `phphr_attendance`
--

INSERT INTO `phphr_attendance` (`id`, `employee_id`, `attendance_date`, `check_in`, `check_out`, `status`, `created_at`) VALUES
(1, 1, '2024-09-01', '09:05:00', '18:00:00', 'present', '2025-12-14 12:08:14'),
(2, 2, '2024-09-01', '09:30:00', '18:10:00', 'late', '2025-12-14 12:08:14'),
(3, 3, '2024-09-01', NULL, NULL, 'absent', '2025-12-14 12:08:14'),
(4, 4, '2024-09-01', '09:10:00', '13:30:00', 'half_day', '2025-12-14 12:08:14'),
(5, 5, '2024-09-01', '08:55:00', '17:45:00', 'present', '2025-12-14 12:08:14'),
(6, 6, '2026-02-06', '15:54:00', '09:00:00', 'present', '2026-02-06 07:54:26'),
(7, 7, '2026-02-06', '16:03:00', '16:03:00', 'present', '2026-02-06 08:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `phphr_employees`
--
-- Creation: Feb 06, 2026 at 07:57 AM
--
 
DROP TABLE IF EXISTS `phphr_employees`;
CREATE TABLE IF NOT EXISTS `phphr_employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `employee_code` varchar(50) DEFAULT NULL,
  `first_name` varchar(25) DEFAULT NULL,
  `middle_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(25) DEFAULT NULL,
  `date_of_birth` varchar(25) DEFAULT NULL,
  `gender` varchar(25) DEFAULT NULL,
  `nationality` varchar(25) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_address` varchar(25) DEFAULT NULL,
  `current_address` varchar(25) DEFAULT NULL,
  `emergency_name` varchar(25) DEFAULT NULL,
  `emergency_number` varchar(25) DEFAULT NULL,
  `emergency_relationship` varchar(25) DEFAULT NULL,
  `department` varchar(25) DEFAULT NULL,
  `designation` varchar(25) DEFAULT NULL,
  `date_of_joining` varchar(25) DEFAULT NULL,
  `probation_end_date` varchar(25) DEFAULT NULL,
  `employment_type` varchar(25) DEFAULT NULL,
  `reporting_manager` varchar(25) DEFAULT NULL,
  `work_location` varchar(25) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `ID_filename` varchar(25) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_code` (`employee_code`),
  KEY `department_id` (`department`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `phphr_employees`:
--

--
-- Dumping data for table `phphr_employees`
--

INSERT INTO `phphr_employees` (`id`, `user_id`, `employee_code`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `gender`, `nationality`, `phone`, `email_address`, `current_address`, `emergency_name`, `emergency_number`, `emergency_relationship`, `department`, `designation`, `date_of_joining`, `probation_end_date`, `employment_type`, `reporting_manager`, `work_location`, `salary`, `status`, `ID_filename`, `created_at`) VALUES
(6, 1, 'EMP-5727', 'AC', NULL, 'DL', NULL, NULL, NULL, '123456', NULL, NULL, NULL, NULL, NULL, 'BOSS', 'BOSS', '2025-01-06', NULL, NULL, NULL, NULL, 100000.00, 1, NULL, '2026-02-06 02:31:22'),
(7, 1, 'EMP-5536', 'LG', NULL, 'DL', NULL, NULL, NULL, '123456', NULL, NULL, NULL, NULL, NULL, 'CIRD', 'BOSS', '2026-02-06', NULL, NULL, NULL, NULL, 99000.00, 1, NULL, '2026-02-06 07:58:40'),
(9, 1, 'EMP-6844', 'Tassi', 'asdfgh', '5', '2026-02-10', 'Male', 'American', '123456', '@yahoo.com', 'ewan', 'si inay', '123456', 'Father', 'Sales', 'BOSS', '2024-01-10', '', 'Part-time', 'sinundot', 'Office', 45000.00, 1, NULL, '2026-02-11 07:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `phphr_leaves`
--
-- Creation: Feb 05, 2026 at 12:57 AM
--

DROP TABLE IF EXISTS `phphr_leaves`;
CREATE TABLE IF NOT EXISTS `phphr_leaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `leave_type` enum('casual','sick','paid','unpaid') DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- RELATIONSHIPS FOR TABLE `phphr_leaves`:
--
--
-- Dumping data for table `phphr_leaves`
--

INSERT INTO `phphr_leaves` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `applied_at`) VALUES
(1, 1, 'casual', '2024-09-05', '2024-09-05', 'Personal work', 'approved', '2025-12-14 12:09:11'),
(2, 2, 'sick', '2024-09-10', '2024-09-12', 'Viral fever', 'approved', '2025-12-14 12:09:11'),
(3, 3, 'paid', '2024-09-15', '2024-09-20', 'Family function', 'pending', '2025-12-14 12:09:11'),
(4, 4, 'unpaid', '2024-09-18', '2024-09-19', 'Emergency personal matter', 'approved', '2025-12-14 12:09:11'),
(5, 5, 'casual', '2024-09-25', '2024-09-25', 'Bank work', 'rejected', '2025-12-14 12:09:11');

-- --------------------------------------------------------

--
-- Table structure for table `phphr_payroll`
--
-- Creation: Feb 05, 2026 at 12:57 AM
--

DROP TABLE IF EXISTS `phphr_payroll`;
CREATE TABLE IF NOT EXISTS `phphr_payroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `salary_month` varchar(20) DEFAULT NULL,
  `basic_salary` decimal(10,2) DEFAULT NULL,
  `allowances` decimal(10,2) DEFAULT 0.00,
  `deductions` decimal(10,2) DEFAULT 0.00,
  `net_salary` decimal(10,2) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `phphr_payroll`:
--

--
-- Dumping data for table `phphr_payroll`
--

INSERT INTO `phphr_payroll` (`id`, `employee_id`, `salary_month`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `generated_at`) VALUES
(1, 1, 'September 2024', 55000.00, 5000.00, 2000.00, 58000.00, '2025-12-14 12:10:11'),
(2, 2, 'September 2024', 42000.00, 3000.00, 1500.00, 43500.00, '2025-12-14 12:10:11'),
(3, 3, 'September 2024', 65000.00, 8000.00, 2500.00, 70500.00, '2025-12-14 12:10:11'),
(4, 4, 'September 2024', 38000.00, 2000.00, 1000.00, 39000.00, '2025-12-14 12:10:11'),
(5, 5, 'September 2024', 72000.00, 10000.00, 3000.00, 79000.00, '2025-12-14 12:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `phphr_users`
--
-- Creation: Feb 13, 2026 at 05:31 AM
--

DROP TABLE IF EXISTS `phphr_users`;
CREATE TABLE IF NOT EXISTS `phphr_users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `role` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `phphr_users`:
--

--
-- Dumping data for table `phphr_users`
--

INSERT INTO `phphr_users` (`id`, `username`, `email`, `password_hash`, `full_name`, `status`, `role`, `created_at`) VALUES
(1, 'admin@phphr.com', 'admin@phphr.com', '$2y$10$An0BgSTq3JbR/9lKz8maGuqT22sSV8dTZ5CuxFnvVjSq0KCv77RA.', 'Administrator', 1, 'admin', '2025-11-29 12:19:52'),
(2, 'trace_hr', 'trace_hr@gmail.com', '$2y$10$An0BgSTq3JbR/9lKz8maGuqT22sSV8dTZ5CuxFnvVjSq0KCv77RA.', 'tgbugatti', 1, 'hr', '2026-02-13 05:51:56');

--
-- Metadata
--
USE `phpmyadmin`;

--
-- Metadata for table phphr_attendance
--
--
-- Metadata for table phphr_employees
--

--
-- Metadata for table phphr_leaves
--

--
-- Metadata for table phphr_payroll
--

--
-- Metadata for table phphr_users
--

--
-- Metadata for database hrm
--
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
