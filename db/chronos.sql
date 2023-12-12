-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2020 at 03:54 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chronos`
--
CREATE DATABASE IF NOT EXISTS `chronos` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `chronos`;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `nik` varchar(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `nik`, `name`, `password`, `level`, `date_register`) VALUES
(2, '0000', 'Manager', '$2y$10$LhJmmSUHq8FgAMdJkpkUn.K6djYxLB2wfAUrOtptuHDd.RpGTv/..', 0, '2020-03-05 03:34:19'),
(3, '1111', 'biasa', '$2y$10$bgz7ON1u1VL3zj/tt1X2QObPCuA/505PEbfiGrecrTBUaKBRFfGLK', 1, '2020-02-25 06:38:28'),
(4, '2222', 'Super Admin', '$2y$10$0PJ6j.xFBIyGBheHk/K0tusPbwUvPL9g5/aGaoZnVooCHL5/kqv6G', 22, '2020-03-06 01:13:12'),
(5, 'ieuser', 'IE', '$2y$10$ZQNhmToCu/xkzlQko4oaiulLaJ.FdkJ6bYb1BfKG7/csFh7.ppFpK', 1, '2020-03-23 08:14:35'),
(6, 'xc', 'xc', '$2y$10$WtXzGXz9FEYsYouR0Z3m2umRdAUi/b8M1NZSuJmNzUciZNErXqVpC', 22, '2020-06-15 08:52:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
