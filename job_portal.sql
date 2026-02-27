-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 03:15 AM
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
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `applicant_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `certificate_name` varchar(255) DEFAULT NULL,
  `issued_by` varchar(255) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `start_year` year(4) DEFAULT NULL,
  `end_year` year(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `user_id`, `degree`, `institution`, `start_year`, `end_year`, `created_at`) VALUES
(8, 6, 'btech', 'griet', '2023', '2027', '2026-02-17 15:20:31'),
(9, 6, '12th MPC', 'Vikas', '2021', '2023', '2026-02-17 15:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`id`, `user_id`, `job_title`, `company`, `start_date`, `end_date`, `description`, `created_at`) VALUES
(1, 6, 'intern', 'Lush Layers', '2026-01-09', '2026-03-09', NULL, '2026-02-17 15:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `salary` varchar(100) DEFAULT NULL,
  `job_type` varchar(50) DEFAULT NULL,
  `status` enum('active','closed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `employer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_title`, `description`, `company`, `location`, `salary`, `job_type`, `status`, `created_at`, `employer_id`) VALUES
(9, 'Software Engineer', 'Building efficient and reliable applications to meet user and business needs.', 'Amazon', 'Pune, India', '60K-80K', 'Full-time', 'active', '2026-02-26 12:43:52', 9),
(10, 'Web Developer', 'Turning ideas into interactive and visually appealing websites.', 'Google', 'Delhi, India', '60K-80K', 'Full-time', 'active', '2026-02-26 12:44:38', 9);

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `user_id`, `job_id`, `applied_at`, `status`) VALUES
(8, 6, 1, '2026-02-17 13:42:19', 'Pending'),
(9, 6, 2, '2026-02-17 13:42:20', 'Pending'),
(10, 6, 5, '2026-02-26 04:01:17', 'Rejected'),
(11, 6, 3, '2026-02-26 07:11:47', 'Pending'),
(12, 6, 6, '2026-02-26 07:15:33', 'Rejected'),
(14, 10, 10, '2026-02-26 12:47:22', 'Accepted'),
(15, 10, 9, '2026-02-26 12:47:25', 'Pending'),
(16, 10, 8, '2026-02-26 12:47:27', 'Pending'),
(17, 10, 7, '2026-02-26 14:15:43', 'Pending'),
(18, 10, 6, '2026-02-26 14:36:00', 'Pending'),
(19, 10, 5, '2026-02-26 14:36:03', 'Pending'),
(20, 11, 10, '2026-02-26 14:44:09', 'Pending'),
(21, 11, 9, '2026-02-26 14:44:13', 'Accepted');

-- --------------------------------------------------------

--
-- Table structure for table `job_seeker_profiles`
--

CREATE TABLE `job_seeker_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `headline` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `about` text DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_seeker_profiles`
--

INSERT INTO `job_seeker_profiles` (`id`, `user_id`, `user_name`, `email`, `dob`, `phone`, `location`, `resume`, `created_at`, `headline`, `address`, `about`, `linkedin`) VALUES
(1, 0, 'Divyani Nigam', 'divyani.a.nigam@gmail.com', '2004-10-26', '07506927334', 'hyd,inida', '1769063981_Divyani_Nigam_Resume.pdf', '2026-01-22 06:39:41', NULL, NULL, NULL, NULL),
(3, 0, 'Divyani Nigam', 'divyani.a.nigam@gmail.com', '2004-10-26', '07506927334', 'lucknow, india', '1769096814_Divyani_Nigam_Resume.pdf', '2026-01-22 15:46:54', NULL, NULL, NULL, NULL),
(4, 0, 'Divyani Nigam', 'divyani.a.nigam@gmail.com', '2004-10-26', '07506927334', 'lucknow, india', '1769134433_Divyani_Nigam_Resume.pdf', '2026-01-23 02:13:53', NULL, NULL, NULL, NULL),
(5, 0, 'Divyani Nigam', 'divyani.a.nigam@gmail.com', '2004-10-26', '07506927334', 'lucknow, india', '1769134559_Divyani_Nigam_Resume.pdf', '2026-01-23 02:15:59', NULL, NULL, NULL, NULL),
(6, 0, 'Divyani Nigam', 'divyani.a.nigam@gmail.com', '2004-10-26', '07506927334', 'lucknow, india', '1769134633_Divyani_Nigam_Resume.pdf', '2026-01-23 02:17:13', NULL, NULL, NULL, NULL),
(7, 0, 'Divyani Nigam', 'divyani.a.nigam@gmail.com', '2004-10-26', '7506927334', 'Hyderabad', '1769966929_team sign.pdf', '2026-02-01 17:28:49', NULL, NULL, NULL, NULL),
(8, 0, 'Divyani Nigam', 'divyani.a.nigam@gmail.com', '2004-10-26', '7506927334', 'Hyderabad', '1770054461_team sign.pdf', '2026-02-02 17:47:41', NULL, NULL, NULL, NULL),
(9, 1, '', NULL, '2004-10-26', '07506927334', 'Hyderabad ,india', '1770562578_Beyond the Blame.pdf', '2026-02-08 14:51:01', NULL, NULL, NULL, NULL),
(10, 3, '', NULL, '2005-10-26', '8826114305', 'Hyderabad ,india', 'uploads/1770875416_Divyani_Nigam_Resume.pdf', '2026-02-12 05:50:16', NULL, NULL, NULL, NULL),
(11, 1, '', NULL, '2004-10-26', '7506927334', 'Hyderabad ,india', 'uploads/1770988898_Obsession\'s Dark Embrace.pdf', '2026-02-13 13:21:38', NULL, NULL, NULL, NULL),
(12, 1, '', NULL, '2004-10-26', '7506927334', 'Hyderabad ,india', 'uploads/1771333401_Toasty - The Toaster.pdf', '2026-02-17 13:03:21', NULL, NULL, NULL, NULL),
(13, 4, '', NULL, '1996-12-10', '1234567890', 'Hyderabad ', 'uploads/1771334105_Beyond the Blame.pdf', '2026-02-17 13:15:05', NULL, NULL, NULL, NULL),
(14, 1, '', NULL, '2004-10-26', '07506927334', 'lko,india', 'uploads/1771335561_Divyani_Nigam_Resume.pdf', '2026-02-17 13:39:21', NULL, NULL, NULL, NULL),
(15, 6, '', NULL, '2004-10-26', '07506927334', 'lucknow, india', 'uploads/1771335690_Divyani_Nigam_Resume.pdf', '2026-02-17 13:41:30', NULL, NULL, NULL, NULL),
(16, 6, '', NULL, '2000-04-12', '1236547890', 'Hyderabad', 'uploads/1772078465_Divyani Nigam_Offer_Letter(oasis_infobyte.pdf', '2026-02-26 04:01:05', NULL, NULL, NULL, NULL),
(17, 6, '', NULL, '2000-04-12', '1236547890', 'Hyderabad', 'uploads/1772078628_Divyani Nigam_Navodita_offerletter.pdf', '2026-02-26 04:03:48', NULL, NULL, NULL, NULL),
(18, 6, '', NULL, '2000-04-21', '1236547890', 'Hyderabad ', 'uploads/1772088822_Divyani Nigam_Navodita_offerletter.pdf', '2026-02-26 06:53:42', NULL, NULL, NULL, NULL),
(19, 6, '', NULL, '2000-04-21', '1236547890', 'Hyderabad', 'uploads/1772089894_Divyani Nigam_Navodita_offerletter.pdf', '2026-02-26 07:11:34', NULL, NULL, NULL, NULL),
(20, 6, '', NULL, '2000-04-21', '1236547890', 'Hyderabad ', 'uploads/1772090127_Divyani Nigam_Offer_Letter(oasis_infobyte.pdf', '2026-02-26 07:15:27', NULL, NULL, NULL, NULL),
(21, 6, '', NULL, '2000-04-12', '1236547890', 'Hyderabad', 'uploads/resumes/1772108294_Divyani Nigam_Offer_Letter(oasis_infobyte.pdf', '2026-02-26 12:18:14', NULL, NULL, NULL, NULL),
(22, 8, '', NULL, '2000-02-17', '4569873210', 'hyd,inida', '1772109212_Divyani Nigam_Offer_Letter(oasis_infobyte.pdf', '2026-02-26 12:33:32', NULL, NULL, NULL, NULL),
(23, 10, '', NULL, '2000-11-28', '9955667246', 'Banglore, India', '1772110016_Divyani Nigam_Navodita_offerletter.pdf', '2026-02-26 12:46:56', NULL, NULL, NULL, NULL),
(24, 10, '', NULL, '2000-11-28', '9955667246', 'lucknow, india', '1772111236_Divyani Nigam_Navodita_offerletter.pdf', '2026-02-26 13:07:16', NULL, NULL, NULL, NULL),
(25, 11, '', NULL, '2000-11-28', '9955667246', 'Hyderabad ', '1772117045_Beyond the Blame.pdf', '2026-02-26 14:44:05', NULL, NULL, NULL, NULL),
(26, 11, '', NULL, '0000-00-00', '', '', NULL, '2026-02-26 14:49:16', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `saved_jobs`
--

CREATE TABLE `saved_jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_jobs`
--

INSERT INTO `saved_jobs` (`id`, `user_id`, `job_id`, `saved_at`) VALUES
(3, 3, 4, '2026-02-12 10:25:50'),
(4, 6, 1, '2026-02-17 13:49:09'),
(5, 6, 2, '2026-02-17 13:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `skill_level` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `user_id`, `skill_name`, `skill_level`, `created_at`) VALUES
(1, 6, 'python', 30, '2026-02-17 15:24:56'),
(2, 6, 'front-end', 70, '2026-02-17 15:25:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `security_question` varchar(255) DEFAULT NULL,
  `security_answer` varchar(255) DEFAULT NULL,
  `oauth_provider` varchar(50) DEFAULT NULL,
  `oauth_id` varchar(255) DEFAULT NULL,
  `profile_completion` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `headline` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `objective` text DEFAULT NULL,
  `skills_text` text DEFAULT NULL,
  `projects` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `security_question`, `security_answer`, `oauth_provider`, `oauth_id`, `profile_completion`, `created_at`, `profile_pic`, `phone`, `address`, `dob`, `headline`, `bio`, `linkedin`, `objective`, `skills_text`, `projects`) VALUES
(1, 'Divyani Nigam', 'divyani.a.nigam@gmail.com', '$2y$10$hxQmFUWVlBdd9z6uHgMgS.PHWmaWearI850MDxWfKg6uiSaQpIOsq', 'candidate', 'What is your pet\'s name?', '$2y$10$IQjeoxKaLG7s9hmxqG9fIO9apOVk4K5cKobdUz.5fTDfr9496lzo.', NULL, NULL, 100, '2026-02-01 17:20:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'diya', 'diya123@gmail.com', '$2y$10$xhZ1yEfcuntYvwx/e8LaLezpN249/BI1IHzAXll7OwMq7GYeUzR8u', 'candidate', 'What is your pet\'s name?', '$2y$10$4lcg8dGnke1sCHIAgkssG.dH7WFfvnHcX/0bBt8piFsFq4UpL3Lhq', NULL, NULL, 100, '2026-02-17 13:40:58', '', '07506927334', 'A-605 Vidyuth Arcade\r\nBachupally, Miyapur', '2004-10-26', '', '', '', NULL, NULL, NULL),
(8, 'Radha', 'radha123@gmail.com', '$2y$10$cuqNI1KWxtgt9UGB2W8wUOh.jU1X/0Hy6mNvq6s6oOfS5untzAlKi', 'candidate', 'What was your first car?', '$2y$10$SAHk6alJe6G9tpcpTxckTec5N23TVjvtXEf/fZhHfU3rhNHwKksGG', NULL, NULL, 0, '2026-02-26 12:32:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Ishita Nigam', 'ishitanigam@gmail.com', '$2y$10$pUlfOvASmBEezf.KOgNoneX2BQcz83GeMQpAernMtUNKrH.q1/8q2', 'employer', 'What is your pet\'s name?', '$2y$10$QoZGwaoglPmxxEG3BfL9weptGrsCEkEf4Rv6tbeCknasIVd8m39Xq', NULL, NULL, 0, '2026-02-26 12:41:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Divyanshi Singh', 'divyanshiisingh@gmail.com', '$2y$10$vAwHyq3smHn5m5SaBh.sr.atP3qeKD6qnOgZl/4ci6wKbJ/uSEemq', 'candidate', 'What was your first car?', '$2y$10$/vkJsk8T87N9YhJfvtvDIOao.FMVTdFp/vR2XxgawSs/FykOktm32', NULL, NULL, 0, '2026-02-26 14:43:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_job` (`user_id`,`job_id`);

--
-- Indexes for table `job_seeker_profiles`
--
ALTER TABLE `job_seeker_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`job_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `job_seeker_profiles`
--
ALTER TABLE `job_seeker_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `education`
--
ALTER TABLE `education`
  ADD CONSTRAINT `education_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `experience`
--
ALTER TABLE `experience`
  ADD CONSTRAINT `experience_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
