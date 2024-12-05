-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 25, 2024 at 11:13 PM
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
-- Database: `COMP_4150_project_trial`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `action`, `employee_id`, `performed_by`, `ip_address`, `timestamp`) VALUES
(1, 'Created new department', 4, 1, '192.168.1.101', '2024-11-01 13:00:00'),
(2, 'Updated budget', 5, 2, '192.168.1.102', '2024-11-02 14:15:00'),
(3, 'Added employee', 6, 1, '192.168.1.103', '2024-11-03 16:30:00'),
(4, 'Assigned project', 7, 2, '192.168.1.104', '2024-11-04 17:45:00'),
(5, 'Deleted inactive user', 8, 3, '192.168.1.105', '2024-11-05 19:00:00'),
(6, 'Approved leave request', 9, 4, '192.168.1.106', '2024-11-06 20:15:00'),
(7, 'Logged in', 10, 1, '192.168.1.107', '2024-11-07 21:30:00'),
(8, 'Updated profile', 11, 1, '192.168.1.108', '2024-11-08 22:45:00'),
(9, 'Logged out', 12, 1, '192.168.1.109', '2024-11-09 23:00:00'),
(10, 'Reviewed performance report', 13, 4, '192.168.1.110', '2024-11-11 00:15:00'),
(11, 'Logged In', 1, 1, '::1', '2024-11-25 09:35:02'),
(12, 'Logged Out', 1, 1, '::1', '2024-11-25 09:46:06'),
(13, 'Logged In', 4, 4, '::1', '2024-11-25 09:47:06'),
(14, 'User Performance Updated', 1, 4, '::1', '2024-11-25 09:57:16'),
(15, 'User Performance Updated', 1, 4, '::1', '2024-11-25 09:57:21'),
(16, 'User Performance Updated', 1, 4, '::1', '2024-11-25 09:58:19'),
(17, 'User Performance Updated', 1, 4, '::1', '2024-11-25 09:58:28'),
(18, 'Project Edited', 4, 4, '::1', '2024-11-25 10:04:32'),
(19, 'Logged Out', 4, 4, '::1', '2024-11-25 10:14:42'),
(20, 'Logged In', 1, 1, '::1', '2024-11-25 10:15:07'),
(21, 'Activated user ID: 2', 2, 1, '::1', '2024-11-25 10:16:57'),
(22, 'Deactivated user ID: 2', 2, 1, '::1', '2024-11-25 10:16:59'),
(23, 'Activated user ID: 2', 2, 1, '::1', '2024-11-25 10:17:00'),
(24, 'Updated user details: smit.thesiya', 2, 1, '::1', '2024-11-25 10:17:08'),
(25, 'Updated user details: smit.thesiya', 2, 1, '::1', '2024-11-25 10:17:43'),
(26, 'Updated department details: Human Resources', NULL, 1, '::1', '2024-11-25 10:17:57'),
(27, 'Updated department details: Human Resources', NULL, 1, '::1', '2024-11-25 10:18:22'),
(28, 'Added a new department: testing', NULL, 1, '::1', '2024-11-25 10:18:57'),
(29, 'Logged Out', 1, 1, '::1', '2024-11-25 10:19:59'),
(30, 'Logged In', 9, 9, '::1', '2024-11-25 17:19:02'),
(31, 'Updated Task: Design Database Schema123 (ID: 1)', 9, 9, '::1', '2024-11-25 17:58:55'),
(32, 'Updated Task: Design Database Schema (ID: 1)', 9, 9, '::1', '2024-11-25 17:59:07'),
(33, 'Logged In', 4, 4, '::1', '2024-11-25 18:35:22'),
(34, 'Logged Out', 4, 4, '::1', '2024-11-25 18:35:27'),
(35, 'Logged In', 4, 4, '::1', '2024-11-25 18:35:34'),
(36, 'Logged In', 9, 9, '::1', '2024-11-25 18:36:20'),
(37, 'Logged Out', 9, 9, '::1', '2024-11-25 18:41:28'),
(38, 'Logged In', 1, 1, '::1', '2024-11-25 18:41:49'),
(39, 'Logged Out', 1, 1, '::1', '2024-11-25 18:42:07'),
(40, 'Logged In', 9, 9, '::1', '2024-11-25 18:43:58'),
(41, 'Logged In', 9, 9, '::1', '2024-11-25 18:45:20'),
(42, 'Logged Out', 9, 9, '::1', '2024-11-25 18:47:42'),
(43, 'Logged In', 4, 4, '::1', '2024-11-25 18:47:48'),
(44, 'Logged Out', 4, 4, '::1', '2024-11-25 18:50:41'),
(45, 'Logged In', 9, 9, '::1', '2024-11-25 18:50:49'),
(47, 'Logged In', 9, 9, '::1', '2024-11-25 19:04:02'),
(49, 'Logged Out', 9, 9, '::1', '2024-11-25 19:11:30'),
(50, 'Logged In', 9, 9, '::1', '2024-11-25 19:15:30'),
(51, 'Logged Out', 9, 9, '::1', '2024-11-25 19:46:00'),
(52, 'Logged In', 1, 1, '::1', '2024-11-25 19:47:43'),
(53, 'Logged In', 4, 4, '::1', '2024-11-25 19:47:50'),
(54, 'Logged In', 9, 9, '::1', '2024-11-25 19:47:57'),
(55, 'Logged In', 9, 9, '::1', '2024-11-25 19:55:18');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(50) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`, `manager_id`, `budget`) VALUES
(1, 'Human Resources', 4, 100000.00),
(2, 'Finance', 5, 200000.00),
(3, 'Engineering', 6, 300000.00),
(4, 'Marketing', 7, 150000.00),
(5, 'IT Support', 8, 250000.00),
(6, 'Sales', 4, 180000.00),
(7, 'Research and Development', 5, 350000.00),
(8, 'Customer Service', 6, 90000.00),
(9, 'Legal', 7, 120000.00),
(10, 'Operations', 8, 220000.00),
(11, 'testing', 4, 100000.00);

-- --------------------------------------------------------

--
-- Table structure for table `department_location`
--

CREATE TABLE `department_location` (
  `department_id` int(11) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_location`
