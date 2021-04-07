-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 07, 2021 at 01:05 AM
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
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `awardID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `date` date NOT NULL,
  `tournamentID` int(11) NOT NULL,
  `note` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event`, `type`) VALUES
('Anatomy and Physiology', 'Core Knowledge (Test Only)'),
('Astronomy', 'Core Knowledge (Test Only)'),
('Boomilever', 'Build'),
('Chemistry Lab', 'Hybrid Lab'),
('Circuit Lab', 'Hybrid Lab'),
('Codebusters', 'Core Knowledge (Test Only)'),
('Designer Genes', 'Core Knowledge (Test Only)'),
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
('Water Quality', 'Hybrid Lab'),
('Wright Stuff', 'Build'),
('Write It CAD It', 'Build'),
('Write It Do It', 'Build');

-- --------------------------------------------------------

--
-- Table structure for table `eventsyear`
--

CREATE TABLE `eventsyear` (
  `yearID` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `event` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventsyear`
--

INSERT INTO `eventsyear` (`yearID`, `year`, `event`) VALUES
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
(27, 2021, 'Write It CAD It');

-- --------------------------------------------------------

--
-- Table structure for table `eventtype`
--

CREATE TABLE `eventtype` (
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
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
-- Table structure for table `phonetype`
--

CREATE TABLE `phonetype` (
  `phoneType` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
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
-- Table structure for table `studentplacement`
--

CREATE TABLE `studentplacement` (
  `studentPlacementID` int(11) NOT NULL,
  `event` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tournament` int(11) NOT NULL,
  `place` int(11) NOT NULL,
  `partner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `studentID` int(11) NOT NULL,
  `last` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `first` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `yearGraduating` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `emailAlt` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phoneType` varchar(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'cell',
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
-- Dumping data for table `students`
--

INSERT INTO `students` (`studentID`, `last`, `first`, `yearGraduating`, `email`, `emailAlt`, `phoneType`, `phone`, `parent1Last`, `parent1First`, `parent1Email`, `parent1Phone`, `parent2Last`, `parent2First`, `parent2Email`, `parent2Phone`) VALUES
(1, 'Huang', 'Susanna', 2022, 'Susanna.lmt.196@gmail.com', NULL, 'cell', NULL, 'Huang', 'add', 'add@me.com', '770-555-5555', 'Huang', 'two', NULL, NULL),
(2, 'Melnikova', 'Tonya (Antonina)', 2021, 'tonya.melnik7@gmail.com', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Yan', 'Grace', 2021, 'graceyan61317@gmail.com', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Joshi', 'Chinmay', 2022, 'chinmayj.walton@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(5, 'Lee', 'Rebecca (Eunjae)', 2021, 'eunjaerebecca@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(6, 'Feren', 'Emily', 2021, 'emferen3@gmail.com', '', 'cell', '7705555555', '', '', '', '', '', '', '', ''),
(7, 'Peng', 'Cynthia', 2021, 'alcp6201@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(8, 'Rami', 'Rima', 2022, 'rimazazu@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(9, 'Shen', 'Grace', 2022, 'graceshen04@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(10, 'Wei', 'Banglue', 2021, 'banglueweiga@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(11, 'Lai', 'Sheena', 2021, 'sheenalai2012@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(12, 'Seigmund', 'Julian', 2023, 'juljs05@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(13, 'Huang', 'Faith', 2023, 'fyizhenh@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(14, 'Wang', 'Chris', 2022, 'goodchris0831@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(15, 'Yamin', 'Asad', 2022, 'yaminasad@gmail.com', '', '', '', '', '', '', '', '', '', '', ''),
(16, 'Mei', 'Andrew', 2023, 'andrewmei915@gmail.com', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

CREATE TABLE `tournament` (
  `tournamentID` int(11) NOT NULL,
  `tournamentInfo` int(11) NOT NULL,
  `dateTournament` date DEFAULT NULL,
  `dateRegistration` date DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `numberTeams` int(11) DEFAULT NULL,
  `waltonPlace` int(11) NOT NULL,
  `note` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tournamentinfo`
--

CREATE TABLE `tournamentinfo` (
  `tournamentInfoID` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `host` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `addressBilling` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `websiteHost` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `websiteSciOly` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `director` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `directorEmail` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateRegistration` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournamentinfo`
--

INSERT INTO `tournamentinfo` (`tournamentInfoID`, `name`, `host`, `address`, `addressBilling`, `websiteHost`, `websiteSciOly`, `director`, `directorEmail`, `dateRegistration`) VALUES
(1, 'BEARSO', 'Bay Area Invitational', 'San Francisco, CA (Remote)', NULL, NULL, 'https://scilympiad.com/bearso', 'Peter Hung', 'peterhung@socalscioly.org', '08/27'),
(2, 'SoFo', 'South Forsyth High School', 'South Forsyth, GA (Remote)', NULL, 'https://www.forsyth.k12.ga.us/Page/22519', 'https://scilympiad.com/sofo', 'Amy Chisam', 'achisam@gmail.com', '09/01'),
(3, 'Practice Tournament', 'National Science Olympiad', 'Remote', NULL, NULL, 'https://scilympiad.com/sopractice', NULL, NULL, '11/07'),
(4, 'UGA', 'Science Olympiad Outreach at UGA', 'Athens, GA (Remote)', NULL, 'https://www.ugascienceolympiad.net/', 'https://scilympiad.com/uga', 'Science Olympiad Outreach', 'scienceolympiad@uga.edu', '10/12'),
(5, 'SOLVI', 'Clark High School', 'Las Vegas, NV (Remote)', '4291 Pennwood Ave, Las Vegas, NV 89102', 'http://www.clarkscienceolympiad.com/solvi.html', 'https://scilympiad.com/nv-clark', NULL, 'clarkscioly@gmail.com', '09/10'),
(6, 'Aggie', 'UC Davis', 'Davis, CA (Remote)', NULL, 'https://sciolyatucdavis.wixsite.com/aggieinvitational', 'https://scilympiad.com/aggie', 'Chad Mowers and Claire Chapman', NULL, '09/18'),
(7, 'BISOT', 'Brookwood High School', '1255 DOGWOOD ROAD, SNELLVILLE, GEORGIA 30078', '1255 DOGWOOD ROAD, SNELLVILLE, GEORGIA 30078', 'http://brookwoodso.weebly.com/bisot.html', NULL, 'Chuck Thorton / Jon Erwin', NULL, '12/10'),
(8, 'MIT', 'Science Olympiad at MIT', 'Cambridge, MA (Remote)', NULL, 'https://scioly.mit.edu/', NULL, 'Science Olympiad at MIT', 'scioly@mit.edu', '09/12'),
(9, 'Harvard-Brown Tournament', 'HUSO and BUSO', 'Cambridge, MA (Remote)', NULL, NULL, 'https://www.sciolyharvard.org/divc', 'Harvard Undergraduate Science Olympiad ', 'sciolyharvard@gmail.com', NULL),
(10, 'PUSO', 'Princeton', 'Princeton, NJ (Remote)', NULL, 'https://scioly.princeton.edu/', NULL, NULL, 'scioly@princeton.edu', '10/01'),
(11, 'State Competition', 'Georgia State Science Olympiad', 'Emory University', NULL, NULL, NULL, 'Arneesh ', 'georgiascioly@gmail.com', NULL),
(12, 'Regional Competitino', 'Georgia State Science Olympiad', 'Varies', NULL, NULL, NULL, 'Arneesh ', 'georgiascioly@gmail.com', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`awardID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event`),
  ADD UNIQUE KEY `event` (`event`);

--
-- Indexes for table `eventsyear`
--
ALTER TABLE `eventsyear`
  ADD UNIQUE KEY `yearID` (`yearID`);

--
-- Indexes for table `eventtype`
--
ALTER TABLE `eventtype`
  ADD PRIMARY KEY (`type`);

--
-- Indexes for table `phonetype`
--
ALTER TABLE `phonetype`
  ADD UNIQUE KEY `phoneType` (`phoneType`);

--
-- Indexes for table `studentplacement`
--
ALTER TABLE `studentplacement`
  ADD PRIMARY KEY (`studentPlacementID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`studentID`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `awardID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eventsyear`
--
ALTER TABLE `eventsyear`
  MODIFY `yearID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `studentplacement`
--
ALTER TABLE `studentplacement`
  MODIFY `studentPlacementID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `tournamentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tournamentinfo`
--
ALTER TABLE `tournamentinfo`
  MODIFY `tournamentInfoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
