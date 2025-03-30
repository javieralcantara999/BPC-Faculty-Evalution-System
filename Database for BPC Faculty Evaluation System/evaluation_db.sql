-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 11:19 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_list`
--

CREATE TABLE `academic_list` (
  `id` int(30) NOT NULL,
  `year` text NOT NULL,
  `semester` int(30) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `restriction` int(2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0=Pending,1=Start,2=Closed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `academic_list`
--

INSERT INTO `academic_list` (`id`, `year`, `semester`, `is_default`, `restriction`, `status`) VALUES
(1, '2023-2024', 2, 1, 0, 1),
(3, '2023-2024', 1, 0, 0, 2),
(11, '2024-2025', 1, 0, 0, 2),
(79, '2024-2025', 2, 0, 0, 2),
(80, '2025-2026', 1, 0, 0, 2),
(82, '2025-2026', 2, 0, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `account_request`
--

CREATE TABLE `account_request` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `class_id` int(30) NOT NULL,
  `avatar` text DEFAULT 'no-image-available.png',
  `reset_token` varchar(100) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `admin_list`
--

CREATE TABLE `admin_list` (
  `id` int(11) NOT NULL,
  `firstname` text DEFAULT NULL,
  `lastname` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `avatar` blob DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `class_list`
--

CREATE TABLE `class_list` (
  `id` int(30) NOT NULL,
  `curriculum` text NOT NULL,
  `level` text NOT NULL,
  `section` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `class_list`
--

INSERT INTO `class_list` (`id`, `curriculum`, `level`, `section`) VALUES
(1, 'BSIS', '4', 'A'),
(2, 'BSIS', '4', 'B'),
(5, 'BSAIS', '4', 'A'),
(6, 'BSAIS', '4', 'B'),
(21, 'BSIS', '2', 'A'),
(45, 'ACT', '2', 'A'),
(46, 'ACT', '2', 'B'),
(47, 'BSIS', '4', 'C'),
(50, 'BSIS', '1', 'B'),
(51, 'BSAIS', '3', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `id` int(30) NOT NULL,
  `criteria` text NOT NULL,
  `order_by` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `criteria_list`
--

INSERT INTO `criteria_list` (`id`, `criteria`, `order_by`) VALUES
(17, 'Attitude Towards Students', 1),
(18, 'Subject Matter Presentation', 2),
(19, 'Classroom Management', 3),
(20, 'Etiquette', 4);

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list_superior`
--

