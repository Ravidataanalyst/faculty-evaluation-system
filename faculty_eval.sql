-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2025 at 06:45 PM
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
-- Database: `faculty_eval`
--

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `faculty_username` varchar(50) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `self_assessment` text DEFAULT NULL,
  `supporting_file` varchar(255) DEFAULT NULL,
  `status` enum('pending','under review','completed') DEFAULT 'pending',
  `reviewed_by` varchar(50) DEFAULT NULL,
  `review_comments` text DEFAULT NULL,
  `submitted_on` datetime DEFAULT current_timestamp(),
  `teaching` text DEFAULT NULL,
  `research` text DEFAULT NULL,
  `service` text DEFAULT NULL,
  `teaching_effectiveness` text DEFAULT NULL,
  `research_contribution` text DEFAULT NULL,
  `service_to_institution` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `faculty_username`, `subject`, `department`, `semester`, `self_assessment`, `supporting_file`, `status`, `reviewed_by`, `review_comments`, `submitted_on`, `teaching`, `research`, `service`, `teaching_effectiveness`, `research_contribution`, `service_to_institution`) VALUES
(13, 'faculty1', 'Wb programming ', 'IT', '4', 'I encouraged active learning and provided regular doubt-clearing sessions.', '1752225069_mongo certi.pdf', 'completed', 'head1', 'good job', '2025-07-11 14:41:09', NULL, NULL, NULL, 'Incorporated live coding demos and quizzes in lectures. Received positive feedback on interactive sessions.', 'Published 1 paper in IEEE, received minor grant for IoT research', 'Organized the Annual Tech Fest and mentored 10+ students in final year projects'),
(14, 'faculty1', 'Data Structures', 'CSE', '3', 'Maintained high punctuality and completed syllabus as per schedule.\r\n\r\n', '1752225147_mongodb certificate completion.pdf', 'completed', 'head1', 'Consistently engaging', '2025-07-11 14:42:27', NULL, NULL, NULL, 'Integrated case-based teaching in DBMS and took regular feedback from students.', 'Presented a research paper at ACM conference and submitted one to Springer.', 'Served as class coordinator and department meeting coordinator'),
(15, 'faculty1', 'Operating Systems ', 'ECE', '5', 'Ensured syllabus coverage and shared updated resources with students', '1752228069_Faculty Performance Evaluation using Data Analytics.pdf', 'completed', 'head1', 'Great research involvement.', '2025-07-11 15:31:09', NULL, NULL, NULL, 'Used blended learning (offline + LMS uploads) and weekly assignments.', 'Guided 3 UG research projects, and contributed to internal R&D.', 'Assisted in NBA documentation and event coordination.'),
(16, 'faculty1', 'Artificial Intelligece ', 'CSE', '6', 'Led final year projects and organized AI seminar', '1752228158_sem 1 marksheet(1).pdf', 'completed', 'head1', 'Needs more student interaction', '2025-07-11 15:32:38', NULL, NULL, NULL, 'Handled a large batch of students with good feedback. Used peer-review assignments.', 'Completed a funded minor project on cybersecurity. One paper under review.', 'Actively participated in IQAC activities and library committee.'),
(17, 'faculty1', 'Cloud Computing ', 'IT', '7', 'Published a paper and guided 3 mini-projects.', '1752228279_sem 2 marksheet.pdf', 'completed', 'head1', 'need to improve', '2025-07-11 15:34:39', NULL, NULL, NULL, 'Conducted flipped classroom experiments and used Moodle platform', 'Published in Scopus-indexed journal and reviewed 2 conference papers.', 'Coordinated online FDP sessions and served as internal examiner.'),
(18, 'faculty1', 'computer networks', 'ECE', '7', 'iam professional in teaching ', '1753506732_mongo certi.pdf', 'completed', 'head1', 'superb', '2025-07-26 10:42:12', NULL, NULL, NULL, 'well', 'nptel', '5 years'),
(19, 'faculty1', 'DBMS', 'AI &DS ', '4', 'I am well motivated in the teaching profession ', '1753714436_mongodb certificate completion.pdf', 'completed', 'head1', 'superb job', '2025-07-28 20:23:56', NULL, NULL, NULL, 'I effectively teach students in a super ways', 'i published a RD sharma arora book in online ', 'Iam best at event coordination ');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_criteria`
--

CREATE TABLE `evaluation_criteria` (
  `id` int(11) NOT NULL,
  `criterion_name` varchar(100) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_criteria`
--

INSERT INTO `evaluation_criteria` (`id`, `criterion_name`, `weight`) VALUES
(1, 'Teaching Effectiveness', 40),
(2, 'Research & Publications', 25),
(3, 'Institutional Service', 15),
(4, 'Student Mentorship & Support', 10),
(5, 'Professional Development', 10);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_scores`
--

CREATE TABLE `evaluation_scores` (
  `evaluation_id` int(11) NOT NULL,
  `criterion_id` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_scores`
--

