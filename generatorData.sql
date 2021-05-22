-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 12, 2021 at 07:34 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id16238380_generatordb`
--

-- --------------------------------------------------------

--
-- Table structure for table `generatorData`
--

CREATE TABLE `generatorData` (
  `id` int(11) NOT NULL,
  `Run_Time` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Volt` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `GEN_status` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `m_start` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `config` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Start_but` tinyint(1) DEFAULT NULL,
  `timer_but` tinyint(1) DEFAULT NULL,
  `Timer_ON` time DEFAULT NULL,
  `Timer_OFF` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `generatorData`
--

INSERT INTO `generatorData` (`id`, `Run_Time`, `Volt`, `GEN_status`, `m_start`, `config`, `Start_but`, `timer_but`, `Timer_ON`, `Timer_OFF`) VALUES
(1, '210', '12', '3', '8.5', '1', 0, 0, '12:20:00', '12:25:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
