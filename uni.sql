-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 13, 2024 at 11:45 AM
-- Server version: 11.6.1-MariaDB-log
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uni`
--

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `cid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL,
  `did` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`cid`, `title`, `description`, `credits`, `did`) VALUES
(1, 'database', 'CENG 351', 3, 1),
(2, 'operating system', 'CENG 341', 3, 1),
(3, 'Introduction to Programming', 'CENG 101', 4, 1),
(4, 'introduction to electronic', 'EE 101', 2, 2),
(5, 'statistic', 'IE 301', 4, 4),
(6, 'circuit theory', 'EE 202', 3, 2),
(7, 'introduction to environment', 'ENV 102', 3, 3),
(8, 'operation research', 'IE 208', 3, 4),
(9, 'summer practice', 'IE 299', 2, 4),
(10, 'summer practice', 'ENV 299', 3, 3),
(11, 'summer practice', 'CENG 299', 3, 1),
(12, 'summer practice', 'EE 299', 3, 2),
(17, 'software Engneering', 'CENG 208', 6, 1),
(18, 'Anatomy', 'MED 101', 6, 5),
(19, 'İntrudection to low', 'Low 101', 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `did` int(11) NOT NULL,
  `dname` varchar(30) NOT NULL,
  `comments` varchar(50) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`did`, `dname`, `comments`, `email`) VALUES
(1, 'Comp. Eng.', 'Computer Eng. Department', 'ceng@fatih.edu.tr'),
(2, 'Elec. Eng.', 'Electronic Eng. Department', 'eee@fatih.edu.tr'),
(3, 'Env. Eng.', 'Environmental Eng. Department', 'env@fatih.edu.tr'),
(4, 'Ind. Eng.', 'Industrial Eng. Department', 'ie@fatih.edu.tr'),
(5, 'Med', 'Medicine Department', 'med@fatih.edu.tr'),
(6, 'Facutly of Low', 'Low Department', 'l@fatih.edu.tr');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `rid` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `did` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`rid`, `description`, `capacity`, `did`) VALUES
(1, 'E-502', 45, 1),
(2, 'F-416', 30, 2),
(3, 'B-224', 52, 3),
(4, 'A-312', 46, 4),
(5, 'A-356', 25, 1),
(6, 'B-114', 62, 2),
(7, 'E-217', 45, 3),
(8, 'F-319', 35, 4);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `cid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `dayOfWeek` char(1) NOT NULL,
  `hourOfDay` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`cid`, `rid`, `dayOfWeek`, `hourOfDay`) VALUES
(1, 8, 'M', '09'),
(2, 8, 'M', '13'),
(3, 7, 'T', '11'),
(4, 7, 'T', '15'),
(5, 6, 'T', '12'),
(6, 6, 'T', '13'),
(7, 5, 'W', '10'),
(8, 5, 'W', '13'),
(9, 4, 'H', '09'),
(10, 4, 'H', '14'),
(11, 2, 'F', '11'),
(12, 1, 'F', '15'),
(17, 1, 'T', '16'),
(18, 1, 'H', '16'),
(19, 1, 'M', '15'),
(19, 1, 'M', '16');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `sid` int(11) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birthplace` varchar(50) DEFAULT NULL,
  `did` int(11) DEFAULT NULL,
  `avatarId` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`sid`, `fname`, `lname`, `birthdate`, `birthplace`, `did`, `avatarId`) VALUES
(1, 'Mustafa', 'Eldikan', '1998-06-04', 'istanbul', 1, 'a9c45961-459b-4907-ba9a-95eb7397a572.png'),
(2, 'Ahmet', 'buyuk', '0000-00-00', 'ankara', 2, '8bc737aa-b1f5-482e-8883-453c25a3ce96.png'),
(3, 'Leyla', 'Sahin', '0000-00-00', 'izmir', 1, ''),
(4, 'Can', 'Turkoglu', '0000-00-00', 'manisa', 2, ''),
(5, 'Aziz', 'Keskin', '0000-00-00', 'istanbul', 2, ''),
(6, 'Talat', 'Sanli', '0000-00-00', 'izmir', 3, ''),
(7, 'Kamuran', 'Kece', '0000-00-00', 'adana', 3, ''),
(8, 'Turgut', 'Cemal', '0000-00-00', 'bursa', 4, ''),
(9, 'Oznur', 'Gunes', '0000-00-00', 'bolu', 2, ''),
(10, 'Pelin', 'Tugay', '0000-00-00', 'izmir', 4, ''),
(11, 'Savaş', 'Tan', '0000-00-00', 'izmir', 4, ''),
(15, 'Ali', 'Rıza', '2024-09-12', 'Mersin', 6, '0');

