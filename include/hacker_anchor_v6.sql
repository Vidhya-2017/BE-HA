-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2020 at 04:05 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

-- --------------------------------------------------------

--
-- Table structure for table `candidate_registration`
--

CREATE TABLE `candidate_registration` (
  `ID` int(11) NOT NULL,
  `EmpID` int(11) NOT NULL,
  `EmpName` varchar(100) NOT NULL,
  `Skills` varchar(255) NOT NULL,
  `AdditionalSkills` varchar(250) NOT NULL,
  `EmailId` varchar(75) NOT NULL,
  `StartDate` datetime NOT NULL,
  `ContactNo` bigint(20) NOT NULL,
  `Expereince` decimal(5,2) NOT NULL,
  `RelevantExperience` decimal(5,2) NOT NULL,
  `isExternal` tinyint(1) NOT NULL DEFAULT 0,
  `isSelected` tinyint(1) NOT NULL DEFAULT 1,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedDate` datetime NOT NULL,
  `CreatedBy` int(11) NOT NULL,
  `UpdatedDate` datetime NOT NULL,
  `UpdatedBy` int(11) NOT NULL,
  `candidate_image` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `ClientId` int(11) NOT NULL,
  `ClientName` varchar(100) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `duration`
--

CREATE TABLE `duration` (
  `DurationID` int(11) NOT NULL,
  `Duration` varchar(100) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `event_organizer`
--

CREATE TABLE `event_organizer` (
  `organizer_autoid` int(11) NOT NULL,
  `organizer_empid` int(11) NOT NULL,
  `orgnizer_name` varchar(100) NOT NULL,
  `orgnizer_eventid` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `event_panel_list`
--

CREATE TABLE `event_panel_list` (
  `pl_id` int(11) NOT NULL,
  `pl_eventId` int(11) NOT NULL,
  `pl_clientId` int(11) NOT NULL,
  `pl_name` varchar(50) NOT NULL,
  `pl_contactno` varchar(11) NOT NULL,
  `pl_email` varchar(100) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `createdBy` varchar(50) NOT NULL,
  `createdDate` datetime NOT NULL,
  `updatedBy` varchar(50) NOT NULL,
  `updatedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `register_event`
--

CREATE TABLE `register_event` (
  `EventID` int(11) NOT NULL,
  `EventName` varchar(100) DEFAULT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `Duration` int(11) NOT NULL,
  `Skills` varchar(255) NOT NULL,
  `Client` int(11) NOT NULL,
  `AssessmentScale` varchar(255) DEFAULT NULL,
  `ProblemSolvingSkillTested` tinyint(1) NOT NULL DEFAULT 1,
  `TechnicalSkilslsTested` tinyint(1) NOT NULL DEFAULT 1,
  `CommunicationSkillIsTested` tinyint(1) NOT NULL DEFAULT 1,
  `LogicalSkillsIsTested` tinyint(1) NOT NULL DEFAULT 1,
  `isClosed` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `SkillId` int(11) NOT NULL,
  `Skills` varchar(100) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `squad`
--

CREATE TABLE `squad` (
  `ID` int(11) NOT NULL,
  `SquadName` varchar(100) NOT NULL,
  `EventID` int(11) NOT NULL,
  `isActive` tinyint(4) NOT NULL DEFAULT 1,
  `CreatedDate` datetime NOT NULL,
  `CreatedBy` int(11) NOT NULL,
  `UpdatedDate` datetime NOT NULL,
  `UpdatedBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `squad_candidates`
--

CREATE TABLE `squad_candidates` (
  `ID` int(11) NOT NULL,
  `SquadID` int(11) NOT NULL,
  `CandidateID` int(11) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedDate` datetime NOT NULL,
  `CreatedBy` int(11) NOT NULL,
  `UpdatedDate` datetime NOT NULL,
  `UpdatedBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `squad_feedback`
--

CREATE TABLE `squad_feedback` (
  `sid` int(11) NOT NULL,
  `squad_id` int(11) NOT NULL,
  `sprintLevel` varchar(255) NOT NULL,
  `panel_list_id` int(11) NOT NULL,
  `candidate_id` int(10) NOT NULL,
  `imagestr` longblob NOT NULL,
  `technical_skill` int(11) NOT NULL,
  `logical_skill` int(11) NOT NULL,
  `communication_skill` int(11) NOT NULL,
  `feedbackTxt` text NOT NULL,
  `isActive` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `createdBy` varchar(50) NOT NULL,
  `createdDate` datetime NOT NULL,
  `updatedBy` varchar(50) NOT NULL,
  `updatedDate` datetime NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  ADD PRIMARY KEY (`ID`);

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
-- Indexes for table `event_organizer`
--
ALTER TABLE `event_organizer`
  ADD PRIMARY KEY (`organizer_autoid`);

--
-- Indexes for table `event_panel_list`
--
ALTER TABLE `event_panel_list`
  ADD PRIMARY KEY (`pl_id`),
  ADD KEY `fk_pl_eventid` (`pl_eventId`),
  ADD KEY `fk_pl_clienid` (`pl_clientId`);

--
-- Indexes for table `register_event`
--
ALTER TABLE `register_event`
  ADD PRIMARY KEY (`EventID`),
  ADD KEY `Duration` (`Duration`),
  ADD KEY `Client` (`Client`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`SkillId`);

--
-- Indexes for table `squad`
--
ALTER TABLE `squad`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `EventID` (`EventID`);

--
-- Indexes for table `squad_candidates`
--
ALTER TABLE `squad_candidates`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `SquadID` (`SquadID`),
  ADD KEY `CandidateID` (`CandidateID`);

--
-- Indexes for table `squad_feedback`
--
ALTER TABLE `squad_feedback`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessmentscale`
--
ALTER TABLE `assessmentscale`
  MODIFY `AssessmentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_event`
--
ALTER TABLE `candidate_event`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_registration`
--
ALTER TABLE `candidate_registration`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `ClientId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `duration`
--
ALTER TABLE `duration`
  MODIFY `DurationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_organizer`
--
ALTER TABLE `event_organizer`
  MODIFY `organizer_autoid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_panel_list`
--
ALTER TABLE `event_panel_list`
  MODIFY `pl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `register_event`
--
ALTER TABLE `register_event`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `SkillId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `squad`
--
ALTER TABLE `squad`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `squad_candidates`
--
ALTER TABLE `squad_candidates`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `squad_feedback`
--
ALTER TABLE `squad_feedback`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `event_panel_list`
--
ALTER TABLE `event_panel_list`
  ADD CONSTRAINT `fk_pl_clienid` FOREIGN KEY (`pl_clientId`) REFERENCES `client` (`ClientId`),
  ADD CONSTRAINT `fk_pl_eventid` FOREIGN KEY (`pl_eventId`) REFERENCES `register_event` (`EventID`);

--
-- Constraints for table `register_event`
--
ALTER TABLE `register_event`
  ADD CONSTRAINT `register_event_ibfk_1` FOREIGN KEY (`Duration`) REFERENCES `duration` (`DurationID`),
  ADD CONSTRAINT `register_event_ibfk_2` FOREIGN KEY (`Duration`) REFERENCES `duration` (`DurationID`),
  ADD CONSTRAINT `register_event_ibfk_6` FOREIGN KEY (`Client`) REFERENCES `client` (`ClientId`);

--
-- Constraints for table `squad`
--
ALTER TABLE `squad`
  ADD CONSTRAINT `squad_ibfk_1` FOREIGN KEY (`EventID`) REFERENCES `register_event` (`EventID`);

--
-- Constraints for table `squad_candidates`
--
ALTER TABLE `squad_candidates`
  ADD CONSTRAINT `squad_candidates_ibfk_1` FOREIGN KEY (`SquadID`) REFERENCES `squad` (`ID`),
  ADD CONSTRAINT `squad_candidates_ibfk_2` FOREIGN KEY (`CandidateID`) REFERENCES `candidate_registration` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
