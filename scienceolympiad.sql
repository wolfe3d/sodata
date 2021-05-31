-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 31, 2021 at 02:07 PM
-- Server version: 8.0.18
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scienceolympiad`
--

-- --------------------------------------------------------

--
-- Table structure for table `award`
--

CREATE TABLE `award` (
  `awardID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `dateAwarded` date NOT NULL,
  `tournamentID` int(11) NOT NULL,
  `note` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE `coach` (
  `coachID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `last` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `emailSchool` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `position` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coach`
--

INSERT INTO `coach` (`coachID`, `userID`, `last`, `first`, `email`, `emailSchool`, `position`) VALUES
(1, 3, 'Wolfe', 'Douglas', 'wolfewhs@gmail.com', 'douglas.wolfe@cobbk12.org', 'Co-Head Coach'),
(2, NULL, 'Taylor', 'Wes', 'addme@gmail.com', 'wesley.taylor@cobbk12.org', 'Co-Head Coach');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `courseID` int(11) NOT NULL,
  `course` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `level` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`courseID`, `course`, `level`) VALUES
(1, 'Biology', 'AP'),
(2, 'Biology', 'Honors'),
(3, 'Biology', 'On level'),
(4, 'Chemistry', 'AP'),
(5, 'Chemistry', 'Honors'),
(6, 'Chemistry', 'On level'),
(7, 'Organic Chemisty', 'College'),
(8, 'Genetics', 'College'),
(9, 'Physics 1', 'AP'),
(10, 'Physics 2', 'AP'),
(11, 'Physics C Mechanics', 'AP'),
(12, 'Physics C Electromagnetism', 'AP'),
(13, 'Physics Freshman', 'Honors'),
(14, 'Physics Freshman', 'On level'),
(15, 'Environmental Science', 'AP'),
(16, 'Anatomy & Physiology OR Human Body Systems', 'Honors'),
(17, 'Biotechnology', 'Honors'),
(18, 'Forensics', 'On level'),
(19, 'Intro to Engineering Design', 'Honors'),
(20, 'Principles of Engineering', 'Honors'),
(21, 'Digital Electronics', 'Honors'),
(22, 'Aerospace Engineering', 'Honors'),
(23, 'Computer Science Principles', 'AP'),
(24, 'Computer Science', 'AP'),
(25, 'Principles of Biomed Science', 'STEM'),
(26, 'Medical Intervention', 'STEM');

-- --------------------------------------------------------

--
-- Table structure for table `coursecompleted`
--