-- --------------------------------------------------------

--
-- Table structure for table `take`
--

CREATE TABLE `take` (
  `sid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `grade` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `take`
--

INSERT INTO `take` (`sid`, `cid`, `grade`) VALUES
(1, 3, 1),
(1, 17, NULL),
(2, 4, 4),
(2, 8, 4),
(3, 1, 4),
(3, 3, 4),
(3, 5, 4),
(4, 18, 2),
(5, 3, 1.5),
(5, 5, 1.5),
(5, 11, 3.5),
(6, 2, 4),
(7, 2, 3),
(7, 5, 1.5),
(7, 8, 1.5),
(8, 2, 3.5),
(8, 7, 1.5),
(10, 2, 4),
(10, 8, 3),
(11, 8, 1),
(15, 19, 3);

-- --------------------------------------------------------

--
-- Table structure for table `teach`
--

CREATE TABLE `teach` (
  `tid` int(11) NOT NULL,
  `cid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teach`
--

INSERT INTO `teach` (`tid`, `cid`) VALUES
(1, 1),
(1, 11),
(2, 3),
(3, 2),
(4, 4),
(4, 6),
(4, 12),
(5, 7),
(5, 10),
(6, 8),
(7, 5),
(7, 9),
(8, 13),
(8, 17),
(11, 18),
(12, 19);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `tid` int(11) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birthplace` varchar(50) DEFAULT NULL,
  `did` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`tid`, `fname`, `lname`, `birthdate`, `birthplace`, `did`) VALUES
(1, 'Selami', 'Durgun', '2024-08-08', 'amasyaa', 1),
(2, 'Cengiz', 'Tahir', '0000-00-00', 'istanbul', 1),
(3, 'Derya', 'Seckin', '0000-00-00', 'mersin', 1),
(4, 'Dogan', 'Gedikli', '0000-00-00', 'istanbul', 2),
(5, 'Ayten', 'Kahraman', '0000-00-00', 'istanbul', 3),
(6, 'Tahsin', 'Ugur', '0000-00-00', 'izmir', 4),
(7, 'Selcuk', 'Ozan', '0000-00-00', 'amasya', 4),
(8, 'Atakan', 'Kurt', '2024-09-04', 'samsung', 1),
(11, 'Nuri', 'Aydın', '2024-09-05', 'cerrahpaşa', 5),
(12, 'Mehmet ', 'Yavuz', '2024-09-11', 'Konya', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `reg_date`) VALUES
(8, 'mustafa', 'mustafa98dikan@gmail.com', '$2y$10$cGegAByfAF7b08DJVznTl.M5/hFc2om8JLjOGo8kvemUVt1.NqEYS', '2024-09-12 06:31:51'),
(9, 'atakan', 'atakanhoca@gmail.com', '$2y$10$OoWnP.JJ/uJOEGdElp5O/uuDWZLQc6e91XXlxiDFp9eYUTYMlBS96', '2024-09-12 12:05:42'),
(10, 'ali alturk', 'ceng@fatih.edu.tr', '$2y$10$6JWpmfeAZ2ADyg2KupNmzue5SBOFhGauOKB9ipZSu3vQAnjzBVdh2', '2024-09-12 12:08:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`did`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`cid`,`rid`,`dayOfWeek`,`hourOfDay`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `take`
--
ALTER TABLE `take`
  ADD PRIMARY KEY (`sid`,`cid`);

--
-- Indexes for table `teach`
--
ALTER TABLE `teach`
  ADD PRIMARY KEY (`tid`,`cid`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `did` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
