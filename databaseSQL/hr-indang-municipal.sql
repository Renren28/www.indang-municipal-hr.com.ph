-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2024 at 02:49 PM
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
-- Database: `hr-indang-municipal`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_departments`
--

CREATE TABLE `tbl_departments` (
  `department_id` int(10) NOT NULL,
  `departmentName` varchar(255) NOT NULL,
  `departmentDescription` varchar(500) NOT NULL,
  `departmentHead` varchar(255) NOT NULL,
  `archive` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_departments`
--

INSERT INTO `tbl_departments` (`department_id`, `departmentName`, `departmentDescription`, `departmentHead`, `archive`) VALUES
(1, 'Department of Human Resources Office', 'The Department of Human Resources Office serves as the administrative hub for managing personnel-related matters within an organization. It oversees recruitment, hiring, training, benefits administration, employee relations, and compliance with labor laws and regulations. Additionally, it plays a crucial role in promoting a positive work environment, fostering professional development, and ensuring fair and equitable treatment of all employees.', 'PRO001', ''),
(2, 'Municipal Office', ' A municipal office is the administrative hub of local government, responsible for managing public services, issuing permits, overseeing infrastructure projects, and fostering community engagement within a specific municipality.', 'PRO001', ''),
(3, 'Agricultural Office', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_designations`
--

CREATE TABLE `tbl_designations` (
  `designation_id` int(255) NOT NULL,
  `designationName` varchar(150) NOT NULL,
  `designationDescription` varchar(255) NOT NULL,
  `dateLastModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dateCreated` date NOT NULL,
  `archive` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_designations`
--

INSERT INTO `tbl_designations` (`designation_id`, `designationName`, `designationDescription`, `dateLastModified`, `dateCreated`, `archive`) VALUES
(1, 'HR Staff', 'HR staff oversee all aspects of personnel management, from recruitment to employee relations, ensuring a positive work environment.', '2024-05-27 11:35:05', '2024-05-27', ''),
(2, 'Clerk', 'A clerk manages administrative tasks like record-keeping and office organization, providing essential support for smooth operations.', '2024-05-28 02:36:38', '2024-05-28', ''),
(100, 'Testing', 'This is for the testing', '2024-05-31 10:16:03', '2024-05-31', ''),
(101, 'try', 'heloooo', '2024-05-31 13:59:42', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_educational_background`
--

CREATE TABLE `tbl_educational_background` (
  `education_id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `graduateStudies` varchar(100) NOT NULL,
  `elemschoolName` varchar(100) NOT NULL,
  `elembasicEducation` varchar(100) NOT NULL,
  `elemhighestLevel` varchar(100) NOT NULL,
  `elemYGraduated` int(11) NOT NULL,
  `elemScholarship` varchar(100) NOT NULL,
  `elemPeriod` date NOT NULL,
  `elemperiodEnd` date NOT NULL,
  `secondschoolName` varchar(100) NOT NULL,
  `secondbasicEducation` varchar(100) NOT NULL,
  `secondhighestLevel` varchar(100) NOT NULL,
  `secondYGraduated` int(11) NOT NULL,
  `secondScholarship` varchar(100) NOT NULL,
  `secondPeriod` date NOT NULL,
  `secondperiodEnd` date NOT NULL,
  `vocationalschoolName` varchar(100) NOT NULL,
  `vocationalbasicEducation` varchar(100) NOT NULL,
  `vocationalhighestLevel` varchar(100) NOT NULL,
  `vocationalYGraduated` int(11) NOT NULL,
  `vocationalScholarship` varchar(100) NOT NULL,
  `vocationalPeriod` date NOT NULL,
  `vocationalperiodEnd` date NOT NULL,
  `collegeschoolName` varchar(100) NOT NULL,
  `collegebasicEducation` varchar(100) NOT NULL,
  `collegehighestLevel` varchar(100) NOT NULL,
  `collegeYGraduated` int(11) NOT NULL,
  `collegeScholarship` varchar(100) NOT NULL,
  `collegePeriod` date NOT NULL,
  `collegeperiodEnd` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_educational_background`
--

INSERT INTO `tbl_educational_background` (`education_id`, `employee_id`, `graduateStudies`, `elemschoolName`, `elembasicEducation`, `elemhighestLevel`, `elemYGraduated`, `elemScholarship`, `elemPeriod`, `elemperiodEnd`, `secondschoolName`, `secondbasicEducation`, `secondhighestLevel`, `secondYGraduated`, `secondScholarship`, `secondPeriod`, `secondperiodEnd`, `vocationalschoolName`, `vocationalbasicEducation`, `vocationalhighestLevel`, `vocationalYGraduated`, `vocationalScholarship`, `vocationalPeriod`, `vocationalperiodEnd`, `collegeschoolName`, `collegebasicEducation`, `collegehighestLevel`, `collegeYGraduated`, `collegeScholarship`, `collegePeriod`, `collegeperiodEnd`) VALUES
(1, 'Regular', 'Top 1 Global Ling', 'Felipe Calderon Elementary School', 'Yi Sun Shin', 'Lancelot', 2011, 'Top 1 Mulawin Ling', '2005-08-22', '2011-08-22', 'Tanza National Comprehensive High School', 'Helcurt', 'Fanny', 2015, 'Top 1 Sanja Mayor Ling', '2011-08-22', '2015-08-22', 'Alternative Learning System', 'Bruno', 'Granger', 2016, 'Top 1 Tanza Ling', '2016-08-22', '2016-08-22', 'Cavite State University Main Campus', 'Layla', 'Miya', 2024, 'Top 1 Cavite Ling', '2019-04-25', '2024-08-22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_family_background`
--

CREATE TABLE `tbl_family_background` (
  `family_id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `spousesurname` varchar(100) NOT NULL,
  `spousename` varchar(100) NOT NULL,
  `spousemiddlename` varchar(100) NOT NULL,
  `spousenameExtension` varchar(100) NOT NULL,
  `spouseOccupation` varchar(100) NOT NULL,
  `spouseEmployer` varchar(100) NOT NULL,
  `spouseBusinessAddress` varchar(100) NOT NULL,
  `spouseTelephone` int(50) NOT NULL,
  `numberOfChildren` int(11) NOT NULL,
  `fathersSurname` varchar(100) NOT NULL,
  `fathersFirstname` varchar(100) NOT NULL,
  `fathersMiddlename` varchar(100) NOT NULL,
  `fathersnameExtension` varchar(100) NOT NULL,
  `MSurname` varchar(100) NOT NULL,
  `MName` varchar(100) NOT NULL,
  `MMName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_laterecordfile`
--

CREATE TABLE `tbl_laterecordfile` (
  `laterecordfile_id` int(255) NOT NULL,
  `monthYearOfRecord` varchar(255) NOT NULL,
  `fileOfRecord` varchar(255) NOT NULL,
  `lastDateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_laterecordfile`
--

INSERT INTO `tbl_laterecordfile` (`laterecordfile_id`, `monthYearOfRecord`, `fileOfRecord`, `lastDateModified`) VALUES
(14, 'January 2024', 'files/upload/laterecords/January 2024-2024-05-31-161812.csv', '2024-05-31 14:18:12'),
(15, 'May 2024', 'files/upload/laterecords/May 2024-2024-05-31-161922.csv', '2024-05-31 14:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leaveappform`
--

CREATE TABLE `tbl_leaveappform` (
  `leaveappform_id` varchar(100) NOT NULL,
  `dateLastModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `employee_id` varchar(100) NOT NULL,
  `departmentName` varchar(150) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `middleName` varchar(100) NOT NULL,
  `dateFiling` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `salary` varchar(100) NOT NULL,
  `typeOfLeave` varchar(100) NOT NULL,
  `typeOfSpecifiedOtherLeave` varchar(255) NOT NULL,
  `typeOfVacationLeave` varchar(100) NOT NULL,
  `typeOfVacationLeaveWithin` varchar(255) NOT NULL,
  `typeOfVacationLeaveAbroad` varchar(255) NOT NULL,
  `typeOfSickLeave` varchar(100) NOT NULL,
  `typeOfSickLeaveInHospital` varchar(255) NOT NULL,
  `typeOfSickLeaveOutPatient` varchar(255) NOT NULL,
  `typeOfSpecialLeaveForWomen` varchar(255) NOT NULL,
  `typeOfStudyLeave` varchar(100) NOT NULL,
  `typeOfOtherLeave` varchar(100) NOT NULL,
  `workingDays` int(50) NOT NULL,
  `inclusiveDateStart` date NOT NULL,
  `inclusiveDateEnd` date NOT NULL,
  `commutation` varchar(100) NOT NULL,
  `asOfDate` date NOT NULL,
  `vacationLeaveTotalEarned` decimal(10,4) NOT NULL,
  `sickLeaveTotalEarned` decimal(10,4) NOT NULL,
  `vacationLeaveLess` decimal(10,4) NOT NULL,
  `sickLeaveLess` decimal(10,4) NOT NULL,
  `vacationLeaveBalance` decimal(10,4) NOT NULL,
  `sickLeaveBalance` decimal(10,4) NOT NULL,
  `recommendation` varchar(100) NOT NULL,
  `recommendMessage` varchar(255) NOT NULL,
  `dayWithPay` int(50) NOT NULL,
  `dayWithoutPay` int(50) NOT NULL,
  `otherDayPay` int(50) NOT NULL,
  `otherDaySpecify` varchar(255) NOT NULL,
  `disapprovedMessage` varchar(255) NOT NULL,
  `hrName` varchar(255) NOT NULL,
  `hrPosition` varchar(100) NOT NULL,
  `deptHeadName` varchar(255) NOT NULL,
  `mayorName` varchar(255) NOT NULL,
  `mayorPosition` varchar(100) NOT NULL,
  `hrmanager_id` varchar(100) NOT NULL,
  `depthead_id` varchar(100) NOT NULL,
  `mayor_id` varchar(100) NOT NULL,
  `status` varchar(80) NOT NULL,
  `archive` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_leaveappform`
--

INSERT INTO `tbl_leaveappform` (`leaveappform_id`, `dateLastModified`, `dateCreated`, `employee_id`, `departmentName`, `lastName`, `firstName`, `middleName`, `dateFiling`, `position`, `salary`, `typeOfLeave`, `typeOfSpecifiedOtherLeave`, `typeOfVacationLeave`, `typeOfVacationLeaveWithin`, `typeOfVacationLeaveAbroad`, `typeOfSickLeave`, `typeOfSickLeaveInHospital`, `typeOfSickLeaveOutPatient`, `typeOfSpecialLeaveForWomen`, `typeOfStudyLeave`, `typeOfOtherLeave`, `workingDays`, `inclusiveDateStart`, `inclusiveDateEnd`, `commutation`, `asOfDate`, `vacationLeaveTotalEarned`, `sickLeaveTotalEarned`, `vacationLeaveLess`, `sickLeaveLess`, `vacationLeaveBalance`, `sickLeaveBalance`, `recommendation`, `recommendMessage`, `dayWithPay`, `dayWithoutPay`, `otherDayPay`, `otherDaySpecify`, `disapprovedMessage`, `hrName`, `hrPosition`, `deptHeadName`, `mayorName`, `mayorPosition`, `hrmanager_id`, `depthead_id`, `mayor_id`, `status`, `archive`) VALUES
('1a972aa5f124295f29198fb1675928239f53ee4b85b093563b', '2024-06-01 11:38:24', '2024-06-01 11:38:24', 'Regular', 'Municipal Office', 'Account', 'Regular', '', '2024-06-01', 'Clerk', '', 'Rehabilitation Privilege', '', '', '', '', '', '', ' ', ' ', '', '0', 1, '2024-06-01', '2024-06-01', 'Requested', '2024-03-28', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', '', 'Submitted', ''),
('236d45f6e2fccf0d887aa6fe78278545cb20bf4386501d6c32', '2024-05-31 08:26:08', '2024-05-31 08:26:08', 'Regular', 'Municipal Office', 'Account', 'Regular', '', '2024-05-31', 'Clerk', '', 'Vacation Leave', '', 'Within the Philippines', 'Boracay', '', '', '', ' ', ' ', '', '0', 4, '2024-05-31', '2024-06-03', 'Requested', '2024-03-28', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', '', 'Submitted', ''),
('34daeb51ae5198388dfea7b23c03e06e2beb219d2337a32391', '2024-06-01 11:06:17', '2024-06-01 11:06:17', 'Regular', 'Municipal Office', 'Account', 'Regular', '', '2024-06-01', 'Clerk', '', 'Vacation Leave', '', 'Within the Philippines', 'Boracay', '', '', '', ' ', ' ', '', '0', 3, '2024-06-06', '2024-06-08', 'Requested', '2024-03-28', 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', '', 'Submitted', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leavedataform`
--

CREATE TABLE `tbl_leavedataform` (
  `leavedataform_id` int(255) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `foreignKeyId` varchar(255) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `recordType` varchar(255) NOT NULL,
  `period` date NOT NULL,
  `periodEnd` date NOT NULL,
  `particular` varchar(255) NOT NULL,
  `particularLabel` varchar(255) NOT NULL,
  `days` int(255) NOT NULL,
  `hours` int(255) NOT NULL,
  `minutes` int(255) NOT NULL,
  `vacationLeaveEarned` decimal(10,4) NOT NULL,
  `vacationLeaveAbsUndWP` decimal(10,4) NOT NULL,
  `vacationLeaveBalance` decimal(10,4) NOT NULL,
  `vacationLeaveAbsUndWOP` decimal(10,4) NOT NULL,
  `sickLeaveEarned` decimal(10,4) NOT NULL,
  `sickLeaveAbsUndWP` decimal(10,4) NOT NULL,
  `sickLeaveBalance` decimal(10,4) NOT NULL,
  `sickLeaveAbsUndWOP` decimal(10,4) NOT NULL,
  `dateOfAction` date NOT NULL,
  `dateLastModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `leaveform_connectionId` varchar(100) NOT NULL,
  `archive` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_leavedataform`
--

INSERT INTO `tbl_leavedataform` (`leavedataform_id`, `employee_id`, `foreignKeyId`, `dateCreated`, `recordType`, `period`, `periodEnd`, `particular`, `particularLabel`, `days`, `hours`, `minutes`, `vacationLeaveEarned`, `vacationLeaveAbsUndWP`, `vacationLeaveBalance`, `vacationLeaveAbsUndWOP`, `sickLeaveEarned`, `sickLeaveAbsUndWP`, `sickLeaveBalance`, `sickLeaveAbsUndWOP`, `dateOfAction`, `dateLastModified`, `leaveform_connectionId`, `archive`) VALUES
(3, 'PRO001', '', '2024-05-28 03:30:45', 'Initial Record', '2023-12-13', '2024-05-28', 'Initial Record', '0', 0, 0, 0, 1.2500, 0.0000, 1.2500, 0.0000, 1.2500, 0.0000, 1.2500, 0.0000, '2024-05-28', '2024-05-29 01:49:10', '', ''),
(23, 'STAFF', '', '2024-05-30 09:07:52', 'Initial Record', '2023-03-17', '2024-04-26', 'Initial Record', '0', 0, 0, 0, 9.7500, 0.0000, 9.7500, 0.0000, 7.0000, 0.0000, 7.0000, 0.0000, '2024-05-30', '2024-05-30 09:08:05', '', ''),
(24, 'Regular', '', '2024-05-30 09:08:32', 'Initial Record', '2024-03-28', '2024-05-30', 'Initial Record', '', 0, 0, 0, 8.0000, 0.0000, 8.0000, 0.0000, 9.0000, 0.0000, 9.0000, 0.0000, '2024-05-30', '2024-05-30 09:08:32', '', ''),
(25, '20240530131', '', '2024-05-30 11:14:12', 'Initial Record', '2024-05-30', '2024-05-30', 'Initial Record', '', 0, 0, 0, 1.2500, 0.0000, 1.2500, 0.0000, 1.2500, 0.0000, 1.2500, 0.0000, '2024-05-30', '2024-05-30 11:14:12', '', ''),
(26, '20240530131', '', '2024-05-30 11:14:13', 'Break Monthly Record', '2024-05-30', '2024-05-30', 'Break Monthly Record', '', 0, 0, 0, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2024-05-30', '2024-05-30 11:14:13', '', ''),
(34, 'Regular', '', '2024-05-31 05:10:55', '', '2024-05-30', '2024-05-31', 'Late', '', 0, 0, 12, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2024-05-31', '2024-05-31 14:19:22', '', ''),
(35, 'STAFF', '', '2024-05-31 05:10:55', '', '2024-05-01', '2024-05-31', 'Late', '', 0, 0, 38, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2024-05-31', '2024-05-31 14:19:22', '', ''),
(36, 'FemaleRen', '', '2024-06-01 11:53:07', 'Initial Record', '2024-06-01', '2024-06-01', 'Initial Record', '', 0, 0, 0, 1.2500, 0.0000, 1.2500, 0.0000, 1.2500, 0.0000, 1.2500, 0.0000, '2024-06-01', '2024-06-01 11:53:07', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leaves`
--

CREATE TABLE `tbl_leaves` (
  `leave_id` int(255) NOT NULL,
  `leaveName` varchar(255) NOT NULL,
  `leaveDescription` varchar(255) NOT NULL,
  `leaveClass` varchar(255) NOT NULL,
  `leaveTotalAmount` decimal(10,4) NOT NULL,
  `leaveReset` varchar(25) NOT NULL,
  `dateLastModified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(255) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `empIdFrom` varchar(255) NOT NULL,
  `empIdTo` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `subjectKey` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `archive` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notification_id`, `dateCreated`, `empIdFrom`, `empIdTo`, `subject`, `message`, `subjectKey`, `link`, `status`, `archive`) VALUES
(12, '2024-05-31 08:26:08', 'Regular', '@Admin', 'Employee Leave Form Request', 'Account Regular is Applying For Vacation Leave', '236d45f6e2fccf0d887aa6fe78278545cb20bf4386501d6c32', '', 'unseen', ''),
(13, '2024-06-01 11:06:17', 'Regular', '@Admin', 'Employee Leave Form Request', 'Account Regular is Applying For Vacation Leave', '34daeb51ae5198388dfea7b23c03e06e2beb219d2337a32391', '', 'unseen', ''),
(14, '2024-06-01 11:38:24', 'Regular', '@Admin', 'Employee Leave Form Request', 'Account Regular is Applying For Rehabilitation Privilege', '1a972aa5f124295f29198fb1675928239f53ee4b85b093563b', '', 'unseen', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_passwordreset_tokens`
--

CREATE TABLE `tbl_passwordreset_tokens` (
  `token_id` int(255) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `employee_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `resetTokenHash` varchar(255) NOT NULL,
  `resetTokenExpiration` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_personal_info`
--

CREATE TABLE `tbl_personal_info` (
  `info_id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `birthplace` varchar(100) NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `bloodtype` varchar(50) NOT NULL,
  `gsis` int(50) NOT NULL,
  `pagibig` int(50) NOT NULL,
  `philhealth` int(50) NOT NULL,
  `sss` int(50) NOT NULL,
  `tin` int(50) NOT NULL,
  `agency` int(50) NOT NULL,
  `citizenship` varchar(100) NOT NULL,
  `houseNo` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `subdivision` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zipCode` int(11) NOT NULL,
  `telephone` int(50) NOT NULL,
  `mobile` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_personal_info`
--

INSERT INTO `tbl_personal_info` (`info_id`, `employee_id`, `birthplace`, `height`, `weight`, `bloodtype`, `gsis`, `pagibig`, `philhealth`, `sss`, `tin`, `agency`, `citizenship`, `houseNo`, `street`, `subdivision`, `city`, `province`, `zipCode`, `telephone`, `mobile`) VALUES
(1, 'Regular', 'Cavite', 10, 20, 'A', 123, 456, 789, 111, 222, 333, 'Filipino', '444', 'Street', 'Subdivision', 'Indang', 'Cavite', 4119, 555, '666');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_systemsettings`
--

CREATE TABLE `tbl_systemsettings` (
  `setting_id` int(255) NOT NULL,
  `settingType` varchar(255) NOT NULL,
  `settingSubject` varchar(255) NOT NULL,
  `settingKey` varchar(255) NOT NULL,
  `settingInCharge` varchar(255) NOT NULL,
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_systemsettings`
--

INSERT INTO `tbl_systemsettings` (`setting_id`, `settingType`, `settingSubject`, `settingKey`, `settingInCharge`, `dateModified`) VALUES
(1, 'Authorized User', 'Human Resources Manager', '201910776', '', '2024-04-03 01:44:33'),
(2, 'Authorized User', 'Municipal Mayor', '201915197', '', '2023-12-12 11:52:07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_useraccounts`
--

CREATE TABLE `tbl_useraccounts` (
  `account_id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photoURL` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `middleName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `suffix` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `sex` varchar(255) NOT NULL,
  `civilStatus` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `jobPosition` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `dateStarted` date NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `reasonForStatus` varchar(255) NOT NULL,
  `archive` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_useraccounts`
--

INSERT INTO `tbl_useraccounts` (`account_id`, `employee_id`, `role`, `email`, `password`, `photoURL`, `firstName`, `middleName`, `lastName`, `suffix`, `birthdate`, `sex`, `civilStatus`, `department`, `jobPosition`, `status`, `dateStarted`, `dateCreated`, `reasonForStatus`, `archive`) VALUES
(1, 'PRO001', 'Admin', 'admin@gmail.com', 'Password', '', 'Admin', '', 'Account', '', '2000-01-01', 'Male', 'Single', '1', '1', 'Active', '2020-01-01', '2024-05-30 09:07:02', '', ''),
(3, 'STAFF', 'Staff', 'staff@gmail.com', 'Password', '', 'Staff', '', 'Account', '', '2000-01-01', 'Male', 'Single', '1', '1', 'Active', '2023-03-17', '2024-05-28 02:39:37', '', ''),
(4, 'Regular', 'Employee', 'regular@gmail.com', 'Password', '', 'Regular', '', 'Account', '', '2001-06-01', 'Male', 'Single', '2', '2', 'Active', '2024-03-28', '2024-05-28 06:40:42', '', ''),
(7, '20240530131', 'Employee', 'alpha@gmail.com', 'Password', '', 'Alpha', '', 'Account', '', '2001-01-01', 'Female', 'Single', '2', '2', 'Active', '2024-05-30', '2024-05-30 11:14:12', '', 'deleted'),
(8, 'FemaleRen', 'Employee', 'jeshuabay@gmail.com', 'Password', '', 'Rena', '', 'Antomae', '', '2001-01-01', 'Female', 'Single', '1', '2', 'Active', '2024-06-01', '2024-06-01 11:53:07', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_departments`
--
ALTER TABLE `tbl_departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `departmentName` (`departmentName`);

--
-- Indexes for table `tbl_designations`
--
ALTER TABLE `tbl_designations`
  ADD PRIMARY KEY (`designation_id`),
  ADD UNIQUE KEY `designationName` (`designationName`);

--
-- Indexes for table `tbl_educational_background`
--
ALTER TABLE `tbl_educational_background`
  ADD PRIMARY KEY (`education_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- Indexes for table `tbl_family_background`
--
ALTER TABLE `tbl_family_background`
  ADD PRIMARY KEY (`family_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- Indexes for table `tbl_laterecordfile`
--
ALTER TABLE `tbl_laterecordfile`
  ADD PRIMARY KEY (`laterecordfile_id`),
  ADD UNIQUE KEY `monthYearOfRecord` (`monthYearOfRecord`);

--
-- Indexes for table `tbl_leaveappform`
--
ALTER TABLE `tbl_leaveappform`
  ADD PRIMARY KEY (`leaveappform_id`);

--
-- Indexes for table `tbl_leavedataform`
--
ALTER TABLE `tbl_leavedataform`
  ADD PRIMARY KEY (`leavedataform_id`);

--
-- Indexes for table `tbl_leaves`
--
ALTER TABLE `tbl_leaves`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `tbl_passwordreset_tokens`
--
ALTER TABLE `tbl_passwordreset_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `resetTokenHash` (`resetTokenHash`);

--
-- Indexes for table `tbl_personal_info`
--
ALTER TABLE `tbl_personal_info`
  ADD PRIMARY KEY (`info_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- Indexes for table `tbl_systemsettings`
--
ALTER TABLE `tbl_systemsettings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `tbl_useraccounts`
--
ALTER TABLE `tbl_useraccounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_departments`
--
ALTER TABLE `tbl_departments`
  MODIFY `department_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_designations`
--
ALTER TABLE `tbl_designations`
  MODIFY `designation_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `tbl_educational_background`
--
ALTER TABLE `tbl_educational_background`
  MODIFY `education_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_family_background`
--
ALTER TABLE `tbl_family_background`
  MODIFY `family_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_laterecordfile`
--
ALTER TABLE `tbl_laterecordfile`
  MODIFY `laterecordfile_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_leavedataform`
--
ALTER TABLE `tbl_leavedataform`
  MODIFY `leavedataform_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_leaves`
--
ALTER TABLE `tbl_leaves`
  MODIFY `leave_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_passwordreset_tokens`
--
ALTER TABLE `tbl_passwordreset_tokens`
  MODIFY `token_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_personal_info`
--
ALTER TABLE `tbl_personal_info`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_systemsettings`
--
ALTER TABLE `tbl_systemsettings`
  MODIFY `setting_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_useraccounts`
--
ALTER TABLE `tbl_useraccounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
