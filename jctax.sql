-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 07:48 PM
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
-- Database: `jctax`
--

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `doc_id` int(10) DEFAULT NULL,
  `return_ID` int(10) DEFAULT NULL,
  `doc_type` varchar(5) DEFAULT NULL,
  `file_path` varchar(100) DEFAULT NULL,
  `datetime` datetime(6) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_data`
--

CREATE TABLE `financial_data` (
  `data_id` int(10) DEFAULT NULL,
  `return_id` int(10) DEFAULT NULL,
  `total_income` float DEFAULT NULL,
  `total_expenses` float DEFAULT NULL,
  `data_source` varchar(20) DEFAULT NULL,
  `processed_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submission`
--

CREATE TABLE `submission` (
  `submission_id` int(10) DEFAULT NULL,
  `return_ID` int(10) DEFAULT NULL,
  `submission_method` varchar(10) DEFAULT NULL,
  `submitted_at` datetime(6) DEFAULT NULL,
  `confirmation_num` int(10) DEFAULT NULL,
  `status` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_calculation`
--

CREATE TABLE `tax_calculation` (
  `calc_id` int(10) DEFAULT NULL,
  `return_ID` int(10) DEFAULT NULL,
  `fed_tax_owed` float DEFAULT NULL,
  `state_tax_owed` float DEFAULT NULL,
  `total_deductions` float DEFAULT NULL,
  `final_amt` float DEFAULT NULL,
  `calculated_amt` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_returns`
--

CREATE TABLE `tax_returns` (
  `return_ID` int(10) DEFAULT NULL,
  `User_ID` int(10) DEFAULT NULL,
  `tax_year` int(4) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `submitted_at` datetime(6) DEFAULT NULL,
  `submission_type` varchar(10) DEFAULT NULL,
  `is_complete` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `w2_forms`
--

CREATE TABLE `w2_forms` (
  `w2_id` int(20) DEFAULT NULL,
  `return_ID` int(10) DEFAULT NULL,
  `employer_ein` varchar(10) DEFAULT NULL,
  `wages` float DEFAULT NULL,
  `fed_tax_withheld` float DEFAULT NULL,
  `state_tax_withheld` float DEFAULT NULL,
  `tax_year` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