--

INSERT INTO `department_location` (`department_id`, `city`) VALUES
(1, 'Toronto'),
(2, 'Vancouver'),
(3, 'Montreal'),
(4, 'Calgary'),
(5, 'Ottawa'),
(6, 'Edmonton'),
(7, 'Quebec City'),
(8, 'Winnipeg'),
(9, 'Halifax'),
(10, 'Victoria');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `SSN` int(9) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `add` varchar(50) NOT NULL,
  `DOB` date NOT NULL DEFAULT current_timestamp(),
  `role` enum('Admin','Manager','User') NOT NULL DEFAULT 'User',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` enum('Active','Inactive') DEFAULT 'Active',
  `performance_score` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `SSN`, `username`, `password`, `first_name`, `last_name`, `add`, `DOB`, `role`, `created_at`, `Status`, `performance_score`) VALUES
(1, 123456789, 'darshan.bodara', '$2y$10$X7V.kOrC5CD2B26LQw1YSu.3K9pdRx2gx0UHYfKbcteJ1mUTQ2pQ2', 'Darshan', 'Bodara Admin', '123 Main St', '1985-01-01', 'Admin', '2024-11-25 05:15:24', 'Active', 90),
(2, 223456789, 'smit.thesiya', 'password123', 'Smit', 'Thesiya Admin', '456 Elm St', '1986-02-02', 'Admin', '2024-11-25 05:15:24', 'Active', 88),
(3, 323456789, 'nisarg.patel', 'password123', 'Nisarg', 'Patel Admin', '789 Pine St', '1987-03-03', 'Admin', '2024-11-25 05:15:24', 'Active', 86),
(4, 423456789, 'manager1', '$2y$10$X7V.kOrC5CD2B26LQw1YSu.3K9pdRx2gx0UHYfKbcteJ1mUTQ2pQ2', 'Darshan', 'Bodara Manager', '12 Maple St', '1990-04-04', 'Manager', '2024-11-25 05:17:58', 'Active', 80),
(5, 523456789, 'manager2', 'password123', 'Emily', 'Davis', '34 Oak St', '1991-05-05', 'Manager', '2024-11-25 05:17:58', 'Active', 81),
(6, 623456789, 'manager3', 'password123', 'Michael', 'Brown', '56 Cedar St', '1992-06-06', 'Manager', '2024-11-25 05:17:58', 'Active', 83),
(7, 723456789, 'manager4', 'password123', 'Sarah', 'Wilson', '78 Birch St', '1993-07-07', 'Manager', '2024-11-25 05:17:58', 'Active', 85),
(8, 823456789, 'manager5', 'password123', 'David', 'Miller', '90 Walnut St', '1994-08-08', 'Manager', '2024-11-25 05:17:58', 'Active', 86),
(9, 923456789, 'user1', '$2y$10$X7V.kOrC5CD2B26LQw1YSu.3K9pdRx2gx0UHYfKbcteJ1mUTQ2pQ2', 'Darshan', 'Bodara User', '123 Cherry St', '1995-09-09', 'User', '2024-11-25 05:17:58', 'Active', 70),
(10, 1023456789, 'user2', 'password123', 'Anna', 'Scott', '234 Peach St', '1996-10-10', 'User', '2024-11-25 05:17:58', 'Active', 72),
(11, 1123456789, 'user3', 'password123', 'Tom', 'Holland', '345 Plum St', '1997-11-11', 'User', '2024-11-25 05:17:58', 'Active', 73),
(12, 1223456789, 'user4', 'password123', 'Lucy', 'Liu', '456 Orange St', '1998-12-12', 'User', '2024-11-25 05:17:58', 'Active', 75),
(13, 1323456789, 'user5', 'password123', 'James', 'Dean', '567 Lemon St', '1999-01-01', 'User', '2024-11-25 05:17:58', 'Active', 76),
(14, 1423456789, 'user6', 'password123', 'Sophia', 'Turner', '678 Lime St', '2000-02-02', 'User', '2024-11-25 05:17:58', 'Active', 71),
(15, 1523456789, 'user7', 'password123', 'Liam', 'Neeson', '789 Mango St', '2001-03-03', 'User', '2024-11-25 05:17:58', 'Active', 73),
(16, 1623456789, 'user8', 'password123', 'Olivia', 'Cook', '890 Grapefruit St', '2002-04-04', 'User', '2024-11-25 05:17:58', 'Active', 74),
(17, 1723456789, 'user9', 'password123', 'Henry', 'Cavill', '901 Pear St', '2003-05-05', 'User', '2024-11-25 05:17:58', 'Active', 76),
(18, 1823456789, 'user10', 'password123', 'Ella', 'Fitzgerald', '123 Kiwi St', '2004-06-06', 'User', '2024-11-25 05:17:58', 'Active', 78),
(19, 1923456789, 'user11', 'password123', 'John', 'Doe', '456 Pine St', '1995-01-15', 'User', '2024-11-25 10:17:58', 'Inactive', 60),
(20, 202345678, 'user12', 'password123', 'Jane', 'Smith', '789 Elm St', '1990-05-10', 'User', '2024-11-25 10:17:58', 'Inactive', 65),
(21, 212345678, 'user13', 'password123', 'Alex', 'Taylor', '321 Oak St', '1998-03-25', 'User', '2024-11-25 10:17:58', 'Inactive', 55),
(22, 222345678, 'user14', 'password123', 'Emily', 'Johnson', '654 Birch St', '1988-07-20', 'User', '2024-11-25 10:17:58', 'Inactive', 50),
(23, 232345678, 'user15', 'password123', 'Michael', 'Williams', '987 Cedar St', '1992-11-30', 'User', '2024-11-25 10:17:58', 'Inactive', 58);

-- --------------------------------------------------------

--
-- Table structure for table `employee_absences`
--

CREATE TABLE `employee_absences` (
  `absence_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `absence_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_absences`
