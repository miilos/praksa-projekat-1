-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 14, 2025 at 10:08 AM
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
-- Database: `praksa-projekat-1`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `applicationId` varchar(50) NOT NULL,
  `userId` varchar(50) NOT NULL,
  `jobId` varchar(50) NOT NULL,
  `submittedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`applicationId`, `userId`, `jobId`, `submittedAt`) VALUES
('25210088-18b9-47f3-8865-2af6ca7c04cc', 'debe185b-7cf5-4f85-9ad2-989382bd8fb0', '2ad67dc2-3f84-45de-a4f3-3f17fc70d79d', '2025-04-12 23:47:24'),
('a157b884-8bda-45fe-a123-51cdb2f5aed8', '16cba2b1-58a9-438f-aa67-92e8715be11d', 'b2c592be-db13-4a94-96a1-3bf4b05789a9', '2025-04-13 23:07:55'),
('f1112365-13b0-48fb-8ba9-d90c40663fb1', '16cba2b1-58a9-438f-aa67-92e8715be11d', '438c0d92-4efc-4048-8945-e58266610e2b', '2025-04-13 22:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

CREATE TABLE `employers` (
  `employerId` varchar(50) NOT NULL,
  `employerName` varchar(30) NOT NULL,
  `basedIn` varchar(30) NOT NULL,
  `employerDescription` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employers`
--

INSERT INTO `employers` (`employerId`, `employerName`, `basedIn`, `employerDescription`) VALUES
('asdf123', 'Laguna', 'Beograd', 'jako dobra knjizara Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem'),
('asjdlf1023', 'Google', 'Palo Alto', 'google Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has be');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `favouriteId` varchar(50) NOT NULL,
  `userId` varchar(50) NOT NULL,
  `jobId` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `jobId` varchar(50) NOT NULL,
  `employerId` varchar(50) NOT NULL,
  `jobName` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `field` varchar(20) NOT NULL,
  `startSalary` int(11) NOT NULL,
  `shifts` int(11) NOT NULL DEFAULT 1,
  `location` varchar(50) NOT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp(),
  `flexibleHours` tinyint(1) NOT NULL,
  `workFromHome` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`jobId`, `employerId`, `jobName`, `description`, `field`, `startSalary`, `shifts`, `location`, `createdAt`, `flexibleHours`, `workFromHome`) VALUES
('2ad67dc2-3f84-45de-a4f3-3f17fc70d79d', 'asjdlf1023', 'Software engineer', 'softver engineer u guglu', 'it', 200000, 1, 'Novi Sad', '2025-04-12', 1, 1),
('438c0d92-4efc-4048-8945-e58266610e2b', 'asdf123', 'Menadzer u knjizari', 'menadzer u laguni', 'menadzment', 210000, 1, 'Subotica', '2025-04-09', 1, 0),
('9dbb71c2-5fcf-4a49-a1c9-e68b921f055f', 'asjdlf1023', 'Backend web developer', 'backend web developer u guglu', 'it', 200000, 1, 'Beograd', '2025-04-09', 1, 1),
('b2c592be-db13-4a94-96a1-3bf4b05789a9', 'asdf123', 'Prodavac u knjizari', 'prodavac u laguni', 'prodaja', 150000, 2, 'Subotica', '2025-04-09', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` varchar(50) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `field` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `role` varchar(15) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `firstName`, `lastName`, `password`, `field`, `email`, `role`) VALUES
('16cba2b1-58a9-438f-aa67-92e8715be11d', 'test', 'test', '$2y$10$DJgTOOiFF63GBggXMF9IBOlaezatMCDHe0d2Amg/Zq1NnynC.ihkC', 'prodaja', 'test@gmail.com', 'admin'),
('3e8a7bae-5f7b-45a9-a218-96ee45fcf696', 'new', 'user', '$2y$10$vLmd/qMLMLwREqbCUp66BevWvyoEXxg3s5yR1Pv/e.3ooBNAqcPOS', 'prodaja', 'new@gmail.com', 'user'),
('88d05f25-9b65-494f-95bc-3ba3ce63b434', 'milos', 'ja', '$2y$10$5VnXslejo82D4lJRx.cx6.tkBvh6f/n6MGU6n2IdKggfhTBakWBnu', 'prodaja', 'milos@gmail.com', 'user'),
('debe185b-7cf5-4f85-9ad2-989382bd8fb0', 'new', 'test', '$2y$10$ATIt1KFVFw/xLRhcDwpvZexG6G79iEsHfshEg5LMYliWXexYEkaeO', 'it', 'new.test@gmail.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`applicationId`),
  ADD KEY `fk_application_user` (`userId`),
  ADD KEY `fk_application_job` (`jobId`);

--
-- Indexes for table `employers`
--
ALTER TABLE `employers`
  ADD PRIMARY KEY (`employerId`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`favouriteId`),
  ADD KEY `fk_favourite_user` (`userId`),
  ADD KEY `fk_favourite_job` (`jobId`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`jobId`),
  ADD KEY `employerId` (`employerId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_application_job` FOREIGN KEY (`jobId`) REFERENCES `jobs` (`jobId`),
  ADD CONSTRAINT `fk_application_user` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `favourites`
--
ALTER TABLE `favourites`
  ADD CONSTRAINT `fk_favourite_job` FOREIGN KEY (`jobId`) REFERENCES `jobs` (`jobId`),
  ADD CONSTRAINT `fk_favourite_user` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_job_employer` FOREIGN KEY (`employerId`) REFERENCES `employers` (`employerId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
