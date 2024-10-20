-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2024 at 06:00 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Database: `dbpost`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblposts`
--

CREATE TABLE `tblposts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `post_image` varchar(255) DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblposts`
--

INSERT INTO `tblposts` (`post_id`, `user_id`, `post_content`, `post_image`, `post_date`) VALUES
(1, 1, 'Hello, this is my first post!', 'image1.jpg', '2024-08-20 06:00:00'),
(2, 2, 'Love this new app!', NULL, '2024-08-20 06:01:00'),
(3, 3, 'Just uploaded a new picture.', 'image2.png', '2024-08-20 06:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `user_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`user_id`, `username`, `password`, `full_name`) VALUES
(1, 'john', '$2y$10$6rYb9yQG18Dpq1zOpQ1dOOC2x/pz2Rv2wGJts8lEB6pHfhsOf64zK', 'John Doe'),
(2, 'nic', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8vYwX8HRVq1aG6Y27M/6BGfU9Y.BI6', 'Nicholas Batum'),
(3, 'justin', '$2y$10$dBjJ5ZUv.cL2gRGeaX18B.nyJ0jzBclhb.bFYTGG0V0B9czMgTlW2', 'Justin Bieber');

-- --------------------------------------------------------

--
-- Table structure for table `tblreactions`
--

CREATE TABLE `tblreactions` (
  `reaction_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reaction_type` enum('love','care','like') NOT NULL,
  `reaction_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblreactions`
--

INSERT INTO `tblreactions` (`reaction_id`, `post_id`, `user_id`, `reaction_type`, `reaction_date`) VALUES
(1, 1, 2, 'love', '2024-08-20 06:05:00'),
(2, 2, 1, 'like', '2024-08-20 06:06:00'),
(3, 3, 3, 'care', '2024-08-20 06:07:00');

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblposts`
--
ALTER TABLE `tblposts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tblreactions`
--
ALTER TABLE `tblreactions`
  ADD PRIMARY KEY (`reaction_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblposts`
--
ALTER TABLE `tblposts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblreactions`
--
ALTER TABLE `tblreactions`
  MODIFY `reaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblposts`
--
ALTER TABLE `tblposts`
  ADD CONSTRAINT `tblposts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tblusers` (`user_id`);

--
-- Constraints for table `tblreactions`
--
ALTER TABLE `tblreactions`
  ADD CONSTRAINT `tblreactions_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `tblposts` (`post_id`),
  ADD CONSTRAINT `tblreactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tblusers` (`user_id`);
COMMIT;