--

INSERT INTO `employee_absences` (`absence_id`, `employee_id`, `absence_date`, `reason`) VALUES
(1, 6, '2024-11-10', 'Medical Leave'),
(2, 7, '2024-11-15', 'Vacation'),
(3, 11, '2024-11-12', 'Family Emergency'),
(4, 16, '2024-11-20', 'Medical Leave'),
(5, 18, '2024-11-18', 'Personal Leave'),
(6, 17, '2024-11-08', 'Family Emergency'),
(7, 10, '2024-11-22', 'Vacation'),
(8, 15, '2024-11-14', 'Sick Leave'),
(9, 14, '2024-11-19', 'Conference'),
(10, 12, '2024-11-16', 'Workshop');

-- --------------------------------------------------------

--
-- Table structure for table `employee_contact`
--

CREATE TABLE `employee_contact` (
  `contact_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `contact_type` varchar(20) DEFAULT NULL,
  `contact_value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_contact`
--

INSERT INTO `employee_contact` (`contact_id`, `employee_id`, `contact_type`, `contact_value`) VALUES
(1, 1, 'Phone', '123-456-7890'),
(2, 1, 'Email', 'admin1@company.com'),
(3, 2, 'Phone', '123-555-7890'),
(4, 2, 'Email', 'admin2@company.com'),
(5, 3, 'Phone', '123-777-7890'),
(6, 3, 'Email', 'admin3@company.com'),
(7, 4, 'Phone', '321-456-1230'),
(8, 4, 'Email', 'manager1@company.com'),
(9, 5, 'Phone', '321-555-1230'),
(10, 5, 'Email', 'manager2@company.com'),
(11, 6, 'Phone', '321-777-1230'),
(12, 6, 'Email', 'manager3@company.com'),
(13, 7, 'Phone', '321-888-1230'),
(14, 7, 'Email', 'manager4@company.com'),
(15, 8, 'Phone', '321-999-1230'),
(16, 8, 'Email', 'manager5@company.com'),
(17, 9, 'Phone', '111-456-7890'),
(18, 9, 'Email', 'user1@company.co'),
(19, 10, 'Phone', '111-555-7890'),
(20, 10, 'Email', 'user2@company.com'),
(21, 11, 'Phone', '111-777-7890'),
(22, 11, 'Email', 'user3@company.com'),
(23, 12, 'Phone', '111-888-7890'),
(24, 12, 'Email', 'user4@company.com'),
(25, 13, 'Phone', '111-999-7890'),
(26, 13, 'Email', 'user5@company.com'),
(27, 14, 'Phone', '222-456-7890'),
(28, 14, 'Email', 'user6@company.com'),
(29, 15, 'Phone', '222-555-7890'),
(30, 15, 'Email', 'user7@company.com'),
(31, 16, 'Phone', '222-777-7890'),
(32, 16, 'Email', 'user8@company.com'),
(33, 17, 'Phone', '222-888-7890'),
(34, 17, 'Email', 'user9@company.com'),
(35, 18, 'Phone', '222-999-7890'),
(36, 18, 'Email', 'user10@company.com');

-- --------------------------------------------------------

--
-- Table structure for table `employee_role`
--

CREATE TABLE `employee_role` (
  `role_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_role`
--

INSERT INTO `employee_role` (`role_id`, `employee_id`, `dept_id`, `project_id`) VALUES
(1, 1, NULL, NULL),
(2, 2, NULL, NULL),
(3, 3, NULL, NULL),
(4, 4, 1, NULL),
(5, 5, 2, NULL),
(6, 6, 3, NULL),
(7, 7, 4, NULL),
(8, 8, 5, NULL),
(9, 9, 1, 1),
(10, 10, 1, 2),
(11, 11, 2, 3),
(12, 12, 2, 4),
(13, 13, 3, 5),
(14, 14, 3, 1),
(15, 15, 4, 2),
(16, 16, 4, 3),
(17, 17, 5, 4),
(18, 18, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `employee_supervisor`
--

CREATE TABLE `employee_supervisor` (
  `employee_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_supervisor`
--

INSERT INTO `employee_supervisor` (`employee_id`, `supervisor_id`) VALUES
(9, 4),
(10, 4),
(11, 4),
(12, 4),
(13, 4),
(14, 5),
(15, 5),
(16, 5),
(17, 5),
(18, 5);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `project_name`, `start_date`, `end_date`, `dept_id`) VALUES
(1, 'Website Redesign', '2024-01-15', '2024-03-30', 1),
(2, 'New Product Launch', '2024-02-01', '2024-05-31', 2),
(3, 'Employee Training Program', '2024-03-01', '2024-04-15', 3),
(4, 'Market Research Initiative', '2024-01-20', '2024-06-01', 4),
(5, 'IT Infrastructure Upgrade', '2024-04-01', '2024-07-15', 5);

-- --------------------------------------------------------

--
-- Table structure for table `project_assignment`
--

CREATE TABLE `project_assignment` (
  `assignment_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_assignment`
--

INSERT INTO `project_assignment` (`assignment_id`, `employee_id`, `assigned_by`, `project_id`, `start_date`, `end_date`) VALUES
(1, 9, 4, 1, '2024-01-15', '2024-03-30'),
(2, 10, 4, 1, '2024-01-15', '2024-03-30'),
(3, 11, 5, 2, '2024-02-01', '2024-05-31'),
(4, 12, 5, 2, '2024-02-01', '2024-05-31'),
(5, 13, 6, 3, '2024-03-01', '2024-04-15'),
(6, 14, 6, 3, '2024-03-01', '2024-04-15'),
(7, 15, 7, 4, '2024-01-20', '2024-06-01'),
(8, 16, 7, 4, '2024-01-20', '2024-06-01'),
(9, 17, 8, 5, '2024-04-01', '2024-07-15'),
(10, 18, 8, 5, '2024-04-01', '2024-07-15');

-- --------------------------------------------------------

--
-- Table structure for table `project_department`
--

CREATE TABLE `project_department` (
  `project_department_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_department`
--

INSERT INTO `project_department` (`project_department_id`, `project_id`, `department_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(100) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `due_date` date NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `progress` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`task_id`, `task_name`, `project_id`, `due_date`, `status`, `progress`) VALUES
(1, 'Design Database Schema', 1, '2024-12-15', 'Completed', 100),
(2, 'Develop Backend API', 1, '2024-12-20', 'In Progress', 70),
(3, 'Write Unit Tests', 1, '2024-12-25', 'Pending', 0),
(4, 'UI Design', 2, '2024-12-10', 'Completed', 100),
(5, 'Frontend Implementation', 2, '2024-12-20', 'In Progress', 50),
(6, 'Integrate Payment Gateway', 2, '2024-12-30', 'Pending', 0),
(7, 'Create Marketing Strategy', 3, '2024-12-05', 'Completed', 100),
(8, 'Develop Campaign Content', 3, '2024-12-15', 'In Progress', 60),
(9, 'Analyze Campaign Metrics', 3, '2024-12-25', 'Pending', 0),
(10, 'Setup Infrastructure', 4, '2024-12-01', 'Completed', 100),
(11, 'Deploy Application', 4, '2024-12-10', 'In Progress', 80),
(12, 'Monitor System Performance', 4, '2024-12-20', 'Pending', 0),
(13, 'Conduct User Interviews', 5, '2024-11-30', 'Completed', 100),
(14, 'Define User Personas', 5, '2024-12-10', 'In Progress', 50),
(15, 'Finalize UX Design', 5, '2024-12-15', 'Pending', 0);

