-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2024 at 02:52 PM
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
-- Database: `sis`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `postsID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `content` varchar(500) NOT NULL,
  `dateTime` varchar(99) NOT NULL,
  `privacy` varchar(99) NOT NULL,
  `isDelayed` varchar(99) NOT NULL,
  `attachment` varchar(99) NOT NULL,
  `cityID` int(11) NOT NULL,
  `provinceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postsID`, `userID`, `content`, `dateTime`, `privacy`, `isDelayed`, `attachment`, `cityID`, `provinceID`) VALUES
(1, 101, 'Just had a great day at the park!', '2024-11-01 14:30:00', 'Public', '0', '', 1, 2),
(2, 102, 'Excited for the upcoming concert!', '2024-11-01 15:00:00', 'Friends', '1', 'image1.jpg', 2, 2),
(3, 103, 'Just finished a new book, it was amazing!', '2024-11-01 16:00:00', 'Public', '0', '', 1, 3),
(4, 104, 'Can\'t wait for the weekend!', '2024-11-01 17:00:00', 'Friends', '0', 'video.mp4', 3, 1),
(5, 105, 'Dinner was fantastic tonight!', '2024-11-01 18:00:00', 'Private', '0', 'image2.jpg', 2, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
