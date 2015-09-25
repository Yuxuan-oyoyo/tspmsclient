-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2015 at 07:40 AM
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
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`c_id`, `title`, `first_name`, `last_name`, `company_name`, `password_hash`, `hp_number`, `other_number`, `email`, `is_active`, `last_updated`, `username`) VALUES
(1, 'Mr', 'Lu', 'Ning', 'SMU', '!@#', '83572238', '7839274', 'ninglu.2013@sis.smu.edu.sg', 1, '0000-00-00 00:00:00', ''),
(2, 'Mr', 'Alex', 'Lu', 'ABC', '!@#', '7687686', '8790789', 'ninglu.2013@smu.edu.sg', 1, '0000-00-00 00:00:00', ''),
(3, '', '', '', '', '', '', '', '', 1, '0000-00-00 00:00:00', ''),
(4, '', '', '', '', '', '', '', '', 1, '0000-00-00 00:00:00', ''),
(5, '', '', '', '', '', '', '', '', 1, '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `internal_user`
--

CREATE TABLE IF NOT EXISTS `internal_user` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass_hash` varchar(200) NOT NULL,
  `bb_username` varchar(200) DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `internal_user`
--

INSERT INTO `internal_user` (`u_id`, `name`, `username`, `pass_hash`, `bb_username`, `type`, `is_active`) VALUES
(1, 'luning', 'ninglu1994', 'sfd', 'luning1994@gmail.com', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
