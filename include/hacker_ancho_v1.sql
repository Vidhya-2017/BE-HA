-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2020 at 02:09 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hacker_anchor`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessmentscale`
--

CREATE TABLE `assessmentscale` (
  `AssessmentId` int(11) NOT NULL,
  `AssementScaleName` varchar(100) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `assessmentscale`
--

INSERT INTO `assessmentscale` (`AssessmentId`, `AssementScaleName`, `isActive`) VALUES
(1, 'AssementScaleName 1', 1),
(2, 'AssementScaleName 2', 1),
(3, 'AssementScaleName 3', 1),
(4, 'AssementScaleName 4', 1),
(5, 'AssementScaleName 5', 1),
(6, 'AssementScaleName 6', 1),
(7, 'AssementScaleName 7', 1),
(8, 'AssementScaleName 8', 1),
(9, 'AssementScaleName 9', 1),
(10, 'AssementScaleName 10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `candidate_event`
--

CREATE TABLE `candidate_event` (
  `ID` int(11) NOT NULL,
  `CanidateID` int(11) NOT NULL,
  `EventID` int(11) NOT NULL,
  `isSelected` tinyint(1) NOT NULL DEFAULT 1,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedDate` datetime NOT NULL,
  `CreatedBy` int(11) NOT NULL,
  `UpdatedDate` datetime NOT NULL,
  `UpdatedBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidate_event`
--

INSERT INTO `candidate_event` (`ID`, `CanidateID`, `EventID`, `isSelected`, `isActive`, `CreatedDate`, `CreatedBy`, `UpdatedDate`, `UpdatedBy`) VALUES
(2, 1, 1, 1, 1, '2020-02-26 11:11:16', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `candidate_registration`
--

CREATE TABLE `candidate_registration` (
  `ID` int(11) NOT NULL,
  `EmpID` int(11) NOT NULL,
  `EmpName` varchar(100) NOT NULL,
  `Skills` int(11) NOT NULL,
  `StartDate` datetime NOT NULL,
  `ContactNo` bigint(20) NOT NULL,
  `Expereince` decimal(5,2) NOT NULL,
  `RelevantExperience` decimal(5,2) NOT NULL,
  `isSelected` tinyint(1) NOT NULL DEFAULT 1,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedDate` datetime NOT NULL,
  `CreatedBy` int(11) NOT NULL,
  `UpdatedDate` datetime NOT NULL,
  `UpdatedBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidate_registration`
--

INSERT INTO `candidate_registration` (`ID`, `EmpID`, `EmpName`, `Skills`, `StartDate`, `ContactNo`, `Expereince`, `RelevantExperience`, `isSelected`, `isActive`, `CreatedDate`, `CreatedBy`, `UpdatedDate`, `UpdatedBy`) VALUES
(1, 1223, 'Aswin', 1, '2020-02-26 11:10:23', 9952428940, '2.40', '2.00', 1, 1, '2020-02-26 11:10:23', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `ClientId` int(11) NOT NULL,
  `ClientName` varchar(100) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`ClientId`, `ClientName`, `isActive`) VALUES
(1, 'Client A', 1),
(2, 'Client B', 1),
(3, 'Client C', 1),
(4, 'Client D', 1),
(5, 'Client E', 1),
(6, 'Client F', 1),
(7, 'Client G', 1),
(8, 'Client H', 1),
(9, 'Client I', 1),
(10, 'Client J', 1);

-- --------------------------------------------------------

--
-- Table structure for table `duration`
--

CREATE TABLE `duration` (
  `DurationID` int(11) NOT NULL,
  `Duration` varchar(100) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `duration`
--

INSERT INTO `duration` (`DurationID`, `Duration`, `isActive`) VALUES
(1, 'Duration 1', 1),
(2, 'Duration 2', 1),
(3, 'Duration 3', 1),
(4, 'Duration 4', 1),
(5, 'Duration 5', 1),
(6, 'Duration 6', 1),
(7, 'Duration 7', 1),
(8, 'Duration 8', 1),
(9, 'Duration 9', 1),
(10, 'Duration 10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `register_event`
--

CREATE TABLE `register_event` (
  `EventID` int(11) NOT NULL,
  `EventName` varchar(100) DEFAULT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Duration` int(11) NOT NULL,
  `Skills` int(11) NOT NULL,
  `Client` int(11) NOT NULL,
  `AssessmentScale` int(11) NOT NULL,
  `ProblemSolvingSkillTested` tinyint(1) NOT NULL DEFAULT 1,
  `TechnicalSkilslsTested` tinyint(1) NOT NULL DEFAULT 1,
  `CommunicationSkillIsTested` tinyint(1) NOT NULL DEFAULT 1,
  `LogicalSkillsIsTested` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `register_event`
--

INSERT INTO `register_event` (`EventID`, `EventName`, `Date`, `Duration`, `Skills`, `Client`, `AssessmentScale`, `ProblemSolvingSkillTested`, `TechnicalSkilslsTested`, `CommunicationSkillIsTested`, `LogicalSkillsIsTested`) VALUES
(1, 'Hackathon', '2020-02-28 09:00:14', 1, 4, 2, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `SkillId` int(11) NOT NULL,
  `Skills` varchar(100) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`SkillId`, `Skills`, `isActive`) VALUES
(1, 'PHP', 1),
(2, 'VB.NET', 1),
(3, 'ASP.NET', 1),
(4, 'JAVA', 1),
(5, 'CSS', 1),
(6, 'JAVASCRIPT', 1),
(7, 'JQUERY', 1),
(8, 'HTML', 1),
(9, 'SQL', 1),
(10, 'MYSQL', 1),
(11, 'ORACLE', 1),
(12, 'NOSQL', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessmentscale`
--
ALTER TABLE `assessmentscale`
  ADD PRIMARY KEY (`AssessmentId`);

--
-- Indexes for table `candidate_event`
--
ALTER TABLE `candidate_event`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CanidateID` (`CanidateID`),
  ADD KEY `EventID` (`EventID`);

--
-- Indexes for table `candidate_registration`
--
ALTER TABLE `candidate_registration`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Skills` (`Skills`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`ClientId`);

--
-- Indexes for table `duration`
--
ALTER TABLE `duration`
  ADD PRIMARY KEY (`DurationID`);

--
-- Indexes for table `register_event`
--
ALTER TABLE `register_event`
  ADD PRIMARY KEY (`EventID`),
  ADD KEY `Duration` (`Duration`),
  ADD KEY `Skills` (`Skills`),
  ADD KEY `Client` (`Client`),
  ADD KEY `AssessmentScale` (`AssessmentScale`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`SkillId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessmentscale`
--
ALTER TABLE `assessmentscale`
  MODIFY `AssessmentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `candidate_event`
--
ALTER TABLE `candidate_event`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `candidate_registration`
--
ALTER TABLE `candidate_registration`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `ClientId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `duration`
--
ALTER TABLE `duration`
  MODIFY `DurationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `register_event`
--
ALTER TABLE `register_event`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `SkillId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidate_event`
--
ALTER TABLE `candidate_event`
  ADD CONSTRAINT `candidate_event_ibfk_1` FOREIGN KEY (`CanidateID`) REFERENCES `candidate_registration` (`ID`),
  ADD CONSTRAINT `candidate_event_ibfk_2` FOREIGN KEY (`EventID`) REFERENCES `register_event` (`EventID`);

--
-- Constraints for table `candidate_registration`
--
ALTER TABLE `candidate_registration`
  ADD CONSTRAINT `candidate_registration_ibfk_1` FOREIGN KEY (`Skills`) REFERENCES `skills` (`SkillId`);

--
-- Constraints for table `register_event`
--
ALTER TABLE `register_event`
  ADD CONSTRAINT `register_event_ibfk_1` FOREIGN KEY (`Duration`) REFERENCES `duration` (`DurationID`),
  ADD CONSTRAINT `register_event_ibfk_2` FOREIGN KEY (`Duration`) REFERENCES `duration` (`DurationID`),
  ADD CONSTRAINT `register_event_ibfk_3` FOREIGN KEY (`Skills`) REFERENCES `skills` (`SkillId`),
  ADD CONSTRAINT `register_event_ibfk_4` FOREIGN KEY (`Skills`) REFERENCES `client` (`ClientId`),
  ADD CONSTRAINT `register_event_ibfk_5` FOREIGN KEY (`Skills`) REFERENCES `client` (`ClientId`),
  ADD CONSTRAINT `register_event_ibfk_6` FOREIGN KEY (`Client`) REFERENCES `client` (`ClientId`),
  ADD CONSTRAINT `register_event_ibfk_7` FOREIGN KEY (`AssessmentScale`) REFERENCES `assessmentscale` (`AssessmentId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
