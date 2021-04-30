-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 30, 2021 at 03:22 AM
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
(8, 'Genetics', 'College');

-- --------------------------------------------------------

--
-- Table structure for table `coursecompleted`
--

CREATE TABLE `coursecompleted` (
  `myID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coursecompleted`
--

INSERT INTO `coursecompleted` (`myID`, `courseID`, `studentID`) VALUES
(7, 2, 6),
(8, 1, 6),
(10, 5, 6),
(12, 1, 1),
(13, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `courseenrolled`
--

CREATE TABLE `courseenrolled` (
  `myID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courseenrolled`
--

INSERT INTO `courseenrolled` (`myID`, `courseID`, `studentID`) VALUES
(3, 2, 6),
(10, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event`, `type`) VALUES
('Anatomy and Physiology', 'Core Knowledge (Test Only)'),
('Astronomy', 'Core Knowledge (Test Only)'),
('Boomilever', 'Build'),
('Chemistry Lab', 'Hybrid Lab'),
('Circuit Lab', 'Hybrid Lab'),
('Codebusters', 'Core Knowledge (Test Only)'),
('Designer Genes', 'Core Knowledge (Test Only)'),
('Detector Building', 'Hybrid Build'),
('Digital Structures', 'Build'),
('Disease Detectives', 'Core Knowledge (Test Only)'),
('Dynamic Planet', 'Core Knowledge (Test Only)'),
('Experimental Design', 'Laboratory or Hands On'),
('Forensics', 'Hybrid Lab'),
('Fossils', 'Core Knowledge (Test Only)'),
('GeoLogic Mapping', 'Core Knowledge (Test Only)'),
('Gravity Vehicle', 'Build'),
('Machines', 'Hybrid Build'),
('Ornithology', 'Core Knowledge (Test Only)'),
('Ping Pong Parachute', 'Build'),
('Protein Modeling', 'Hybrid Build'),
('Sounds of Music', 'Hybrid Build'),
('test', 'Hybrid Build'),
('Water Quality', 'Hybrid Lab'),
('Wright Stuff', 'Build'),
('Write It CAD It', 'Build'),
('Write It Do It', 'Build');

-- --------------------------------------------------------

--
-- Table structure for table `eventchoice`
--

CREATE TABLE `eventchoice` (
  `eventChoiceID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventchoice`
--

INSERT INTO `eventchoice` (`eventChoiceID`, `studentID`, `eventID`, `priority`) VALUES
(15, 6, 15, 1),
(16, 6, 16, 1),
(17, 6, 5, 1),
(19, 6, 2, 2),
(20, 6, 1, 2),
(21, 6, 3, 2),
(22, 6, 28, 1),
(23, 1, 1, 1),
(24, 1, 3, 1),
(25, 1, 18, 1);

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
  `eventID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `event` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventyear`
--

INSERT INTO `eventyear` (`eventID`, `year`, `event`) VALUES
(1, 2021, 'Anatomy and Physiology'),
(2, 2021, 'Astronomy'),
(3, 2021, 'Boomilever'),
(4, 2021, 'Chemistry Lab'),
(5, 2021, 'Circuit Lab'),
(6, 2021, 'Codebusters'),
(7, 2021, 'Designer Genes'),
(8, 2021, 'Digital Structures'),
(9, 2021, 'Disease Detectives'),
(10, 2021, 'Dynamic Planet'),
(11, 2021, 'Experimental Design'),
(12, 2021, 'Forensics'),
(13, 2021, 'Fossils'),
(14, 2021, 'GeoLogic Mapping'),
(15, 2021, 'Gravity Vehicle'),
(16, 2021, 'Machines'),
(17, 2021, 'Ornithology'),
(18, 2021, 'Ping Pong Parachute'),
(19, 2021, 'Protein Modeling'),
(20, 2021, 'Sounds of Music'),
(21, 2021, 'Water Quality'),
(22, 2021, 'Wright Stuff'),
(27, 2021, 'Write It CAD It'),
(28, 2022, 'Anatomy and Physiology'),
(29, 2021, 'Detector Building');

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
(1, 5, 2021, 'Captain');

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
  `level` int(3) NOT NULL,
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
(1, 0, '', 'Huang', 'Susanna', 1, 2022, 'Susanna.lmt.196@gmail.com', NULL, 'cell', NULL, 'Huang', 'add', 'add@me.com', '770-555-5555', 'Huang', 'two', NULL, NULL),
(2, 0, '', 'Melnikova', 'Tonya (Antonina)', 0, 2021, 'tonya.melnik7@gmail.com', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 0, '', 'Yan', 'Grace', 0, 2021, 'graceyan61317@gmail.com', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 0, '', 'Joshi', 'Chinmay', 1, 2022, 'chinmayj.walton@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(5, 0, '', 'Lee', 'Rebecca (Eunjae)', 0, 2021, 'eunjaerebecca@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(6, 0, '', 'Feren', 'Emily', 0, 2021, 'emferen3@gmail.com', '', 'cell', '7705555555', '', '', '', '', '', '', '', ''),
(7, 0, '', 'Peng', 'Cynthia', 1, 2021, 'alcp6201@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(8, 0, '', 'Rami', 'Rima', 1, 2022, 'rimazazu@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(9, 0, '', 'Shen', 'Grace', 1, 2022, 'graceshen04@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(10, 0, '', 'Wei', 'Banglue', 0, 2021, 'banglueweiga@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(11, 0, '', 'Lai', 'Sheena', 0, 2021, 'sheenalai2012@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(12, 0, '', 'Seigmund', 'Julian', 1, 2023, 'juljs05@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(13, 0, '', 'Huang', 'Faith', 1, 2023, 'fyizhenh@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(14, 0, '', 'Wang', 'Chris', 1, 2022, 'goodchris0831@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(15, 0, '', 'Yamin', 'Asad', 0, 2022, 'yaminasad@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(16, 0, '', 'Mei', 'Andrew', 0, 2023, 'andrewmei915@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(22, 9, '', 'Wolfe', 'Doug', 1, 2022, 'dougwolfejr@gmail.com', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, NULL, '', 'Last', 'Wolfe', 1, 2025, '', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, NULL, '', 'He', 'Jessica', 1, 2024, '', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `teamName` int(11) NOT NULL,
  `tournamentID` int(11) NOT NULL,
  `teamPlace` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeblock`
--

CREATE TABLE `timeblock` (
  `blockID` int(11) NOT NULL,
  `blockName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `timeStart` datetime NOT NULL,
  `timeEnd` datetime NOT NULL,
  `teamID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(6, 6, '2021-01-16', '2020-09-18', 2021, NULL, 1, 90, ''),
(7, 7, '2021-01-16', '2020-12-10', 2021, NULL, 2, 50, ''),
(8, 8, '2021-01-22', '2020-09-12', 2021, NULL, 1, 100, ''),
(9, 9, '2021-01-30', '2020-09-01', 2021, NULL, 1, 100, ''),
(10, 10, '2021-02-20', '2020-10-01', 2021, NULL, 1, 100, ''),
(11, 11, '2021-03-13', '2020-09-01', 2021, NULL, 3, 90, '');

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
(9, 'google', '109397293342063106702', 4, 'Doug', 'W', 'dougwolfejr@gmail.com', '', 'en', 'https://lh3.googleusercontent.com/a-/AOh14GhWinau5RYqIDwGfGGEBoOVdd7KGnEhpNtBLvw-=s96-c', '2021-04-25 00:28:24', '2021-04-30 01:11:15');

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
  ADD PRIMARY KEY (`myID`);

--
-- Indexes for table `courseenrolled`
--
ALTER TABLE `courseenrolled`
  ADD PRIMARY KEY (`myID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event`),
  ADD UNIQUE KEY `event` (`event`);

--
-- Indexes for table `eventchoice`
--
ALTER TABLE `eventchoice`
  ADD PRIMARY KEY (`eventChoiceID`);

--
-- Indexes for table `eventtype`
--
ALTER TABLE `eventtype`
  ADD PRIMARY KEY (`type`);

--
-- Indexes for table `eventyear`
--
ALTER TABLE `eventyear`
  ADD UNIQUE KEY `yearID` (`eventID`);

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
  ADD PRIMARY KEY (`blockID`);

--
-- Indexes for table `tournament`
--
ALTER TABLE `tournament`
  ADD UNIQUE KEY `tournamentID` (`tournamentID`);

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
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coursecompleted`
--
ALTER TABLE `coursecompleted`
  MODIFY `myID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `courseenrolled`
--
ALTER TABLE `courseenrolled`
  MODIFY `myID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `eventchoice`
--
ALTER TABLE `eventchoice`
  MODIFY `eventChoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `eventyear`
--
ALTER TABLE `eventyear`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `officer`
--
ALTER TABLE `officer`
  MODIFY `officerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rule`
--
ALTER TABLE `rule`
  MODIFY `ruleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `studentplacement`
--
ALTER TABLE `studentplacement`
  MODIFY `studentPlacementID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `teamID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timeblock`
--
ALTER TABLE `timeblock`
  MODIFY `blockID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `tournamentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tournamentinfo`
--
ALTER TABLE `tournamentinfo`
  MODIFY `tournamentInfoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