CREATE TABLE `coursecompleted` (
  `coursecompletedID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coursecompleted`
--

INSERT INTO `coursecompleted` (`coursecompletedID`, `courseID`, `studentID`) VALUES
(12, 1, 1),
(13, 2, 1),
(14, 15, 33),
(15, 15, 32),
(16, 15, 31),
(17, 13, 16),
(18, 2, 26),
(19, 13, 26),
(20, 20, 26),
(21, 19, 26),
(22, 15, 43),
(23, 2, 35),
(24, 9, 35),
(25, 15, 36),
(26, 2, 42),
(27, 4, 42),
(28, 2, 10),
(29, 9, 10),
(30, 10, 10),
(31, 15, 41),
(32, 2, 4),
(33, 1, 4),
(34, 11, 4),
(35, 15, 7),
(36, 2, 52),
(37, 2, 49),
(38, 2, 48),
(39, 2, 27),
(40, 1, 27),
(41, 4, 27),
(42, 11, 27),
(45, 1, 6),
(46, 2, 6),
(47, 2, 29),
(48, 15, 13),
(49, 18, 50),
(50, 2, 50),
(51, 1, 50),
(52, 2, 3),
(53, 4, 3),
(54, 15, 3),
(55, 2, 51),
(56, 2, 44),
(57, 1, 44),
(58, 9, 44),
(59, 2, 54),
(60, 2, 55),
(61, 4, 55),
(62, 15, 55),
(63, 2, 56),
(64, 18, 57),
(65, 2, 57),
(66, 2, 60),
(67, 2, 58),
(68, 4, 58),
(69, 15, 58),
(70, 4, 5),
(71, 15, 5),
(72, 11, 5),
(74, 16, 50),
(75, 16, 50),
(76, 5, 50),
(78, 23, 50),
(79, 1, 29),
(80, 16, 29),
(81, 5, 29),
(82, 17, 29),
(83, 23, 29),
(84, 5, 60),
(85, 25, 60),
(86, 16, 60),
(87, 5, 55),
(88, 5, 55),
(89, 11, 55),
(91, 15, 77),
(92, 16, 77),
(93, 2, 77),
(94, 5, 77),
(96, 1, 51),
(97, 16, 51),
(98, 25, 51),
(99, 26, 51),
(102, 4, 41),
(103, 5, 41),
(104, 19, 41),
(105, 23, 41),
(106, 16, 28),
(107, 2, 28),
(108, 5, 28),
(109, 25, 28),
(110, 4, 31),
(111, 5, 31),
(112, 19, 31),
(113, 23, 31),
(114, 19, 14),
(115, 20, 14),
(116, 21, 14),
(119, 12, 4),
(120, 4, 4),
(121, 5, 4),
(122, 19, 4),
(123, 20, 4),
(124, 23, 4),
(125, 24, 4),
(127, 1, 69),
(129, 10, 69);

-- --------------------------------------------------------

--
-- Table structure for table `courseenrolled`
--

CREATE TABLE `courseenrolled` (
  `courseenrolledID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courseenrolled`
--

INSERT INTO `courseenrolled` (`courseenrolledID`, `courseID`, `studentID`) VALUES
(10, 1, 1),
(11, 4, 33),
(12, 11, 33),
(13, 24, 32),
(15, 2, 16),
(16, 15, 76),
(17, 5, 26),
(18, 9, 26),
(19, 21, 26),
(20, 4, 43),
(21, 12, 35),
(22, 4, 36),
(23, 11, 36),
(24, 1, 42),
(25, 11, 42),
(26, 11, 10),
(27, 4, 41),
(30, 15, 25),
(31, 4, 7),
(33, 1, 3),
(34, 11, 3),
(36, 15, 24),
(38, 1, 57),
(39, 15, 59),
(40, 1, 58),
(41, 11, 50),
(42, 11, 29),
(43, 9, 60),
(44, 10, 60),
(45, 4, 60),
(46, 1, 60),
(47, 26, 60),
(48, 12, 55),
(49, 1, 55),
(50, 1, 77),
(51, 4, 77),
(52, 26, 77),
(53, 11, 51),
(54, 1, 28),
(55, 26, 28),
(56, 24, 31),
(57, 11, 31),
(58, 22, 14),
(59, 7, 4);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `eventID` int(11) NOT NULL,
  `event` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`eventID`, `event`, `type`) VALUES
(1, 'Anatomy and Physiology', 'Core Knowledge (Test Only)'),
(2, 'Astronomy', 'Core Knowledge (Test Only)'),
(3, 'Boomilever', 'Build'),
(4, 'Bridges', 'Build'),
(5, 'Cell Biology', 'Core Knowledge (Test Only)'),
(6, 'Chemistry Lab', 'Hybrid Lab'),
(7, 'Circuit Lab', 'Hybrid Lab'),
(8, 'Codebusters', 'Core Knowledge (Test Only)'),
(9, 'Designer Genes', 'Core Knowledge (Test Only)'),
(10, 'Detector Building', 'Hybrid Build'),
(11, 'Digital Structures', 'Build'),
(12, 'Disease Detectives', 'Core Knowledge (Test Only)'),
(13, 'Dynamic Planet', 'Core Knowledge (Test Only)'),
(14, 'Environmental Chemistry', 'Core Knowledge (Test Only)'),
(15, 'Experimental Design', 'Laboratory or Hands On'),
(16, 'Forensics', 'Hybrid Lab'),
(17, 'Fossils', 'Core Knowledge (Test Only)'),
(18, 'GeoLogic Mapping', 'Core Knowledge (Test Only)'),
(19, 'Gravity Vehicle', 'Build'),
(20, 'Green Generation', 'Core Knowledge (Test Only)'),
(21, 'Its About Time', 'Hybrid Build'),
(22, 'Machines', 'Hybrid Build'),
(23, 'Ornithology', 'Core Knowledge (Test Only)'),
(24, 'Ping Pong Parachute', 'Build'),
(25, 'Protein Modeling', 'Hybrid Build'),
(26, 'Remote Sensing', 'Hybrid Build'),
(27, 'Rocks & Minerals', 'Core Knowledge (Test Only)'),
(28, 'Sounds of Music', 'Hybrid Build'),
(29, 'test', 'Hybrid Build'),
(30, 'Trajectory', 'Build'),
(31, 'Water Quality', 'Hybrid Lab'),
(32, 'WiFi Lab', 'Hybrid Build'),
(33, 'Wright Stuff', 'Build'),
(34, 'Write It CAD It', 'Build'),
(35, 'Write It Do It', 'Build'),
(36, 'Anatomy and Physiology3', 'Core Knowledge (Test Only)'),
(37, 'try', 'Build');

-- --------------------------------------------------------

--
-- Table structure for table `eventchoice`
--

CREATE TABLE `eventchoice` (
  `eventchoiceID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `eventyearID` int(11) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventchoice`
--

INSERT INTO `eventchoice` (`eventchoiceID`, `studentID`, `eventyearID`, `priority`) VALUES
(15, 6, 15, 1),
(16, 6, 16, 1),
(17, 6, 5, 1),
(19, 6, 2, 2),
(20, 6, 1, 2),
(21, 6, 3, 2),
(22, 6, 28, 1),
(23, 1, 1, 1),
(24, 1, 3, 1),
(25, 1, 18, 1),
(26, 25, 3, 1),
(27, 26, 2, 1),
(29, 25, 7, 3),
(30, 25, 11, 1),
(31, 43, 10, 1),
(32, 43, 17, 1),
(33, 52, 12, 1),
(34, 52, 13, 1),
(35, 52, 7, 1),
(36, 52, 17, 1),
(37, 52, 9, 1),
(38, 67, 12, 1),
(39, 67, 5, 1),
(40, 67, 13, 1),
(41, 67, 22, 1),
(42, 67, 22, 1),
(43, 67, 27, 1),
(45, 33, 9, 1),
(46, 33, 4, 2),
(47, 33, 1, 3),
(48, 33, 27, 4),
(49, 33, 14, 5),
(50, 32, 21, 1),
(51, 32, 4, 2),
(52, 32, 10, 3),
(53, 32, 11, 4),
(54, 32, 19, 5),
(55, 31, 21, 1),
(56, 31, 4, 2),
(57, 31, 18, 3),
(58, 31, 11, 4),
(59, 31, 2, 5),
(60, 16, 15, 1),
(61, 16, 29, 2),
(62, 16, 5, 3),
(63, 16, 22, 4),
(64, 16, 16, 5),
(65, 76, 2, 1),
(66, 76, 14, 2),
(67, 76, 20, 3),
(68, 76, 12, 4),
(69, 76, 9, 5),
(70, 40, 18, 1),
(71, 40, 16, 2),
(72, 40, 3, 3),
(73, 40, 15, 4),
(74, 40, 5, 5),
(75, 43, 10, 1),
(76, 43, 21, 2),
(77, 43, 9, 3),
(78, 43, 13, 4),
(79, 43, 27, 5),
(81, 26, 11, 2),
(82, 26, 27, 3),
(83, 26, 16, 4),
(84, 26, 6, 5),
(85, 35, 18, 1),
(86, 35, 12, 2),
(87, 35, 20, 3),
(88, 35, 16, 4),
(89, 35, 5, 5),
(90, 36, 2, 1),
(91, 36, 10, 2),
(92, 36, 6, 3),
(93, 36, 27, 4),
(94, 36, 9, 5),
(95, 42, 7, 1),
(96, 42, 4, 2),
(97, 42, 1, 3),
(98, 42, 19, 4),
(99, 42, 9, 5),
(100, 10, 14, 1),
(101, 10, 20, 2),
(102, 10, 5, 3),
(103, 10, 11, 4),
(104, 10, 13, 5),
(105, 41, 21, 1),
(106, 41, 4, 2),
(107, 41, 11, 3),
(108, 41, 10, 4),
(109, 41, 22, 5),
(110, 4, 1, 1),
(111, 4, 2, 2),
(112, 4, 7, 3),
(113, 4, 29, 4),
(114, 4, 6, 5),
(115, 14, 3, 1),
(116, 14, 22, 2),
(117, 14, 15, 3),
(118, 14, 18, 4),
(119, 14, 16, 5),
(120, 25, 7, 1),
(121, 25, 22, 2),
(122, 25, 11, 3),
(123, 25, 3, 4),
(124, 25, 16, 5),
(125, 7, 17, 1),
(126, 7, 21, 2),
(127, 7, 9, 3),
(128, 7, 4, 4),
(129, 7, 13, 5),
(130, 52, 7, 1),
(131, 52, 1, 2),
(132, 52, 9, 3),
(133, 52, 12, 4),
(134, 52, 22, 5),
(135, 49, 11, 1),
(136, 49, 27, 2),
(137, 49, 1, 3),
(138, 49, 6, 4),
(139, 49, 9, 5),
(140, 48, 3, 1),
(141, 48, 16, 2),
(142, 48, 22, 3),
(143, 48, 18, 4),
(144, 48, 15, 5),
(145, 27, 7, 1),
(146, 27, 4, 2),
(147, 27, 20, 3),
(148, 27, 9, 4),
(149, 27, 12, 5),
(150, 6, 18, 1),
(151, 6, 15, 2),
(152, 6, 22, 3),
(153, 6, 18, 1),
(154, 6, 15, 2),
(155, 6, 27, 3),
(156, 6, 20, 4),
(157, 6, 22, 5),
(158, 74, 16, 1),
(160, 74, 12, 1),
(161, 29, 9, 1),
(162, 29, 19, 2),
(163, 29, 7, 3),
(164, 29, 4, 4),
(165, 29, 12, 5),
(166, 13, 10, 1),
(167, 13, 21, 2),
(168, 13, 9, 3),
(169, 13, 13, 4),
(170, 13, 27, 5),
(171, 50, 7, 1),
(172, 50, 12, 2),
(173, 50, 20, 3),
(174, 50, 18, 4),
(175, 50, 9, 5),
(176, 3, 7, 1),
(177, 3, 9, 2),
(178, 3, 19, 3),
(179, 3, 6, 4),
(180, 3, 11, 5),
(181, 51, 6, 1),
(182, 51, 2, 2),
(183, 51, 11, 3),
(184, 51, 13, 4),
(185, 51, 17, 5),
(186, 24, 12, 1),
(187, 24, 9, 2),
(188, 24, 17, 3),
(189, 24, 4, 4),
(190, 24, 1, 5),
(191, 12, 15, 1),
(192, 12, 18, 2),
(193, 12, 3, 3),
(194, 12, 16, 4),
(195, 12, 22, 5),
(196, 44, 1, 1),
(197, 44, 7, 2),
(198, 44, 13, 3),
(199, 44, 17, 4),
(200, 44, 27, 5),
(201, 54, 7, 1),
(202, 54, 11, 2),
(203, 54, 12, 3),
(204, 54, 2, 4),
(205, 54, 9, 5),
(206, 55, 4, 1),
(207, 55, 1, 2),
(208, 55, 6, 3),
(209, 55, 12, 4),
(210, 55, 21, 5),
(211, 56, 29, 1),
(212, 56, 16, 2),
(213, 56, 13, 3),
(214, 56, 22, 4),
(215, 56, 27, 5),
(216, 57, 27, 1),
(217, 57, 1, 2),
(218, 57, 7, 3),
(219, 57, 12, 4),
(220, 57, 9, 5),
(221, 59, 3, 1),
(222, 59, 5, 2),
(223, 59, 1, 3),
(224, 59, 4, 4),
(225, 59, 18, 5),
(226, 60, 1, 1),
(227, 60, 5, 2),
(228, 60, 4, 3),
(229, 60, 12, 4),
(230, 60, 21, 5),
(231, 58, 19, 1),
(232, 58, 1, 2),
(233, 58, 7, 3),
(234, 58, 27, 4),
(235, 58, 9, 5),
(236, 5, 22, 1),
(237, 5, 21, 2),
(238, 5, 14, 3),
(239, 5, 27, 4),
(240, 5, 10, 5),
(250, 69, 0, 1),
(251, 69, 0, 1),
(252, 69, 0, 2),
(253, 69, 0, 1),
(254, 69, 0, 1),
(262, 69, 30, 2),
(266, 69, 28, 2),
(268, 69, 47, 4),
(269, 69, 48, 3),
(270, 57, 32, 1);

-- --------------------------------------------------------

--
-- Table structure for table `eventtype`
--

CREATE TABLE `eventtype` (
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventtype`
--

INSERT INTO `eventtype` (`type`) VALUES
('Build'),
('Core Knowledge (Test Only)'),
('Hybrid Build'),
('Hybrid Lab'),
('Laboratory or Hands On');

-- --------------------------------------------------------

--
-- Table structure for table `eventyear`
--

CREATE TABLE `eventyear` (
  `eventyearID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `studentID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventyear`
--

INSERT INTO `eventyear` (`eventyearID`, `eventID`, `year`, `studentID`) VALUES
(1, 1, 2021, 54),
(2, 2, 2021, 4),
(3, 3, 2021, 69),
(4, 6, 2021, 0),
(5, 7, 2021, 0),
(6, 8, 2021, 0),
(7, 9, 2021, 0),
(8, 11, 2021, 0),
(9, 12, 2021, 0),
(10, 13, 2021, 0),
(11, 15, 2021, 0),
(12, 16, 2021, 0),
(13, 17, 2021, 0),
(14, 18, 2021, 0),
(15, 19, 2021, 0),
(16, 22, 2021, 0),
(17, 23, 2021, 0),
(18, 24, 2021, 0),
(19, 25, 2021, 0),
(20, 28, 2021, 0),
(21, 31, 2021, 0),
(22, 33, 2021, 0),
(27, 34, 2021, 0),
(28, 1, 2022, 67),
(29, 10, 2021, 0),
(32, 6, 2022, 66),
(33, 8, 2022, 0),
(36, 13, 2022, 34),
(38, 14, 2022, NULL),
(39, 15, 2022, 0),
(40, 16, 2022, NULL),
(41, 19, 2022, NULL),
(42, 20, 2022, NULL),
(43, 0, 2022, NULL),
(44, 23, 2022, NULL),
(45, 24, 2022, NULL),
(46, 26, 2022, NULL),
(47, 27, 2022, NULL),
(48, 30, 2022, NULL),
(49, 32, 2022, NULL),
(52, 21, 2022, NULL),
(53, 18, 2022, NULL),
(54, 17, 2022, NULL),
(55, 29, 2022, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `officer`
--

CREATE TABLE `officer` (
  `officerID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `position` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officer`
--

INSERT INTO `officer` (`officerID`, `studentID`, `year`, `position`) VALUES
(1, 5, 2021, 'Captain'),
(2, 3, 2021, 'Vice-Captain'),
(3, 1, 2021, 'Vice-Captain'),
(4, 8, 2021, 'Secretary'),
(5, 6, 2021, 'Build-It Boss'),
(6, 11, 2021, 'Testing Coordinator'),
(7, 7, 2021, 'Testing Coordinator'),
(8, 35, 2021, 'Team Competition Coordinator - A'),
(9, 4, 2021, 'Team Competition Coordinator - C'),
(10, 4, 2022, 'Captain'),
(11, 1, 2022, 'Vice-Captain'),
(14, 8, 2022, 'Vice-Captain'),
(15, 25, 2022, 'Secretary'),
(16, 14, 2022, 'Build-It Boss'),
(17, 7, 2022, 'Testing Coordinator'),
(18, 24, 2022, 'Testing Coordinator'),
(19, 26, 2022, 'Database Manager'),
(20, 28, 2022, 'Team Competition Coordinator - A'),
(21, 13, 2022, 'Team Competition Coordinator - B'),
(22, 29, 2022, 'Team Competition Coordinator - C');

-- --------------------------------------------------------

--
-- Table structure for table `phonetype`
--

CREATE TABLE `phonetype` (
  `phoneType` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phonetype`
--

INSERT INTO `phonetype` (`phoneType`) VALUES
('cell'),
('home'),
('parent\'s cell');

-- --------------------------------------------------------

--
-- Table structure for table `rule`
--

CREATE TABLE `rule` (
  `ruleID` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `fileName` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rule`
--

INSERT INTO `rule` (`ruleID`, `level`, `year`, `fileName`) VALUES
(1, 3, 2021, 'Science_Olympiad_Div_C_2021.pdf'),
(2, 2, 2021, 'Science_Olympiad_Div_B_2021.pdf'),
(3, 3, 2020, 'Science_Olympiad_Div_C_2020.pdf'),
(4, 2, 2020, 'Science_Olympiad_Div_C_2020.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `uniqueToken` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `yearGraduating` int(11) NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `emailSchool` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phoneType` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'cell',
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent1Last` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent1First` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent1Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent1Phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent2Last` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent2First` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent2Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent2Phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentID`, `userID`, `uniqueToken`, `last`, `first`, `active`, `yearGraduating`, `email`, `emailSchool`, `phoneType`, `phone`, `parent1Last`, `parent1First`, `parent1Email`, `parent1Phone`, `parent2Last`, `parent2First`, `parent2Email`, `parent2Phone`) VALUES
(1, 21, '', 'Huang', 'Susanna', 1, 2022, 'Susanna.lmt.196@gmail.com', NULL, 'cell', '678-833-7075', ' Hu', 'Lillian', 'dd_1027@yahoo.com', '347-268-3292', 'Huang', 'two', NULL, NULL),
(3, 0, '', 'Yan', 'Grace', 0, 2021, 'graceyan61317@gmail.com', NULL, 'cell', '770-309-9868', 'Zeng ', 'Xiaoyan ', 'xzengx@yahoo.com', '770-330-7097', NULL, NULL, NULL, NULL),
(4, 12, '', 'Joshi', 'Chinmay', 1, 2022, 'chinmayj.walton@gmail.com', '', '', '425-217-9013', 'Joshi', 'Neha', 'nehapjoshi@yahoo.com', '4252159879', 'Joshi', 'Prasanna', 'prasmohanjoshi@gmail.com', '425-375-1369'),
(5, 0, '', 'Lee', 'Rebecca (Eunjae)', 0, 2021, 'eunjaerebecca@gmail.com', '', '', '678-978-2635', 'Huh', 'Inhee', 'inhee319@gmail.com', '404-247-5442', '', '', '', ''),
(6, 0, '', 'Feren', 'Emily', 0, 2021, 'emferen3@gmail.com', '', 'cell', '828-989-2539', 'Feren', 'Stephen', 'Sferen@gmail.com', '828-989-1561', '', '', '', ''),
(7, 16, '', 'Peng', 'Cynthia', 1, 2022, 'alcp6201@gmail.com', '', '', '770-795-7109', 'Lu', 'Wendy', 'wendy_lu@yahoo.com', '770-380-4560', '', '', '', ''),
(8, 14, '', 'Rami', 'Rima', 1, 2022, 'rimazazu@gmail.com', '', '', '4703883822', 'Rami', 'Rafi', '', '4044443440', 'Rami', 'Parviz', 'parviz_rami@yahoo.com', '4044444499'),
(10, 0, '', 'Wei', 'Banglue', 0, 2021, 'banglueweiga@gmail.com', '', '', '4703042706', 'Liu', 'Shizen', 'shizhenliu@hotmail.com', '4704267236', '', '', '', ''),
(11, 0, '', 'Lai', 'Sheena', 0, 2021, 'sheenalai2012@gmail.com', '', '', '404-955-2502', 'Bao', 'Jieqiong ', 'jbao24548@gmail.com', '770-971-5234', '', '', '', ''),
(12, 0, '', 'Siegmund', 'Julian', 1, 2023, 'juljs05@gmail.com', '', '', '4049609241', '', '', '', '', '', '', '', ''),
(13, 15, '', 'Huang', 'Faith', 1, 2023, 'fyizhenh@gmail.com', '', '', '770-294-0421', 'Huang', 'Rongbing', 'rongbing.huang@gmail.com', '770-309-7851', '', '', '', ''),
(14, 19, '', 'Wang', 'Chris', 1, 2022, 'goodchris0831@gmail.com', 'Chris.Wang@students.cobbk12.org', '', '4702656105', 'Chang', 'Jung Chu', 'cjungchu@gmail.com', '6785388116', 'Wang', 'Tai', '', ''),
(15, 0, '', 'Yamin', 'Asad', 0, 2022, 'yaminasad@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(16, 10, '', 'Mei', 'Andrew', 0, 2023, 'andrewmei915@gmail.com', '', '', '404-348-3229', 'Mei', 'Chase', 'chasemei@gmail.com', '470-403-0480', '', '', '', ''),
(22, 9, '', 'Wolfe', 'Doug', 1, 2022, 'dougwolfejr@gmail.com', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 22, '', 'Last', 'Wolfe', 1, 2025, '', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 17, '', 'He', 'Jennifer', 1, 2024, 'jenniferhe0203@gmail.com', NULL, 'cell', NULL, 'He', 'Jim ', 'jingwu_he@yahoo.com', '404-667-5076', 'Zhang ', 'Jun', 'shirley_jun@yahoo.com', '404-472-9421'),
(25, 18, '', 'Hable', 'Christian', 1, 2024, 'cmhable@gmail.com', NULL, 'cell', '14043603478', 'Li', 'Jue', 'leejue@yahoo.com', '9012010337', 'Hable', 'Bill', 'billokc67@gmail.com', '7703164304'),
(26, 10, '', 'Greig', 'Andrew', 1, 2022, 'acgreig@gmail.com', NULL, 'cell', '470-572-4613', 'Greig', 'David', 'dcgreig@gmail.com', '678-810-1685', 'Greig', 'Angela', 'angela.greig@cobbk12.org', '470-527-4613'),
(27, NULL, '', 'Joo', 'Emily', 1, 2021, 'emilyjpie110@gmail.com', NULL, 'cell', '404-259-6032', 'Jeong', 'Youngmin', 'Joomin01@hotmail.com', '404-319-1613', NULL, NULL, NULL, NULL),
(28, 13, '', 'Wei', 'Joanna (Zhuyun)', 1, 2023, 'joannawei05@gmail.com', 'zhuyun.wei@students.cobbk12.org', 'cell', '770-625-0188', 'Wei', 'Lusia ', NULL, '7706880586', 'Zhao', 'Sharp', NULL, '7708265888'),
(29, 11, '', 'Yang', 'Emily', 1, 2022, 'emilysun1160@gmail.com', 'emily.yang916@students.cobbk12.org', 'cell', '678-559-8506', 'Sun', 'Weitao', 'weitao3@yahoo.com', '508-904-7561', 'Yang', 'Richard', NULL, '5089047549'),
(31, NULL, '', 'Karuman', 'Akshita', 1, 2023, 'akshita.karuman@gmail.com', 'akshita.karuman@students.cobbk12.org', 'cell', '470-377-6656', 'Damodaran', 'Rajiv ', 'rajivdeepna@gmail.com', '404-510-6107', 'Rajiv', 'Deepna', '4703014030', NULL),
(32, NULL, '', 'Kona', 'Abhishek', 1, 2022, 'abhishek.kona135@gmail.com', NULL, 'cell', '678-581-9817', 'Kona', 'Sirisha ', 'mail_sirisha@yahoo.com', '770-756-7967', NULL, NULL, NULL, NULL),
(33, NULL, '', 'Kona', 'Abhinav', 1, 2022, 'avkona0325@gmail.com', NULL, 'cell', '404-953-1112', 'Kona', 'Sirisha', 'mail_sirisha@yahoo.com', '770-856-7967', NULL, NULL, NULL, NULL),
(34, NULL, '', 'Dahiya', 'Anchita', 1, 2022, 'anniedahiya1@gmail.com', NULL, 'cell', '404-987-9517', 'Rani', 'Kirti', 'kirtirani@gmail.com', '804-316-8139', NULL, NULL, NULL, NULL),
(35, NULL, '', 'Melnikova', 'Tonya (Antonina)', 1, 2021, 'tonya.melnik7@gmail.com', NULL, 'cell', '4048237643', ' Melnikov', 'Oleg', 'lerik78@gmail.com', '4048248439', NULL, NULL, NULL, NULL),
(36, NULL, '', 'Roy', 'Aryan', 1, 2022, 'aryanaviroy@gmail.com', NULL, 'cell', '4702789888', 'Roy', 'Avijit', NULL, '6787884473', NULL, NULL, NULL, NULL),
(40, NULL, '', 'Sankuratri', 'Anish ', 1, 2023, 'anishdfish@gmail.com', NULL, 'cell', '678-799-7579', ' Sankuratri', 'Kodanda', 'kodanda.rs@gmail.com', '678-862-2682', NULL, NULL, NULL, NULL),
(41, NULL, '', 'Yetukuri', 'Rinky (Chaitanya Sri)', 1, 2023, 'rinky.yetukuri@gmail.com', 'chaitanya.yetukuri@students.cobbk12.org', 'cell', '470-454-5572', 'Yetukuri', 'Devaraju', 'devaraju.yetukuri@gmail.com', '404-386-1516', 'Ghanta', 'Hymavathi', NULL, '7705732366'),
(42, NULL, '', 'Yamin', 'Asad', 1, 2022, 'yaminasad@gmail.com', NULL, 'cell', '7707576259', 'Yamin', 'Khalid ', 'khalid_yamin@msn.com', '4044027613', NULL, NULL, NULL, NULL),
(43, NULL, '', 'Lee', 'Annabelle', 1, 2022, 'soyoonlee0629@gmail.com', NULL, 'cell', '6787885232', 'Lee', 'Jieun', 'jizzang2@hanmail.net', '6782963736', NULL, NULL, NULL, NULL),
(44, NULL, '', 'Ramaswamy', 'Karthika', 1, 2021, 'karthika.v.ramaswamy@gmail.com', NULL, 'cell', '470-269-1542', ' Ramaswamy', 'Mohan', 'mohan_ramaswamy@yahoo.com', '678-516-1298', NULL, NULL, NULL, NULL),
(48, NULL, '', 'Tyler', 'Dominick', 1, 2023, 'dttyler12@gmail.com', NULL, 'cell', '9843640083', 'Tyler', 'Caroline', 'carolinetyler@me.com', '919-619-1102', NULL, NULL, NULL, NULL),
(49, NULL, '', 'Liu', 'David', 1, 2023, 'davidleoliu2@gmail.com', NULL, 'cell', '347-794-7932', 'Shi', 'Li', 'shili2720@gmail.com', '201-496-3599', NULL, NULL, NULL, NULL),
(50, NULL, '', 'Guo', 'Fiona', 1, 2022, 'fitgps1@gmail.com', 'fiona.guo@students.cobbk12.org', 'cell', '6308352869', 'Guo', 'Gary (Qing)', 'gqyy2010@gmail.com', '6307683790', 'Zhou', 'Yuanyuan Zhou', NULL, '6306961085'),
(51, NULL, '', 'Shen', 'Grace', 1, 2022, 'graceshen04@gmail.com', NULL, 'cell', '678-641-1633', 'Shen', 'Peiqing (Patrick)', 'pqshen@yahoo.com', '404-831-3696', 'Yin', 'Hong', NULL, '7703547057'),
(52, NULL, '', 'Maslamani', 'Dana', 1, 2022, 'danamasla1999@gmail.com', NULL, 'cell', '4049920928', 'Maslamani', 'Badera ', 'Baderal@almaslamani.com', '4049802457', NULL, NULL, NULL, NULL),
(53, NULL, '', 'Siegmund', 'Julian', 1, 2023, 'juljs05@gmail.com', NULL, 'cell', '4049609241', 'Siegmund', 'Heike', 'heike_b_siegmund@yahoo.com', '4044063729', NULL, NULL, NULL, NULL),
(54, NULL, '', 'Gunjan', 'Mayank', 1, 2023, 'Mgunj1001@gmail.com', NULL, 'cell', '678-382-7232', 'Gunjan', 'Samir', 'gunjansk@yahoo.com', '859-559-1608', NULL, NULL, NULL, NULL),
(55, NULL, '', 'Reddy', 'Megan', 1, 2022, 'meganreddys@gmail.com', 'megan.reddy@students.cobbk12.org', 'cell', '770-362-5455', 'Peddareddy', 'Lakshmi ', 'venkyrm@hotmail.com', '7045198345', 'Mukthapuram', 'Venkat ', NULL, '7703625455'),
(56, NULL, '', 'Hari', 'Nandana', 1, 2023, 'nandanahari@hotmail.com', NULL, 'cell', '678-974-9505', 'Hari', 'Soumya', 'chat.to.soumya@gmail.com', '678-974-9505', NULL, NULL, NULL, NULL),
(57, NULL, '', 'Dileep', 'Nivedita', 1, 2022, 'nivi.dileep@gmail.com', NULL, 'cell', '4046937018', 'Dileep', 'Ambili', 'pambilip@gmail.com', '4044169718', NULL, NULL, NULL, NULL),
(58, NULL, '', 'Malladi', 'Pranav', 1, 2022, 'the.pranav123@gmail.com', NULL, 'cell', '4042022327', 'Malladi', 'Ravisankara', NULL, '6789937378', NULL, NULL, NULL, NULL),
(59, NULL, '', 'Inan', 'Omer', 1, 2024, 'omer.m.inan2024@gmail.com', NULL, 'cell', '7705589882', 'Inan', 'Erin ', 'oeinan@hotmail.com', '6508148589', NULL, NULL, NULL, NULL),
(60, NULL, '', 'Jain', 'Palak', 1, 2023, 'Jnpalak2005@gmail.com', 'Palak.jain@students.cobbk12.org', 'cell', '4704735588', 'Jain', 'Kirti', 'kirti.jain@adityabirla.com', '4044264207', 'Jain', 'Sonila', NULL, '2294161001'),
(62, NULL, '', 'Venkatesh ', 'Saanvi', 1, 2024, 'saanvikv@gmail.com', NULL, 'cell', '470-633-9082', 'Venkatesh', 'Sheetal ', 'sheetalhs@gmail.com', '407-810-2494', NULL, NULL, NULL, NULL),
(65, NULL, '', 'Shetty', 'Samrita', 1, 2024, 'SamritaSShetty@gmail.com', NULL, 'cell', '470-755-2844', ' Shetty', 'Samith', 'samithshetty@hotmail.com', '704-264-6312', NULL, NULL, NULL, NULL),
(66, NULL, '', 'Clark', 'Sarah', 1, 2024, 'sarahkatclark06@gmail.com', NULL, 'cell', '678-644-4361', 'Clark', 'Jennifer', 'JenClark@aol.com', '205-249-7713', NULL, NULL, NULL, NULL),
(67, NULL, '', 'Umesh', 'Shashank', 1, 2022, 'shashumesh@gmail.com', NULL, 'cell', '6789000148', 'Rajamani', 'Gayathri', 'gayathri.umesh@gmail.com', '4048837177', NULL, NULL, NULL, NULL),
(69, NULL, '', 'Choi', 'Wonho', 1, 2022, 'wonhoc4161@gmail.com', NULL, 'cell', '4046617456', 'Choi', 'Yongmin', 'dr.choi70@gmail.com', '4705584262', NULL, NULL, NULL, NULL),
(71, NULL, '', 'Vijay', 'Varun', 1, 2022, 'varunvj12@gmail.com', NULL, 'cell', '4047025555', 'Sundar', 'Vijaya', 'vijayaks19@gmail.com', '7709255322', NULL, NULL, NULL, NULL),
(72, NULL, '', 'Raj', 'Shivani', 1, 2022, 'shivani.raj2630@gmail.com', NULL, 'cell', '4048074822', 'Venugopal', 'Sridevi', 'srijay2630@gmail.com', '4048015353', NULL, NULL, NULL, NULL),
(73, NULL, '', 'Wang', 'Zachary', 1, 2021, 'zackzwang@gmail.con', NULL, 'cell', '470-535-1276', 'Wang', 'Thomas', 'tomswang@hotmail.com', '404-449-1094', NULL, NULL, NULL, NULL),
(74, NULL, '', 'Gistren ', 'Jasmine ', 1, 2024, 'jazzy.gistren@gmail.com', NULL, 'cell', '239-235-9292', 'Gistern ', 'Joss ', 'jossgistren@yahoo.com', '239-888-0580', NULL, NULL, NULL, NULL),
(76, NULL, '', 'Fang', 'Andrew', 1, 2024, 'Andrewdavidfang@gmail.com', NULL, 'cell', '678-221-4009', 'Fang', 'Yunnan', 'valinedna@yahoo.com', '614-805-4839', NULL, NULL, NULL, NULL),
(77, NULL, '', 'Venkatesan', 'Priya', 1, 2025, 'priya.venki2016@gmail.com', 'waltonhighwebmaster@gmail.com', 'cell', '4049519235', 'Ganapathy', 'Lakshmi ', NULL, '6784629696', 'Sundaram', 'Venkatesan', NULL, '4049577350');

-- --------------------------------------------------------

--
-- Table structure for table `studentplacement`
--

CREATE TABLE `studentplacement` (
  `studentPlacementID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `event` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `teamID` int(11) NOT NULL,
  `place` int(11) NOT NULL,
  `partnerID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `teamID` int(11) NOT NULL,
  `teamName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tournamentID` int(11) NOT NULL,
  `teamPlace` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`teamID`, `teamName`, `tournamentID`, `teamPlace`) VALUES
(1, 'A', 0, NULL),
(2, 'A', 6, NULL),
(3, 'A', 7, NULL),
(4, 'A', 7, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `timeblock`
--

CREATE TABLE `timeblock` (
  `timeblockID` int(11) NOT NULL,
  `timeStart` datetime NOT NULL,
  `timeEnd` datetime NOT NULL,
  `tournamentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeblock`
--

INSERT INTO `timeblock` (`timeblockID`, `timeStart`, `timeEnd`, `tournamentID`) VALUES
(13, '2021-01-16 00:00:00', '2021-01-16 02:00:00', 6),
(17, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 6),
(18, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 6),
(19, '2021-01-16 00:00:00', '2021-01-16 03:00:00', 6),
(20, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 6),
(21, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 6),
(22, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 6),
(23, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 6),
(24, '2021-01-16 00:00:00', '2021-01-16 00:00:00', 6),
(25, '2020-10-10 11:00:00', '2020-10-10 11:50:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

CREATE TABLE `tournament` (
  `tournamentID` int(11) NOT NULL,
  `tournamentInfoID` int(11) NOT NULL,
  `dateTournament` date DEFAULT NULL,
  `dateRegistration` date DEFAULT NULL,
  `year` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `numberTeams` int(11) DEFAULT NULL,
  `weighting` int(11) NOT NULL,
  `note` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournament`
--

INSERT INTO `tournament` (`tournamentID`, `tournamentInfoID`, `dateTournament`, `dateRegistration`, `year`, `type`, `numberTeams`, `weighting`, `note`) VALUES
(1, 1, '2020-10-10', '2020-08-27', 2021, NULL, 1, 100, 'nada'),
(2, 2, '2020-10-24', '2020-09-01', 2021, NULL, 3, 50, ''),
(3, 3, '2020-11-28', '2020-11-07', 2021, NULL, 3, 100, ''),
(4, 4, '2020-11-14', '2020-10-12', 2021, NULL, 3, 75, ''),
(5, 5, '2020-12-19', '2020-09-10', 2021, NULL, 3, 75, ''),
(6, 6, '2021-01-16', '2020-09-18', 2021, 2, 1, 90, ''),
(7, 7, '2021-01-16', '2020-12-10', 2021, NULL, 2, 50, ''),
(8, 8, '2021-01-22', '2020-09-12', 2021, NULL, 1, 100, ''),
(9, 9, '2021-01-30', '2020-09-01', 2021, NULL, 1, 100, ''),
(10, 10, '2021-02-20', '2020-10-01', 2021, NULL, 1, 100, ''),
(11, 11, '2021-03-13', '2020-09-01', 2021, NULL, 3, 90, '');

-- --------------------------------------------------------

--
-- Table structure for table `tournamentevent`
--

CREATE TABLE `tournamentevent` (
  `tournamenteventID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `tournamentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournamentevent`
--

INSERT INTO `tournamentevent` (`tournamenteventID`, `eventID`, `tournamentID`) VALUES
(3, 1, 6),
(4, 2, 6),
(5, 3, 6),
(6, 6, 6),
(7, 7, 6),
(8, 8, 6),
(9, 9, 6),
(10, 11, 6),
(11, 12, 6),
(12, 13, 6),
(13, 15, 6),
(14, 16, 6),
(15, 17, 6),
(16, 18, 6),
(17, 19, 6),
(18, 22, 6),
(19, 23, 6),
(20, 24, 6),
(21, 25, 6),
(22, 28, 6),
(23, 31, 6),
(24, 33, 6),
(25, 34, 6),
(26, 10, 6),
(27, 1, 1),
(28, 2, 1),
(29, 3, 1),
(30, 6, 1),
(31, 7, 1),
(32, 8, 1),
(33, 9, 1),
(34, 11, 1),
(35, 12, 1),
(36, 13, 1),
(37, 15, 1),
(38, 16, 1),
(39, 17, 1),
(40, 18, 1),
(41, 19, 1),
(42, 22, 1),
(43, 23, 1),
(44, 24, 1),
(45, 25, 1),
(46, 28, 1),
(47, 31, 1),
(48, 33, 1),
(49, 34, 1),
(50, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tournamenteventtime`
--

CREATE TABLE `tournamenteventtime` (
  `tournamenteventID` int(11) NOT NULL,
  `timeblockID` int(11) NOT NULL,
  `teamID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tournamentinfo`
--

CREATE TABLE `tournamentinfo` (
  `tournamentInfoID` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `host` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `addressBilling` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `websiteHost` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `websiteSciOly` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `director` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `directorEmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `monthRegistration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournamentinfo`
--

INSERT INTO `tournamentinfo` (`tournamentInfoID`, `name`, `host`, `address`, `addressBilling`, `websiteHost`, `websiteSciOly`, `director`, `directorEmail`, `monthRegistration`) VALUES
(1, 'BEARSO', 'Bay Area Invitational', 'San Francisco, CA (Remote)', NULL, NULL, 'https://scilympiad.com/bearso', 'Peter Hung', 'peterhung@socalscioly.org', 8),
(2, 'SoFo', 'South Forsyth High School', 'South Forsyth, GA (Remote)', NULL, 'https://www.forsyth.k12.ga.us/Page/22519', 'https://scilympiad.com/sofo', 'Amy Chisam', 'achisam@gmail.com', 9),
(3, 'Practice Tournament', 'National Science Olympiad', 'Remote', NULL, NULL, 'https://scilympiad.com/sopractice', NULL, NULL, 11),
(4, 'UGA', 'Science Olympiad Outreach at UGA', 'Athens, GA (Remote)', NULL, 'https://www.ugascienceolympiad.net/', 'https://scilympiad.com/uga', 'Science Olympiad Outreach', 'scienceolympiad@uga.edu', 10),
(5, 'SOLVI', 'Clark High School', 'Las Vegas, NV (Remote)', '4291 Pennwood Ave, Las Vegas, NV 89102', 'http://www.clarkscienceolympiad.com/solvi.html', 'https://scilympiad.com/nv-clark', NULL, 'clarkscioly@gmail.com', 9),
(6, 'Aggie', 'UC Davis', 'Davis, CA (Remote)', NULL, 'https://sciolyatucdavis.wixsite.com/aggieinvitational', 'https://scilympiad.com/aggie', 'Chad Mowers and Claire Chapman', NULL, 9),
(7, 'BISOT', 'Brookwood High School', '1255 DOGWOOD ROAD, SNELLVILLE, GEORGIA 30078', '1255 DOGWOOD ROAD, SNELLVILLE, GEORGIA 30078', 'http://brookwoodso.weebly.com/bisot.html', NULL, 'Chuck Thorton / Jon Erwin', NULL, 10),
(8, 'MIT', 'Science Olympiad at MIT', 'Cambridge, MA (Remote)', NULL, 'https://scioly.mit.edu/', NULL, 'Science Olympiad at MIT', 'scioly@mit.edu', 9),
(9, 'Harvard-Brown Tournament', 'HUSO and BUSO', 'Cambridge, MA (Remote)', NULL, NULL, 'https://www.sciolyharvard.org/divc', 'Harvard Undergraduate Science Olympiad ', 'sciolyharvard@gmail.com', 9),
(10, 'PUSO', 'Princeton', 'Princeton, NJ (Remote)', NULL, 'https://scioly.princeton.edu/', NULL, NULL, 'scioly@princeton.edu', 10),
(11, 'State Competition', 'Georgia State Science Olympiad', 'Emory University', NULL, NULL, NULL, 'Arneesh ', 'georgiascioly@gmail.com', 9),
(12, 'Regional Competitino', 'Georgia State Science Olympiad', 'Varies', NULL, NULL, NULL, 'Arneesh ', 'georgiascioly@gmail.com', 9);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `oauth_provider` enum('google','facebook','twitter','linkedin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'google',
  `oauth_uid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `privilege` int(11) DEFAULT NULL,
  `first_name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `oauth_provider`, `oauth_uid`, `privilege`, `first_name`, `last_name`, `email`, `gender`, `locale`, `picture`, `created`, `modified`) VALUES
(3, 'google', '108096504576017257484', 1, 'Doug', 'Wolfe', 'wolfewhs@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a/AATXAJyVjZvobWp9Wd8bdGAb96Ehc4g_UHqWbpXFVO9r=s96-c', '2021-04-20 22:39:04', '2021-04-30 01:42:26'),
(9, 'google', '109397293342063106702', 4, 'Doug', 'W', 'dougwolfejr@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GhWinau5RYqIDwGfGGEBoOVdd7KGnEhpNtBLvw-=s96-c', '2021-04-25 00:28:24', '2021-04-30 01:11:15'),
(10, 'google', '103874619842696589534', 2, 'Andrew', 'Greig', 'acgreig@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14Gg9JZv6l6GPu9um1rRhi-x5zo9Jqet0vaLoLAHGjLY=s96-c', '2021-04-30 16:13:06', '2021-04-30 16:13:06'),
(11, 'google', '107148355485375603861', 2, 'Emily', 'Yang', 'emilysun1160@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GiedMg4cRfkL8uZ33uE5u2lOaU5OY7mpm3sU0iwTA=s96-c', '2021-04-30 16:15:06', '2021-04-30 16:15:06'),
(12, 'google', '111253112150007245823', 2, 'Chinmay', 'Joshi', 'chinmayj.walton@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a/AATXAJwYM7GKh9LHbuVnusA8J1HinDmdW46OweWnwH8V=s96-c', '2021-04-30 16:16:39', '2021-04-30 16:16:39'),
(13, 'google', '102385444909511766502', 2, 'Joanna', 'Wei', 'joannawei05@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a/AATXAJz2Or3KG3rq2kdqGylvQKmPx4wZSJcmFNxy_uDS=s96-c', '2021-04-30 16:16:42', '2021-04-30 16:16:42'),
(14, 'google', '112468442879454322251', 2, 'Rima', 'Rami', 'rimazazu@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14Gh0SZzmVio4hlx-u3JVxi8jLNlpqWj8PY3vP_s0ag=s96-c', '2021-04-30 16:16:47', '2021-04-30 16:16:47'),
(15, 'google', '102894286402057637896', 2, 'Faith', 'Huang', 'fyizhenh@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GjXobT7gm6Vhtinx9kEyS51xaWyQnGmJq-hL14IKg=s96-c', '2021-04-30 16:17:15', '2021-04-30 16:17:15'),
(16, 'google', '112051074721749425737', 2, 'Cynthia', 'Peng', 'alcp6201@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GgnKiqOhLb0sMRw86JMoUngv8-s2a1Em-MDXW3dOw=s96-c', '2021-04-30 16:17:17', '2021-04-30 16:17:17'),
(17, 'google', '112356622834711211761', 2, 'Jennifer', 'He', 'jenniferhe0203@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14Gi6ufKCl3uXEE5zGz1g3e9rvZM3BI_AqgO7Sa_xSMc=s96-c', '2021-04-30 16:19:34', '2021-04-30 16:19:34'),
(18, 'google', '118077389039177077874', 2, 'Christian', 'Hable', 'cmhable@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GhQzK1HHb5u6f5PKC27AROoTQ580HKjMCgSEzmisw=s96-c', '2021-04-30 16:20:01', '2021-04-30 16:20:01'),
(19, 'google', '100350629985317200334', 2, 'Chris', 'Wang', 'goodchris0831@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GiNQtjYqSwUSjM3lDaQ2EkJ7z-T2teAOULCcaR5=s96-c', '2021-04-30 16:20:21', '2021-04-30 16:20:21'),
(20, 'google', '116164729655931508486', NULL, 'Walton', 'Habitat', 'waltonhabitatapps@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a/AATXAJwkHlAEdAzscu_kVXvMHKVPGiPKpbo5Gq9PYr54=s96-c', '2021-05-07 13:53:50', '2021-05-07 13:53:50'),
(21, 'google', '103400986357747872543', 3, 'Susanna', 'H.', 'susanna.lmt.196@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14Gi2ZiKu7_c99MJ9TRbypNWntl-HdN-xnahSbopG=s96-c', '2021-05-07 16:24:01', '2021-05-07 16:24:01'),
(22, 'google', '', NULL, '', '', '', '', '', '', '2021-05-16 01:48:20', '2021-05-16 01:48:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `award`
--
ALTER TABLE `award`
  ADD PRIMARY KEY (`awardID`);

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`coachID`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`courseID`);

--
-- Indexes for table `coursecompleted`
--
ALTER TABLE `coursecompleted`
  ADD PRIMARY KEY (`coursecompletedID`);

--
-- Indexes for table `courseenrolled`
--
ALTER TABLE `courseenrolled`
  ADD PRIMARY KEY (`courseenrolledID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventID`),
  ADD UNIQUE KEY `event` (`event`);

--
-- Indexes for table `eventchoice`
--
ALTER TABLE `eventchoice`
  ADD PRIMARY KEY (`eventchoiceID`);

--
-- Indexes for table `eventtype`
--
ALTER TABLE `eventtype`
  ADD PRIMARY KEY (`type`);

--
-- Indexes for table `eventyear`
--
ALTER TABLE `eventyear`
  ADD UNIQUE KEY `yearID` (`eventyearID`);

--
-- Indexes for table `officer`
--
ALTER TABLE `officer`
  ADD PRIMARY KEY (`officerID`);

--
-- Indexes for table `phonetype`
--
ALTER TABLE `phonetype`
  ADD UNIQUE KEY `phoneType` (`phoneType`);

--
-- Indexes for table `rule`
--
ALTER TABLE `rule`
  ADD PRIMARY KEY (`ruleID`) USING BTREE;

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `studentplacement`
--
ALTER TABLE `studentplacement`
  ADD PRIMARY KEY (`studentPlacementID`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`teamID`);

--
-- Indexes for table `timeblock`
--
ALTER TABLE `timeblock`
  ADD PRIMARY KEY (`timeblockID`);

--
-- Indexes for table `tournament`
--
ALTER TABLE `tournament`
  ADD UNIQUE KEY `tournamentID` (`tournamentID`);

--
-- Indexes for table `tournamentevent`
--
ALTER TABLE `tournamentevent`
  ADD PRIMARY KEY (`tournamenteventID`);

--
-- Indexes for table `tournamentinfo`
--
ALTER TABLE `tournamentinfo`
  ADD PRIMARY KEY (`tournamentInfoID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `award`
--
ALTER TABLE `award`
  MODIFY `awardID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coach`
--
ALTER TABLE `coach`
  MODIFY `coachID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `coursecompleted`
--
ALTER TABLE `coursecompleted`
  MODIFY `coursecompletedID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `courseenrolled`
--
ALTER TABLE `courseenrolled`
  MODIFY `courseenrolledID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `eventchoice`
--
ALTER TABLE `eventchoice`
  MODIFY `eventchoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT for table `eventyear`
--
ALTER TABLE `eventyear`
  MODIFY `eventyearID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `officer`
--
ALTER TABLE `officer`
  MODIFY `officerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `rule`
--
ALTER TABLE `rule`
  MODIFY `ruleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `studentplacement`
--
ALTER TABLE `studentplacement`
  MODIFY `studentPlacementID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `teamID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `timeblock`
--
ALTER TABLE `timeblock`
  MODIFY `timeblockID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `tournamentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tournamentevent`
--
ALTER TABLE `tournamentevent`
  MODIFY `tournamenteventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tournamentinfo`
--
ALTER TABLE `tournamentinfo`
  MODIFY `tournamentInfoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
