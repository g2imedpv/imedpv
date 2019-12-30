-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 28, 2019 at 11:16 AM
-- Server version: 5.5.60-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isafetydb_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `sd_users`
--

CREATE TABLE `sd_users` (
  `id` int(11) NOT NULL,
  `sd_role_id` int(11) NOT NULL,
  `sd_company_id` int(11) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `username` varchar(60) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `site_number` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `title` varchar(25) DEFAULT NULL,
  `phone_country_code` varchar(5) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `extention` varchar(5) DEFAULT NULL,
  `cell_country_code` varchar(5) DEFAULT NULL,
  `cell_phone_no` varchar(20) DEFAULT NULL,
  `verification` varchar(255) DEFAULT NULL,
  `phone_alert` int(1) DEFAULT '0' COMMENT '0 - Disable,1 - Enable',
  `email_alert` int(1) DEFAULT '0' COMMENT '0 - Disable,1 - Enable',
  `is_never` smallint(1) DEFAULT NULL,
  `account_expire_date` date DEFAULT NULL,
  `is_email_verified` tinyint(1) DEFAULT '0',
  `reset_password_expire_time` datetime DEFAULT NULL,
  `is_import_user` tinyint(1) DEFAULT '0',
  `is_medra` smallint(1) DEFAULT NULL,
  `is_whodra` smallint(1) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `assign_protocol` int(11) DEFAULT '1' COMMENT '1- Can work on all Protocols,2 - Can not work on any Protocols,3 - Assign Selected Protocols only',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0 - Inactive,1-Active, 2-Blocked',
  `default_language` varchar(10) DEFAULT NULL,
  `is_imedsae_tracking` smallint(1) DEFAULT NULL,
  `is_imed_safety_database` smallint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sd_users`
--

INSERT INTO `sd_users` (`id`, `sd_role_id`, `sd_company_id`, `firstname`, `lastname`, `username`, `email`, `password`, `thumbnail`, `site_number`, `site_name`, `title`, `phone_country_code`, `phone`, `extention`, `cell_country_code`, `cell_phone_no`, `verification`, `phone_alert`, `email_alert`, `is_never`, `account_expire_date`, `is_email_verified`, `reset_password_expire_time`, `is_import_user`, `is_medra`, `is_whodra`, `job_title`, `assign_protocol`, `status`, `default_language`, `is_imedsae_tracking`, `is_imed_safety_database`, `created_by`, `created_dt`, `modified_by`, `modified_dt`) VALUES
(1, 2, 1, 'Sean', 'Shang', '', 'sean.shang@g2-mds.com', '$2y$10$UEhWLVNZU1RFTVMtU0FFLOEOhFdo7EDtTgFmGbaS77MDRqLcEnNF2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vd4kzXQI2uyzEFMAv2LjPs6ntxLXevKh', 0, 1, NULL, NULL, 1, NULL, 0, NULL, NULL, NULL, 1, 1, 'en_US', NULL, NULL, NULL, '2018-05-07 21:19:37', 1, '2018-07-25 01:11:05'),
(2, 2, 3, 'Daniel', 'Yang', NULL, 'daniel.yang@g2-mds.com', '$2y$10$UEhWLVNZU1RFTVMtU0FFLOEOhFdo7EDtTgFmGbaS77MDRqLcEnNF2', NULL, NULL, NULL, 'Mr.', NULL, NULL, NULL, NULL, NULL, 'Ep4H9K3TFTbnU6QjpzBvL5RUfBJ93pUy', 0, 1, 1, NULL, 1, NULL, 0, 1, 1, NULL, 1, 1, 'en_US', NULL, NULL, 1, '2018-05-07 22:56:39', 2, '2018-07-12 07:52:56'),
(3, 2, 3, 'Shiyin', 'Lin', NULL, 'shiyin.lin@g2-mds.com', '$2y$10$UEhWLVNZU1RFTVMtU0FFLOEOhFdo7EDtTgFmGbaS77MDRqLcEnNF2', NULL, NULL, NULL, 'Mr.', NULL, NULL, NULL, NULL, NULL, 'rz5QY2TZ3hYANxIFCuhvE4KIi2nnGeaW', 0, 1, 1, NULL, 1, NULL, 0, 1, 1, NULL, 1, 1, 'en_US', NULL, NULL, 1, '2018-05-07 22:58:05', 3, '2018-07-25 02:51:55'),
(4, 5, 3, 'Roger', 'Li', NULL, 'roger.li@g2-mds.com', '$2y$10$UEhWLVNZU1RFTVMtU0FFLOEOhFdo7EDtTgFmGbaS77MDRqLcEnNF2', NULL, NULL, NULL, 'Mr.', NULL, NULL, NULL, NULL, NULL, 'g52nyWU5wXdGACyiYL5hNcCTZBUDETqK', 0, 1, 1, NULL, 1, NULL, 0, 1, 1, NULL, 1, 1, 'en_US', NULL, NULL, 1, '2018-05-07 23:00:47', 4, '2018-09-26 01:47:46'),
(5, 5, 3, 'Bella', 'Yang', NULL, 'bella.yang@g2-mds.com', '$2y$10$UEhWLVNZU1RFTVMtU0FFLOEOhFdo7EDtTgFmGbaS77MDRqLcEnNF2', NULL, NULL, NULL, 'Ms.', NULL, NULL, NULL, NULL, NULL, 'xyQvCsI72z67QG76EXzD22WAzwIkEAE2', 0, 1, 1, NULL, 1, NULL, 0, 1, 1, NULL, 1, 1, 'en_US', NULL, NULL, 1, '2018-05-07 23:03:52', 5, '2018-07-24 12:17:02'),
(6, 2, 2, 'Sophie', 'Shu', NULL, 'Sophie@g2-mds.com', '$2y$10$UEhWLVNZU1RFTVMtU0FFLOEOhFdo7EDtTgFmGbaS77MDRqLcEnNF2', NULL, NULL, NULL, 'Mr.', NULL, NULL, NULL, NULL, NULL, 'tTQDkU4UH3RbycHmFSLkdiYbIxyNxmK8', 0, 1, 1, NULL, 1, NULL, 0, 1, 1, NULL, 1, 1, 'en_US', NULL, NULL, 1, '2018-05-07 23:04:44', 6, '2018-05-08 10:08:59'),
(7, 2, 4, 'Lulu', 'Wang', NULL, 'lulu.wang@g2-mds.com', '$2y$10$UEhWLVNZU1RFTVMtU0FFLOEOhFdo7EDtTgFmGbaS77MDRqLcEnNF2', NULL, NULL, NULL, 'Mr.', NULL, NULL, NULL, NULL, NULL, 'jGKhFvUNdiszdburfcTNkMEJwktZpBJQ', 0, 1, 1, NULL, 1, NULL, 0, 0, 0, NULL, 1, 1, 'en_US', NULL, NULL, 1, '2018-05-11 06:17:50', 7, '2018-06-07 05:13:51'),
(8, 17, 6, 'Support', 'One', NULL, 'query1@yopmail.com', '$2y$10$UEhWLVNZU1RFTVMtU0FFLOEOhFdo7EDtTgFmGbaS77MDRqLcEnNF2', NULL, NULL, NULL, 'Mr.', NULL, NULL, NULL, NULL, NULL, 'CK6I5UK7e482NQQcFb7TRtpxjFguBRwk', 0, 1, 1, NULL, 1, NULL, 0, 1, 1, NULL, 1, 1, 'en_US', NULL, NULL, 1, '2018-05-16 18:39:17', 1, '2018-05-21 12:57:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sd_users`
--
ALTER TABLE `sd_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sd_role_id` (`sd_role_id`),
  ADD KEY `company_id` (`sd_company_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sd_users`
--
ALTER TABLE `sd_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
