-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2024 at 02:28 AM
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
-- Database: `dbcontacts`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblbooks`
--

CREATE TABLE `tblbooks` (
  `book_Id` int(11) NOT NULL,
  `book_title` varchar(50) NOT NULL,
  `book_subject` varchar(50) NOT NULL,
  `book_author` varchar(50) NOT NULL,
  `book_qty` int(11) NOT NULL,
  `book_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbooks`
--

INSERT INTO `tblbooks` (`book_Id`, `book_title`, `book_subject`, `book_author`, `book_qty`, `book_price`) VALUES
(1, 'Ang Kinabuhi ni Kide', 'Adventure', 'Kide Dimakakiyomaluya', 100, 69),
(2, 'Flutter for Idiots', 'Programming', 'Princess Mirah', 150, 100),
(3, 'Banyo Queen', 'Adventure', 'Andrewe T.', 50, 150),
(4, 'Ang Gugma Ni Anadyl', 'Love', 'Christian X', 30, 500),
(5, 'Nang Umutot Si Mopal', 'Stories', 'Sir Mopz', 400, 750);

-- --------------------------------------------------------

--
-- Table structure for table `tblcontacts`
--

CREATE TABLE `tblcontacts` (
  `Contact_ID` int(11) NOT NULL,
  `contact_userId` int(11) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_phone` int(15) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_address` varchar(100) NOT NULL,
  `contact_group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcontacts`
--

INSERT INTO `tblcontacts` (`Contact_ID`, `contact_userId`, `contact_name`, `contact_phone`, `contact_email`, `contact_address`, `contact_group`) VALUES
(1, 4, 'Gamay', 963513783, 'kaayo@gmail.com', 'Manila Zoo', 4),
(2, 2, 'makapoy kaayo', 9424486, 'kapoy@yatay.com', 'Bukid', 2),
(3, 3, 'Jin Woo', 864421, 'Koreaano@yati.com', 'Korea', 3),
(4, 1, 'Lu Kang', 56744, 'mk@google.com', 'Mars', 1),
(5, 1, 'Balo', 6578978, 'balo@gmail.com', 'cdo', 4),
(6, 10, 'jo', 6326, 'jg@gmail.com', 'cdo', 1),
(7, 1, 'koko', 97781, 'cocomartin@gmail.com', 'martin', 1),
(8, 1, 'Lebron', 677154, 'KingJames@gmail.com', 'USA', 1),
(9, 10, 'Binladin', 611, 'bombhasbeenplanted.com', 'Iraq', 10),
(10, 10, 'Vladimir', 6326678, 'Soviet.com', 'Russia', 10),
(11, 4, 'Harambe', 95423567, 'GorillaBig@banana.com', 'Usa Zoo', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tblgroups`
--

CREATE TABLE `tblgroups` (
  `grp_id` int(11) NOT NULL,
  `grp_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblgroups`
--

INSERT INTO `tblgroups` (`grp_id`, `grp_name`) VALUES
(1, 'Family'),
(2, 'Friends'),
(3, 'Classmates'),
(4, 'Cousins');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `usr_id` int(11) NOT NULL,
  `usr_username` varchar(30) NOT NULL,
  `usr_password` varchar(30) NOT NULL,
  `usr_fullname` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`usr_id`, `usr_username`, `usr_password`, `usr_fullname`) VALUES
(1, 'John', '12345', 'John Doe'),
(2, 'Nicholas', '54321', 'Nicholas Batum'),
(3, 'Justin', '12345', 'Justin Bieber'),
(4, 'qwe', '123', 'Qwerty Asdf'),
(5, 'zeke', '123', 'Zeke Johann'),
(6, 'elsa', 'elsa', 'elsa'),
(7, 'john', 'john', 'john'),
(8, 'jack', 'jack', 'jack'),
(9, '123', '123', 'John Miguel'),
(10, '111', '111', 'Osama Bin Laden');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblbooks`
--
ALTER TABLE `tblbooks`
  ADD PRIMARY KEY (`book_Id`);

--
-- Indexes for table `tblcontacts`
--
ALTER TABLE `tblcontacts`
  ADD PRIMARY KEY (`Contact_ID`);

--
-- Indexes for table `tblgroups`
--
ALTER TABLE `tblgroups`
  ADD PRIMARY KEY (`grp_id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`usr_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblbooks`
--
ALTER TABLE `tblbooks`
  MODIFY `book_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblcontacts`
--
ALTER TABLE `tblcontacts`
  MODIFY `Contact_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblgroups`
--
ALTER TABLE `tblgroups`
  MODIFY `grp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