INSERT INTO `evaluation_scores` (`evaluation_id`, `criterion_id`, `score`) VALUES
(13, 1, 45),
(13, 2, 78),
(13, 3, 54),
(13, 4, 21),
(13, 5, 98),
(14, 1, 78),
(14, 2, 95),
(14, 3, 70),
(14, 4, 72),
(14, 5, 80),
(15, 1, 75),
(15, 2, 68),
(15, 3, 60),
(15, 4, 70),
(15, 5, 65),
(16, 1, 85),
(16, 2, 70),
(16, 3, 75),
(16, 4, 95),
(16, 5, 82),
(17, 1, 50),
(17, 2, 40),
(17, 3, 80),
(17, 4, 90),
(17, 5, 45),
(19, 1, 52),
(19, 2, 65),
(19, 3, 65),
(19, 4, 45),
(19, 5, 78);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `role` varchar(20) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `role`, `username`, `message`, `created_at`) VALUES
(1, 'faculty', NULL, '📢 Don\'t forget to submit your evaluation before 31st July!', '2025-07-26 10:48:23'),
(2, 'faculty', 'faculty1', '✅ Your AI Evaluation was reviewed by the HOD!', '2025-07-26 10:48:23');

-- --------------------------------------------------------

--
-- Table structure for table `pd_records`
--

CREATE TABLE `pd_records` (
  `id` int(11) NOT NULL,
  `faculty_username` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `pd_score` int(11) DEFAULT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pd_records`
--

INSERT INTO `pd_records` (`id`, `faculty_username`, `title`, `type`, `duration`, `proof_file`, `pd_score`, `submitted_on`) VALUES
(1, 'faculty1', 'nptel', 'Workshop', 45, '1752516457_mongodb certificate completion.pdf', 30, '2025-07-14 18:07:37'),
(2, 'faculty1', 'nptel', 'NPTEL', 56, '1752604982_PRATHYUSHA.pdf', 30, '2025-07-15 18:43:02'),
(3, 'faculty1', 'nptel', 'Workshop', 65, '1752689816_resume_new.pdf', 30, '2025-07-16 18:16:56');

-- --------------------------------------------------------

--
-- Table structure for table `professional_development`
--

CREATE TABLE `professional_development` (
  `id` int(11) NOT NULL,
  `faculty_username` varchar(50) DEFAULT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `type` enum('course','workshop','certification') DEFAULT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_feedback`
--

CREATE TABLE `student_feedback` (
  `id` int(11) NOT NULL,
  `student_username` varchar(100) DEFAULT NULL,
  `faculty_username` varchar(100) DEFAULT NULL,
  `communication` int(11) DEFAULT NULL,
  `punctuality` int(11) DEFAULT NULL,
  `knowledge` int(11) DEFAULT NULL,
  `feedback_text` text DEFAULT NULL,
  `submitted_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_feedback`
--

INSERT INTO `student_feedback` (`id`, `student_username`, `faculty_username`, `communication`, `punctuality`, `knowledge`, `feedback_text`, `submitted_on`) VALUES
(1, 'student1', 'faculty1', 4, 5, 3, 'good innovative teaching', '2025-07-11 10:29:53'),
(2, 'student1', 'faculty1', 4, 3, 2, 'innovative teaching', '2025-07-11 11:33:52'),
(3, 'student1', 'faculty1', 4, 1, 2, 'innovative teaching', '2025-07-18 10:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','faculty','dept_head','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'admin1', '$2y$10$0LzLfAHEU535C5lmokbSLuUTAXNIGilSbWy.O77DyXclTy4L3StU.', 'admin1@example.com', 'admin'),
(2, 'faculty1', '$2y$10$/h.PIJaBhHuSD4ndsckaN.HwG0/RZpc2axv41rLRsJ2jZofVJUfUq', 'projectfacultyeval123@gmail.com', 'faculty'),
(3, 'head1', '$2y$10$8iMtXDy8Hd0KBEIzetvrpu9ZpoTlmLWR48kCMWjann0I7/3xj3b4K', 'head1@example.com', 'dept_head'),
(4, 'student1', '$2y$10$q3wls7avc9BlZYxYOo.t1.wnda3r6Y.ctFi8BsvEVDG7FRdQc1xna', 'projectfacultyeval123@gmail.com', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_criteria`
--
ALTER TABLE `evaluation_criteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_scores`
--
ALTER TABLE `evaluation_scores`
  ADD PRIMARY KEY (`evaluation_id`,`criterion_id`),
  ADD KEY `criterion_id` (`criterion_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pd_records`
--
ALTER TABLE `pd_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professional_development`
--
ALTER TABLE `professional_development`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_feedback`
--
ALTER TABLE `student_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `evaluation_criteria`
--
ALTER TABLE `evaluation_criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pd_records`
--
ALTER TABLE `pd_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `professional_development`
--
ALTER TABLE `professional_development`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_feedback`
--
ALTER TABLE `student_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evaluation_scores`
--
ALTER TABLE `evaluation_scores`
  ADD CONSTRAINT `evaluation_scores_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluation_scores_ibfk_2` FOREIGN KEY (`criterion_id`) REFERENCES `evaluation_criteria` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
