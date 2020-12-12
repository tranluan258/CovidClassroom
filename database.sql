-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2020 at 07:52 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--
CREATE DATABASE IF NOT EXISTS `database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `database`;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `yourname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `birthday` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `actived` int(255) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `active_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`, `yourname`, `birthday`, `email`, `phone`, `actived`, `type`, `active_token`) VALUES
('admin', '$2y$10$jbfmq4W8Xz4tPT5CqTso2uIMsrmpWJ8eLCc5.DlDz4.hV2Hhkp2ry', 'Admin', '01/01/2000', 'covidclassroom212@gmail.com', '01', 1, 2, ''),
('linhkook', '$2y$10$bQ6THY8MaqdzUbaIcyFV4epo4Ztv5SWr8pQsxlkyv0HMKN4Kl.TbW', 'Lê Quang Linh', '1/1/2000', 'doanvanphuc91@gmail.com', '0367890432', 1, 0, ''),
('tranluan187', '$2y$10$XmoUqwSrHY2XQY187gexCeXZU1d2QdV9Su.JUd/uPmU2D10vuM8LK', 'Trần Luân', '20/10/2000', 'tranluanqqq@gmail.com', '01268953654', 1, 1, ''),
('trinhhieu2908', '$2y$10$i1714Mx2jhknq3U19bkRw.zTYY6GoZFpxDoBYAtyV0X/YaifJelTS', 'Trịnh Hiếu', '29/08/2000', 'hieu01217@gmail.com', '0949961157', 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `class` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `information` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `datepost` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notice`
--

INSERT INTO `notice` (`id`, `class`, `username`, `information`, `link`, `datepost`) VALUES
(57, 'LAIpyAFI', 'tranluan187', 'a', NULL, '2020-12-02');

-- --------------------------------------------------------

--
-- Table structure for table `notice_comment`
--

CREATE TABLE `notice_comment` (
  `id` int(11) NOT NULL,
  `idnotice` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `code` varchar(255) NOT NULL,
  `teacher` varchar(255) NOT NULL,
  `classname` varchar(255) NOT NULL,
  `subjectname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `room` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`code`, `teacher`, `classname`, `subjectname`, `room`) VALUES
('LAIpyAFI', 'tranluan187', '123', 'monhoc', 'A123'),
('UqoSVrkA', 'linhkook', 'hoc hanh', 'monhoc', 'A123');

-- --------------------------------------------------------

--
-- Table structure for table `subject_info`
--

CREATE TABLE `subject_info` (
  `code` varchar(255) NOT NULL,
  `student` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class` (`class`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `notice_comment`
--
ALTER TABLE `notice_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `idnotice` (`idnotice`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`code`),
  ADD KEY `teacher` (`teacher`);

--
-- Indexes for table `subject_info`
--
ALTER TABLE `subject_info`
  ADD PRIMARY KEY (`code`,`student`),
  ADD KEY `code` (`code`),
  ADD KEY `student` (`student`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `notice_comment`
--
ALTER TABLE `notice_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notice`
--
ALTER TABLE `notice`
  ADD CONSTRAINT `notice_ibfk_1` FOREIGN KEY (`class`) REFERENCES `subject` (`code`),
  ADD CONSTRAINT `notice_ibfk_2` FOREIGN KEY (`username`) REFERENCES `account` (`username`);

--
-- Constraints for table `notice_comment`
--
ALTER TABLE `notice_comment`
  ADD CONSTRAINT `notice_comment_ibfk_1` FOREIGN KEY (`username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `notice_comment_ibfk_2` FOREIGN KEY (`idnotice`) REFERENCES `notice` (`id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`teacher`) REFERENCES `account` (`username`);

--
-- Constraints for table `subject_info`
--
ALTER TABLE `subject_info`
  ADD CONSTRAINT `subject_info_ibfk_1` FOREIGN KEY (`code`) REFERENCES `subject` (`code`),
  ADD CONSTRAINT `subject_info_ibfk_2` FOREIGN KEY (`student`) REFERENCES `account` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
