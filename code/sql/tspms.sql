-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2015 at 10:04 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tspms`
--

CREATE DATABASE IF NOT EXISTS tspms;
USE tspms;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `password_hash` varchar(200) NOT NULL,
  `hp_number` varchar(20) NOT NULL,
  `other_number` varchar(20) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `last_updated` timestamp NOT NULL,
  `username` varchar(50) NOT NULL,
  PRIMARY KEY (`c_id`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`c_id`, `title`, `first_name`, `last_name`, `company_name`, `password_hash`, `hp_number`, `other_number`, `email`, `is_active`, `last_updated`, `username`) VALUES
(1, 'Mr', 'Lu', 'Ning', 'SMU', '!@#', '83572238', '7839274', 'ninglu.2013@sis.smu.edu.sg', 1, '0000-00-00 00:00:00', ''),
(2, 'Mr', 'Alex', 'Lu', 'ABC', '!@#', '7687686', '8790789', 'ninglu.2013@smu.edu.sg', 1, '0000-00-00 00:00:00', ''),
(3, '', 'TT', 'Wang', 'SMU', '', '12345678', '87654321', '123@smu.edu.sg', 1, '0000-00-00 00:00:00', ''),
(4, '', 'TT', 'Wang', 'SMU', '', '12345678', '87654321', '123@smu.edu.sg', 1, '0000-00-00 00:00:00', ''),
(5, '', 'TT', 'W', 'SIS', '', '123', '123', 'AS', 1, '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_project`
--

