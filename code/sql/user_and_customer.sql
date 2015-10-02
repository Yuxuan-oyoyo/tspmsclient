
-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2015 at 07:40 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";


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
CREATE TABLE customer
(
    c_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(20) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    company_name VARCHAR(200) NOT NULL,
    password_hash VARCHAR(200) NOT NULL,
    hp_number VARCHAR(20) NOT NULL,
    other_number VARCHAR(20),
    email VARCHAR(200) NOT NULL,
    is_active TINYINT DEFAULT 1 NOT NULL,
    last_updated TIMESTAMP NOT NULL,
    username VARCHAR(50) NOT NULL
);
CREATE TABLE internal_user
(
    u_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(200),
    bb_username VARCHAR(200),
    type TINYINT NOT NULL,
    is_active TINYINT DEFAULT 1 NOT NULL,
    last_updated TIMESTAMP NOT NULL
);
