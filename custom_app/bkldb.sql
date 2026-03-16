-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2026 at 03:10 PM
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
-- Database: `bkldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `BookID` int(11) NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Author` varchar(255) DEFAULT NULL,
  `Publisher` varchar(255) DEFAULT NULL,
  `Pub_year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`BookID`, `Title`, `Author`, `Publisher`, `Pub_year`) VALUES
(1, 'The Lord of the Rings', 'J.R.R. Tolkien', 'HarperCollins Publishers', 1954),
(2, 'A Tale of Two Cities', 'Charles Dickens', 'Chapman and Hall', 1859),
(3, 'Think and Grow Rich', 'Napoleon Hill', 'Sound Wisdom', 1937),
(12, 'Dream of the Red Chamber', 'Cao Xueqin', 'Anchor', 1958),
(13, 'The Hobbit', 'J.R.R. Tolkien', 'HarperCollins Children\'s Books', 1998),
(14, 'The Alchemist', 'Paulo Coelho', 'HarperCollins', 1993),
(15, 'Watership Down', 'Richard Adams', 'Avon Books', 1975),
(16, 'The Tale of Peter Rabbit', 'Beatrix Potter', 'Warne', 2002),
(17, 'Harry Potter and the Deathly Hallows', 'J.K. Rowling', 'Arthur A. Levine Books', 2007),
(18, 'Jonathan Livingston Seagull', 'Richard Bach', 'Scribner', 2006),
(19, 'Sophie’s World', 'Jostein Gaarder', 'Phoenix', 1995),
(20, 'War and Peace', 'Leo Tolstoy', 'Oxford University Press', 1998),
(21, 'Pinocchio', 'Carlo Collodi', 'Puffin Books', 1996);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`BookID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `BookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
