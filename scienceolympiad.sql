-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 27, 2021 at 09:52 AM
-- Server version: 8.0.25-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

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
  `awardID` int NOT NULL,
  `awardName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `studentID` int NOT NULL,
  `dateAwarded` date NOT NULL,
  `tournamentID` int NOT NULL,
  `note` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE `coach` (
  `coachID` int NOT NULL,
  `userID` int DEFAULT NULL,
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
  `courseID` int NOT NULL,
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
  `coursecompletedID` int NOT NULL,
  `courseID` int NOT NULL,
  `studentID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coursecompleted`
--

INSERT INTO `coursecompleted` (`coursecompletedID`, `courseID`, `studentID`) VALUES
(14, 15, 33),
(15, 15, 32),
(16, 15, 31),
(17, 13, 16),
(18, 2, 26),
(19, 13, 26),
(20, 20, 26),
(21, 19, 26),
(23, 2, 35),
(24, 9, 35),
(25, 15, 36),
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
(127, 15, 66),
(128, 15, 24),
(129, 15, 25),
(130, 5, 54),
(131, 16, 54),
(132, 25, 49),
(134, 16, 49),
(135, 5, 49),
(136, 4, 13),
(137, 23, 13),
(138, 11, 42),
(144, 9, 26),
(145, 5, 26),
(146, 23, 7),
(147, 4, 7),
(148, 11, 7),
(149, 11, 1),
(150, 4, 1),
(151, 15, 1),
(152, 23, 1),
(153, 2, 8),
(154, 5, 8),
(155, 4, 8),
(156, 1, 8),
(157, 25, 8),
(158, 16, 8),
(159, 26, 8),
(160, 2, 69),
(161, 4, 69),
(162, 11, 69),
(166, 1, 57),
(167, 15, 74),
(168, 5, 56),
(169, 19, 56),
(170, 20, 56),
(171, 15, 59),
(172, 4, 33),
(173, 11, 33),
(174, 24, 32),
(175, 4, 11),
(176, 15, 11),
(177, 11, 11),
(179, 1, 58),
(180, 16, 58),
(181, 5, 58),
(182, 23, 58),
(183, 16, 52),
(184, 5, 52),
(185, 12, 35),
(186, 2, 72),
(187, 4, 36),
(188, 11, 36),
(189, 5, 51),
(190, 15, 65),
(191, 20, 53),
(192, 19, 53),
(193, 23, 53),
(194, 9, 67),
(195, 2, 67),
(196, 5, 67),
(197, 19, 67),
(198, 20, 67),
(199, 21, 67),
(200, 24, 67),
(201, 23, 67),
(202, 25, 77),
(203, 1, 71),
(204, 2, 71),
(205, 2, 73),
(206, 1, 73),
(207, 15, 73),
(208, 9, 73),
(209, 11, 10),
(210, 4, 42),
(211, 1, 42),
(212, 2, 42),
(213, 5, 42),
(214, 24, 42),
(215, 23, 42),
(216, 1, 3),
(217, 11, 3),
(218, 5, 66),
(219, 1, 34),
(220, 15, 34),
(221, 16, 34),
(222, 2, 34),
(223, 5, 34),
(224, 17, 34),
(225, 18, 34),
(226, 15, 76);

-- --------------------------------------------------------

--
-- Table structure for table `courseenrolled`
--

CREATE TABLE `courseenrolled` (
  `courseenrolledID` int NOT NULL,
  `courseID` int NOT NULL,
  `studentID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courseenrolled`
--

INSERT INTO `courseenrolled` (`courseenrolledID`, `courseID`, `studentID`) VALUES
(15, 2, 16),
(41, 11, 50),
(42, 11, 29),
(43, 9, 60),
(45, 4, 60),
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
(59, 7, 4),
(61, 4, 66),
(62, 4, 24),
(63, 4, 25),
(64, 1, 54),
(68, 1, 49),
(69, 23, 49),
(70, 11, 13),
(71, 7, 42),
(72, 12, 42),
(74, 22, 26),
(75, 23, 26),
(76, 1, 7),
(77, 1, 1),
(78, 7, 1),
(79, 17, 8),
(80, 4, 74),
(81, 12, 26),
(82, 4, 56),
(83, 16, 56),
(84, 1, 60),
(85, 11, 58),
(86, 1, 52),
(87, 9, 8),
(88, 4, 65),
(89, 23, 65),
(90, 24, 53),
(91, 9, 53),
(92, 22, 53),
(93, 10, 67),
(94, 11, 41),
(95, 24, 41),
(96, 9, 34),
(97, 4, 76),
(98, 5, 76);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `eventID` int NOT NULL,
  `event` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` int DEFAULT NULL,
  `description` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `calculatorType` int DEFAULT NULL,
  `goggleType` int DEFAULT NULL,
  `numberStudents` int NOT NULL DEFAULT '2',
  `sciolyLink` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`eventID`, `event`, `type`, `description`, `calculatorType`, `goggleType`, `numberStudents`, `sciolyLink`) VALUES