--
-- Triggers `task`
--
DELIMITER $$
CREATE TRIGGER `enforce_task_progress_constraint` BEFORE INSERT ON `task` FOR EACH ROW BEGIN
    IF NEW.progress < 0 OR NEW.progress > 100 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Progress value must be between 0 and 100';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_employee_full_names`
-- (See below for the actual view)
--
CREATE TABLE `view_employee_full_names` (
`employee_id` int(11)
,`full_name` varchar(101)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_employee_certifications`
-- (See below for the actual view)
--
CREATE TABLE `vw_employee_certifications` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_employee_details`
-- (See below for the actual view)
--
CREATE TABLE `vw_employee_details` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_employee_time_tracking`
-- (See below for the actual view)
--
CREATE TABLE `vw_employee_time_tracking` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_project_status`
-- (See below for the actual view)
--
CREATE TABLE `vw_project_status` (
`project_id` int(11)
,`project_name` varchar(100)
,`start_date` date
,`end_date` date
,`department_name` varchar(50)
,`total_tasks` bigint(21)
,`completed_tasks` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Structure for view `view_employee_full_names`
--
DROP TABLE IF EXISTS `view_employee_full_names`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `comp_4150_project_trial`.`view_employee_full_names`  AS SELECT `comp_4150_project_trial`.`employee`.`employee_id` AS `employee_id`, concat(`comp_4150_project_trial`.`employee`.`first_name`,' ',`comp_4150_project_trial`.`employee`.`last_name`) AS `full_name` FROM `comp_4150_project_trial`.`employee` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_employee_certifications`
--
DROP TABLE IF EXISTS `vw_employee_certifications`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `comp_4150_project_trial`.`vw_employee_certifications`  AS SELECT `e`.`employee_id` AS `employee_id`, `e`.`first_name` AS `first_name`, `e`.`last_name` AS `last_name`, `c`.`cert_name` AS `cert_name`, `ec`.`achieved_date` AS `achieved_date` FROM ((`comp_4150_project_trial`.`employee` `e` join `comp_4150_project_trial`.`employee_certification` `ec` on(`e`.`employee_id` = `ec`.`employee_id`)) join `comp_4150_project_trial`.`certification` `c` on(`ec`.`cert_id` = `c`.`cert_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_employee_details`
--
DROP TABLE IF EXISTS `vw_employee_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `comp_4150_project_trial`.`vw_employee_details`  AS SELECT `e`.`employee_id` AS `employee_id`, `e`.`first_name` AS `first_name`, `e`.`last_name` AS `last_name`, `e`.`job_title` AS `job_title`, `e`.`salary` AS `salary`, `d`.`department_name` AS `department_name`, `dl`.`city` AS `department_location` FROM ((`comp_4150_project_trial`.`employee` `e` left join `comp_4150_project_trial`.`department` `d` on(`e`.`dept_id` = `d`.`department_id`)) left join `comp_4150_project_trial`.`department_location` `dl` on(`d`.`department_id` = `dl`.`department_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_employee_time_tracking`
--
DROP TABLE IF EXISTS `vw_employee_time_tracking`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `comp_4150_project_trial`.`vw_employee_time_tracking`  AS SELECT `e`.`employee_id` AS `employee_id`, `e`.`first_name` AS `first_name`, `e`.`last_name` AS `last_name`, `t`.`task_name` AS `task_name`, `p`.`project_name` AS `project_name`, `tl`.`log_date` AS `log_date`, `tl`.`hours_worked` AS `hours_worked` FROM (((`comp_4150_project_trial`.`employee` `e` join `comp_4150_project_trial`.`time_log` `tl` on(`e`.`employee_id` = `tl`.`employee_id`)) join `comp_4150_project_trial`.`task` `t` on(`tl`.`task_id` = `t`.`task_id`)) join `comp_4150_project_trial`.`project` `p` on(`t`.`project_id` = `p`.`project_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_project_status`
--
DROP TABLE IF EXISTS `vw_project_status`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `comp_4150_project_trial`.`vw_project_status`  AS SELECT `p`.`project_id` AS `project_id`, `p`.`project_name` AS `project_name`, `p`.`start_date` AS `start_date`, `p`.`end_date` AS `end_date`, `d`.`department_name` AS `department_name`, count(`t`.`task_id`) AS `total_tasks`, sum(case when `t`.`status` = 'Completed' then 1 else 0 end) AS `completed_tasks` FROM ((`comp_4150_project_trial`.`project` `p` left join `comp_4150_project_trial`.`department` `d` on(`p`.`dept_id` = `d`.`department_id`)) left join `comp_4150_project_trial`.`task` `t` on(`p`.`project_id` = `t`.`project_id`)) GROUP BY `p`.`project_id`, `p`.`project_name`, `p`.`start_date`, `p`.`end_date`, `d`.`department_name` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_performed_by` (`performed_by`),
  ADD KEY `fk_employee_id` (`employee_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`),
  ADD KEY `fk_department_manager` (`manager_id`),
  ADD KEY `idx_manager_id` (`manager_id`);

--
-- Indexes for table `department_location`
--
ALTER TABLE `department_location`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `idx_employee_performance` (`performance_score`);

--
-- Indexes for table `employee_absences`
--
ALTER TABLE `employee_absences`
  ADD PRIMARY KEY (`absence_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employee_contact`
--
ALTER TABLE `employee_contact`
  ADD PRIMARY KEY (`contact_id`),
  ADD UNIQUE KEY `unique_contact` (`employee_id`,`contact_type`),
  ADD KEY `fk_employee_contact_employee` (`employee_id`);

--
-- Indexes for table `employee_role`
--
ALTER TABLE `employee_role`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `fk_employee_role_department` (`dept_id`),
  ADD KEY `fk_employee_role_employee` (`employee_id`),
  ADD KEY `fk_employee_role_project` (`project_id`);

--
-- Indexes for table `employee_supervisor`
--
ALTER TABLE `employee_supervisor`
  ADD PRIMARY KEY (`employee_id`,`supervisor_id`),
  ADD KEY `fk_employee_supervisor_supervisor` (`supervisor_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `fk_project_department` (`dept_id`);

--
-- Indexes for table `project_assignment`
--
ALTER TABLE `project_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `fk_project_assignment_employee` (`employee_id`),
  ADD KEY `fk_project_assignment_project` (`project_id`),
  ADD KEY `fk_assigned_by` (`assigned_by`);

--
-- Indexes for table `project_department`
--
ALTER TABLE `project_department`
  ADD PRIMARY KEY (`project_department_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `fk_task_project` (`project_id`),
  ADD KEY `idx_task_progress` (`progress`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `employee_absences`
--
ALTER TABLE `employee_absences`
  MODIFY `absence_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee_contact`
--
ALTER TABLE `employee_contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `project_department`
--
ALTER TABLE `project_department`
  MODIFY `project_department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `fk_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `fk_performed_by` FOREIGN KEY (`performed_by`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `fk_department_manager` FOREIGN KEY (`manager_id`) REFERENCES `employee` (`employee_id`) ON DELETE SET NULL;

--
-- Constraints for table `department_location`
--
ALTER TABLE `department_location`
  ADD CONSTRAINT `fk_dept_location_department` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `employee_absences`
--
ALTER TABLE `employee_absences`
  ADD CONSTRAINT `employee_absences_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `employee_contact`
--
ALTER TABLE `employee_contact`
  ADD CONSTRAINT `fk_employee_contact_employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `employee_role`
--
ALTER TABLE `employee_role`
  ADD CONSTRAINT `fk_employee_role_department` FOREIGN KEY (`dept_id`) REFERENCES `department` (`department_id`),
  ADD CONSTRAINT `fk_employee_role_employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `fk_employee_role_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `employee_supervisor`
--
ALTER TABLE `employee_supervisor`
  ADD CONSTRAINT `fk_employee_supervisor_employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `fk_employee_supervisor_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_project_department` FOREIGN KEY (`dept_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `project_assignment`
--
ALTER TABLE `project_assignment`
  ADD CONSTRAINT `fk_assigned_by` FOREIGN KEY (`assigned_by`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `fk_project_assignment_employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `fk_project_assignment_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `project_department`
--
ALTER TABLE `project_department`
  ADD CONSTRAINT `project_department_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`),
  ADD CONSTRAINT `project_department_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk_task_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