CREATE TABLE `criteria_list_superior` (
  `id` int(30) NOT NULL,
  `criteria` text NOT NULL,
  `order_by` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `criteria_list_superior`
--

INSERT INTO `criteria_list_superior` (`id`, `criteria`, `order_by`) VALUES
(1, 'On Instruction and Administrative Functions', 0),
(2, 'On Professionalism', 1);

-- --------------------------------------------------------

--
-- Table structure for table `drafts`
--

CREATE TABLE `drafts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `draft_data` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers`
--

CREATE TABLE `evaluation_answers` (
  `evaluation_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_answers`
--

INSERT INTO `evaluation_answers` (`evaluation_id`, `question_id`, `rate`) VALUES
(1, 1, 5),
(1, 2, 4),
(1, 3, 5),
(1, 4, 4),
(1, 5, 3),
(1, 6, 4),
(1, 7, 5),
(1, 8, 4),
(1, 9, 5),
(1, 10, 5),
(1, 11, 4),
(1, 12, 3),
(1, 13, 4),
(1, 14, 5),
(1, 15, 4),
(1, 16, 5),
(2, 1, 5),
(2, 2, 4),
(2, 3, 5),
(2, 4, 4),
(2, 5, 5),
(2, 6, 4),
(2, 7, 5),
(2, 8, 4),
(2, 9, 5),
(2, 10, 5),
(2, 11, 4),
(2, 12, 5),
(2, 13, 4),
(2, 14, 5),
(2, 15, 4),
(2, 16, 5),
(3, 1, 5),
(3, 2, 4),
(3, 3, 5),
(3, 4, 4),
(3, 5, 5),
(3, 6, 5),
(3, 7, 4),
(3, 8, 5),
(3, 9, 5),
(3, 10, 5),
(3, 11, 4),
(3, 12, 5),
(3, 13, 5),
(3, 14, 4),
(3, 15, 5),
(3, 16, 4),
(4, 1, 3),
(4, 2, 3),
(4, 3, 3),
(4, 4, 3),
(4, 5, 3),
(4, 6, 3),
(4, 7, 3),
(4, 8, 3),
(4, 9, 3),
(4, 10, 3),
(4, 11, 3),
(4, 12, 3),
(4, 13, 3),
(4, 14, 3),
(4, 15, 3),
(4, 16, 3),
(5, 1, 5),
(5, 2, 4),
(5, 3, 5),
(5, 4, 4),
(5, 5, 5),
(5, 6, 4),
(5, 7, 5),
(5, 8, 4),
(5, 9, 5),
(5, 10, 4),
(5, 11, 5),
(5, 12, 4),
(5, 13, 5),
(5, 14, 4),
(5, 15, 5),
(5, 16, 4),
(6, 1, 5),
(6, 2, 4),
(6, 3, 5),
(6, 4, 4),
(6, 5, 5),
(6, 6, 4),
(6, 7, 5),
(6, 8, 4),
(6, 9, 5),
(6, 10, 5),
(6, 11, 4),
(6, 12, 4),
(6, 13, 5),
(6, 14, 4),
(6, 15, 5),
(6, 16, 4),
(7, 1, 5),
(7, 2, 5),
(7, 3, 5),
(7, 4, 5),
(7, 5, 5),
(7, 6, 5),
(7, 7, 5),
(7, 8, 5),
(7, 9, 5),
(7, 10, 5),
(7, 11, 5),
(7, 12, 5),
(7, 13, 5),
(7, 14, 5),
(7, 15, 5),
(7, 16, 5),
(8, 1, 1),
(8, 2, 2),
(8, 3, 1),
(8, 4, 3),
(8, 5, 2),
(8, 6, 3),
(8, 7, 3),
(8, 8, 3),
(8, 9, 3),
(8, 10, 4),
(8, 11, 3),
(8, 12, 4),
(8, 13, 3),
(8, 14, 4),
(8, 15, 3),
(8, 16, 4),
(9, 1, 5),
(9, 2, 4),
(9, 3, 5),
(9, 4, 4),
(9, 5, 5),
(9, 6, 4),
(9, 7, 5),
(9, 8, 4),
(9, 9, 5),
(9, 10, 5),
(9, 11, 4),
(9, 12, 5),
(9, 13, 5),
(9, 14, 4),
(9, 15, 5),
(9, 16, 4),
(10, 1, 2),
(10, 2, 2),
(10, 3, 1),
(10, 4, 2),
(10, 5, 1),
(10, 6, 2),
(10, 7, 2),
(10, 8, 2),
(10, 9, 2),
(10, 10, 2),
(10, 11, 2),
(10, 12, 2),
(10, 13, 2),
(10, 14, 2),
(10, 15, 2),
(10, 16, 2),
(11, 1, 5),
(11, 2, 4),
(11, 3, 5),
(11, 4, 4),
(11, 5, 5),
(11, 6, 4),
(11, 7, 5),
(11, 8, 4),
(11, 9, 5),
(11, 10, 4),
(11, 11, 5),
(11, 12, 4),
(11, 13, 3),
(11, 14, 4),
(11, 15, 3),
(11, 16, 4),
(12, 1, 5),
(12, 2, 4),
(12, 3, 5),
(12, 4, 4),
(12, 5, 5),
(12, 6, 4),
(12, 7, 5),
(12, 8, 4),
(12, 9, 4),
(12, 10, 3),
(12, 11, 4),
(12, 12, 3),
(12, 13, 4),
(12, 14, 3),
(12, 15, 4),
(12, 16, 3),
(13, 1, 5),
(13, 2, 4),
(13, 3, 5),
(13, 4, 4),
(13, 5, 5),
(13, 6, 5),
(13, 7, 5),
(13, 8, 4),
(13, 9, 5),
(13, 10, 4),
(13, 11, 5),
(13, 12, 4),
(13, 13, 5),
(13, 14, 4),
(13, 15, 4),
(13, 16, 3),
(15, 1, 5),
(15, 2, 4),
(15, 3, 5),
(15, 4, 4),
(15, 5, 5),
(15, 6, 4),
(15, 7, 5),
(15, 8, 5),
(15, 9, 5),
(15, 10, 5),
(15, 11, 4),
(15, 12, 5),
(15, 13, 5),
(15, 14, 4),
(15, 15, 5),
(15, 16, 4),
(16, 1, 5),
(16, 2, 4),
(16, 3, 5),
(16, 4, 4),
(16, 5, 4),
(16, 6, 5),
(16, 7, 4),
(16, 8, 5),
(16, 9, 4),
(16, 10, 5),
(16, 11, 5),
(16, 12, 4),
(16, 13, 5),
(16, 14, 4),
(16, 15, 5),
(16, 16, 4),
(17, 1, 5),
(17, 2, 4),
(17, 3, 5),
(17, 4, 4),
(17, 5, 5),
(17, 6, 4),
(17, 7, 4),
(17, 8, 4),
(17, 9, 5),
(17, 10, 5),
(17, 11, 4),
(17, 12, 5),
(17, 13, 5),
(17, 14, 5),
(17, 15, 4),
(17, 16, 4),
(18, 1, 5),
(18, 2, 4),
(18, 3, 5),
(18, 4, 4),
(18, 5, 5),
(18, 6, 4),
(18, 7, 5),
(18, 8, 4),
(18, 9, 5),
(18, 10, 4),
(18, 11, 5),
(18, 12, 4),
(18, 13, 5),
(18, 14, 4),
(18, 15, 5),
(18, 16, 4),
(19, 1, 5),
(19, 2, 4),
(19, 3, 5),
(19, 4, 4),
(19, 5, 5),
(19, 6, 4),
(19, 7, 5),
(19, 8, 4),
(19, 9, 5),
(19, 10, 5),
(19, 11, 4),
(19, 12, 4),
(19, 13, 4),
(19, 14, 3),
(19, 15, 4),
(19, 16, 5),
(20, 1, 5),
(20, 2, 4),
(20, 3, 5),
(20, 4, 5),
(20, 5, 5),
(20, 6, 4),
(20, 7, 5),
(20, 8, 4),
(20, 9, 4),
(20, 10, 4),
(20, 11, 4),
(20, 12, 4),
(20, 13, 4),
(20, 14, 5),
(20, 15, 4),
(20, 16, 5),
(21, 1, 5),
(21, 2, 5),
(21, 3, 4),
(21, 4, 5),
(21, 5, 5),
(21, 6, 5),
(21, 7, 5),
(21, 8, 5),
(21, 9, 4),
(21, 10, 4),
(21, 11, 5),
(21, 12, 5),
(21, 13, 5),
(21, 14, 4),
(21, 15, 5),
(21, 16, 5),
(22, 1, 5),
(22, 2, 5),
(22, 3, 5),
(22, 4, 5),
(22, 5, 5),
(22, 6, 5),
(22, 7, 5),
(22, 8, 5),
(22, 9, 5),
(22, 10, 5),
(22, 11, 5),
(22, 12, 5),
(22, 13, 5),
(22, 14, 5),
(22, 15, 5),
(22, 16, 5),
(23, 1, 5),
(23, 2, 4),
(23, 3, 5),
(23, 4, 4),
(23, 5, 5),
(23, 6, 4),
(23, 7, 5),
(23, 8, 5),
(23, 9, 5),
(23, 10, 5),
(23, 11, 4),
(23, 12, 5),
(23, 13, 5),
(23, 14, 4),
(23, 15, 5),
(23, 16, 4),
(24, 1, 5),
(24, 2, 4),
(24, 3, 5),
(24, 4, 5),
(24, 5, 4),
(24, 6, 4),
(24, 7, 5),
(24, 8, 5),
(24, 9, 5),
(24, 10, 5),
(24, 11, 4),
(24, 12, 5),
(24, 13, 5),
(24, 14, 4),
(24, 15, 5),
(24, 16, 4),
(25, 1, 5),
(25, 2, 4),
(25, 3, 5),
(25, 4, 4),
(25, 5, 5),
(25, 6, 4),
(25, 7, 5),
(25, 8, 4),
(25, 9, 5),
(25, 10, 4),
(25, 11, 4),
(25, 12, 4),
(25, 13, 5),
(25, 14, 4),
(25, 15, 5),
(25, 16, 4),
(26, 1, 5),
(26, 2, 5),
(26, 3, 5),
(26, 4, 5),
(26, 5, 5),
(26, 6, 4),
(26, 7, 5),
(26, 8, 5),
(26, 9, 4),
(26, 10, 5),
(26, 11, 4),
(26, 12, 5),
(26, 13, 4),
(26, 14, 4),
(26, 15, 5),
(26, 16, 4),
(27, 1, 5),
(27, 2, 4),
(27, 3, 5),
(27, 4, 4),
(27, 5, 5),
(27, 6, 5),
(27, 7, 5),
(27, 8, 4),
(27, 9, 5),
(27, 10, 5),
(27, 11, 4),
(27, 12, 5),
(27, 13, 5),
(27, 14, 4),
(27, 15, 5),
(27, 16, 4),
(28, 1, 5),
(28, 2, 4),
(28, 3, 5),
(28, 4, 5),
(28, 5, 5),
(28, 6, 5),
(28, 7, 4),
(28, 8, 5),
(28, 9, 5),
(28, 10, 5),
(28, 11, 4),
(28, 12, 5),
(28, 13, 5),
(28, 14, 5),
(28, 15, 4),
(28, 16, 5),
(29, 1, 5),
(29, 2, 4),
(29, 3, 5),
(29, 4, 4),
(29, 5, 3),
(29, 6, 4),
(29, 7, 4),
(29, 8, 5),
(29, 9, 4),
(29, 10, 5),
(29, 11, 4),
(29, 12, 5),
(29, 13, 4),
(29, 14, 4),
(29, 15, 4),
(29, 16, 5),
(30, 1, 5),
(30, 2, 4),
(30, 3, 5),
(30, 4, 4),
(30, 5, 5),
(30, 6, 5),
(30, 7, 4),
(30, 8, 5),
(30, 9, 4),
(30, 10, 5),
(30, 11, 4),
(30, 12, 5),
(30, 13, 4),
(30, 14, 5),
(30, 15, 4),
(30, 16, 5),
(31, 1, 5),
(31, 2, 4),
(31, 3, 5),
(31, 4, 4),
(31, 5, 5),
(31, 6, 4),
(31, 7, 5),
(31, 8, 4),
(31, 9, 5),
(31, 10, 5),
(31, 11, 4),
(31, 12, 5),
(31, 13, 5),
(31, 14, 4),
(31, 15, 5),
(31, 16, 4),
(32, 1, 5),
(32, 2, 5),
(32, 3, 4),
(32, 4, 5),
(32, 5, 5),
(32, 6, 4),
(32, 7, 5),
(32, 8, 4),
(32, 9, 5),
(32, 10, 5),
(32, 11, 4),
(32, 12, 5),
(32, 13, 4),
(32, 14, 5),
(32, 15, 4),
(32, 16, 5),
(33, 1, 5),
(33, 2, 4),
(33, 3, 5),
(33, 4, 4),
(33, 5, 5),
(33, 6, 4),
(33, 7, 5),
(33, 8, 5),
(33, 9, 4),
(33, 10, 5),
(33, 11, 4),
(33, 12, 5),
(33, 13, 5),
(33, 14, 4),
(33, 15, 5),
(33, 16, 4),
(34, 1, 5),
(34, 2, 4),
(34, 3, 5),
(34, 4, 5),
(34, 5, 5),
(34, 6, 4),
(34, 7, 5),
(34, 8, 5),
(34, 9, 4),
(34, 10, 5),
(34, 11, 5),
(34, 12, 5),
(34, 13, 4),
(34, 14, 5),
(34, 15, 4),
(34, 16, 5),
(35, 1, 5),
(35, 2, 5),
(35, 3, 5),
(35, 4, 4),
(35, 5, 5),
(35, 6, 4),
(35, 7, 5),
(35, 8, 4),
(35, 9, 5),
(35, 10, 4),
(35, 11, 5),
(35, 12, 4),
(35, 13, 5),
(35, 14, 4),
(35, 15, 5),
(35, 16, 4),
(36, 1, 5),
(36, 2, 4),
(36, 3, 5),
(36, 4, 4),
(36, 5, 5),
(36, 6, 5),
(36, 7, 4),
(36, 8, 5),
(36, 9, 5),
(36, 10, 5),
(36, 11, 4),
(36, 12, 5),
(36, 13, 5),
(36, 14, 5),
(36, 15, 5),
(36, 16, 4),
(37, 1, 5),
(37, 2, 5),
(37, 3, 4),
(37, 4, 5),
(37, 5, 5),
(37, 6, 4),
(37, 7, 5),
(37, 8, 5),
(37, 9, 4),
(37, 10, 5),
(37, 11, 4),
(37, 12, 5),
(37, 13, 5),
(37, 14, 5),
(37, 15, 4),
(37, 16, 5),
(38, 1, 5),
(38, 2, 4),
(38, 3, 5),
(38, 4, 4),
(38, 5, 5),
(38, 6, 4),
(38, 7, 5),
(38, 8, 4),
(38, 9, 5),
(38, 10, 5),
(38, 11, 4),
(38, 12, 5),
(38, 13, 4),
(38, 14, 3),
(38, 15, 3),
(38, 16, 3),
(39, 1, 5),
(39, 2, 5),
(39, 3, 5),
(39, 4, 5),
(39, 5, 5),
(39, 6, 5),
(39, 7, 5),
(39, 8, 5),
(39, 9, 5),
(39, 10, 5),
(39, 11, 5),
(39, 12, 5),
(39, 13, 5),
(39, 14, 5),
(39, 15, 5),
(39, 16, 5);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers_superior`
--

CREATE TABLE `evaluation_answers_superior` (
  `id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `rate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_answers_superior`
--

INSERT INTO `evaluation_answers_superior` (`id`, `evaluation_id`, `question_id`, `rate`) VALUES
(4, 4, 1, 5),
(5, 4, 3, 4),
(6, 4, 36, 5),
(7, 4, 38, 4),
(8, 4, 39, 5),
(9, 4, 40, 4),
(10, 4, 41, 5),
(11, 4, 42, 4),
(12, 4, 43, 5),
(13, 4, 44, 5),
(14, 4, 45, 4),
(15, 4, 46, 3),
(16, 4, 47, 4),
(17, 4, 48, 4),
(18, 4, 49, 5),
(19, 5, 1, 1),
(20, 5, 3, 1),
(21, 5, 36, 1),
(22, 5, 38, 1),
(23, 5, 39, 1),
(24, 5, 40, 1),
(25, 5, 41, 1),
(26, 5, 42, 1),
(27, 5, 43, 1),
(28, 5, 44, 1),
(29, 5, 45, 1),
(30, 5, 46, 1),
(31, 5, 47, 1),
(32, 5, 48, 1),
(33, 5, 49, 1),
(34, 6, 1, 5),
(35, 6, 3, 5),
(36, 6, 36, 5),
(37, 6, 38, 5),
(38, 6, 39, 5),
(39, 6, 40, 5),
(40, 6, 41, 5),
(41, 6, 42, 5),
(42, 6, 43, 5),
(43, 6, 44, 5),
(44, 6, 45, 5),
(45, 6, 46, 5),
(46, 6, 47, 5),
(47, 6, 48, 5),
(48, 6, 49, 5),
(49, 7, 1, 5),
(50, 7, 3, 5),
(51, 7, 36, 4),
(52, 7, 38, 5),
(53, 7, 39, 4),
(54, 7, 40, 5),
(55, 7, 41, 4),
(56, 7, 42, 5),
(57, 7, 43, 5),
(58, 7, 44, 5),
(59, 7, 45, 5),
(60, 7, 46, 4),
(61, 7, 47, 5),
(62, 7, 48, 5),
(63, 7, 49, 5),
(64, 8, 1, 5),
(65, 8, 3, 4),
(66, 8, 36, 5),
(67, 8, 38, 4),
(68, 8, 39, 5),
(69, 8, 40, 4),
(70, 8, 41, 5),
(71, 8, 42, 4),
(72, 8, 43, 5),
(73, 8, 44, 4),
(74, 8, 45, 5),
(75, 8, 46, 4),
(76, 8, 47, 5),
(77, 8, 48, 4),
(78, 8, 49, 4),
(79, 9, 1, 5),
(80, 9, 3, 4),
(81, 9, 36, 5),
(82, 9, 38, 5),
(83, 9, 39, 5),
(84, 9, 40, 5),
(85, 9, 41, 5),
(86, 9, 42, 4),
(87, 9, 43, 5),
(88, 9, 44, 5),
(89, 9, 45, 4),
(90, 9, 46, 5),
(91, 9, 47, 5),
(92, 9, 48, 5),
(93, 9, 49, 5),
(94, 10, 1, 5),
(95, 10, 3, 4),
(96, 10, 36, 5),
(97, 10, 38, 4),
(98, 10, 39, 5),
(99, 10, 40, 5),
(100, 10, 41, 5),
(101, 10, 42, 4),
(102, 10, 43, 5),
(103, 10, 44, 5),
(104, 10, 45, 5),
(105, 10, 46, 4),
(106, 10, 47, 5),
(107, 10, 48, 5),
(108, 10, 49, 5),
(109, 11, 1, 5),
(110, 11, 3, 4),
(111, 11, 36, 4),
(112, 11, 38, 5),
(113, 11, 39, 5),
(114, 11, 40, 5),
(115, 11, 41, 4),
(116, 11, 42, 5),
(117, 11, 43, 4),
(118, 11, 44, 5),
(119, 11, 45, 5),
(120, 11, 46, 5),
(121, 11, 47, 4),
(122, 11, 48, 5),
(123, 11, 49, 5),
(124, 12, 1, 5),
(125, 12, 3, 4),
(126, 12, 36, 5),
(127, 12, 38, 4),
(128, 12, 39, 5),
(129, 12, 40, 5),
(130, 12, 41, 5),
(131, 12, 42, 5),
(132, 12, 43, 5),
(133, 12, 44, 4),
(134, 12, 45, 5),
(135, 12, 46, 5),
(136, 12, 47, 4),
(137, 12, 48, 5),
(138, 12, 49, 5),
(139, 13, 1, 5),
(140, 13, 3, 4),
(141, 13, 36, 5),
(142, 13, 38, 5),
(143, 13, 39, 4),
(144, 13, 40, 5),
(145, 13, 41, 4),
(146, 13, 42, 5),
(147, 13, 43, 5),
(148, 13, 44, 5),
(149, 13, 45, 5),
(150, 13, 46, 4),
(151, 13, 47, 5),
(152, 13, 48, 5),
(153, 13, 49, 4),
(154, 14, 1, 5),
(155, 14, 3, 5),
(156, 14, 36, 5),
(157, 14, 38, 5),
(158, 14, 39, 5),
(159, 14, 40, 4),
(160, 14, 41, 5),
(161, 14, 42, 4),
(162, 14, 43, 5),
(163, 14, 44, 4),
(164, 14, 45, 5),
(165, 14, 46, 4),
(166, 14, 47, 5),
(167, 14, 48, 5),
(168, 14, 49, 5),
(169, 15, 1, 5),
(170, 15, 3, 4),
(171, 15, 36, 5),
(172, 15, 38, 5),
(173, 15, 39, 5),
(174, 15, 40, 5),
(175, 15, 41, 4),
(176, 15, 42, 5),
(177, 15, 43, 5),
(178, 15, 44, 5),
(179, 15, 45, 4),
(180, 15, 46, 5),
(181, 15, 47, 5),
(182, 15, 48, 5),
(183, 15, 49, 4),
(184, 16, 1, 5),
(185, 16, 3, 4),
(186, 16, 36, 5),
(187, 16, 38, 4),
(188, 16, 39, 5),
(189, 16, 40, 4),
(190, 16, 41, 5),
(191, 16, 42, 5),
(192, 16, 43, 4),
(193, 16, 44, 4),
(194, 16, 45, 5),
(195, 16, 46, 5),
(196, 16, 47, 5),
(197, 16, 48, 4),
(198, 16, 49, 5),
(199, 17, 1, 5),
(200, 17, 3, 4),
(201, 17, 36, 5),
(202, 17, 38, 4),
(203, 17, 39, 5),
(204, 17, 40, 4),
(205, 17, 41, 5),
(206, 17, 42, 4),
(207, 17, 43, 5),
(208, 17, 44, 5),
(209, 17, 45, 5),
(210, 17, 46, 5),
(211, 17, 47, 4),
(212, 17, 48, 5),
(213, 17, 49, 5);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_comments`
--

CREATE TABLE `evaluation_comments` (
  `id` int(11) NOT NULL,
  `evaluation_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `comments` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_comments`
--

INSERT INTO `evaluation_comments` (`id`, `evaluation_id`, `student_id`, `faculty_id`, `section_id`, `subject_id`, `comments`, `created_at`) VALUES
(1, 1, 20, 1, 2, 26, 'Sir Abel is a good communicator!', '2024-04-15 09:02:20'),
(2, 2, 20, 2, 2, 25, 'Ma\'am Lynzel shows expertise in programming', '2024-04-15 09:04:02'),
(3, 3, 20, 4, 2, 23, 'Sir Paulo is very kind!', '2024-04-15 09:04:26'),
(4, 4, 20, 7, 2, 21, 'Sir Rhey is so considerate', '2024-04-15 13:55:00'),
(5, 5, 21, 1, 2, 26, 'Sir Abel is okay', '2024-04-15 15:53:25'),
(6, 6, 21, 2, 2, 25, 'Ma\'am Lynzel is great!', '2024-04-15 16:15:18'),
(7, 7, 21, 4, 2, 23, 'Sir Paulo is fine', '2024-04-15 16:16:07'),
(8, 8, 21, 7, 2, 21, 'Sir Rhey is so nice', '2024-04-15 16:16:39'),
(9, 9, 22, 1, 2, 26, 'Sir Abel teaches well and is very kind', '2024-04-15 16:23:49'),
(10, 10, 22, 2, 2, 25, 'Ma\'am Lynzel is very considerate', '2024-04-15 16:24:26'),
(11, 11, 22, 4, 2, 23, 'Sir Paulo is fair', '2024-04-15 16:33:00'),
(12, 12, 22, 8, 2, 3, 'Sir Ladislao is a good teacher!', '2024-04-15 17:20:06'),
(13, 13, 22, 7, 2, 21, 'Sir rhey is good in communicating', '2024-04-15 17:20:43'),
(14, 15, 32, 10, 1, 33, 'Sir Rommel is very respectful!', '2024-04-19 01:46:11'),
(15, 16, 32, 8, 1, 23, 'Sir Ladislao checks the attendance systematically.', '2024-04-19 02:53:32'),
(16, 17, 32, 7, 2, 21, 'Sir Rhey is very pleasant.', '2024-04-19 02:56:28'),
(17, 18, 32, 4, 2, 23, 'Sir Pau dresses neatly', '2024-04-19 02:57:49'),
(18, 19, 32, 8, 2, 3, 'Sir Ladislao returns activities and projects', '2024-04-19 03:00:37'),
(19, 20, 32, 1, 2, 26, 'Sir Abel is handsome.', '2024-04-19 05:17:08'),
(20, 21, 32, 2, 2, 25, 'Mam Lynzel is pretty', '2024-04-19 05:33:08'),
(21, 22, 32, 2, 2, 25, 'aaa', '2024-04-19 05:35:58'),
(22, 23, 4, 8, 8, 3, 'very nice!', '2024-04-21 05:35:44'),
(23, 24, 40, 2, 2, 25, 'Mam is so respectful to her students. Sobrang bait ni mam samin', '2024-04-21 15:02:46'),
(24, 25, 40, 8, 8, 3, 'Napakagaling magturo ni sir ng subject nya. He acts very professional', '2024-04-21 15:03:58'),
(25, 26, 22, 10, 10, 35, 'Mapagbigay at maintindihin sa mga estudyante', '2024-04-21 23:18:07'),
(26, 27, 5, 7, 7, 21, 'Mahusay mag code!', '2024-04-21 23:23:19'),
(27, 28, 5, 1, 1, 26, 'Coding is good', '2024-04-21 23:27:08'),
(28, 29, 5, 10, 10, 35, 'Good at call centers', '2024-04-21 23:27:49'),
(29, 30, 5, 4, 2, 23, 'Very professional', '2024-04-21 23:38:10'),
(30, 31, 44, 4, 2, 23, 'Sample comment', '2024-04-22 00:57:08'),
(31, 32, 4, 1, 2, 26, 'Good teacher', '2024-04-22 03:45:29'),
(32, 33, 4, 2, 2, 25, 'Great!', '2024-04-22 12:45:51'),
(33, 34, 4, 7, 2, 21, 'Gives fair grades', '2024-04-23 02:51:13'),
(34, 35, 4, 4, 2, 23, 'Great!', '2024-04-23 08:29:46'),
(35, 36, 68, 1, 2, 26, 'Nice', '2024-04-23 15:00:06'),
(36, 37, 22, 12, 2, 35, 'Nice Nice', '2024-04-23 19:13:44'),
(37, 38, 115, 4, 1, 3, 'Very respectful', '2024-04-23 19:17:22'),
(38, 39, 116, 2, 2, 25, 'Kind', '2025-03-28 17:27:13');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_comments_superior`
--

CREATE TABLE `evaluation_comments_superior` (
  `id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `superior_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_comments_superior`
--

INSERT INTO `evaluation_comments_superior` (`id`, `evaluation_id`, `superior_id`, `faculty_id`, `comments`, `created_at`) VALUES
(4, 4, 1, 1, 'Sir Abel is good', '2024-04-15 10:37:06'),
(5, 5, 1, 8, 'Sample bad result', '2024-04-15 13:03:28'),
(6, 6, 1, 4, 'Sample fine neutral', '2024-04-15 13:04:35'),
(7, 7, 1, 0, 'Mabait po si mam', '2024-04-21 16:05:42'),
(8, 8, 1, 7, 'asffsf', '2024-04-21 22:12:07'),
(9, 9, 8, 2, 'Mataas po magbigay ng grade si mam', '2024-04-21 22:13:31'),
(10, 10, 8, 1, 'Mahusay mag code', '2024-04-21 23:21:24'),
(11, 11, 8, 4, 'Mahusay magsalita at mag hikayat sa mga estudyante na mag aral nang mabuti', '2024-04-21 23:22:11'),
(12, 12, 8, 8, 'Very kind and gentle', '2024-04-22 01:06:13'),
(13, 13, 1, 11, 'Great', '2024-04-22 03:48:29'),
(14, 14, 8, 11, 'Great!', '2024-04-23 02:52:46'),
(15, 15, 8, 12, 'Very great', '2024-04-23 19:10:51'),
(16, 16, 10, 1, 'kind and handsome', '2024-04-23 19:18:17'),
(17, 17, 10, 2, 'afsafs', '2024-04-24 02:23:03');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `evaluation_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `restriction_id` int(30) NOT NULL,
  `comments` longtext NOT NULL,
  `date_taken` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_list`
--

INSERT INTO `evaluation_list` (`evaluation_id`, `academic_id`, `class_id`, `student_id`, `subject_id`, `faculty_id`, `restriction_id`, `comments`, `date_taken`) VALUES
(1, 1, 2, 20, 26, 1, 61, 'Sir abel is a good communicator!', '2024-04-15 17:02:20'),
(2, 1, 2, 20, 25, 2, 63, 'Mam Lynzel shows expertise in programming', '2024-04-15 17:04:01'),
(3, 1, 2, 20, 23, 4, 64, 'Sir Paulo is very kind!', '2024-04-15 17:04:26'),
(4, 1, 2, 20, 21, 7, 65, 'Sir Rhey is so considerate', '2024-04-15 21:55:00'),
(5, 1, 2, 21, 26, 1, 61, 'Sir abel is okay', '2024-04-15 23:53:25'),
(6, 1, 2, 21, 25, 2, 63, 'Sir pau is okay', '2024-04-16 00:15:18'),
(7, 1, 2, 21, 23, 4, 64, 'Sir pau is fine', '2024-04-16 00:16:07'),
(8, 1, 2, 21, 21, 7, 65, 'Sir Ladislao is very kind', '2024-04-16 00:16:39'),
(9, 1, 2, 22, 26, 1, 61, 'Mam Lynzel is the best!', '2024-04-16 00:23:49'),
(10, 1, 2, 22, 25, 2, 63, 'Sir Rhey is just okay', '2024-04-16 00:24:26'),
(11, 1, 2, 22, 23, 4, 64, 'Sir Rhey teaches well', '2024-04-16 00:33:00'),
(12, 1, 2, 22, 3, 8, 62, 'Sir Ladislao is a good teacher!', '2024-04-16 01:20:06'),
(13, 1, 2, 22, 21, 7, 65, 'Sir Rhey teaches very well', '2024-04-16 01:20:43'),
(15, 1, 1, 32, 33, 10, 66, 'Sir Rommel is very respectful!', '2024-04-19 09:46:11'),
(16, 1, 1, 32, 23, 8, 67, 'Sir Ladislao checks the attendance systematically.', '2024-04-19 10:53:32'),
(17, 1, 2, 32, 21, 7, 65, 'Sir Rhey is very pleasant.', '2024-04-19 10:56:28'),
(18, 1, 2, 32, 23, 4, 64, 'Sir Pau dresses neatly', '2024-04-19 10:57:49'),
(19, 1, 2, 32, 3, 8, 62, 'Sir Ladislao returns activities and projects', '2024-04-19 11:00:37'),
(20, 1, 2, 32, 26, 1, 61, 'Sir Abel is handsome.', '2024-04-19 13:17:08'),
(21, 1, 2, 32, 25, 2, 63, 'Mam Lynzel is pretty', '2024-04-19 13:33:08'),
(23, 1, 2, 4, 3, 8, 62, 'very nice!', '2024-04-21 13:35:44'),
(24, 1, 2, 40, 25, 2, 63, 'Mam is so respectful to her students. Sobrang bait ni mam samin', '2024-04-21 23:02:46'),
(25, 1, 2, 40, 3, 8, 62, 'Napakagaling magturo ni sir ng subject nya. He acts very professional', '2024-04-21 23:03:58'),
(26, 1, 2, 22, 35, 10, 69, 'Mapagbigay at maintindihin sa mga estudyante', '2024-04-22 07:18:07'),
(27, 1, 2, 5, 21, 7, 65, 'Mahusay mag code!', '2024-04-22 07:23:19'),
(28, 1, 1, 5, 26, 1, 61, 'Coding is good', '2024-04-22 07:27:08'),
(29, 1, 10, 5, 35, 10, 69, 'Good at call centers', '2024-04-22 07:27:48'),
(30, 1, 2, 5, 23, 4, 64, 'Very professional', '2024-04-22 07:38:10'),
(31, 1, 2, 44, 23, 4, 64, 'Sample comment', '2024-04-22 08:57:08'),
(32, 1, 2, 4, 26, 1, 61, 'Good teacher', '2024-04-22 11:45:29'),
(33, 1, 2, 4, 25, 2, 63, 'Great!', '2024-04-22 20:45:51'),
(34, 1, 2, 4, 21, 7, 65, 'Gives fair grades', '2024-04-23 10:51:13'),
(35, 1, 2, 4, 23, 4, 64, 'Great!', '2024-04-23 16:29:46'),
(36, 1, 2, 68, 26, 1, 61, 'Nice', '2024-04-23 23:00:06'),
(37, 1, 2, 22, 35, 12, 72, 'Nice Nice', '2024-04-24 03:13:44'),
(38, 1, 1, 115, 3, 4, 73, 'Very respectful', '2024-04-24 03:17:22'),
(39, 1, 2, 116, 25, 2, 63, 'Kind', '2025-03-29 01:27:13');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list_superior`
--

CREATE TABLE `evaluation_list_superior` (
  `evaluation_id` int(11) NOT NULL,
  `superior_id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_list_superior`
--

INSERT INTO `evaluation_list_superior` (`evaluation_id`, `superior_id`, `academic_id`, `faculty_id`, `comments`, `created_at`) VALUES
(4, 1, 1, 1, 'Sir Abel is good', '2024-04-15 10:37:06'),
(5, 1, 1, 8, 'Sample bad result', '2024-04-15 13:03:28'),
(6, 1, 1, 4, 'Congrats', '2024-04-15 13:04:35'),
(7, 1, 1, 2, 'Mabait po si mam', '2024-04-21 16:05:42'),
(8, 1, 1, 7, 'asffsf', '2024-04-21 22:12:07'),
(9, 8, 1, 2, 'Mataas po magbigay ng grade si mam', '2024-04-21 22:13:31'),
(10, 8, 1, 1, 'Mahusay mag code', '2024-04-21 23:21:24'),
(11, 8, 1, 4, 'Mahusay magsalita at mag hikayat sa mga estudyante na mag aral nang mabuti', '2024-04-21 23:22:11'),
(12, 8, 1, 8, 'Very kind and gentle', '2024-04-22 01:06:13'),
(13, 1, 1, 11, 'Great', '2024-04-22 03:48:29'),
(14, 8, 1, 11, 'Great!', '2024-04-23 02:52:46'),
(15, 8, 1, 12, 'Very great', '2024-04-23 19:10:51'),
(16, 10, 1, 1, 'kind and handsome', '2024-04-23 19:18:17'),
(17, 10, 1, 2, 'afsafs', '2024-04-24 02:23:03');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_list`
--

CREATE TABLE `faculty_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT '''no-image-available.png''',
  `reset_token` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faculty_list`
--

INSERT INTO `faculty_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `reset_token`, `date_created`) VALUES
(1, '1001', 'Abel', 'Palero', 'abelpalero@gmail.com', 'e9036294f33b2120d94a0c40e750113b', '1713203160_1713108180_1710073320_sir_abel.jpg', '6627cdb2860da', '2020-12-15 13:45:18'),
(2, '1003', 'Lynzel', 'Valenzuela', 'lynzelvalenzuela@gmail.com', 'e9036294f33b2120d94a0c40e750113b', 'mam_lynzel.jpg', '', '2024-03-10 20:23:14'),
(4, '1004', 'Paulo', 'Victoria', 'paulovictoria@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '1713280800_435687928_1458611031674066_1422466194529998335_n.jpg', '', '2024-03-11 15:48:06'),
(7, '1005', 'Reynaldo', 'Santos', 'reynaldosantos@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '1713280800_316525581_9015403065140151_8903803334010608974_n.jpg', '', '2024-04-14 23:23:39'),
(8, '1002', 'Ladislao', 'Mercado', 'ladislao@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '1713280800_323409047_684137723409462_1452702392509300263_n.jpg', '', '2024-04-14 23:24:24'),
(12, '1006', 'Rommel', 'Ompoc', 'rommelompoc@gmail.com', 'e9036294f33b2120d94a0c40e750113b', '1713891780_sir rommel.jpg', '', '2024-04-24 01:03:04');

-- --------------------------------------------------------

--
-- Table structure for table `questions_list`
--

CREATE TABLE `questions_list` (
  `id` int(255) NOT NULL,
  `questions` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions_list`
--

INSERT INTO `questions_list` (`id`, `questions`) VALUES
(2, 'Imposes discipline among students fairly and consistently. (Patas at palagian ang pagpapatupad ng disiplina sa lahat ng mga mag-aaral).'),
(3, 'Shows respect and consideration to all students. (Nagpapakita ng paggalang at konsiderasyon sa mga estudyante).'),
(4, 'Encourages stuents to study harder. (Hinihimok ang mga mag-aaral na mag-aral nang mabuti).'),
(5, 'Follows the syllabus/course outline provided by BPC. (Sinusunod ang gabay sa pagtuturo ng BPC).'),
(6, 'Discusses lesson clearly & shows mastery on it. (Tinatalakay nang malinaw at nagpapakita ng malalim na kaalaman ukol sa sa aralin).'),
(7, 'Answers questions intelligently and welcomes comments. (Sinasagot ng may katalinuhan ang mga tanong at tinatanggap ang mga komento).'),
(8, 'Uses chalkboard and/or other audio-visual materials to effectively contribute tot student\'s understanding of the lesson. (Gumagamit ng chalkboard at/o anumang audio-visual na bagay upang higit na makatulong sa pagkatuto ng mga mag-aaral).'),
(9, 'Speaks loudly and uses classroom language efficiently. (Malakas magsalita at ginagamit ng maayos ang lengwaheng pangsilid-aralan).'),
(10, 'Checks the attendance systematically & consistently. (Maayos at palagian tsine-check ang attendance).'),
(11, 'Requires students to cooperate in maintaining classroom cleanliness for a better classroom atmosphere. (Inuutusan ang mga mag-aaral na makiisa sa pagpapanatili ng kaaya-ayang silid-aralan).'),
(12, 'Implements school\'s rules and regulations particularly in wearing ID and proper uniform. (Ipinapatupad ang batas at panuntunan ng paaralan lalo na sa pagsusuot ng ID at tamang uniporme).'),
(13, 'Dresses neatly and respectably. (Malinis at kagalang-galang manamit).'),
(14, 'Starts and ends classes on time. (Sinisimulan at tinatapos ang klase sa tamang oras).'),
(15, 'Returns recorded test papers and projects. (Ibinabalik ang mga naitalang test papers at proyekto).'),
(16, 'Gives transparent and fair grades and criticism. (Nagbibigay ng malinaw at patas na marka at puna).'),
(17, 'Listens and understands student\'s point of view; he/she may not agree but students feel understood. (Marunong makinig sa opinyon ng mga estudyante).');

-- --------------------------------------------------------

--
-- Table structure for table `questions_list_superior`
--

CREATE TABLE `questions_list_superior` (
  `id` int(255) NOT NULL,
  `questions` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions_list_superior`
--

INSERT INTO `questions_list_superior` (`id`, `questions`) VALUES
(1, 'Adheres to the faithful implementation of the policies on students performance in the class, values development, examination procedures, and other school policies.'),
(3, 'Imposes objectivism, by providing a sound judgement of evaluation of the students\' academic performance according to the grading system of policies.'),
(6, 'Creates a classroom climate conductive to teaching and learning processes.'),
(7, 'Practices exemplary punctuality in attending classes.'),
(8, 'Attends faculty meetings, area or committee meetings, in-service training assemblies, graduation exercises and other school functions.'),
(9, 'Imposes virtual classroom disciplines.'),
(10, 'Serves as good class adviser or club moderator/serves as good example in the campus.'),
(11, 'Endeavors for the accomplishment of the institution\'s mission and vision.'),
(12, 'Conducts pre-consultation with the College Administrator/Cluster Heads before implementing certain rules and regulations.'),
(13, 'Possesses potential qualities of being present-minded, creative, optimistic, open-minded and willing to champion he/her profession.'),
(14, 'Upholds the noble task of providing and insuring high standard of teaching through the adoption of innovative methodologies.'),
(15, 'Adopts conscientious, just and humane teaching techniques that promote desirable transformation of moral values of the students.'),
(16, 'Instills harmonious relationship with the administration and adheres to the objectives and implementing rules of the school by recognizing the authority channels, and open to communication process.'),
(17, 'Gives importance to the needs of the school\'s working committees through active participation if needed.'),
(18, 'Have a strong support and full understanding of the contributions of the faculty and their area of specialization in the common task of higher education.');

-- --------------------------------------------------------

--
-- Table structure for table `question_list`
--

CREATE TABLE `question_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `question` text NOT NULL,
  `order_by` int(30) NOT NULL,
  `criteria_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_list`
--

INSERT INTO `question_list` (`id`, `academic_id`, `question`, `order_by`, `criteria_id`) VALUES
(1, 1, 'Imposes discipline among students fairly and consistently. (Patas at palagian ang pagpapatupad ng disiplina sa lahat ng mga mag-aaral).', 0, 17),
(2, 1, 'Listens and understands student\'s point of view; he/she may not agree but students feel understood. (Marunong makinig sa opinyon ng mga estudyante).', 1, 17),
(3, 1, 'Shows respect and consideration to all students. (Nagpapakita ng paggalang at konsiderasyon sa mga estudyante).', 2, 17),
(4, 1, 'Encourages stuents to study harder. (Hinihimok ang mga mag-aaral na mag-aral nang mabuti).', 3, 17),
(5, 1, 'Follows the syllabus/course outline provided by BPC. (Sinusunod ang gabay sa pagtuturo ng BPC).', 4, 18),
(6, 1, 'Discusses lesson clearly & shows mastery on it. (Tinatalakay nang malinaw at nagpapakita ng malalim na kaalaman ukol sa sa aralin).', 5, 18),
(7, 1, 'Answers questions intelligently and welcomes comments. (Sinasagot ng may katalinuhan ang mga tanong at tinatanggap ang mga komento).', 6, 18),
(8, 1, 'Uses chalkboard and/or other audio-visual materials to effectively contribute tot student\'s understanding of the lesson. (Gumagamit ng chalkboard at/o anumang audio-visual na bagay upang higit na makatulong sa pagkatuto ng mga mag-aaral).', 7, 18),
(9, 1, 'Speaks loudly and uses classroom language efficiently. (Malakas magsalita at ginagamit ng maayos ang lengwaheng pangsilid-aralan).', 8, 18),
(10, 1, 'Checks the attendance systematically & consistently. (Maayos at palagian tsine-check ang attendance).', 9, 19),
(11, 1, 'Requires students to cooperate in maintaining classroom cleanliness for a better classroom atmosphere. (Inuutusan ang mga mag-aaral na makiisa sa pagpapanatili ng kaaya-ayang silid-aralan).', 10, 19),
(12, 1, 'Implements school\'s rules and regulations particularly in wearing ID and proper uniform. (Ipinapatupad ang batas at panuntunan ng paaralan lalo na sa pagsusuot ng ID at tamang uniporme).', 11, 19),
(13, 1, 'Dresses neatly and respectably. (Malinis at kagalang-galang manamit).', 12, 20),
(14, 1, 'Starts and ends classes on time. (Sinisimulan at tinatapos ang klase sa tamang oras).', 13, 20),
(15, 1, 'Returns recorded test papers and projects. (Ibinabalik ang mga naitalang test papers at proyekto).', 14, 20),
(16, 1, 'Gives transparent and fair grades and criticism. (Nagbibigay ng malinaw at patas na marka at puna).', 15, 20),
(18, 82, 'Imposes discipline among students fairly and consistently. (Patas at palagian ang pagpapatupad ng disiplina sa lahat ng mga mag-aaral).', 0, 17);

-- --------------------------------------------------------

--
-- Table structure for table `question_list_superior`
--

CREATE TABLE `question_list_superior` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `question` text NOT NULL,
  `order_by` int(30) NOT NULL,
  `criteria_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_list_superior`
--

INSERT INTO `question_list_superior` (`id`, `academic_id`, `question`, `order_by`, `criteria_id`) VALUES
(1, 1, 'Adheres to the faithful implementation of the policies on students performance in the class, values development, examination procedures, and other school policies.', 0, 1),
(3, 1, 'Imposes objectivism, by providing a sound judgement of evaluation of the students\' academic performance according to the grading system of policies.', 1, 1),
(36, 1, 'Practices exemplary punctuality in attending classes.', 2, 1),
(38, 1, 'Creates a classroom climate conductive to teaching and learning processes.', 3, 1),
(39, 1, 'Attends faculty meetings, area or committee meetings, in-service training assemblies, graduation exercises and other school functions.', 4, 1),
(40, 1, 'Imposes virtual classroom disciplines.', 5, 1),
(41, 1, 'Serves as good class adviser or club moderator/serves as good example in the campus.', 6, 1),
(42, 1, 'Endeavors for the accomplishment of the institution\'s mission and vision.', 7, 1),
(43, 1, 'Conducts pre-consultation with the College Administrator/Cluster Heads before implementing certain rules and regulations.', 8, 1),
(44, 1, 'Possesses potential qualities of being present-minded, creative, optimistic, open-minded and willing to champion he/her profession.', 9, 2),
(45, 1, 'Upholds the noble task of providing and insuring high standard of teaching through the adoption of innovative methodologies.', 10, 2),
(46, 1, 'Adopts conscientious, just and humane teaching techniques that promote desirable transformation of moral values of the students.', 11, 2),
(47, 1, 'Instills harmonious relationship with the administration and adheres to the objectives and implementing rules of the school by recognizing the authority channels, and open to communication process.', 12, 2),
(48, 1, 'Gives importance to the needs of the school\'s working committees through active participation if needed.', 13, 2),
(49, 1, 'Have a strong support and full understanding of the contributions of the faculty and their area of specialization in the common task of higher education.', 14, 2),
(50, 11, 'Adheres to the faithful implementation of the policies on students performance in the class, values development, examination procedures, and other school policies.', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `restriction_list`
--

CREATE TABLE `restriction_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `restriction_list`
--

INSERT INTO `restriction_list` (`id`, `academic_id`, `faculty_id`, `class_id`, `subject_id`) VALUES
(52, 3, 1, 2, 3),
(61, 1, 1, 2, 26),
(63, 1, 2, 2, 25),
(64, 1, 4, 2, 23),
(65, 1, 7, 2, 21),
(67, 1, 8, 1, 23),
(70, 1, 1, 1, 26),
(72, 1, 12, 2, 35),
(73, 1, 4, 1, 3),
(74, 82, 1, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sentiment_terms`
--

CREATE TABLE `sentiment_terms` (
  `term_id` int(11) NOT NULL,
  `term` varchar(255) NOT NULL,
  `value` int(11) NOT NULL CHECK (`value` in (1,0,-1)),
  `term_type` varchar(10) NOT NULL CHECK (`term_type` in ('Positive','Neutral','Negative'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sentiment_terms`
--

INSERT INTO `sentiment_terms` (`term_id`, `term`, `value`, `term_type`) VALUES
(17, 'good', 0, 'Neutral'),
(20, 'bad', -1, 'Negative'),
(21, 'considerate', 1, 'Positive'),
(22, 'kind', 1, 'Positive'),
(23, 'disrespectful', -1, 'Negative'),
(25, 'best', 1, 'Positive'),
(26, 'okay', 0, 'Neutral'),
(28, 'fine', 0, 'Neutral'),
(32, 'nice', 1, 'Positive'),
(34, 'unprepared', -1, 'Negative'),
(35, 'inattentive', -1, 'Negative'),
(36, 'fair', 0, 'Neutral'),
(37, 'experienced', 0, 'Neutral'),
(38, 'professional', 1, 'Positive'),
(39, 'disorganized', -1, 'Negative'),
(44, 'great', 1, 'Positive'),
(45, 'unhelpful', -1, 'Negative'),
(46, 'moderate', 0, 'Neutral'),
(50, 'well', 1, 'Positive'),
(52, 'expertise', 0, 'Neutral'),
(60, 'mabait', 1, 'Positive'),
(61, 'mapagbigay', 1, 'Positive'),
(62, 'masungit', -1, 'Negative'),
(63, 'normal', 0, 'Neutral'),
(65, 'mataray', -1, 'Negative'),
(66, 'madamot', -1, 'Negative'),
(67, 'casual', 0, 'Neutral');

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `class_id` int(30) NOT NULL,
  `status` int(5) NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `reset_token` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `class_id`, `status`, `avatar`, `reset_token`, `date_created`) VALUES
(4, 'MA-20-01-0579', 'Janelle Fate', 'Picao', 'ma20010579@bpc.edu.ph', 'e9036294f33b2120d94a0c40e750113b', 2, 0, '1711342320_jan.jpg', '', '2024-03-25 12:54:34'),
(5, 'MA-20-01-0801', 'Reydan', 'Rogel', 'ma20010801@bpc.edu.ph', 'e9036294f33b2120d94a0c40e750113b', 2, 0, '1711361040_rey.jpg', '', '2024-03-25 18:04:55'),
(20, 'MA-20-01-1386', 'Daniel', 'Alcantara', 'ma20011386@bpc.edu.ph', 'e9036294f33b2120d94a0c40e750113b', 2, 0, '1712209320_dan.jpg', '661f5bb354a57', '2024-03-27 15:17:18'),
(22, 'MA-20-01-0960', 'Jocel', 'Santiago', 'ma20010960@bpc.edu.ph', 'e9036294f33b2120d94a0c40e750113b', 2, 0, '1713280920_joc.jpg', '', '2024-03-27 15:23:21'),
(114, 'MA-20-01-9999', 'Student', 'Import', 'studentimport@gmail.com', 'e9036294f33b2120d94a0c40e750113b', 2, 0, 'no-image-available.png', '', '2024-04-24 02:54:41'),
(115, 'MA-20-01-1234', 'Aaaaa', 'Aaaaa', 'aaaaa@gmail.com', 'e9036294f33b2120d94a0c40e750113b', 1, 0, 'no-image-available.png', '', '2024-04-24 02:54:41'),
(116, 'MA-20-01-1234', 'Daniel', 'Alcantara', 'dan@gmail.com', 'e9036294f33b2120d94a0c40e750113b', 2, 0, 'no-image-available.png', '', '2025-03-29 01:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `subject_list`
--

CREATE TABLE `subject_list` (
  `id` int(30) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_list`
--

INSERT INTO `subject_list` (`id`, `code`, `subject`, `description`) VALUES
(3, 'IS-ePT 423', 'Principles of Teaching', 'Foundational Concepts'),
(20, 'IS-CAP 413', 'Capstone Project 1', 'Research Thesis 1'),
(21, 'IS-OJT 413', 'On The Job Training', 'Company Work Training'),
(23, 'IS-CAP 423', 'Capstone Project 2', 'Research Thesis 2'),
(25, 'IS-MIS 423', 'Management Information System', 'Computer Hardware/Software'),
(26, 'IS-CP2 423', 'Computer Programming 2', 'Software Programming / Actual Coding'),
(35, 'IS-ECC 423', 'Call Center', 'Communications And Multimedia ');

-- --------------------------------------------------------

--
-- Table structure for table `superior_list`
--

CREATE TABLE `superior_list` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'no-image-available.png',
  `reset_token` varchar(100) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `superior_list`
--

INSERT INTO `superior_list` (`id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `reset_token`, `date_created`) VALUES
(1, 'Sample', 'Superior', 'samplesuperior@gmail.com', 'e9036294f33b2120d94a0c40e750113b', '1713060720_team-4.jpg', NULL, '2024-04-13 20:59:18'),
(8, 'Sample', 'Superior 2', 'samplesuperior2@gmail.com', 'e9036294f33b2120d94a0c40e750113b', '1713710820_435687928_1458611031674066_1422466194529998335_n.jpg', NULL, '2024-04-21 22:47:36'),
(10, 'Sample', 'Superior 3', 'samplesuperior3@gmail.com', 'e9036294f33b2120d94a0c40e750113b', '1713899760_1713060660_w4.png', NULL, '2024-04-24 03:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Faculty Evaluation System', 'javieralcantara999@gmail.com', '09923796561', 'Mojon, City of Malolos, Bulacan', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`) VALUES
(1, 'Daniel', 'Alcantara', 'daniel@gmail.com', 'e9036294f33b2120d94a0c40e750113b', '1712146500_dan.jpg', '2020-11-26 10:57:04'),
(6, 'Daniel', 'Admin', 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'no-image-available.png', '2024-04-19 15:42:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `user_id` int(11) NOT NULL,
  `question_number` int(11) NOT NULL,
  `answer` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_list`
--
ALTER TABLE `academic_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_request`
--
ALTER TABLE `account_request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_id` (`school_id`);

--
-- Indexes for table `admin_list`
--
ALTER TABLE `admin_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_list`
--
ALTER TABLE `class_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `criteria_list_superior`
--
ALTER TABLE `criteria_list_superior`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drafts`
--
ALTER TABLE `drafts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `evaluation_answers_superior`
--
ALTER TABLE `evaluation_answers_superior`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_comments`
--
ALTER TABLE `evaluation_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_comments_superior`
--
ALTER TABLE `evaluation_comments_superior`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `evaluation_list_superior`
--
ALTER TABLE `evaluation_list_superior`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_list`
--
ALTER TABLE `questions_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_list_superior`
--
ALTER TABLE `questions_list_superior`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_list`
--
ALTER TABLE `question_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_list_superior`
--
ALTER TABLE `question_list_superior`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restriction_list`
--
ALTER TABLE `restriction_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sentiment_terms`
--
ALTER TABLE `sentiment_terms`
  ADD PRIMARY KEY (`term_id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_list`
--
ALTER TABLE `subject_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `superior_list`
--
ALTER TABLE `superior_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`user_id`,`question_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_list`
--
ALTER TABLE `academic_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `account_request`
--
ALTER TABLE `account_request`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `admin_list`
--
ALTER TABLE `admin_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_list`
--
ALTER TABLE `class_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `criteria_list_superior`
--
ALTER TABLE `criteria_list_superior`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `drafts`
--
ALTER TABLE `drafts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_answers_superior`
--
ALTER TABLE `evaluation_answers_superior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `evaluation_comments`
--
ALTER TABLE `evaluation_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `evaluation_comments_superior`
--
ALTER TABLE `evaluation_comments_superior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `evaluation_list_superior`
--
ALTER TABLE `evaluation_list_superior`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `faculty_list`
--
ALTER TABLE `faculty_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `questions_list`
--
ALTER TABLE `questions_list`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `questions_list_superior`
--
ALTER TABLE `questions_list_superior`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `question_list`
--
ALTER TABLE `question_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `question_list_superior`
--
ALTER TABLE `question_list_superior`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `restriction_list`
--
ALTER TABLE `restriction_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `sentiment_terms`
--
ALTER TABLE `sentiment_terms`
  MODIFY `term_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `subject_list`
--
ALTER TABLE `subject_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `superior_list`
--
ALTER TABLE `superior_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `drafts`
--
ALTER TABLE `drafts`
  ADD CONSTRAINT `drafts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `drafts_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_list` (`id`),
  ADD CONSTRAINT `drafts_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subject_list` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