(1, 'Anatomy and Physiology', NULL, '', NULL, NULL, 2, ''),
(2, 'Astronomy', NULL, '', NULL, NULL, 2, ''),
(3, 'Boomilever', 1, '', NULL, 1, 2, ''),
(4, 'Bridges', 1, '', NULL, NULL, 2, ''),
(5, 'Cell Biology', NULL, '', NULL, NULL, 2, ''),
(6, 'Chemistry Lab', 4, '', NULL, NULL, 2, ''),
(7, 'Circuit Lab', 4, '', NULL, NULL, 2, ''),
(8, 'Codebusters', NULL, '', NULL, NULL, 3, ''),
(9, 'Designer Genes', NULL, '', NULL, NULL, 2, ''),
(10, 'Detector Building', 3, '', NULL, NULL, 2, ''),
(11, 'Digital Structures', 1, '', NULL, NULL, 2, ''),
(12, 'Disease Detectives', NULL, '', NULL, NULL, 2, ''),
(13, 'Dynamic Planet', NULL, '', NULL, NULL, 2, ''),
(14, 'Environmental Chemistry', NULL, '', NULL, NULL, 2, ''),
(15, 'Experimental Design', 2, '', NULL, NULL, 3, ''),
(16, 'Forensics', 2, '', NULL, NULL, 2, ''),
(17, 'Fossils', NULL, '', NULL, NULL, 2, ''),
(18, 'GeoLogic Mapping', NULL, '', NULL, NULL, 2, ''),
(19, 'Gravity Vehicle', 1, '', NULL, NULL, 2, ''),
(20, 'Green Generation', NULL, '', NULL, NULL, 2, ''),
(21, 'It\'s About Time', 3, '', NULL, NULL, 2, ''),
(22, 'Machines', 3, '', NULL, NULL, 2, ''),
(23, 'Ornithology', NULL, '', NULL, NULL, 2, ''),
(24, 'Ping Pong Parachute', 1, '', NULL, NULL, 2, ''),
(25, 'Protein Modeling', 3, '', NULL, NULL, 3, ''),
(26, 'Remote Sensing', NULL, '', NULL, NULL, 2, ''),
(27, 'Rocks & Minerals', NULL, '', NULL, NULL, 2, ''),
(28, 'Sounds of Music', 3, '', NULL, NULL, 2, ''),
(29, 'test', NULL, '', NULL, NULL, 2, ''),
(30, 'Trajectory', 1, '', NULL, NULL, 2, ''),
(31, 'Water Quality', NULL, '', NULL, NULL, 2, ''),
(32, 'WiFi Lab', 3, '', NULL, NULL, 2, ''),
(33, 'Wright Stuff', 1, '', NULL, NULL, 2, ''),
(34, 'Write It CAD It', 2, '', NULL, NULL, 2, ''),
(35, 'Write It Do It', 2, '', NULL, NULL, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `eventchoice`
--

CREATE TABLE `eventchoice` (
  `eventchoiceID` int NOT NULL,
  `studentID` int NOT NULL,
  `eventyearID` int NOT NULL,
  `priority` int NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventchoice`
--

INSERT INTO `eventchoice` (`eventchoiceID`, `studentID`, `eventyearID`, `priority`) VALUES
(27, 26, 2, 1),
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
(125, 7, 17, 1),
(126, 7, 21, 2),
(127, 7, 9, 3),
(128, 7, 4, 4),
(129, 7, 13, 5),
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
(161, 29, 9, 1),
(162, 29, 19, 2),
(163, 29, 7, 3),
(164, 29, 4, 4),
(165, 29, 12, 5),
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
(245, 53, 15, 1),
(246, 53, 18, 2),
(247, 53, 3, 3),
(248, 53, 16, 4),
(249, 53, 22, 5),
(250, 69, 6, 1),
(251, 69, 4, 2),
(252, 69, 14, 3),
(253, 69, 27, 4),
(254, 69, 12, 5),
(255, 66, 12, 1),
(256, 66, 9, 2),
(257, 66, 4, 3),
(258, 66, 2, 4),
(259, 66, 7, 5),
(260, 34, 1, 1),
(261, 34, 27, 2),
(262, 34, 7, 3),
(263, 34, 9, 4),
(264, 34, 2, 5),
(265, 6, 18, 1),
(266, 6, 15, 2),
(267, 6, 27, 3),
(268, 6, 20, 4),
(269, 6, 22, 5),
(270, 74, 10, 1),
(271, 74, 1, 2),
(272, 74, 9, 3),
(273, 74, 13, 4),
(274, 74, 6, 5),
(275, 74, 28, 1),
(276, 74, 36, 2),
(277, 74, 35, 3),
(278, 74, 38, 4),
(279, 74, 44, 5),
(280, 54, 31, 1),
(281, 54, 28, 2),
(282, 54, 32, 3),
(283, 54, 35, 4),
(284, 54, 39, 5),
(285, 50, 40, 1),
(286, 50, 31, 2),
(287, 50, 51, 3),
(288, 50, 42, 4),
(289, 50, 37, 5),
(290, 25, 7, 1),
(291, 25, 22, 2),
(292, 25, 11, 3),
(293, 25, 3, 4),
(294, 25, 16, 5),
(295, 25, 39, 1),
(296, 25, 42, 2),
(297, 25, 50, 3),
(298, 25, 31, 4),
(299, 25, 38, 5),
(300, 56, 34, 1),
(301, 56, 28, 2),
(302, 56, 31, 3),
(303, 56, 32, 4),
(304, 56, 38, 5),
(305, 24, 42, 1),
(306, 24, 39, 2),
(307, 24, 44, 3),
(308, 24, 50, 4),
(309, 24, 38, 5),
(315, 1, 19, 1),
(316, 1, 3, 2),
(317, 1, 4, 3),
(318, 1, 21, 4),
(319, 1, 27, 5),
(320, 1, 32, 1),
(321, 1, 31, 2),
(322, 1, 51, 3),
(323, 1, 37, 4),
(324, 1, 39, 5),
(325, 60, 28, 1),
(326, 60, 40, 2),
(327, 60, 35, 3),
(328, 60, 31, 4),
(329, 60, 32, 5),
(330, 4, 31, 1),
(331, 4, 30, 2),
(332, 4, 34, 3),
(333, 4, 33, 4),
(334, 4, 49, 5),
(335, 31, 38, 1),
(336, 31, 51, 2),
(337, 31, 32, 3),
(338, 31, 42, 4),
(339, 31, 33, 5),
(340, 11, 17, 1),
(341, 11, 21, 2),
(342, 11, 6, 3),
(343, 11, 22, 4),
(344, 11, 14, 5),
(350, 49, 33, 1),
(351, 49, 39, 2),
(354, 49, 37, 3),
(355, 49, 40, 4),
(356, 49, 28, 5),
(357, 13, 10, 1),
(358, 13, 21, 2),
(359, 13, 9, 3),
(360, 13, 13, 4),
(361, 13, 27, 5),
(362, 13, 36, 1),
(363, 13, 42, 2),
(364, 13, 38, 3),
(365, 13, 40, 4),
(366, 13, 47, 5),
(367, 58, 31, 1),
(368, 58, 28, 2),
(369, 58, 51, 3),
(370, 58, 35, 4),
(371, 58, 32, 5),
(372, 52, 7, 1),
(373, 52, 1, 2),
(374, 52, 9, 3),
(375, 52, 12, 4),
(376, 52, 22, 5),
(377, 7, 44, 1),
(378, 7, 42, 2),
(379, 7, 38, 3),
(380, 7, 47, 4),
(381, 7, 33, 5),
(382, 72, 27, 1),
(383, 72, 1, 2),
(384, 72, 7, 3),
(385, 72, 9, 4),
(386, 72, 12, 5),
(387, 8, 1, 1),
(388, 8, 9, 2),
(389, 8, 27, 3),
(390, 8, 4, 4),
(391, 8, 7, 5),
(392, 8, 35, 1),
(393, 8, 28, 2),
(394, 8, 40, 3),
(395, 8, 51, 4),
(396, 8, 47, 5),
(397, 55, 33, 1),
(398, 55, 40, 2),
(399, 55, 42, 3),
(400, 55, 32, 4),
(401, 55, 30, 5),
(402, 51, 47, 1),
(403, 51, 33, 2),
(404, 51, 30, 3),
(405, 51, 31, 4),
(406, 51, 44, 5),
(407, 65, 27, 1),
(408, 65, 6, 2),
(409, 65, 14, 3),
(410, 65, 9, 4),
(411, 65, 11, 5),
(412, 65, 33, 1),
(413, 65, 51, 2),
(414, 65, 40, 3),
(415, 65, 39, 4),
(416, 65, 38, 5),
(417, 53, 48, 1),
(418, 53, 45, 2),
(419, 53, 37, 3),
(420, 53, 34, 4),
(421, 53, 49, 5),
(422, 67, 2, 1),
(423, 67, 27, 2),
(424, 67, 18, 3),
(425, 67, 14, 4),
(426, 67, 4, 5),
(427, 67, 30, 1),
(428, 67, 51, 2),
(429, 67, 45, 3),
(430, 67, 52, 4),
(431, 67, 33, 5),
(432, 77, 28, 1),
(433, 77, 42, 2),
(434, 77, 39, 3),
(435, 77, 40, 4),
(436, 77, 38, 5),
(437, 71, 1, 1),
(438, 71, 9, 2),
(439, 71, 6, 3),
(440, 71, 19, 4),
(441, 71, 2, 5),
(442, 14, 37, 1),
(443, 14, 45, 2),
(444, 14, 50, 3),
(445, 14, 41, 4),
(446, 14, 52, 5),
(447, 73, 7, 1),
(448, 73, 20, 2),
(449, 73, 4, 3),
(450, 73, 1, 4),
(451, 73, 19, 5),
(452, 28, 7, 1),
(453, 28, 1, 2),
(454, 28, 9, 3),
(455, 28, 4, 4),
(456, 28, 19, 5),
(457, 28, 31, 1),
(458, 28, 28, 2),
(459, 28, 40, 3),
(460, 28, 32, 4),
(461, 28, 35, 5),
(462, 42, 28, 1),
(463, 42, 31, 2),
(464, 42, 32, 3),
(465, 42, 35, 4),
(466, 42, 38, 5),
(467, 29, 40, 1),
(468, 29, 31, 2),
(469, 29, 51, 3),
(470, 29, 33, 4),
(471, 29, 42, 5),
(472, 41, 38, 1),
(473, 41, 32, 2),
(474, 41, 42, 3),
(475, 41, 36, 4),
(476, 41, 51, 5),
(477, 66, 31, 1),
(478, 66, 42, 2),
(479, 66, 35, 3),
(480, 66, 30, 4),
(481, 66, 47, 5),
(482, 34, 51, 1),
(483, 34, 31, 2),
(484, 34, 28, 3),
(485, 34, 35, 4),
(486, 34, 30, 5),
(487, 76, 42, 1),
(488, 76, 33, 2),
(489, 76, 44, 3),
(490, 76, 35, 4),
(491, 76, 45, 5);

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
  `eventyearID` int NOT NULL,
  `eventID` int NOT NULL,
  `year` int NOT NULL,
  `studentID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventyear`
--

INSERT INTO `eventyear` (`eventyearID`, `eventID`, `year`, `studentID`) VALUES
(1, 1, 2021, 42),
(2, 2, 2021, 4),
(3, 3, 2021, 14),
(4, 6, 2021, 0),
(5, 7, 2021, 4),
(6, 8, 2021, 3),
(7, 9, 2021, 3),
(8, 11, 2021, 4),
(9, 12, 2021, 8),
(10, 13, 2021, 13),
(11, 15, 2021, 5),
(12, 16, 2021, 35),
(13, 17, 2021, 51),
(14, 18, 2021, 10),
(15, 19, 2021, 6),
(16, 22, 2021, 53),
(17, 23, 2021, 7),
(18, 24, 2021, 6),
(19, 25, 2021, 1),
(20, 28, 2021, 35),
(21, 31, 2021, 11),
(22, 33, 2021, 5),
(27, 34, 2021, 8),
(28, 1, 2022, 0),
(29, 10, 2021, 4),
(30, 2, 2022, NULL),
(31, 5, 2022, NULL),
(32, 6, 2022, NULL),
(33, 8, 2022, NULL),
(34, 10, 2022, NULL),
(35, 12, 2022, NULL),
(36, 13, 2022, NULL),
(37, 4, 2022, NULL),
(38, 14, 2022, NULL),
(39, 15, 2022, NULL),
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
(50, 33, 2022, NULL),
(51, 35, 2022, NULL),
(52, 21, 2022, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `officer`
--

CREATE TABLE `officer` (
  `officerID` int NOT NULL,
  `studentID` int NOT NULL,
  `year` int NOT NULL,
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
  `ruleID` int NOT NULL,
  `level` int NOT NULL,
  `year` int NOT NULL,
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
  `studentID` int NOT NULL,
  `userID` int DEFAULT NULL,
  `uniqueToken` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `yearGraduating` int NOT NULL,
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
(1, 21, '', 'Huang', 'Susanna', 1, 2022, 'Susanna.lmt.196@gmail.com', NULL, 'cell', '678-833-7075', ' Hu', 'Lillian', 'dd_1027@yahoo.com', '347-268-3292', 'Huang', 'Zhen', NULL, '770-578-1535'),
(3, 0, '', 'Yan', 'Grace', 0, 2021, 'graceyan61317@gmail.com', NULL, 'cell', '770-309-9868', 'Zeng ', 'Xiaoyan ', 'xzengx@yahoo.com', '770-330-7097', NULL, NULL, NULL, NULL),
(4, 12, '', 'Joshi', 'Chinmay', 1, 2022, 'chinmayj.walton@gmail.com', '', '', '425-217-9013', 'Joshi', 'Neha', 'nehapjoshi@yahoo.com', '4252159879', 'Joshi', 'Prasanna', 'prasmohanjoshi@gmail.com', '425-375-1369'),
(5, 0, '', 'Lee', 'Rebecca (Eunjae)', 0, 2021, 'eunjaerebecca@gmail.com', '', '', '678-978-2635', 'Huh', 'Inhee', 'inhee319@gmail.com', '404-247-5442', '', '', '', ''),
(6, 0, '', 'Feren', 'Emily', 0, 2021, 'emferen3@gmail.com', '', 'cell', '828-989-2539', 'Feren', 'Stephen', 'Sferen@gmail.com', '828-989-1561', '', '', '', ''),
(7, 16, '', 'Peng', 'Cynthia', 1, 2022, 'alcp6201@gmail.com', 'cynthia.peng@students.cobbk12.org', '', '770-795-7109', 'Lu', 'Wendy', 'wendy_lu@yahoo.com', '770-380-4560', 'Peng', 'Jack', '', '7703033405'),
(8, 14, '', 'Rami', 'Rima', 1, 2022, 'rimazazu@gmail.com', 'rima.rami@students.cobbk12.org', '', '4703883822', 'Rami', 'Rafi', '', '4044443440', 'Rami', 'Parviz', 'parviz_rami@yahoo.com', '4044444499'),
(10, 0, '', 'Wei', 'Banglue', 0, 2021, 'banglueweiga@gmail.com', '', '', '4703042706', 'Liu', 'Shizen', 'shizhenliu@hotmail.com', '4704267236', '', '', '', ''),
(11, 0, '', 'Lai', 'Sheena', 0, 2021, 'sheenalai2012@gmail.com', '', '', '404-955-2502', 'Bao', 'Jieqiong ', 'jbao24548@gmail.com', '770-971-5234', '', '', '', ''),
(13, 15, '', 'Huang', 'Faith', 1, 2023, 'fyizhenh@gmail.com', 'faith.huang546@students.cobbk12.org', '', '770-294-0421', 'Huang', 'Rongbing', 'rongbing.huang@gmail.com', '770-309-7851', 'Huo', 'Linlin', '', '7705298243'),
(14, 19, '', 'Wang', 'Chris', 1, 2022, 'goodchris0831@gmail.com', 'Chris.Wang@students.cobbk12.org', '', '4702656105', 'Chang', 'Jung Chu', 'cjungchu@gmail.com', '6785388116', 'Wang', 'Tai', '', ''),
(16, 10, '', 'Mei', 'Andrew', 0, 2023, 'andrewmei915@gmail.com', '', '', '404-348-3229', 'Mei', 'Chase', 'chasemei@gmail.com', '470-403-0480', '', '', '', ''),
(22, 9, '', 'Wolfe', 'Doug', 1, 2022, 'dougwolfejr@gmail.com', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 17, '', 'He', 'Jennifer', 1, 2024, 'jenniferhe0203@gmail.com', 'jennifer.he@students.cobbk12.org', 'cell', NULL, 'He', 'Jim ', 'jingwu_he@yahoo.com', '404-667-5076', 'Zhang ', 'Jun', 'shirley_jun@yahoo.com', '404-472-9421'),
(25, 18, '', 'Hable', 'Christian', 1, 2024, 'cmhable@gmail.com', 'christian.hable@students.cobbk12.org', 'cell', '14043603478', 'Li', 'Jue', 'leejue@yahoo.com', '9012010337', 'Hable', 'Bill', 'billokc67@gmail.com', '7703164304'),
(26, 10, '', 'Greig', 'Andrew', 1, 2022, 'acgreig@gmail.com', 'andrew.greig@students.cobbk12.org', 'cell', '470-572-4613', 'Greig', 'David', 'dcgreig@gmail.com', '678-810-1685', 'Greig', 'Angela', 'angela.greig@cobbk12.org', '470-527-4613'),
(27, NULL, '', 'Joo', 'Emily', 0, 2021, 'emilyjpie110@gmail.com', NULL, 'cell', '404-259-6032', 'Jeong', 'Youngmin', 'Joomin01@hotmail.com', '404-319-1613', NULL, NULL, NULL, NULL),
(28, 13, '', 'Wei', 'Joanna (Zhuyun)', 1, 2023, 'joannawei05@gmail.com', 'zhuyun.wei@students.cobbk12.org', 'cell', '770-625-0188', 'Wei', 'Lusia ', NULL, '7706880586', 'Zhao', 'Sharp', NULL, '7708265888'),
(29, 11, '', 'Yang', 'Emily', 1, 2022, 'emilysun1160@gmail.com', 'emily.yang916@students.cobbk12.org', 'cell', '678-559-8506', 'Sun', 'Weitao', 'weitao3@yahoo.com', '508-904-7561', 'Yang', 'Richard', NULL, '5089047549'),
(31, NULL, '', 'Karuman', 'Akshita', 1, 2023, 'akshita.karuman@gmail.com', 'akshita.karuman@students.cobbk12.org', 'cell', '470-377-6656', 'Damodaran', 'Rajiv ', 'rajivdeepna@gmail.com', '404-510-6107', 'Rajiv', 'Deepna', '4703014030', NULL),
(32, NULL, '', 'Kona', 'Abhishek', 1, 2022, 'abhishek.kona135@gmail.com', NULL, 'cell', '678-581-9817', 'Kona', 'Sirisha ', 'mail_sirisha@yahoo.com', '770-756-7967', NULL, NULL, NULL, NULL),
(33, NULL, '', 'Kona', 'Abhinav', 1, 2022, 'avkona0325@gmail.com', NULL, 'cell', '404-953-1112', 'Kona', 'Sirisha', 'mail_sirisha@yahoo.com', '770-856-7967', NULL, NULL, NULL, NULL),
(34, NULL, '', 'Dahiya', 'Anchita', 1, 2022, 'anniedahiya1@gmail.com', NULL, 'cell', '404-987-9517', 'Rani', 'Kirti', 'kirtirani@gmail.com', '804-316-8139', NULL, NULL, NULL, NULL),
(35, NULL, '', 'Melnikova', 'Tonya (Antonina)', 0, 2021, 'tonya.melnik7@gmail.com', NULL, 'cell', '4048237643', ' Melnikov', 'Oleg', 'lerik78@gmail.com', '4048248439', NULL, NULL, NULL, NULL),
(36, NULL, '', 'Roy', 'Aryan', 1, 2022, 'aryanaviroy@gmail.com', NULL, 'cell', '4702789888', 'Roy', 'Avijit', NULL, '6787884473', NULL, NULL, NULL, NULL),
(40, NULL, '', 'Sankuratri', 'Anish ', 1, 2023, 'anishdfish@gmail.com', NULL, 'cell', '678-799-7579', ' Sankuratri', 'Kodanda', 'kodanda.rs@gmail.com', '678-862-2682', NULL, NULL, NULL, NULL),
(41, NULL, '', 'Yetukuri', 'Rinky (Chaitanya Sri)', 1, 2023, 'rinky.yetukuri@gmail.com', 'chaitanya.yetukuri@students.cobbk12.org', 'cell', '470-454-5572', 'Yetukuri', 'Devaraju', 'devaraju.yetukuri@gmail.com', '404-386-1516', 'Ghanta', 'Hymavathi', NULL, '7705732366'),
(42, NULL, '', 'Yamin', 'Asad', 1, 2022, 'yaminasad@gmail.com', 'Asad.Yamin@students.cobbk12.org', 'cell', '7707576259', 'Yamin', 'Khalid ', 'khalid_yamin@msn.com', '4044027613', 'Yamin', 'Aliya', NULL, '6786447912'),
(44, NULL, '', 'Ramaswamy', 'Karthika', 0, 2021, 'karthika.v.ramaswamy@gmail.com', NULL, 'cell', '470-269-1542', ' Ramaswamy', 'Mohan', 'mohan_ramaswamy@yahoo.com', '678-516-1298', NULL, NULL, NULL, NULL),
(48, NULL, '', 'Tyler', 'Dominick', 1, 2023, 'dttyler12@gmail.com', NULL, 'cell', '9843640083', 'Tyler', 'Caroline', 'carolinetyler@me.com', '919-619-1102', NULL, NULL, NULL, NULL),
(49, NULL, '', 'Liu', 'David', 1, 2023, 'davidleoliu2@gmail.com', '', 'cell', '347-794-7932', 'Shi', 'Li', 'shili2720@gmail.com', '201-496-3599', 'Liu', 'Li', NULL, '2014960294'),
(50, NULL, '', 'Guo', 'Fiona', 1, 2022, 'fitgps1@gmail.com', 'fiona.guo@students.cobbk12.org', 'cell', '6308352869', 'Guo', 'Gary (Qing)', 'gqyy2010@gmail.com', '6307683790', 'Zhou', 'Yuanyuan Zhou', NULL, '6306961085'),
(51, NULL, '', 'Shen', 'Grace', 1, 2022, 'graceshen04@gmail.com', NULL, 'cell', '678-641-1633', 'Shen', 'Peiqing (Patrick)', 'pqshen@yahoo.com', '404-831-3696', 'Yin', 'Hong', NULL, '7703547057'),
(52, NULL, '', 'Maslamani', 'Dana', 1, 2022, 'danamasla1999@gmail.com', NULL, 'cell', '4049920928', 'Maslamani', 'Badera ', 'Baderal@almaslamani.com', '4049802457', NULL, NULL, NULL, NULL),
(53, NULL, '', 'Siegmund', 'Julian', 1, 2023, 'juljs05@gmail.com', NULL, 'cell', '4049609241', 'Siegmund', 'Heike', 'heike_b_siegmund@yahoo.com', '4044063729', NULL, NULL, NULL, NULL),
(54, NULL, '', 'Gunjan', 'Mayank', 1, 2023, 'Mgunj1001@gmail.com', 'mayank.gunjan@students.cobbk12.org', 'cell', '678-382-7232', 'Gunjan', 'Samir', 'gunjansk@yahoo.com', '859-559-1608', 'Mallick', 'Madhumita', NULL, '859-227-2441'),
(55, NULL, '', 'Reddy', 'Megan', 1, 2022, 'meganreddys@gmail.com', 'megan.reddy@students.cobbk12.org', 'cell', '770-362-5455', 'Peddareddy', 'Lakshmi ', 'venkyrm@hotmail.com', '7045198345', 'Mukthapuram', 'Venkat ', NULL, '7703625455'),
(56, NULL, '', 'Hari', 'Nandana', 1, 2023, 'nandanahari@hotmail.com', NULL, 'cell', '678-974-9505', 'Hari', 'Soumya', 'chat.to.soumya@gmail.com', '678-974-9505', NULL, NULL, NULL, NULL),
(57, NULL, '', 'Dileep', 'Nivedita', 1, 2022, 'nivi.dileep@gmail.com', NULL, 'cell', '4046937018', 'Dileep', 'Ambili', 'pambilip@gmail.com', '4044169718', NULL, NULL, NULL, NULL),
(58, NULL, '', 'Malladi', 'Pranav', 1, 2022, 'the.pranav123@gmail.com', NULL, 'cell', '4042022327', 'Malladi', 'Ravisankara', NULL, '6789937378', NULL, NULL, NULL, NULL),
(59, NULL, '', 'Inan', 'Omer', 1, 2024, 'omer.m.inan2024@gmail.com', NULL, 'cell', '7705589882', 'Inan', 'Erin ', 'oeinan@hotmail.com', '6508148589', NULL, NULL, NULL, NULL),
(60, NULL, '', 'Jain', 'Palak', 1, 2023, 'Jnpalak2005@gmail.com', 'Palak.jain@students.cobbk12.org', 'cell', '4704735588', 'Jain', 'Kirti', 'kirti.jain@adityabirla.com', '4044264207', 'Jain', 'Sonila', NULL, '2294161001'),
(62, NULL, '', 'Venkatesh ', 'Saanvi', 1, 2024, 'saanvikv@gmail.com', NULL, 'cell', '470-633-9082', 'Venkatesh', 'Sheetal ', 'sheetalhs@gmail.com', '407-810-2494', NULL, NULL, NULL, NULL),
(65, NULL, '', 'Shetty', 'Samrita', 1, 2024, 'SamritaSShetty@gmail.com', NULL, 'cell', '470-755-2844', ' Shetty', 'Samith', 'samithshetty@hotmail.com', '704-264-6312', NULL, NULL, NULL, NULL),
(66, NULL, '', 'Clark', 'Sarah', 1, 2024, 'sarahkatclark06@gmail.com', 'sarah.clark@students.cobbk12.org', 'cell', '678-644-4361', 'Clark', 'Jennifer', 'JenClark@aol.com', '205-249-7713', 'Clark', 'Eddie', NULL, NULL),
(67, NULL, '', 'Umesh', 'Shashank', 1, 2022, 'shashumesh@gmail.com', NULL, 'cell', '6789000148', 'Rajamani', 'Gayathri', 'gayathri.umesh@gmail.com', '4048837177', NULL, NULL, NULL, NULL),
(69, NULL, '', 'Choi', 'Wonho', 1, 2022, 'wonhoc4161@gmail.com', NULL, 'cell', '4046617456', 'Choi', 'Yongmin', 'dr.choi70@gmail.com', '4705584262', NULL, NULL, NULL, NULL),
(71, NULL, '', 'Vijay', 'Varun', 1, 2022, 'varunvj12@gmail.com', NULL, 'cell', '4047025555', 'Sundar', 'Vijaya', 'vijayaks19@gmail.com', '7709255322', NULL, NULL, NULL, NULL),
(72, NULL, '', 'Raj', 'Shivani', 1, 2022, 'shivani.raj2630@gmail.com', NULL, 'cell', '4048074822', 'Venugopal', 'Sridevi', 'srijay2630@gmail.com', '4048015353', NULL, NULL, NULL, NULL),
(73, NULL, '', 'Wang', 'Zachary', 0, 2021, 'zackzwang@gmail.con', NULL, 'cell', '470-535-1276', 'Wang', 'Thomas', 'tomswang@hotmail.com', '404-449-1094', NULL, NULL, NULL, NULL),
(74, NULL, '', 'Gistren ', 'Jasmine ', 1, 2024, 'jazzy.gistren@gmail.com', NULL, 'cell', '239-235-9292', 'Gistern ', 'Joss ', 'jossgistren@yahoo.com', '239-888-0580', NULL, NULL, NULL, NULL),
(76, NULL, '', 'Fang', 'Andrew', 1, 2024, 'Andrewdavidfang@gmail.com', NULL, 'cell', '678-221-4009', 'Fang', 'Yunnan', 'valinedna@yahoo.com', '614-805-4839', NULL, NULL, NULL, NULL),
(77, NULL, '', 'Venkatesan', 'Priya', 1, 2025, 'priya.venki2016@gmail.com', '', 'cell', '4049519235', 'Ganapathy', 'Lakshmi ', NULL, '6784629696', 'Sundaram', 'Venkatesan', NULL, '4049577350');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `teamID` int NOT NULL,
  `teamName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tournamentID` int NOT NULL,
  `teamPlace` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`teamID`, `teamName`, `tournamentID`, `teamPlace`) VALUES
(1, 'A', 6, NULL),
(2, 'A', 1, NULL),
(3, 'A', 10, NULL),
(4, 'A', 11, NULL),
(5, 'A', 9, NULL),
(6, 'A', 8, NULL),
(7, 'A', 7, NULL),
(8, 'B', 7, NULL),
(9, 'A', 5, NULL),
(10, 'B', 5, NULL),
(11, 'C', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teammate`
--

CREATE TABLE `teammate` (
  `teamID` int NOT NULL,
  `studentID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teammate`
--

INSERT INTO `teammate` (`teamID`, `studentID`) VALUES
(1, 6),
(1, 42),
(1, 10),
(1, 4),
(1, 14),
(1, 7),
(1, 29),
(1, 13),
(1, 1),
(1, 51),
(1, 3),
(1, 5),
(1, 8),
(1, 11),
(1, 35),
(3, 6),
(3, 1),
(3, 4),
(3, 32),
(3, 11),
(3, 5),
(3, 16),
(3, 35),
(3, 7),
(3, 51),
(3, 10),
(3, 42),
(3, 3),
(3, 29),
(5, 13),
(5, 1),
(5, 4),
(5, 11),
(5, 5),
(5, 35),
(5, 7),
(5, 8),
(5, 44),
(5, 51),
(5, 14),
(5, 10),
(5, 3),
(5, 29),
(6, 26),
(6, 35),
(6, 42),
(6, 10),
(6, 4),
(6, 14),
(6, 6),
(6, 29),
(6, 13),
(6, 51),
(6, 3),
(6, 28),
(6, 5),
(6, 11),
(6, 1),
(7, 32),
(7, 26),
(7, 16),
(7, 25),
(7, 27),
(7, 13),
(7, 50),
(7, 28),
(7, 53),
(7, 44),
(7, 55),
(7, 57),
(7, 67),
(8, 33),
(8, 34),
(8, 76),
(8, 36),
(8, 52),
(8, 49),
(8, 74),
(8, 24),
(8, 56),
(8, 60),
(8, 41),
(8, 65),
(8, 66),
(8, 72),
(8, 69),
(2, 42),
(2, 10),
(2, 4),
(2, 14),
(2, 7),
(2, 6),
(2, 3),
(2, 51),
(2, 53),
(2, 44),
(2, 5),
(2, 8),
(2, 11),
(2, 1),
(2, 35),
(9, 31),
(9, 76),
(9, 26),
(9, 16),
(9, 35),
(9, 42),
(9, 41),
(9, 25),
(9, 52),
(9, 74),
(9, 44),
(9, 54),
(9, 65),
(9, 1),
(9, 69),
(10, 33),
(10, 10),
(10, 14),
(10, 13),
(10, 3),
(10, 24),
(10, 53),
(10, 55),
(10, 56),
(10, 57),
(10, 60),
(10, 58),
(10, 67),
(10, 11),
(10, 71),
(11, 32),
(11, 34),
(11, 4),
(11, 7),
(11, 49),
(11, 6),
(11, 27),
(11, 29),
(11, 50),
(11, 51),
(11, 5),
(11, 8),
(11, 62),
(11, 66),
(11, 28),
(7, 71),
(7, 58);

-- --------------------------------------------------------

--
-- Table structure for table `teammateplace`
--

CREATE TABLE `teammateplace` (
  `studentID` int NOT NULL,
  `tournamenteventID` int NOT NULL,
  `teamID` int NOT NULL,
  `place` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teammateplace`
--

INSERT INTO `teammateplace` (`studentID`, `tournamenteventID`, `teamID`, `place`) VALUES
(42, 1, 1, 6),
(8, 1, 1, 6),
(4, 2, 1, 2),
(51, 2, 1, 2),
(10, 5, 1, 24),
(35, 5, 1, 24),
(7, 4, 1, 11),
(1, 4, 1, 11),
(3, 7, 1, 32),
(4, 7, 1, 32),
(6, 10, 1, 31),
(5, 10, 1, 31),
(4, 6, 1, 33),
(29, 6, 1, 33),
(3, 6, 1, 33),
(42, 9, 1, 15),
(7, 9, 1, 15),
(10, 24, 1, 30),
(6, 24, 1, 30),
(11, 17, 1, 1),
(51, 17, 1, 1),
(4, 11, 1, 8),
(51, 11, 1, 8),
(5, 11, 1, 8),
(1, 8, 1, 5),
(14, 8, 1, 5),
(11, 14, 1, 8),
(10, 14, 1, 8),
(35, 16, 1, 35),
(14, 16, 1, 35),
(3, 19, 1, 1),
(42, 19, 1, 1),
(29, 19, 1, 1),
(7, 21, 1, 31),
(13, 21, 1, 31),
(35, 12, 1, 5),
(29, 12, 1, 5),
(7, 13, 1, 4),
(51, 13, 1, 4),
(6, 20, 1, 2),
(10, 20, 1, 2),
(1, 23, 1, 27),
(8, 23, 1, 27),
(6, 44, 3, 25),
(6, 34, 3, 39),
(1, 43, 3, 20),
(1, 34, 3, 39),
(4, 26, 3, 18),
(4, 30, 3, 60),
(4, 48, 3, NULL),
(32, 28, 3, 40),
(32, 45, 3, 9),
(11, 45, 3, 9),
(11, 41, 3, 9),
(11, 38, 3, 82),
(5, 35, 3, NULL),
(5, 40, 3, 38),
(16, 29, 3, 59),
(16, 48, 3, NULL),
(6, 82, 6, 115),
(6, 92, 6, 59),
(26, 74, 6, 49),
(26, 88, 6, 53),
(26, 95, 6, 24),
(1, 95, 6, 24),
(1, 91, 6, 22),
(1, 76, 6, 65),
(13, 82, 6, 115),
(13, 93, 6, 8),
(4, 73, 6, 29),
(4, 79, 6, 35),
(4, 80, 6, 5),
(11, 78, 6, 81),
(11, 93, 6, 8),
(5, 83, 6, 31),
(35, 92, 6, 59),
(35, 84, 6, 49),
(51, 74, 6, 49),
(51, 83, 6, 31),
(51, 85, 6, 26),
(51, 89, 6, 96),
(14, 77, 6, 122),
(14, 80, 6, 5),
(14, 88, 6, 53),
(28, 86, 6, 81),
(28, 85, 6, 26),
(28, 89, 6, 96),
(10, 77, 6, 122),
(10, 86, 6, 81),
(42, 73, 6, 29),
(42, 79, 6, 35),
(42, 81, 6, 49),
(42, 91, 6, 22),
(3, 81, 6, 49),
(3, 83, 6, 31),
(3, 91, 6, 22),
(29, 78, 6, 81),
(26, 78, 6, 81),
(3, 76, 6, 65),
(29, 84, 6, 49),
(6, 96, 6, 30),
(4, 96, 6, 30),
(29, 60, 5, 21),
(35, 60, 5, 21),
(14, 62, 5, 82),
(5, 62, 5, 82),
(13, 68, 5, 25),
(10, 68, 5, 25),
(51, 54, 5, 60),
(29, 54, 5, 60),
(44, 54, 5, 60),
(8, 57, 5, 24),
(3, 57, 5, 24),
(11, 65, 5, 9),
(7, 65, 5, 9),
(7, 52, 5, 40),
(1, 52, 5, 40),
(44, 55, 5, 27),
(3, 55, 5, 27),
(5, 58, 5, 39),
(13, 58, 5, 39),
(35, 64, 5, 38),
(10, 64, 5, 38),
(29, 67, 5, 20),
(3, 67, 5, 20),
(1, 67, 5, 20),
(13, 69, 5, 9),
(11, 69, 5, 9),
(44, 49, 5, 48),
(8, 49, 5, 48),
(4, 50, 5, 18),
(51, 50, 5, 18),
(35, 53, 5, 59),
(10, 53, 5, 59),
(7, 61, 5, 38),
(13, 61, 5, 38),
(42, 121, 2, 38),
(44, 121, 2, 38),
(4, 122, 2, 36),
(51, 122, 2, 36),
(35, 125, 2, 128),
(10, 125, 2, 128),
(35, 136, 2, 159),
(53, 136, 2, 159),
(1, 139, 2, 38),
(3, 139, 2, 38),
(42, 139, 2, 38),
(7, 141, 2, 37),
(5, 141, 2, 37),
(1, 128, 2, 54),
(14, 128, 2, 54),
(4, 131, 2, 18),
(5, 131, 2, 18),
(7, 131, 2, 18),
(10, 134, 2, 57),
(11, 134, 2, 57),
(3, 126, 2, 84),
(51, 126, 2, 84),
(35, 126, 2, 84),
(4, 144, 2, 111),
(53, 144, 2, 111),
(42, 129, 2, 46),
(8, 129, 2, 46),
(11, 137, 2, 10),
(7, 137, 2, 10),
(5, 124, 2, 55),
(7, 124, 2, 55),
(4, 127, 2, 27),
(42, 127, 2, 27),
(10, 130, 2, 153),
(1, 130, 2, 153),
(35, 132, 2, 86),
(5, 132, 2, 86),
(51, 133, 2, 77),
(44, 133, 2, 77),
(6, 140, 2, 102),
(10, 140, 2, 102),
(8, 143, 2, 120),
(1, 143, 2, 120),
(53, 135, 2, 203),
(6, 135, 2, 203),
(55, 100, 7, 2),
(32, 100, 7, 2),
(50, 105, 7, 17),
(71, 105, 7, 17),
(44, 113, 7, 16),
(28, 113, 7, 16),
(28, 110, 7, 9),
(67, 110, 7, 9),
(26, 112, 7, 12),
(53, 112, 7, 12),
(32, 117, 7, 3),
(13, 117, 7, 3),
(26, 119, 7, 4),
(58, 119, 7, 4),
(50, 108, 7, 4),
(55, 108, 7, 4),
(28, 109, 7, 9),
(44, 109, 7, 9),
(53, 101, 7, 5),
(16, 101, 7, 5),
(53, 120, 7, 4),
(16, 120, 7, 4),
(27, 104, 7, 2),
(25, 104, 7, 2),
(44, 97, 7, 20),
(57, 97, 7, 20),
(26, 98, 7, 18),
(67, 98, 7, 18),
(44, 103, 7, 14),
(58, 103, 7, 14),
(28, 106, 7, 8),
(13, 106, 7, 8),
(26, 107, 7, 6),
(25, 107, 7, 6),
(55, 107, 7, 6),
(26, 102, 7, 17),
(53, 102, 7, 17),
(55, 102, 7, 17),
(25, 115, 7, 6),
(58, 115, 7, 6),
(71, 115, 7, 6),
(27, 116, 7, 28),
(50, 116, 7, 28),
(49, 105, 8, 23),
(72, 105, 8, 23),
(33, 100, 8, 15),
(69, 100, 8, 15),
(52, 113, 8, 13),
(24, 113, 8, 13),
(33, 112, 8, 33),
(56, 112, 8, 33),
(36, 117, 8, 25),
(41, 117, 8, 25),
(65, 110, 8, 5),
(76, 110, 8, 5),
(60, 101, 8, 16),
(66, 101, 8, 16),
(72, 108, 8, 22),
(69, 108, 8, 22),
(56, 109, 8, 17),
(52, 109, 8, 17),
(36, 119, 8, 10),
(65, 119, 8, 10),
(60, 104, 8, 3),
(24, 104, 8, 3),
(56, 120, 8, 22),
(76, 98, 8, 22),
(66, 98, 8, 22),
(34, 97, 8, 27),
(36, 107, 8, 8),
(49, 107, 8, 8),
(69, 107, 8, 8),
(41, 106, 8, 11),
(34, 103, 8, 20),
(24, 103, 8, 20),
(36, 102, 8, 23),
(65, 102, 8, 23),
(49, 102, 8, 23),
(72, 115, 8, 21),
(69, 115, 8, 21),
(60, 116, 8, 13),
(76, 116, 8, 13),
(74, 106, 8, 11),
(74, 106, 8, 11);

-- --------------------------------------------------------

--
-- Table structure for table `timeblock`
--

CREATE TABLE `timeblock` (
  `timeblockID` int NOT NULL,
  `timeStart` datetime NOT NULL,
  `timeEnd` datetime NOT NULL,
  `tournamentID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeblock`
--

INSERT INTO `timeblock` (`timeblockID`, `timeStart`, `timeEnd`, `tournamentID`) VALUES
(2, '2021-01-16 11:00:00', '2021-01-16 11:50:00', 6),
(4, '2021-01-16 12:00:00', '2021-01-16 12:50:00', 6),
(5, '2021-01-16 13:00:00', '2021-01-16 13:50:00', 6),
(6, '2021-01-16 14:00:00', '2021-01-16 14:50:00', 6),
(7, '2021-01-16 15:00:00', '2021-01-16 15:50:00', 6),
(8, '2021-01-16 16:00:00', '2021-01-16 16:50:00', 6),
(9, '2021-02-20 09:00:00', '2021-02-20 09:50:00', 10),
(10, '2021-02-20 10:00:00', '2021-02-20 10:50:00', 10),
(11, '2021-02-20 11:00:00', '2021-02-20 11:50:00', 10),
(12, '2021-02-20 12:00:00', '2021-02-20 12:50:00', 10),
(13, '2021-02-20 13:00:00', '2021-02-20 13:50:00', 10),
(14, '2021-02-20 14:00:00', '2021-02-20 14:50:00', 10),
(15, '2021-02-20 15:00:00', '2021-02-20 15:50:00', 10),
(16, '2021-01-30 11:00:00', '2021-01-30 11:50:00', 9),
(17, '2021-01-30 12:15:00', '2021-01-30 13:05:00', 9),
(18, '2021-01-30 13:30:00', '2021-01-30 14:20:00', 9),
(19, '2021-01-30 14:45:00', '2021-01-30 15:35:00', 9),
(20, '2021-01-30 16:00:00', '2021-01-30 16:50:00', 9),
(21, '2021-01-22 11:00:00', '2021-01-22 11:50:00', 8),
(22, '2021-01-22 12:00:00', '2021-01-22 12:50:00', 8),
(23, '2021-01-22 13:00:00', '2021-01-22 13:50:00', 8),
(24, '2021-01-22 14:00:00', '2021-01-22 14:50:00', 8),
(25, '2021-01-22 15:00:00', '2021-01-22 15:50:00', 8),
(26, '2021-01-22 16:00:00', '2021-01-22 16:50:00', 8),
(27, '2021-01-22 17:00:00', '2021-01-22 17:50:00', 8),
(28, '2021-01-16 08:00:00', '2021-01-16 08:50:00', 7),
(30, '2021-01-16 09:00:00', '2021-01-16 09:50:00', 7),
(31, '2021-01-16 10:00:00', '2021-01-16 10:50:00', 7),
(32, '2021-01-16 11:00:00', '2021-01-16 11:50:00', 7),
(33, '2021-01-16 12:00:00', '2021-01-16 12:50:00', 7),
(34, '2021-01-16 13:00:00', '2021-01-16 13:50:00', 7),
(35, '2020-10-10 11:00:00', '2020-10-10 11:50:00', 1),
(36, '2020-10-10 12:00:00', '2020-10-10 12:50:00', 1),
(37, '2020-10-10 13:00:00', '2020-10-10 13:50:00', 1),
(38, '2020-10-10 14:00:00', '2020-10-10 14:50:00', 1),
(39, '2020-10-10 15:00:00', '2020-10-10 15:50:00', 1),
(40, '2020-10-10 16:00:00', '2020-10-10 16:50:00', 1),
(41, '2020-10-10 17:00:00', '2020-10-10 17:50:00', 1),
(42, '2020-12-19 11:00:00', '2020-12-19 11:55:00', 5),
(43, '2020-12-19 12:10:00', '2020-12-19 13:05:00', 5),
(44, '2020-12-19 13:20:00', '2020-12-19 14:15:00', 5),
(45, '2020-12-19 14:30:00', '2020-12-19 15:25:00', 5),
(46, '2020-12-19 15:40:00', '2020-12-19 16:35:00', 5),
(47, '2020-12-19 16:50:00', '2020-12-19 17:45:00', 5),
(48, '2020-12-19 18:00:00', '2020-12-19 18:55:00', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

CREATE TABLE `tournament` (
  `tournamentID` int NOT NULL,
  `tournamentName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `host` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateTournament` date DEFAULT NULL,
  `dateRegistration` date DEFAULT NULL,
  `year` int NOT NULL,
  `type` int DEFAULT NULL,
  `numberTeams` int DEFAULT NULL,
  `weighting` int NOT NULL,
  `note` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `addressBilling` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `websiteHost` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `websiteScilympiad` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `director` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `directorEmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `directorPhone` varchar(12) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournament`
--

INSERT INTO `tournament` (`tournamentID`, `tournamentName`, `host`, `dateTournament`, `dateRegistration`, `year`, `type`, `numberTeams`, `weighting`, `note`, `address`, `addressBilling`, `websiteHost`, `websiteScilympiad`, `director`, `directorEmail`, `directorPhone`) VALUES
(1, 'BEARSO', 'Bay Area Invitational', '2020-10-10', '2020-08-27', 2021, NULL, 1, 100, 'nada', 'San Francisco, CA (Remote)', '', '', 'https://scilympiad.com/bearso', 'Peter Hung', 'peterhung@socalscioly.org', ''),
(2, 'SoFo', 'South Forsyth High School', '2020-10-24', '2020-09-01', 2021, NULL, 3, 50, '', 'South Forsyth, GA (Remote)', '', 'https://www.forsyth.k12.ga.us/Page/22519', 'https://scilympiad.com/sofo', 'Amy Chisam', 'achisam@gmail.com', ''),
(3, 'Practice Tournament', 'National Science Olympiad', '2020-11-28', '2020-11-07', 2021, NULL, 3, 100, '', 'Remote', '', '', 'https://scilympiad.com/sopractice', '', '', ''),
(4, 'UGA', 'Science Olympiad Outreach at UGA', '2020-11-14', '2020-10-12', 2021, NULL, 3, 75, '', 'Athens, GA (Remote)', 'https://www.ugascienceolympiad.net/', 'https://scilympiad.com/uga', 'Science Olympiad Outreach', '', 'scienceolympiad@uga.edu', ''),
(5, 'SOLVI', 'Clark High School', '2020-12-19', '2020-09-10', 2021, NULL, 3, 75, '', 'Las Vegas, NV (Remote)', '4291 Pennwood Ave, Las Vegas, NV 89102', 'http://www.clarkscienceolympiad.com/solvi.html', 'https://scilympiad.com/nv-clark', '', 'clarkscioly@gmail.com', ''),
(6, 'Aggie', 'UC Davis', '2021-01-16', '2020-09-18', 2021, 2, 1, 90, '', 'Davis, CA (Remote)', '', 'https://sciolyatucdavis.wixsite.com/aggieinvitational', 'https://scilympiad.com/aggie', 'Chad Mowers and Claire Chapman', '', ''),
(7, 'BISOT', 'Brookwood High School', '2021-01-16', '2020-12-10', 2021, NULL, 2, 50, '', '1255 DOGWOOD ROAD, SNELLVILLE, GEORGIA 30078', '1255 DOGWOOD ROAD, SNELLVILLE, GEORGIA 30078', 'http://brookwoodso.weebly.com/bisot.html', '', 'Chuck Thorton / Jon Erwin', '', ''),
(8, 'MIT', 'Science Olympiad at MIT', '2021-01-22', '2020-09-12', 2021, NULL, 1, 100, '', 'Cambridge, MA (Remote)', '', 'https://scioly.mit.edu/', '', 'Science Olympiad at MIT', 'scioly@mit.edu', ''),
(9, 'Harvard-Brown Tournament', 'HUSO and BUSO', '2021-01-30', '2020-09-01', 2021, NULL, 1, 100, '', 'Cambridge, MA (Remote)', '', '', 'https://www.sciolyharvard.org/divc', 'Harvard Undergraduate Science Olympiad ', 'sciolyharvard@gmail.com', ''),
(10, 'PUSO', 'Princeton', '2021-02-20', '2020-10-01', 2021, NULL, 1, 100, '', 'Princeton, NJ (Remote)', '', 'https://scioly.princeton.edu/', '', '', 'scioly@princeton.edu', ''),
(11, 'State Competition', 'Georgia State Science Olympiad', '2021-03-13', '2020-09-01', 2021, NULL, 3, 90, '', 'Remote', '', '', '', 'Arneesh ', 'georgiascioly@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `tournamentevent`
--

CREATE TABLE `tournamentevent` (
  `tournamenteventID` int NOT NULL,
  `eventID` int NOT NULL,
  `tournamentID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tournamentevent`
--

INSERT INTO `tournamentevent` (`tournamenteventID`, `eventID`, `tournamentID`) VALUES
(1, 1, 6),
(2, 2, 6),
(3, 3, 6),
(4, 6, 6),
(5, 7, 6),
(6, 8, 6),
(7, 9, 6),
(8, 11, 6),
(9, 12, 6),
(10, 13, 6),
(11, 15, 6),
(12, 16, 6),
(13, 17, 6),
(14, 18, 6),
(16, 22, 6),
(17, 23, 6),
(18, 24, 6),
(19, 25, 6),
(20, 28, 6),
(21, 31, 6),
(22, 33, 6),
(23, 34, 6),
(24, 10, 6),
(25, 1, 10),
(26, 2, 10),
(28, 6, 10),
(29, 7, 10),
(30, 8, 10),
(31, 9, 10),
(33, 12, 10),
(34, 13, 10),
(35, 15, 10),
(36, 16, 10),
(37, 17, 10),
(38, 18, 10),
(39, 19, 10),
(40, 22, 10),
(41, 23, 10),
(43, 25, 10),
(44, 28, 10),
(45, 31, 10),
(48, 10, 10),
(49, 1, 9),
(50, 2, 9),
(52, 6, 9),
(53, 7, 9),
(54, 8, 9),
(55, 9, 9),
(57, 12, 9),
(58, 13, 9),
(60, 16, 9),
(61, 17, 9),
(62, 18, 9),
(64, 22, 9),
(65, 23, 9),
(67, 25, 9),
(68, 28, 9),
(69, 31, 9),
(73, 1, 8),
(74, 2, 8),
(76, 6, 8),
(77, 7, 8),
(78, 8, 8),
(79, 9, 8),
(80, 11, 8),
(81, 12, 8),
(82, 13, 8),
(83, 15, 8),
(84, 16, 8),
(85, 17, 8),
(86, 18, 8),
(88, 22, 8),
(89, 23, 8),
(91, 25, 8),
(92, 28, 8),
(93, 31, 8),
(95, 34, 8),
(96, 10, 8),
(97, 1, 7),
(98, 2, 7),
(100, 6, 7),
(101, 7, 7),
(102, 8, 7),
(103, 9, 7),
(104, 11, 7),
(105, 12, 7),
(106, 13, 7),
(107, 15, 7),
(108, 16, 7),
(109, 17, 7),
(110, 18, 7),
(112, 22, 7),
(113, 23, 7),
(115, 25, 7),
(116, 28, 7),
(117, 31, 7),
(119, 34, 7),
(120, 10, 7),
(121, 1, 1),
(122, 2, 1),
(124, 6, 1),
(125, 7, 1),
(126, 8, 1),
(127, 9, 1),
(128, 11, 1),
(129, 12, 1),
(130, 13, 1),
(131, 15, 1),
(132, 16, 1),
(133, 17, 1),
(134, 18, 1),
(135, 19, 1),
(136, 22, 1),
(137, 23, 1),
(139, 25, 1),
(140, 28, 1),
(141, 31, 1),
(143, 34, 1),
(144, 10, 1),
(145, 1, 5),
(146, 2, 5),
(148, 6, 5),
(149, 7, 5),
(150, 8, 5),
(151, 9, 5),
(153, 12, 5),
(154, 13, 5),
(156, 16, 5),
(157, 17, 5),
(158, 18, 5),
(160, 22, 5),
(161, 23, 5),
(163, 25, 5),
(164, 28, 5),
(165, 31, 5),
(168, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tournamenttimeavailable`
--

CREATE TABLE `tournamenttimeavailable` (
  `tournamenteventID` int NOT NULL,
  `timeblockID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournamenttimeavailable`
--

INSERT INTO `tournamenttimeavailable` (`tournamenteventID`, `timeblockID`) VALUES
(1, 2),
(2, 2),
(5, 2),
(4, 4),
(7, 4),
(10, 4),
(6, 5),
(9, 5),
(24, 5),
(17, 5),
(11, 6),
(14, 6),
(8, 6),
(16, 7),
(19, 7),
(21, 7),
(23, 8),
(20, 8),
(13, 8),
(15, 8),
(12, 8),
(40, 9),
(43, 9),
(45, 9),
(25, 10),
(26, 10),
(29, 10),
(36, 11),
(37, 11),
(44, 11),
(28, 12),
(31, 12),
(34, 12),
(30, 13),
(33, 13),
(41, 13),
(35, 14),
(38, 14),
(48, 14),
(39, 15),
(60, 16),
(62, 16),
(68, 16),
(54, 17),
(57, 17),
(65, 17),
(52, 18),
(55, 18),
(58, 18),
(64, 19),
(67, 19),
(69, 19),
(49, 20),
(50, 20),
(53, 20),
(61, 20),
(63, 20),
(71, 20),
(73, 21),
(74, 21),
(77, 21),
(76, 22),
(79, 22),
(82, 22),
(78, 23),
(81, 23),
(96, 23),
(89, 23),
(83, 24),
(86, 24),
(80, 24),
(88, 25),
(91, 25),
(93, 25),
(95, 26),
(85, 26),
(92, 26),
(84, 27),
(105, 28),
(100, 28),
(113, 28),
(110, 30),
(117, 30),
(112, 30),
(119, 31),
(108, 31),
(109, 31),
(101, 31),
(97, 32),
(98, 32),
(120, 32),
(104, 32),
(106, 33),
(107, 33),
(103, 33),
(115, 34),
(116, 34),
(102, 34),
(121, 35),
(125, 35),
(141, 36),
(122, 35),
(139, 36),
(136, 36),
(128, 37),
(131, 37),
(134, 37),
(144, 38),
(129, 38),
(126, 38),
(137, 38),
(124, 39),
(127, 39),
(130, 39),
(132, 40),
(133, 40),
(143, 40),
(140, 40),
(135, 41),
(156, 42),
(157, 42),
(150, 43),
(153, 43),
(161, 43),
(148, 44),
(151, 44),
(154, 44),
(145, 45),
(149, 45),
(146, 45),
(168, 46),
(164, 46),
(163, 47),
(165, 47),
(160, 47),
(158, 48);

-- --------------------------------------------------------

--
-- Table structure for table `tournamenttimechosen`
--

CREATE TABLE `tournamenttimechosen` (
  `tournamenteventID` int NOT NULL,
  `timeblockID` int NOT NULL,
  `teamID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournamenttimechosen`
--

INSERT INTO `tournamenttimechosen` (`tournamenteventID`, `timeblockID`, `teamID`) VALUES
(1, 2, 1),
(2, 2, 1),
(5, 2, 1),
(4, 4, 1),
(6, 5, 1),
(7, 4, 1),
(24, 5, 1),
(8, 6, 1),
(9, 5, 1),
(10, 4, 1),
(11, 6, 1),
(12, 8, 1),
(13, 8, 1),
(14, 6, 1),
(15, 8, 1),
(16, 7, 1),
(17, 5, 1),
(19, 7, 1),
(20, 8, 1),
(21, 7, 1),
(23, 8, 1),
(25, 10, 3),
(26, 10, 3),
(28, 12, 3),
(29, 10, 3),
(30, 13, 3),
(31, 12, 3),
(48, 14, 3),
(33, 13, 3),
(34, 12, 3),
(35, 14, 3),
(36, 11, 3),
(37, 11, 3),
(38, 14, 3),
(39, 15, 3),
(41, 13, 3),
(44, 11, 3),
(43, 9, 3),
(45, 9, 3),
(40, 9, 3),
(49, 20, 5),
(50, 20, 5),
(52, 18, 5),
(53, 20, 5),
(54, 17, 5),
(55, 18, 5),
(57, 17, 5),
(58, 18, 5),
(61, 20, 5),
(60, 16, 5),
(62, 16, 5),
(63, 20, 5),
(64, 19, 5),
(65, 17, 5),
(67, 19, 5),
(68, 16, 5),
(69, 19, 5),
(71, 20, 5),
(73, 21, 6),
(74, 21, 6),
(76, 22, 6),
(77, 21, 6),
(78, 23, 6),
(79, 22, 6),
(96, 23, 6),
(80, 24, 6),
(81, 23, 6),
(82, 22, 6),
(83, 24, 6),
(84, 27, 6),
(85, 26, 6),
(86, 24, 6),
(88, 25, 6),
(89, 23, 6),
(91, 25, 6),
(92, 26, 6),
(93, 25, 6),
(95, 26, 6),
(100, 28, 7),
(101, 31, 7),
(102, 34, 7),
(103, 33, 7),
(120, 32, 7),
(104, 32, 7),
(106, 33, 7),
(105, 28, 7),
(107, 33, 7),
(108, 31, 7),
(105, 28, 8),
(107, 33, 8),
(104, 32, 8),
(120, 32, 8),
(106, 33, 8),
(103, 33, 8),
(102, 34, 8),
(100, 28, 8),
(101, 31, 8),
(108, 31, 8),
(109, 31, 7),
(109, 31, 8),
(110, 30, 7),
(113, 28, 7),
(113, 28, 8),
(112, 30, 7),
(112, 30, 8),
(115, 34, 7),
(115, 34, 8),
(116, 34, 7),
(116, 34, 8),
(117, 30, 7),
(117, 30, 8),
(119, 31, 7),
(119, 31, 8),
(97, 32, 7),
(98, 32, 7),
(110, 30, 8),
(98, 32, 8),
(121, 35, 2),
(122, 35, 2),
(124, 39, 2),
(125, 35, 2),
(126, 38, 2),
(127, 39, 2),
(144, 38, 2),
(128, 37, 2),
(129, 38, 2),
(130, 39, 2),
(131, 37, 2),
(132, 40, 2),
(133, 40, 2),
(134, 37, 2),
(136, 36, 2),
(137, 38, 2),
(135, 41, 2),
(140, 40, 2),
(139, 36, 2),
(141, 36, 2),
(143, 40, 2),
(145, 45, 9),
(145, 45, 10),
(145, 45, 11),
(146, 45, 9),
(146, 45, 10),
(146, 45, 11),
(148, 44, 9),
(148, 44, 10),
(148, 44, 11),
(149, 45, 9),
(149, 45, 10),
(149, 45, 11),
(150, 43, 9),
(150, 43, 10),
(150, 43, 11),
(151, 44, 9),
(151, 44, 10),
(151, 44, 11),
(168, 46, 9),
(168, 46, 10),
(168, 46, 11),
(153, 43, 9),
(153, 43, 10),
(153, 43, 11),
(154, 44, 9),
(154, 44, 10),
(154, 44, 11),
(156, 42, 9),
(156, 42, 10),
(156, 42, 11),
(157, 42, 9),
(157, 42, 10),
(157, 42, 11),
(158, 48, 9),
(158, 48, 10),
(158, 48, 11),
(160, 47, 9),
(160, 47, 10),
(160, 47, 11),
(161, 43, 9),
(161, 43, 10),
(161, 43, 11),
(163, 47, 9),
(163, 47, 10),
(163, 47, 11),
(164, 46, 9),
(164, 46, 10),
(164, 46, 11),
(165, 47, 9),
(165, 47, 10),
(165, 47, 11),
(97, 32, 8);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `oauth_provider` enum('google','facebook','twitter','linkedin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'google',
  `oauth_uid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `privilege` int DEFAULT NULL,
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
(10, 'google', '103874619842696589534', 3, 'Andrew', 'Greig', 'acgreig@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14Gg9JZv6l6GPu9um1rRhi-x5zo9Jqet0vaLoLAHGjLY=s96-c', '2021-04-30 16:13:06', '2021-04-30 16:13:06'),
(11, 'google', '107148355485375603861', 2, 'Emily', 'Yang', 'emilysun1160@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GiedMg4cRfkL8uZ33uE5u2lOaU5OY7mpm3sU0iwTA=s96-c', '2021-04-30 16:15:06', '2021-04-30 16:15:06'),
(12, 'google', '111253112150007245823', 3, 'Chinmay', 'Joshi', 'chinmayj.walton@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a/AATXAJwYM7GKh9LHbuVnusA8J1HinDmdW46OweWnwH8V=s96-c', '2021-04-30 16:16:39', '2021-04-30 16:16:39'),
(13, 'google', '102385444909511766502', 2, 'Joanna', 'Wei', 'joannawei05@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a/AATXAJz2Or3KG3rq2kdqGylvQKmPx4wZSJcmFNxy_uDS=s96-c', '2021-04-30 16:16:42', '2021-04-30 16:16:42'),
(14, 'google', '112468442879454322251', 2, 'Rima', 'Rami', 'rimazazu@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14Gh0SZzmVio4hlx-u3JVxi8jLNlpqWj8PY3vP_s0ag=s96-c', '2021-04-30 16:16:47', '2021-04-30 16:16:47'),
(15, 'google', '102894286402057637896', 2, 'Faith', 'Huang', 'fyizhenh@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GjXobT7gm6Vhtinx9kEyS51xaWyQnGmJq-hL14IKg=s96-c', '2021-04-30 16:17:15', '2021-04-30 16:17:15'),
(16, 'google', '112051074721749425737', 2, 'Cynthia', 'Peng', 'alcp6201@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GgnKiqOhLb0sMRw86JMoUngv8-s2a1Em-MDXW3dOw=s96-c', '2021-04-30 16:17:17', '2021-04-30 16:17:17'),
(17, 'google', '112356622834711211761', 2, 'Jennifer', 'He', 'jenniferhe0203@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14Gi6ufKCl3uXEE5zGz1g3e9rvZM3BI_AqgO7Sa_xSMc=s96-c', '2021-04-30 16:19:34', '2021-04-30 16:19:34'),
(18, 'google', '118077389039177077874', 2, 'Christian', 'Hable', 'cmhable@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GhQzK1HHb5u6f5PKC27AROoTQ580HKjMCgSEzmisw=s96-c', '2021-04-30 16:20:01', '2021-04-30 16:20:01'),
(19, 'google', '100350629985317200334', 2, 'Chris', 'Wang', 'goodchris0831@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GiNQtjYqSwUSjM3lDaQ2EkJ7z-T2teAOULCcaR5=s96-c', '2021-04-30 16:20:21', '2021-04-30 16:20:21'),
(20, 'google', '116164729655931508486', NULL, 'Walton', 'Habitat', 'waltonhabitatapps@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a/AATXAJwkHlAEdAzscu_kVXvMHKVPGiPKpbo5Gq9PYr54=s96-c', '2021-05-07 13:53:50', '2021-05-07 13:53:50'),
(21, 'google', '103400986357747872543', 3, 'Susanna', 'H.', 'susanna.lmt.196@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14Gi2ZiKu7_c99MJ9TRbypNWntl-HdN-xnahSbopG=s96-c', '2021-05-07 16:24:01', '2021-05-07 16:24:01'),
(22, 'google', '102257913043025695172', NULL, 'George', 'Walton', 'waltonscienceclub@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GhBU-UC5plMxnfszwA14K403sGct7DQP-hwcUk=s96-c', '2021-06-16 18:33:06', '2021-06-16 18:33:06');

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
  MODIFY `awardID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coach`
--
ALTER TABLE `coach`
  MODIFY `coachID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `courseID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `coursecompleted`
--
ALTER TABLE `coursecompleted`
  MODIFY `coursecompletedID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `courseenrolled`
--
ALTER TABLE `courseenrolled`
  MODIFY `courseenrolledID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `eventID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `eventchoice`
--
ALTER TABLE `eventchoice`
  MODIFY `eventchoiceID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=492;

--
-- AUTO_INCREMENT for table `eventyear`
--
ALTER TABLE `eventyear`
  MODIFY `eventyearID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `officer`
--
ALTER TABLE `officer`
  MODIFY `officerID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `rule`
--
ALTER TABLE `rule`
  MODIFY `ruleID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `studentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `teamID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `timeblock`
--
ALTER TABLE `timeblock`
  MODIFY `timeblockID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `tournamentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tournamentevent`
--
ALTER TABLE `tournamentevent`
  MODIFY `tournamenteventID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
