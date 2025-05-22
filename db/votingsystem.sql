-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 06:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `votingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `photo` varchar(150) NOT NULL,
  `created_on` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `firstname`, `lastname`, `photo`, `created_on`, `email`, `otp`, `otp_expiry`) VALUES
(1, 'admin', '$2y$10$eoDZ8wGMOvMB/l/jF8UKEeBv2Co97I5CqmkIu.sUQxisnpqVFZ8wm', 'Admin', 'Admin', 'facebook-profile-image.jpeg', '2018-04-02', '', '', NULL),
(2, 'ECI', '$2y$10$Yc0wR5IrJUUUK5e/DweF/eeMc8/zX20HadE1NWz1bDLUp7WI1BE2W', 'A. R. ', 'Bajaj', '302561114_627903855705524_1395675245697563561_n.jpg', '2025-03-16', 'j4033957@gmail.com', '', NULL),
(4, 'ECI', '$2y$10$ExuQr.JwM.sMwqM1akX1XuAbnVOsV6VOlh2svCAr5uR28.mkwZqgK', 'Smt. Droupadi ', 'Murmu', 'ECI.png', '2025-03-16', 'lmin21920@gmail.com', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `photo` varchar(150) NOT NULL,
  `platform` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `position_id`, `election_id`, `firstname`, `lastname`, `photo`, `platform`) VALUES
(40, 18, 8, 'Chetan', 'Dave', '67e5b11639645.jpg', 'Subject Thought by Java'),
(42, 19, 9, 'Sangeeta', 'Sharma', '67e5b180e103b.jpg', 'Subject Thought by I.c'),
(43, 18, 8, 'Samkit', 'Shah', '67e5bd07254e6.jpg', 'Subject Thought by Php'),
(44, 19, 9, 'Deepa', 'Mahnto', '67e5bd8f42f58.jpg', 'Subject Thought by Oops');

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `title`, `start_time`, `end_time`, `status`) VALUES
(9, 'Best professor in Female', '2025-03-28 01:00:00', '2025-03-28 16:00:00', 'active'),
(10, 'Best HOD', '2025-03-28 11:00:00', '2025-03-28 16:00:00', 'inactive'),
(11, 'Best CR', '2025-03-28 11:00:00', '2025-03-28 16:00:00', 'inactive'),
(12, 'Best Professor in Male', '2025-03-28 11:00:00', '2025-03-28 16:00:00', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `max_vote` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `election_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `description`, `max_vote`, `priority`, `election_id`) VALUES
(18, 'Who is Best professor in Male', 1, 1, 8),
(19, 'Who is Best professor in Female', 1, 2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE `voters` (
  `id` int(11) NOT NULL,
  `voters_id` varchar(15) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `photo` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`id`, `voters_id`, `password`, `firstname`, `lastname`, `photo`, `email`, `dob`, `otp`, `otp_expiry`) VALUES
(12, 'spP2ShnKQHLJx69', '$2y$10$I/FV1v1WCGAM5iRTOmZqCufAyWE2VGFG0JnB9azQnWzWODUIcxma2', 'Ayush', 'Dubey', 'Dubey.jpg', 'ayushdubey3406@gmail.com', '2001-01-30', NULL, NULL),
(14, '9K76U3cLGIJe1nY', '$2y$10$MEesqZW5TMzmovkYcnr8H.uZsBB0vPaChaQCP0WEOkcXEp25vm.5W', 'Marmik', 'variya', 'Mik.jpg', 'variyamarmik@gmail.com', '2003-08-01', NULL, NULL),
(17, 'HYUgR9Nvf6kaClt', '$2y$10$AXtxOTfs6IFZ2zjhzEOqT./x.lA1R7lKTSHi5D/XdRii0JVPmG.va', 'Niral', 'Patel', 'Niral.jpg', 'j4033957@gmail.com', '2004-02-15', NULL, NULL),
(18, 'j15FoTsquB97x3D', '$2y$10$DXBOT2wEQp/OlVA5RYz97Om7rG0NIwseDidWoo7L46XpvoL2qLsum', 'Vidhen', 'Patel', 'vidhen.jpg', 'variyamarmik@gmail.com', '2003-10-30', NULL, NULL),
(19, 'BUOYalnHZp835tK', '$2y$10$1lOrskl1hlZuO881j0bLBuNITjky7WMYFhAZ3P79sGUAJIoYIYdaG', 'Dishant', 'Patel', 'Dishu.jpg', 'variyamik2@gmail.com', '2005-01-01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `voters_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `voters_id`, `candidate_id`, `position_id`, `election_id`) VALUES
(32, 17, 39, 0, 8),
(33, 18, 39, 0, 8),
(34, 19, 40, 0, 8),
(35, 14, 39, 0, 8),
(36, 14, 41, 0, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voters`
--
ALTER TABLE `voters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `voters`
--
ALTER TABLE `voters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_election_status` ON SCHEDULE EVERY 1 MINUTE STARTS '2025-03-28 01:40:46' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE elections
    SET status = 
        CASE 
            WHEN NOW() BETWEEN start_time AND end_time THEN 'active'
            ELSE 'inactive'
        END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