CREATE TABLE IF NOT EXISTS `dev_project` (
  `project_id` int(11) NOT NULL,
  `dev_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL,
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `unique_project_id` (`project_id`),
  KEY `dev_id` (`dev_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `file_name` varchar(50) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `hash` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`file_id`),
  UNIQUE KEY `unique_file_id` (`file_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `internal_user`
--

CREATE TABLE IF NOT EXISTS `internal_user` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(200) DEFAULT NULL,
  `bb_username` varchar(200) DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `last_updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `internal_user`
--

INSERT INTO `internal_user` (`u_id`, `name`, `username`, `password_hash`, `bb_username`, `type`, `is_active`, `last_updated`) VALUES
(1, 'luning', 'ninglu1994', 'sfd', 'luning1994@gmail.com', 1, 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `issue`
--

CREATE TABLE IF NOT EXISTS `issue` (
  `issue_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `reference_post_id` int(11) NOT NULL,
  `assigned_by` varchar(200) NOT NULL,
  `bb_issue_id` int(11) NOT NULL,
  `bb_status` varchar(50) NOT NULL,
  `bb_priority` varchar(50) NOT NULL,
  `bb_title` varchar(200) NOT NULL,
  `bb_assignee` varchar(200) NOT NULL,
  `milestone_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL,
  PRIMARY KEY (`issue_id`),
  UNIQUE KEY `unique_issue_id` (`issue_id`),
  KEY `post_id` (`post_id`),
  KEY `milestone_id` (`milestone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_assignment`
--

CREATE TABLE IF NOT EXISTS `issue_assignment` (
  `issue_id` int(11) NOT NULL,
  `assignee` int(11) NOT NULL,
  PRIMARY KEY (`issue_id`,`assignee`),
  KEY `assignee` (`assignee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `pm_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `direction` varchar(200) NOT NULL,
  `head` varchar(200) NOT NULL,
  `body` text,
  `file_id` int(11) NOT NULL,
  `time_read` timestamp NULL DEFAULT NULL,
  `time_created` timestamp NOT NULL,
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `unique_message_id` (`message_id`),
  KEY `customer_id` (`customer_id`),
  KEY `pm_id` (`pm_id`),
  KEY `project_id` (`project_id`),
  KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `milestone`
--

CREATE TABLE IF NOT EXISTS `milestone` (
  `milestone_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `deadline` timestamp NOT NULL,
  `if_missed` tinyint(4) NOT NULL,
  PRIMARY KEY (`milestone_id`),
  UNIQUE KEY `unique_milestone_id` (`milestone_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_id` int(11) NOT NULL,
  `assignee_id` int(11) NOT NULL,
  `date_browsed` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`notification_id`),
  UNIQUE KEY `unique_notification_id` (`notification_id`),
  KEY `issue_id` (`issue_id`),
  KEY `assignee_id` (`assignee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `phase`
--

CREATE TABLE IF NOT EXISTS `phase` (
  `phase_id` int(11) NOT NULL,
  `phase_name` varchar(50) NOT NULL,
  PRIMARY KEY (`phase_id`),
  UNIQUE KEY `unique_phase_id` (`phase_id`),
  UNIQUE KEY `unique_phase_name` (`phase_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phase`
--

INSERT INTO `phase`(`phase_id`, `phase_name`) VALUES (1,'Lead'),(2,'Requirement'),(3,'Build'),(4,'Testing'),(5,'Deploy');
-- --------------------------------------------------------

--
-- Table structure for table `pm_project`
--

CREATE TABLE IF NOT EXISTS `pm_project` (
  `project_id` int(11) NOT NULL,
  `pm_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL,
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `unique_project_id` (`project_id`),
  KEY `pm_id` (`pm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_phase_id` int(11) NOT NULL,
  `header` varchar(225) NOT NULL,
  `body` text,
  `datetime_created` timestamp NOT NULL,
  `type` varchar(50) NOT NULL,
  `last_updated` timestamp NOT NULL,
  PRIMARY KEY (`post_id`),
  UNIQUE KEY `unique_post_id` (`post_id`),
  KEY `project_phase_id` (`project_phase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_title` varchar(100) NOT NULL,
  `project_description` varchar(100) DEFAULT NULL,
  `tags` varchar(225) DEFAULT NULL,
  `remarks` varchar(225) DEFAULT NULL,
  `start_time` timestamp NOT NULL,
  `current_project_phase_id` int(10) NOT NULL,
  `file_repo_name` varchar(225) DEFAULT NULL,
  `no_of_use_cases` int(10) DEFAULT NULL,
  `bitbucket_repo_name` varchar(225) DEFAULT NULL,
  `project_value` varchar(225) DEFAULT NULL,
  `last_updated` timestamp NOT NULL,
  `is_ongoing` tinyint(4) NOT NULL DEFAULT '1',
  `c_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_phase`
--

CREATE TABLE IF NOT EXISTS `project_phase` (
  `project_phase_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `phase_id` int(11) NOT NULL,
  `start_time` timestamp,
  `end_time` timestamp,
  `estimated_end_time` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NOT NULL,
  PRIMARY KEY (`project_phase_id`),
  UNIQUE KEY `unique_project_phase_id` (`project_phase_id`),
  KEY `project_id` (`project_id`),
  KEY `phase_id` (`phase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `reference_post_id` int(11) DEFAULT NULL,
  `targeted_start_datetime` timestamp NOT NULL,
  `targeted_end_datetime` timestamp NOT NULL,
  `start_datetime` timestamp NOT NULL,
  `end_datetime` timestamp NOT NULL,
  `last_updated_to_bb` timestamp NOT NULL,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `unique_task_id` (`task_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `update`
--

CREATE TABLE IF NOT EXISTS `update` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `posted_by` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`update_id`),
  UNIQUE KEY `unique_update_id` (`update_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE IF NOT EXISTS `userlog` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL,
  `action` varchar(225) NOT NULL,
  `timestamp` timestamp NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dev_project`
--
ALTER TABLE `dev_project`
  ADD CONSTRAINT `dev_project_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`),
  ADD CONSTRAINT `dev_project_ibfk_2` FOREIGN KEY (`dev_id`) REFERENCES `internal_user` (`u_id`);

--
-- Constraints for table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `issue`
--
ALTER TABLE `issue`
  ADD CONSTRAINT `issue_ibfk_2` FOREIGN KEY (`milestone_id`) REFERENCES `milestone` (`milestone_id`),
  ADD CONSTRAINT `issue_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`);

--
-- Constraints for table `issue_assignment`
--
ALTER TABLE `issue_assignment`
  ADD CONSTRAINT `issue_assignment_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `issue` (`issue_id`),
  ADD CONSTRAINT `issue_assignment_ibfk_2` FOREIGN KEY (`assignee`) REFERENCES `internal_user` (`u_id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_4` FOREIGN KEY (`file_id`) REFERENCES `file` (`file_id`),
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`c_id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`pm_id`) REFERENCES `internal_user` (`u_id`),
  ADD CONSTRAINT `message_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `milestone`
--
ALTER TABLE `milestone`
  ADD CONSTRAINT `milestone_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`assignee_id`) REFERENCES `internal_user` (`u_id`),
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `issue` (`issue_id`);

--
-- Constraints for table `pm_project`
--
ALTER TABLE `pm_project`
  ADD CONSTRAINT `pm_project_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`),
  ADD CONSTRAINT `pm_project_ibfk_2` FOREIGN KEY (`pm_id`) REFERENCES `internal_user` (`u_id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`project_phase_id`) REFERENCES `project_phase` (`project_phase_id`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `customer` (`c_id`);

--
-- Constraints for table `project_phase`
--
ALTER TABLE `project_phase`
  ADD CONSTRAINT `project_phase_ibfk_2` FOREIGN KEY (`phase_id`) REFERENCES `phase` (`phase_id`),
  ADD CONSTRAINT `project_phase_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`);

--
-- Constraints for table `update`
--
ALTER TABLE `update`
  ADD CONSTRAINT `update_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`);

--
-- Constraints for table `userlog`
--
ALTER TABLE `userlog`
  ADD CONSTRAINT `userlog_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `internal_user` (`u_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
