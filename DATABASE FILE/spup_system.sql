-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2026 at 07:54 PM
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
-- Database: `spup_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('in-campus','off-campus') NOT NULL,
  `activity_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `objectives` text DEFAULT NULL,
  `objective_1` text DEFAULT NULL,
  `objective_2` text DEFAULT NULL,
  `objective_3` text DEFAULT NULL,
  `leaders` text DEFAULT NULL,
  `speakers` text DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `expected_participants` int(11) DEFAULT NULL,
  `budget_file` varchar(255) DEFAULT NULL,
  `permit_file` varchar(255) DEFAULT NULL,
  `supporting_documents` varchar(255) DEFAULT NULL,
  `status` enum('pending','recommended','approved','rejected') NOT NULL DEFAULT 'pending',
  `workflow_status` enum('draft','noted_by_adviser','noted_by_dean','reviewed_by_psg','endorsed_by_director','approved_by_vp','rejected') NOT NULL DEFAULT 'draft',
  `adviser_comments` text DEFAULT NULL,
  `osa_comments` text DEFAULT NULL,
  `adviser_reviewed_at` timestamp NULL DEFAULT NULL,
  `osa_reviewed_at` timestamp NULL DEFAULT NULL,
  `adviser_id` bigint(20) UNSIGNED DEFAULT NULL,
  `adviser_noted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `adviser_noted_at` timestamp NULL DEFAULT NULL,
  `adviser_notes` text DEFAULT NULL,
  `adviser_signature` varchar(255) DEFAULT NULL,
  `dean_noted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `dean_noted_at` timestamp NULL DEFAULT NULL,
  `dean_notes` text DEFAULT NULL,
  `dean_signature` varchar(255) DEFAULT NULL,
  `psg_reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `psg_reviewed_at` timestamp NULL DEFAULT NULL,
  `psg_review_comments` text DEFAULT NULL,
  `psg_signature` varchar(255) DEFAULT NULL,
  `director_endorsed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `director_endorsed_at` timestamp NULL DEFAULT NULL,
  `director_endorsement_comments` text DEFAULT NULL,
  `director_signature` varchar(255) DEFAULT NULL,
  `vp_approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `vp_approved_at` timestamp NULL DEFAULT NULL,
  `vp_approval_comments` text DEFAULT NULL,
  `vp_signature` varchar(255) DEFAULT NULL,
  `rejected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `meets_deadline_requirement` tinyint(1) NOT NULL DEFAULT 1,
  `days_before_activity` int(11) DEFAULT NULL,
  `copy_distribution` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`copy_distribution`)),
  `copies_distributed` tinyint(1) NOT NULL DEFAULT 0,
  `osa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `osa_submission_date` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `user_id`, `title`, `description`, `type`, `activity_date`, `end_date`, `start_time`, `end_time`, `location`, `objectives`, `objective_1`, `objective_2`, `objective_3`, `leaders`, `speakers`, `budget`, `organization`, `expected_participants`, `budget_file`, `permit_file`, `supporting_documents`, `status`, `workflow_status`, `adviser_comments`, `osa_comments`, `adviser_reviewed_at`, `osa_reviewed_at`, `adviser_id`, `adviser_noted_by`, `adviser_noted_at`, `adviser_notes`, `adviser_signature`, `dean_noted_by`, `dean_noted_at`, `dean_notes`, `dean_signature`, `psg_reviewed_by`, `psg_reviewed_at`, `psg_review_comments`, `psg_signature`, `director_endorsed_by`, `director_endorsed_at`, `director_endorsement_comments`, `director_signature`, `vp_approved_by`, `vp_approved_at`, `vp_approval_comments`, `vp_signature`, `rejected_by`, `rejected_at`, `rejection_reason`, `meets_deadline_requirement`, `days_before_activity`, `copy_distribution`, `copies_distributed`, `osa_id`, `created_at`, `submitted_at`, `osa_submission_date`, `updated_at`) VALUES
(18, 20, 'Cultural & Historical Activities', NULL, 'in-campus', '2026-04-29', '2026-04-30', '07:00:00', '15:00:00', 'Student Center', NULL, 'dasda', 'dsada', 'dasdasfa', 'dsadas', NULL, 10000.00, 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 50, 'activities/budget/1776190698_TESTING.pdf', 'activities/permits/1776190698_TESTING.docx', NULL, 'approved', 'approved_by_vp', NULL, NULL, NULL, NULL, NULL, 4, '2026-04-14 18:19:59', 'nice', NULL, 9, '2026-04-14 18:20:51', NULL, NULL, 13, '2026-04-14 18:21:26', NULL, NULL, 15, '2026-04-14 18:21:57', NULL, NULL, 17, '2026-04-14 18:24:11', NULL, NULL, NULL, NULL, NULL, 1, 13, NULL, 0, NULL, '2026-04-14 18:18:18', '2026-04-14 18:23:43', NULL, '2026-04-14 18:24:11'),
(19, 20, 'business expo', NULL, 'in-campus', '2026-05-01', '2026-05-02', '08:00:00', '13:00:00', 'Student Center', NULL, 'dasda', 'dsafas', 'dasda', 'bugs', 'dsafasfa', NULL, 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 100, 'activities/budget/1776234953_images (1).jpg', 'activities/permits/1776234953_TESTING.pdf', NULL, 'approved', 'approved_by_vp', NULL, NULL, NULL, NULL, NULL, 4, '2026-04-15 06:38:02', NULL, NULL, 9, '2026-04-15 06:38:22', NULL, NULL, 13, '2026-04-15 06:38:43', NULL, NULL, 15, '2026-04-15 06:38:58', NULL, NULL, 17, '2026-04-15 06:39:16', NULL, NULL, NULL, NULL, NULL, 1, 15, NULL, 0, NULL, '2026-04-15 06:35:53', '2026-04-15 06:35:53', NULL, '2026-04-15 06:39:16'),
(21, 21, 'Cybersummit', NULL, 'in-campus', '2026-04-27', '2026-04-27', '07:00:00', '17:00:00', 'Student Center', NULL, 'fasfa', 'fsafaf', 'asfsa', 'dsafa', NULL, NULL, 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 100, 'activities/budget/1776327642_TESTING.pdf', 'activities/permits/1776327642_TESTING.docx', NULL, 'approved', 'approved_by_vp', NULL, NULL, NULL, NULL, NULL, 3, '2026-04-16 08:23:46', NULL, NULL, 10, '2026-04-16 08:24:07', NULL, NULL, 13, '2026-04-16 08:26:49', NULL, NULL, 15, '2026-04-16 08:27:03', NULL, NULL, 17, '2026-04-16 08:27:31', NULL, NULL, NULL, NULL, NULL, 1, 10, NULL, 0, NULL, '2026-04-16 08:20:42', '2026-04-16 08:26:33', NULL, '2026-04-16 08:27:31'),
(23, 21, 'activity1', NULL, 'in-campus', '2026-05-12', '2026-05-13', '07:00:00', '15:00:00', 'Student Center', NULL, 'dasd', NULL, NULL, 'dasdadsada', NULL, NULL, 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 100, 'activities/budget/1778001500_TESTING.pdf', 'activities/permits/1778001500_TESTING.docx', NULL, 'approved', 'approved_by_vp', NULL, NULL, NULL, NULL, NULL, 3, '2026-05-05 17:22:20', NULL, NULL, 10, '2026-05-05 17:22:31', NULL, NULL, 13, '2026-05-05 17:25:33', NULL, NULL, 15, '2026-05-05 17:25:55', NULL, NULL, 17, '2026-05-05 17:26:12', NULL, NULL, NULL, NULL, NULL, 1, 5, NULL, 0, NULL, '2026-05-05 17:18:20', '2026-05-05 17:21:36', NULL, '2026-05-05 17:26:12'),
(24, 21, 'activity2', NULL, 'in-campus', '2026-05-16', '2026-05-17', '07:00:00', '15:00:00', 'Student Center', NULL, 'dasda', NULL, NULL, 'dsada', 'das', NULL, 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 1, 'activities/budget/1778001873_TESTING.pdf', 'activities/permits/1778001873_TESTING.docx', NULL, 'pending', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, NULL, 0, NULL, '2026-05-05 17:24:33', '2026-05-05 17:24:33', NULL, '2026-05-05 17:28:22'),
(25, 21, 'qqqqqqqqqqqqqq1', NULL, 'in-campus', '2026-05-15', '2026-05-16', '07:00:00', '08:00:00', 'Student Center', NULL, 'dsada', NULL, NULL, 'dasdasda', NULL, NULL, 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 14, 'activities/budget/1778005862_TESTING.pdf', 'activities/permits/1778005862_TESTING.docx', NULL, 'pending', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 8, NULL, 0, NULL, '2026-05-05 18:31:02', '2026-05-05 18:52:45', NULL, '2026-05-05 18:52:45'),
(26, 21, 'kdjpads', NULL, 'in-campus', '2026-05-29', '2026-05-30', '08:00:00', '15:00:00', 'Student Center', NULL, 'dasd', NULL, NULL, 'adsda', 'ddasd', NULL, 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 100, 'activities/budget/1779471780_BOOKBINDDDDD.docx', 'activities/permits/1779471780_BOOKBINDDDDD.docx', NULL, 'approved', 'approved_by_vp', NULL, NULL, NULL, NULL, NULL, 3, '2026-05-22 17:43:40', NULL, NULL, 10, '2026-05-22 17:44:30', NULL, NULL, 13, '2026-05-22 17:45:13', NULL, NULL, 15, '2026-05-22 17:45:30', NULL, NULL, 17, '2026-05-22 17:46:11', NULL, NULL, NULL, NULL, NULL, 1, 5, NULL, 0, NULL, '2026-05-22 17:43:00', '2026-05-22 17:43:00', NULL, '2026-05-22 17:46:11'),
(27, 21, 'dsada', NULL, 'in-campus', '2026-05-29', '2026-05-30', '08:00:00', '15:00:00', 'BEU', NULL, 'dsada', NULL, NULL, 'dsada', NULL, NULL, 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 100, 'activities/budget/1779472184_BOOKBINDDDDD.docx', 'activities/permits/1779472184_BOOKBINDDDDD.docx', NULL, 'pending', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, NULL, 0, NULL, '2026-05-22 17:49:44', '2026-05-22 17:51:08', NULL, '2026-05-22 17:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `previous_status` varchar(255) DEFAULT NULL,
  `new_status` varchar(255) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `activity_id`, `user_id`, `action`, `previous_status`, `new_status`, `comments`, `created_at`, `updated_at`) VALUES
(57, 18, 20, 'created', NULL, 'draft', 'Activity created and submitted for adviser approval', '2026-04-14 18:18:18', '2026-04-14 18:18:18'),
(58, 18, 4, 'noted', 'draft', 'noted_by_adviser', 'nice', '2026-04-14 18:19:59', '2026-04-14 18:19:59'),
(59, 18, 9, 'reviewed', 'noted_by_adviser', 'noted_by_dean', 'Activity reviewed and forwarded to PSG', '2026-04-14 18:20:51', '2026-04-14 18:20:51'),
(60, 18, 13, 'reviewed', 'noted_by_dean', 'reviewed_by_psg', 'Activity reviewed and forwarded to Director', '2026-04-14 18:21:26', '2026-04-14 18:21:26'),
(61, 18, 15, 'endorsed', 'reviewed_by_psg', 'endorsed_by_director', 'Activity endorsed and forwarded to VP', '2026-04-14 18:21:57', '2026-04-14 18:21:57'),
(62, 18, 17, 'rejected', 'endorsed_by_director', 'rejected', 'modify it', '2026-04-14 18:22:27', '2026-04-14 18:22:27'),
(63, 18, 20, 'resubmitted', 'rejected', 'endorsed_by_director', 'Resubmitted to Vp', '2026-04-14 18:23:43', '2026-04-14 18:23:43'),
(64, 18, 17, 'approved', 'endorsed_by_director', 'approved_by_vp', 'Activity given final approval by VP', '2026-04-14 18:24:11', '2026-04-14 18:24:11'),
(65, 19, 20, 'created', NULL, 'draft', 'Activity created and submitted for adviser approval', '2026-04-15 06:35:53', '2026-04-15 06:35:53'),
(66, 19, 4, 'noted', 'draft', 'noted_by_adviser', 'Activity noted and forwarded to dean', '2026-04-15 06:38:02', '2026-04-15 06:38:02'),
(67, 19, 9, 'reviewed', 'noted_by_adviser', 'noted_by_dean', 'Activity reviewed and forwarded to PSG', '2026-04-15 06:38:22', '2026-04-15 06:38:22'),
(68, 19, 13, 'reviewed', 'noted_by_dean', 'reviewed_by_psg', 'Activity reviewed and forwarded to Director', '2026-04-15 06:38:43', '2026-04-15 06:38:43'),
(69, 19, 15, 'endorsed', 'reviewed_by_psg', 'endorsed_by_director', 'Activity endorsed and forwarded to VP', '2026-04-15 06:38:58', '2026-04-15 06:38:58'),
(70, 19, 17, 'approved', 'endorsed_by_director', 'approved_by_vp', 'Activity given final approval by VP', '2026-04-15 06:39:16', '2026-04-15 06:39:16'),
(79, 21, 21, 'created', NULL, 'draft', 'Activity created and submitted for adviser approval', '2026-04-16 08:20:42', '2026-04-16 08:20:42'),
(80, 21, 3, 'noted', 'draft', 'noted_by_adviser', 'Activity noted and forwarded to dean', '2026-04-16 08:23:46', '2026-04-16 08:23:46'),
(81, 21, 10, 'reviewed', 'noted_by_adviser', 'noted_by_dean', 'Activity reviewed and forwarded to PSG', '2026-04-16 08:24:07', '2026-04-16 08:24:07'),
(82, 21, 13, 'rejected', 'noted_by_dean', 'rejected', 'revise', '2026-04-16 08:24:37', '2026-04-16 08:24:37'),
(83, 21, 21, 'resubmitted', 'rejected', 'noted_by_dean', 'Resubmitted to Psg adviser', '2026-04-16 08:26:33', '2026-04-16 08:26:33'),
(84, 21, 13, 'reviewed', 'noted_by_dean', 'reviewed_by_psg', 'Activity reviewed and forwarded to Director', '2026-04-16 08:26:49', '2026-04-16 08:26:49'),
(85, 21, 15, 'endorsed', 'reviewed_by_psg', 'endorsed_by_director', 'Activity endorsed and forwarded to VP', '2026-04-16 08:27:03', '2026-04-16 08:27:03'),
(86, 21, 17, 'approved', 'endorsed_by_director', 'approved_by_vp', 'Activity given final approval by VP', '2026-04-16 08:27:31', '2026-04-16 08:27:31'),
(96, 23, 21, 'created', NULL, 'draft', 'Activity created and submitted for adviser approval', '2026-05-05 17:18:20', '2026-05-05 17:18:20'),
(97, 23, 3, 'noted', 'draft', 'noted_by_adviser', 'Activity noted and forwarded to dean', '2026-05-05 17:20:22', '2026-05-05 17:20:22'),
(98, 23, 10, 'reviewed', 'noted_by_adviser', 'noted_by_dean', 'Activity reviewed and forwarded to PSG', '2026-05-05 17:20:48', '2026-05-05 17:20:48'),
(99, 23, 13, 'rejected', 'noted_by_dean', 'rejected', 'kjdjasfa', '2026-05-05 17:21:11', '2026-05-05 17:21:11'),
(100, 23, 21, 'resubmitted', 'rejected', 'draft', 'Resubmitted — approval workflow reset to first step', '2026-05-05 17:21:36', '2026-05-05 17:21:36'),
(101, 23, 3, 'noted', 'draft', 'noted_by_adviser', 'Activity noted and forwarded to dean', '2026-05-05 17:22:20', '2026-05-05 17:22:20'),
(102, 23, 10, 'reviewed', 'noted_by_adviser', 'noted_by_dean', 'Activity reviewed and forwarded to PSG', '2026-05-05 17:22:31', '2026-05-05 17:22:31'),
(103, 24, 21, 'created', NULL, 'draft', 'Activity created and submitted for adviser approval', '2026-05-05 17:24:33', '2026-05-05 17:24:33'),
(104, 23, 13, 'reviewed', 'noted_by_dean', 'reviewed_by_psg', 'Activity reviewed and forwarded to Director', '2026-05-05 17:25:33', '2026-05-05 17:25:33'),
(105, 23, 15, 'endorsed', 'reviewed_by_psg', 'endorsed_by_director', 'Activity endorsed and forwarded to VP', '2026-05-05 17:25:55', '2026-05-05 17:25:55'),
(106, 23, 17, 'approved', 'endorsed_by_director', 'approved_by_vp', 'Activity given final approval by VP', '2026-05-05 17:26:12', '2026-05-05 17:26:12'),
(107, 25, 21, 'created', NULL, 'draft', 'Activity created and submitted for adviser approval', '2026-05-05 18:31:02', '2026-05-05 18:31:02'),
(108, 25, 3, 'noted', 'draft', 'noted_by_adviser', 'Activity noted and forwarded to dean', '2026-05-05 18:41:32', '2026-05-05 18:41:32'),
(109, 25, 10, 'reviewed', 'noted_by_adviser', 'noted_by_dean', 'Activity reviewed and forwarded to PSG', '2026-05-05 18:41:56', '2026-05-05 18:41:56'),
(110, 25, 13, 'reviewed', 'noted_by_dean', 'reviewed_by_psg', 'Activity reviewed and forwarded to Director', '2026-05-05 18:42:15', '2026-05-05 18:42:15'),
(111, 25, 15, 'rejected', 'reviewed_by_psg', 'rejected', 'sorry', '2026-05-05 18:52:06', '2026-05-05 18:52:06'),
(112, 25, 21, 'resubmitted', 'rejected', 'draft', 'Resubmitted — approval workflow reset to first step', '2026-05-05 18:52:45', '2026-05-05 18:52:45'),
(113, 26, 21, 'created', NULL, 'draft', 'Activity created and submitted for adviser approval', '2026-05-22 17:43:00', '2026-05-22 17:43:00'),
(114, 26, 3, 'noted', 'draft', 'noted_by_adviser', 'Activity noted and forwarded to dean', '2026-05-22 17:43:40', '2026-05-22 17:43:40'),
(115, 26, 10, 'reviewed', 'noted_by_adviser', 'noted_by_dean', 'Activity reviewed and forwarded to PSG', '2026-05-22 17:44:30', '2026-05-22 17:44:30'),
(116, 26, 13, 'reviewed', 'noted_by_dean', 'reviewed_by_psg', 'Activity reviewed and forwarded to Director', '2026-05-22 17:45:13', '2026-05-22 17:45:13'),
(117, 26, 15, 'endorsed', 'reviewed_by_psg', 'endorsed_by_director', 'Activity endorsed and forwarded to VP', '2026-05-22 17:45:30', '2026-05-22 17:45:30'),
(118, 26, 17, 'approved', 'endorsed_by_director', 'approved_by_vp', 'Activity given final approval by VP', '2026-05-22 17:46:11', '2026-05-22 17:46:11'),
(119, 27, 21, 'created', NULL, 'draft', 'Activity created and submitted for adviser approval', '2026-05-22 17:49:44', '2026-05-22 17:49:44'),
(120, 27, 3, 'noted', 'draft', 'noted_by_adviser', 'Activity noted and forwarded to dean', '2026-05-22 17:50:05', '2026-05-22 17:50:05'),
(121, 27, 10, 'reviewed', 'noted_by_adviser', 'noted_by_dean', 'Activity reviewed and forwarded to PSG', '2026-05-22 17:50:24', '2026-05-22 17:50:24'),
(122, 27, 13, 'rejected', 'noted_by_dean', 'rejected', 'revise it', '2026-05-22 17:50:42', '2026-05-22 17:50:42'),
(123, 27, 21, 'resubmitted', 'rejected', 'draft', 'Resubmitted — approval workflow reset to first step', '2026-05-22 17:51:08', '2026-05-22 17:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_06_28_161029_create_activities_table', 1),
(6, '2025_06_28_161052_create_activity_logs_table', 1),
(7, '2025_06_28_161109_create_notifications_table', 1),
(8, '2025_06_28_161127_create_organizations_table', 1),
(9, '2025_08_03_130841_add_new_fields_to_activities_table', 1),
(10, '2025_08_03_132739_make_description_nullable_in_activities_table', 1),
(11, '2025_08_03_133748_add_end_date_to_activities_table', 1),
(12, '2025_08_03_142854_add_approval_workflow_to_activities_table', 1),
(13, '2025_08_04_084646_remove_submitted_workflow_status', 1),
(14, '2025_08_05_191010_update_user_passwords', 1),
(15, '2025_08_05_192005_fix_activity_organization_values', 1),
(16, '2025_08_08_141639_add_school_field_to_users_table', 1),
(17, '2025_08_08_141752_update_user_roles_enum', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `activity_id`, `title`, `message`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(122, 4, 18, '📋 New Activity Submission', 'A new activity \'Cultural & Historical Activities\' has been submitted by SBAHM OFFICER and is awaiting your review.\n\nActivity Date: Apr 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-14 18:18:18', '2026-04-14 18:18:18'),
(123, 20, 18, '✅ Activity Submitted Successfully', 'Your activity \'Cultural & Historical Activities\' has been submitted successfully and is now in the approval workflow.\n\nNext step: Adviser Review', 'success', 1, '2026-04-14 18:18:18', '2026-04-15 08:28:07'),
(124, 20, 18, '✅ Activity Approved by Adviser', 'Your activity \'Cultural & Historical Activities\' has been approved by SBAHM ADVISER (Adviser).\n\nComments: nice\n\nNext step: Approval from Dean/Unit Head', 'success', 1, '2026-04-14 18:19:59', '2026-04-15 08:28:07'),
(125, 9, 18, '📋 Activity Awaiting Your review', 'Activity \'Cultural & Historical Activities\' by SBAHM OFFICER is awaiting your review.\n\nActivity Date: Apr 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-14 18:19:59', '2026-04-14 18:19:59'),
(126, 20, 18, '✅ Activity Approved by Dean/Unit Head', 'Your activity \'Cultural & Historical Activities\' has been approved by Dean - Business, Accountancy and Hospitality Management (Dean/Unit Head).\n\nNext step: Approval from PSG Council Adviser', 'success', 1, '2026-04-14 18:20:51', '2026-04-15 08:28:07'),
(127, 13, 18, '📋 Activity Awaiting Your review', 'Activity \'Cultural & Historical Activities\' by SBAHM OFFICER is awaiting your review.\n\nActivity Date: Apr 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-14 18:20:51', '2026-04-14 18:20:51'),
(128, 14, 18, '📋 Activity Awaiting Your review', 'Activity \'Cultural & Historical Activities\' by SBAHM OFFICER is awaiting your review.\n\nActivity Date: Apr 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-14 18:20:51', '2026-04-14 18:20:51'),
(129, 20, 18, '✅ Activity Approved by PSG Council Adviser', 'Your activity \'Cultural & Historical Activities\' has been approved by PSG Council Adviser 1 (PSG Council Adviser).\n\nNext step: Approval from Director of Student Affairs', 'success', 1, '2026-04-14 18:21:26', '2026-04-15 08:28:07'),
(130, 15, 18, '📋 Activity Awaiting Your endorsement', 'Activity \'Cultural & Historical Activities\' by SBAHM OFFICER is awaiting your endorsement.\n\nActivity Date: Apr 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-14 18:21:26', '2026-04-14 18:21:26'),
(131, 16, 18, '📋 Activity Awaiting Your endorsement', 'Activity \'Cultural & Historical Activities\' by SBAHM OFFICER is awaiting your endorsement.\n\nActivity Date: Apr 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-14 18:21:26', '2026-04-14 18:21:26'),
(132, 20, 18, '✅ Activity Approved by Director of Student Affairs', 'Your activity \'Cultural & Historical Activities\' has been approved by Director of Student Affairs 1 (Director of Student Affairs).\n\nNext step: Approval from Vice President for Academics', 'success', 1, '2026-04-14 18:21:57', '2026-04-15 08:28:07'),
(133, 17, 18, '📋 Activity Awaiting Your final approval', 'Activity \'Cultural & Historical Activities\' by SBAHM OFFICER is awaiting your final approval.\n\nActivity Date: Apr 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-14 18:21:57', '2026-04-14 18:21:57'),
(134, 18, 18, '📋 Activity Awaiting Your final approval', 'Activity \'Cultural & Historical Activities\' by SBAHM OFFICER is awaiting your final approval.\n\nActivity Date: Apr 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-14 18:21:57', '2026-04-14 18:21:57'),
(135, 20, 18, '❌ Activity Rejected by Vice President for Academics', 'Your activity \'Cultural & Historical Activities\' has been rejected by Vice President for Academics (Vice President for Academics).\n\nReason: modify it\n\nYou may revise and resubmit your activity if needed.', 'error', 1, '2026-04-14 18:22:27', '2026-04-15 08:28:07'),
(136, 17, 18, '✏️ Resubmission from SBAHM OFFICER', 'The activity \'Cultural & Historical Activities\' has been revised and resubmitted by SBAHM OFFICER. It is awaiting your review again.', 'info', 0, '2026-04-14 18:23:43', '2026-04-14 18:23:43'),
(137, 20, 18, '✅ Activity Resubmitted', 'Your activity \'Cultural & Historical Activities\' has been resubmitted successfully.\n\nNext step: Review by Vice President for Academics (Vice President for Academics)', 'success', 1, '2026-04-14 18:23:43', '2026-04-15 08:28:07'),
(138, 20, 18, '✅ Activity Fully Approved!', 'Your activity \'Cultural & Historical Activities\' has been approved by Vice President for Academics (Vice President for Academics).\n\n✅ Your activity has been fully approved and is ready to proceed!', 'success', 1, '2026-04-14 18:24:11', '2026-04-15 08:28:07'),
(139, 4, 19, '📋 New Activity Submission', 'A new activity \'business expo\' has been submitted by SBAHM OFFICER and is awaiting your review.\n\nActivity Date: May 01, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-15 06:35:53', '2026-04-15 06:35:53'),
(140, 20, 19, '✅ Activity Submitted Successfully', 'Your activity \'business expo\' has been submitted successfully and is now in the approval workflow.\n\nNext step: Adviser Review', 'success', 1, '2026-04-15 06:35:53', '2026-04-15 08:28:07'),
(141, 20, 19, '✅ Activity Approved by Adviser', 'Your activity \'business expo\' has been approved by SBAHM ADVISER (Adviser).\n\nNext step: Approval from Dean/Unit Head', 'success', 1, '2026-04-15 06:38:02', '2026-04-15 08:28:07'),
(142, 9, 19, '📋 Activity Awaiting Your review', 'Activity \'business expo\' by SBAHM OFFICER is awaiting your review.\n\nActivity Date: May 01, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-15 06:38:02', '2026-04-15 06:38:02'),
(143, 20, 19, '✅ Activity Approved by Dean/Unit Head', 'Your activity \'business expo\' has been approved by Dean - Business, Accountancy and Hospitality Management (Dean/Unit Head).\n\nNext step: Approval from PSG Council Adviser', 'success', 1, '2026-04-15 06:38:22', '2026-04-15 08:28:07'),
(144, 13, 19, '📋 Activity Awaiting Your review', 'Activity \'business expo\' by SBAHM OFFICER is awaiting your review.\n\nActivity Date: May 01, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-15 06:38:22', '2026-04-15 06:38:22'),
(145, 14, 19, '📋 Activity Awaiting Your review', 'Activity \'business expo\' by SBAHM OFFICER is awaiting your review.\n\nActivity Date: May 01, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-15 06:38:22', '2026-04-15 06:38:22'),
(146, 20, 19, '✅ Activity Approved by PSG Council Adviser', 'Your activity \'business expo\' has been approved by PSG Council Adviser 1 (PSG Council Adviser).\n\nNext step: Approval from Director of Student Affairs', 'success', 1, '2026-04-15 06:38:43', '2026-04-15 08:28:07'),
(147, 15, 19, '📋 Activity Awaiting Your endorsement', 'Activity \'business expo\' by SBAHM OFFICER is awaiting your endorsement.\n\nActivity Date: May 01, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-15 06:38:43', '2026-04-15 06:38:43'),
(148, 16, 19, '📋 Activity Awaiting Your endorsement', 'Activity \'business expo\' by SBAHM OFFICER is awaiting your endorsement.\n\nActivity Date: May 01, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-15 06:38:43', '2026-04-15 06:38:43'),
(149, 20, 19, '✅ Activity Approved by Director of Student Affairs', 'Your activity \'business expo\' has been approved by Director of Student Affairs 1 (Director of Student Affairs).\n\nNext step: Approval from Vice President for Academics', 'success', 1, '2026-04-15 06:38:58', '2026-04-15 08:28:07'),
(150, 17, 19, '📋 Activity Awaiting Your final approval', 'Activity \'business expo\' by SBAHM OFFICER is awaiting your final approval.\n\nActivity Date: May 01, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-15 06:38:58', '2026-04-15 06:38:58'),
(151, 18, 19, '📋 Activity Awaiting Your final approval', 'Activity \'business expo\' by SBAHM OFFICER is awaiting your final approval.\n\nActivity Date: May 01, 2026\nLocation: Student Center\nOrganization: SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'info', 0, '2026-04-15 06:38:58', '2026-04-15 06:38:58'),
(152, 20, 19, '✅ Activity Fully Approved!', 'Your activity \'business expo\' has been approved by Vice President for Academics (Vice President for Academics).\n\n✅ Your activity has been fully approved and is ready to proceed!', 'success', 1, '2026-04-15 06:39:16', '2026-04-15 08:28:07'),
(170, 3, 21, '📋 New Activity Submission', 'A new activity \'Cybersummit\' has been submitted by SITE OFFICER and is awaiting your review.\n\nActivity Date: Apr 27, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-04-16 08:20:42', '2026-04-16 08:20:42'),
(171, 21, 21, '✅ Activity Submitted Successfully', 'Your activity \'Cybersummit\' has been submitted successfully and is now in the approval workflow.\n\nNext step: Adviser Review', 'success', 1, '2026-04-16 08:20:42', '2026-04-19 10:01:01'),
(172, 21, 21, '✅ Activity Approved by Adviser', 'Your activity \'Cybersummit\' has been approved by SITE ADVISER (Adviser).\n\nNext step: Approval from Dean/Unit Head', 'success', 1, '2026-04-16 08:23:46', '2026-04-19 10:01:01'),
(173, 10, 21, '📋 Activity Awaiting Your review', 'Activity \'Cybersummit\' by SITE OFFICER is awaiting your review.\n\nActivity Date: Apr 27, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-04-16 08:23:46', '2026-04-16 08:23:46'),
(174, 21, 21, '✅ Activity Approved by Dean/Unit Head', 'Your activity \'Cybersummit\' has been approved by Dean - Information Technology and Engineering (Dean/Unit Head).\n\nNext step: Approval from PSG Council Adviser', 'success', 1, '2026-04-16 08:24:07', '2026-04-19 10:01:01'),
(175, 13, 21, '📋 Activity Awaiting Your review', 'Activity \'Cybersummit\' by SITE OFFICER is awaiting your review.\n\nActivity Date: Apr 27, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-04-16 08:24:07', '2026-04-16 08:24:07'),
(176, 14, 21, '📋 Activity Awaiting Your review', 'Activity \'Cybersummit\' by SITE OFFICER is awaiting your review.\n\nActivity Date: Apr 27, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-04-16 08:24:07', '2026-04-16 08:24:07'),
(177, 21, 21, '❌ Activity Rejected by PSG Council Adviser', 'Your activity \'Cybersummit\' has been rejected by PSG Council Adviser 1 (PSG Council Adviser).\n\nReason: revise\n\nYou may revise and resubmit your activity if needed.', 'error', 1, '2026-04-16 08:24:37', '2026-04-19 10:01:01'),
(178, 13, 21, '✏️ Resubmission from SITE OFFICER', 'The activity \'Cybersummit\' has been revised and resubmitted by SITE OFFICER. It is awaiting your review again.', 'info', 0, '2026-04-16 08:26:33', '2026-04-16 08:26:33'),
(179, 21, 21, '✅ Activity Resubmitted', 'Your activity \'Cybersummit\' has been resubmitted successfully.\n\nNext step: Review by PSG Council Adviser 1 (PSG Council Adviser)', 'success', 1, '2026-04-16 08:26:33', '2026-04-19 10:01:01'),
(180, 21, 21, '✅ Activity Approved by PSG Council Adviser', 'Your activity \'Cybersummit\' has been approved by PSG Council Adviser 1 (PSG Council Adviser).\n\nNext step: Approval from Director of Student Affairs', 'success', 1, '2026-04-16 08:26:49', '2026-04-19 10:01:01'),
(181, 15, 21, '📋 Activity Awaiting Your endorsement', 'Activity \'Cybersummit\' by SITE OFFICER is awaiting your endorsement.\n\nActivity Date: Apr 27, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-04-16 08:26:49', '2026-04-16 08:26:49'),
(182, 16, 21, '📋 Activity Awaiting Your endorsement', 'Activity \'Cybersummit\' by SITE OFFICER is awaiting your endorsement.\n\nActivity Date: Apr 27, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-04-16 08:26:49', '2026-04-16 08:26:49'),
(183, 21, 21, '✅ Activity Approved by Director of Student Affairs', 'Your activity \'Cybersummit\' has been approved by Director of Student Affairs 1 (Director of Student Affairs).\n\nNext step: Approval from Vice President for Academics', 'success', 1, '2026-04-16 08:27:03', '2026-04-19 10:01:01'),
(184, 17, 21, '📋 Activity Awaiting Your final approval', 'Activity \'Cybersummit\' by SITE OFFICER is awaiting your final approval.\n\nActivity Date: Apr 27, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-04-16 08:27:03', '2026-04-16 08:27:03'),
(185, 18, 21, '📋 Activity Awaiting Your final approval', 'Activity \'Cybersummit\' by SITE OFFICER is awaiting your final approval.\n\nActivity Date: Apr 27, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-04-16 08:27:03', '2026-04-16 08:27:03'),
(186, 21, 21, '✅ Activity Fully Approved!', 'Your activity \'Cybersummit\' has been approved by Vice President for Academics (Vice President for Academics).\n\n✅ Your activity has been fully approved and is ready to proceed!', 'success', 1, '2026-04-16 08:27:31', '2026-04-19 10:01:01'),
(215, 3, 23, '📋 New Activity Submission', 'A new activity \'activity1\' has been submitted by SITE OFFICER and is awaiting your review.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:18:20', '2026-05-05 17:18:20'),
(216, 21, 23, '✅ Activity Submitted Successfully', 'Your activity \'activity1\' has been submitted successfully and is now in the approval workflow.\n\nNext step: Adviser Review', 'success', 1, '2026-05-05 17:18:20', '2026-05-05 17:18:26'),
(217, 21, 23, '✅ Activity Approved by Adviser', 'Your activity \'activity1\' has been approved by SITE ADVISER (Adviser).\n\nNext step: Approval from Dean/Unit Head', 'success', 1, '2026-05-05 17:20:22', '2026-05-05 18:52:59'),
(218, 10, 23, '📋 Activity Awaiting Your review', 'Activity \'activity1\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:20:23', '2026-05-05 17:20:23'),
(219, 21, 23, '✅ Activity Approved by Dean/Unit Head', 'Your activity \'activity1\' has been approved by Dean - Information Technology and Engineering (Dean/Unit Head).\n\nNext step: Approval from PSG Council Adviser', 'success', 1, '2026-05-05 17:20:48', '2026-05-05 18:52:59'),
(220, 13, 23, '📋 Activity Awaiting Your review', 'Activity \'activity1\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:20:48', '2026-05-05 17:20:48'),
(221, 14, 23, '📋 Activity Awaiting Your review', 'Activity \'activity1\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:20:48', '2026-05-05 17:20:48'),
(222, 21, 23, '❌ Activity Rejected by PSG Council Adviser', 'Your activity \'activity1\' has been rejected by PSG Council Adviser 1 (PSG Council Adviser).\n\nReason: kjdjasfa\n\nYou may revise and resubmit your activity if needed.', 'error', 1, '2026-05-05 17:21:11', '2026-05-05 18:52:59'),
(223, 3, 23, '📋 Activity Awaiting Your approval', 'Activity \'activity1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:21:36', '2026-05-05 17:21:36'),
(224, 4, 23, '📋 Activity Awaiting Your approval', 'Activity \'activity1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:21:36', '2026-05-05 17:21:36'),
(225, 5, 23, '📋 Activity Awaiting Your approval', 'Activity \'activity1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:21:36', '2026-05-05 17:21:36'),
(226, 6, 23, '📋 Activity Awaiting Your approval', 'Activity \'activity1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:21:36', '2026-05-05 17:21:36'),
(227, 7, 23, '📋 Activity Awaiting Your approval', 'Activity \'activity1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:21:36', '2026-05-05 17:21:36'),
(228, 25, 23, '📋 Activity Awaiting Your approval', 'Activity \'activity1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:21:36', '2026-05-05 17:21:36'),
(229, 21, 23, '✅ Activity Resubmitted', 'Your activity \'activity1\' has been resubmitted successfully.\n\nNext step: Adviser Review', 'success', 1, '2026-05-05 17:21:36', '2026-05-05 18:52:59'),
(230, 21, 23, '✅ Activity Approved by Adviser', 'Your activity \'activity1\' has been approved by SITE ADVISER (Adviser).\n\nNext step: Approval from Dean/Unit Head', 'success', 1, '2026-05-05 17:22:20', '2026-05-05 18:52:59'),
(231, 10, 23, '📋 Activity Awaiting Your review', 'Activity \'activity1\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:22:20', '2026-05-05 17:22:20'),
(232, 21, 23, '✅ Activity Approved by Dean/Unit Head', 'Your activity \'activity1\' has been approved by Dean - Information Technology and Engineering (Dean/Unit Head).\n\nNext step: Approval from PSG Council Adviser', 'success', 1, '2026-05-05 17:22:31', '2026-05-05 18:52:59'),
(233, 13, 23, '📋 Activity Awaiting Your review', 'Activity \'activity1\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:22:31', '2026-05-05 17:22:31'),
(234, 14, 23, '📋 Activity Awaiting Your review', 'Activity \'activity1\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:22:31', '2026-05-05 17:22:31'),
(235, 3, 24, '📋 New Activity Submission', 'A new activity \'activity2\' has been submitted by SITE OFFICER and is awaiting your review.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:24:33', '2026-05-05 17:24:33'),
(236, 21, 24, '✅ Activity Submitted Successfully', 'Your activity \'activity2\' has been submitted successfully and is now in the approval workflow.\n\nNext step: Adviser Review', 'success', 1, '2026-05-05 17:24:33', '2026-05-05 18:52:59'),
(237, 21, 23, '✅ Activity Approved by PSG Council Adviser', 'Your activity \'activity1\' has been approved by PSG Council Adviser 1 (PSG Council Adviser).\n\nNext step: Approval from Director of Student Affairs', 'success', 1, '2026-05-05 17:25:33', '2026-05-05 18:52:59'),
(238, 15, 23, '📋 Activity Awaiting Your endorsement', 'Activity \'activity1\' by SITE OFFICER is awaiting your endorsement.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:25:33', '2026-05-05 17:25:33'),
(239, 16, 23, '📋 Activity Awaiting Your endorsement', 'Activity \'activity1\' by SITE OFFICER is awaiting your endorsement.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:25:33', '2026-05-05 17:25:33'),
(240, 21, 23, '✅ Activity Approved by Director of Student Affairs', 'Your activity \'activity1\' has been approved by Director of Student Affairs 1 (Director of Student Affairs).\n\nNext step: Approval from Vice President for Academics', 'success', 1, '2026-05-05 17:25:55', '2026-05-05 18:52:59'),
(241, 17, 23, '📋 Activity Awaiting Your final approval', 'Activity \'activity1\' by SITE OFFICER is awaiting your final approval.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:25:55', '2026-05-05 17:25:55'),
(242, 18, 23, '📋 Activity Awaiting Your final approval', 'Activity \'activity1\' by SITE OFFICER is awaiting your final approval.\n\nActivity Date: May 12, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 17:25:55', '2026-05-05 17:25:55'),
(243, 21, 23, '✅ Activity Fully Approved!', 'Your activity \'activity1\' has been approved by Vice President for Academics (Vice President for Academics).\n\n✅ Your activity has been fully approved and is ready to proceed!', 'success', 1, '2026-05-05 17:26:12', '2026-05-05 18:52:59'),
(244, 3, 25, '📋 New Activity Submission', 'A new activity \'qqqqqqqqqqqqqq\' has been submitted by SITE OFFICER and is awaiting your review.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:31:02', '2026-05-05 18:31:02'),
(245, 21, 25, '✅ Activity Submitted Successfully', 'Your activity \'qqqqqqqqqqqqqq\' has been submitted successfully and is now in the approval workflow.\n\nNext step: Adviser Review', 'success', 1, '2026-05-05 18:31:02', '2026-05-05 18:52:59'),
(246, 21, 25, '✅ Activity Approved by Adviser', 'Your activity \'qqqqqqqqqqqqqq\' has been approved by SITE ADVISER (Adviser).\n\nNext step: Approval from Dean/Unit Head', 'success', 1, '2026-05-05 18:41:32', '2026-05-05 18:52:59'),
(247, 10, 25, '📋 Activity Awaiting Your review', 'Activity \'qqqqqqqqqqqqqq\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:41:32', '2026-05-05 18:41:32'),
(248, 21, 25, '✅ Activity Approved by Dean/Unit Head', 'Your activity \'qqqqqqqqqqqqqq\' has been approved by Dean - Information Technology and Engineering (Dean/Unit Head).\n\nNext step: Approval from PSG Council Adviser', 'success', 1, '2026-05-05 18:41:56', '2026-05-05 18:52:59'),
(249, 13, 25, '📋 Activity Awaiting Your review', 'Activity \'qqqqqqqqqqqqqq\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:41:56', '2026-05-05 18:41:56'),
(250, 14, 25, '📋 Activity Awaiting Your review', 'Activity \'qqqqqqqqqqqqqq\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:41:56', '2026-05-05 18:41:56'),
(251, 21, 25, '✅ Activity Approved by PSG Council Adviser', 'Your activity \'qqqqqqqqqqqqqq\' has been approved by PSG Council Adviser 1 (PSG Council Adviser).\n\nNext step: Approval from Director of Student Affairs', 'success', 1, '2026-05-05 18:42:15', '2026-05-05 18:52:59'),
(252, 15, 25, '📋 Activity Awaiting Your endorsement', 'Activity \'qqqqqqqqqqqqqq\' by SITE OFFICER is awaiting your endorsement.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:42:15', '2026-05-05 18:42:15'),
(253, 16, 25, '📋 Activity Awaiting Your endorsement', 'Activity \'qqqqqqqqqqqqqq\' by SITE OFFICER is awaiting your endorsement.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:42:15', '2026-05-05 18:42:15'),
(254, 21, 25, '❌ Activity Rejected by Director of Student Affairs', 'Your activity \'qqqqqqqqqqqqqq\' has been rejected by Director of Student Affairs 1 (Director of Student Affairs).\n\nReason: sorry\n\nYou may revise and resubmit your activity if needed.', 'error', 1, '2026-05-05 18:52:06', '2026-05-05 18:52:59'),
(255, 3, 25, '📋 Activity Awaiting Your approval', 'Activity \'qqqqqqqqqqqqqq1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:52:45', '2026-05-05 18:52:45'),
(256, 4, 25, '📋 Activity Awaiting Your approval', 'Activity \'qqqqqqqqqqqqqq1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:52:45', '2026-05-05 18:52:45'),
(257, 5, 25, '📋 Activity Awaiting Your approval', 'Activity \'qqqqqqqqqqqqqq1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:52:45', '2026-05-05 18:52:45'),
(258, 6, 25, '📋 Activity Awaiting Your approval', 'Activity \'qqqqqqqqqqqqqq1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:52:45', '2026-05-05 18:52:45'),
(259, 7, 25, '📋 Activity Awaiting Your approval', 'Activity \'qqqqqqqqqqqqqq1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:52:45', '2026-05-05 18:52:45'),
(260, 25, 25, '📋 Activity Awaiting Your approval', 'Activity \'qqqqqqqqqqqqqq1\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 15, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-05 18:52:45', '2026-05-05 18:52:45'),
(261, 21, 25, '✅ Activity Resubmitted', 'Your activity \'qqqqqqqqqqqqqq1\' has been resubmitted successfully.\n\nNext step: Adviser Review', 'success', 1, '2026-05-05 18:52:45', '2026-05-05 18:52:59'),
(262, 3, 26, '📋 New Activity Submission', 'A new activity \'kdjpads\' has been submitted by SITE OFFICER and is awaiting your review.\n\nActivity Date: May 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:43:00', '2026-05-22 17:43:00'),
(263, 21, 26, '✅ Activity Submitted Successfully', 'Your activity \'kdjpads\' has been submitted successfully and is now in the approval workflow.\n\nNext step: Adviser Review', 'success', 1, '2026-05-22 17:43:00', '2026-05-22 17:48:09'),
(264, 21, 26, '✅ Activity Approved by Adviser', 'Your activity \'kdjpads\' has been approved by SITE ADVISER (Adviser).\n\nNext step: Approval from Dean/Unit Head', 'success', 1, '2026-05-22 17:43:40', '2026-05-22 17:48:09'),
(265, 10, 26, '📋 Activity Awaiting Your review', 'Activity \'kdjpads\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:43:40', '2026-05-22 17:43:40'),
(266, 21, 26, '✅ Activity Approved by Dean/Unit Head', 'Your activity \'kdjpads\' has been approved by Dean Site (Dean/Unit Head).\n\nNext step: Approval from PSG Council Adviser', 'success', 1, '2026-05-22 17:44:30', '2026-05-22 17:48:09'),
(267, 13, 26, '📋 Activity Awaiting Your review', 'Activity \'kdjpads\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:44:30', '2026-05-22 17:44:30'),
(268, 14, 26, '📋 Activity Awaiting Your review', 'Activity \'kdjpads\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:44:30', '2026-05-22 17:44:30'),
(269, 21, 26, '✅ Activity Approved by PSG Council Adviser', 'Your activity \'kdjpads\' has been approved by PSG Council Adviser 1 (PSG Council Adviser).\n\nNext step: Approval from Director of Student Affairs', 'success', 1, '2026-05-22 17:45:13', '2026-05-22 17:48:09'),
(270, 15, 26, '📋 Activity Awaiting Your endorsement', 'Activity \'kdjpads\' by SITE OFFICER is awaiting your endorsement.\n\nActivity Date: May 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:45:13', '2026-05-22 17:45:13'),
(271, 16, 26, '📋 Activity Awaiting Your endorsement', 'Activity \'kdjpads\' by SITE OFFICER is awaiting your endorsement.\n\nActivity Date: May 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:45:13', '2026-05-22 17:45:13'),
(272, 21, 26, '✅ Activity Approved by Director of Student Affairs', 'Your activity \'kdjpads\' has been approved by Director of Student Affairs 1 (Director of Student Affairs).\n\nNext step: Approval from Vice President for Academics', 'success', 1, '2026-05-22 17:45:30', '2026-05-22 17:48:09'),
(273, 17, 26, '📋 Activity Awaiting Your final approval', 'Activity \'kdjpads\' by SITE OFFICER is awaiting your final approval.\n\nActivity Date: May 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:45:30', '2026-05-22 17:45:30'),
(274, 18, 26, '📋 Activity Awaiting Your final approval', 'Activity \'kdjpads\' by SITE OFFICER is awaiting your final approval.\n\nActivity Date: May 29, 2026\nLocation: Student Center\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:45:30', '2026-05-22 17:45:30'),
(275, 21, 26, '✅ Activity Fully Approved!', 'Your activity \'kdjpads\' has been approved by Vice President for Academics (Vice President for Academics).\n\n✅ Your activity has been fully approved and is ready to proceed!', 'success', 1, '2026-05-22 17:46:11', '2026-05-22 17:48:09'),
(276, 3, 27, '📋 New Activity Submission', 'A new activity \'dsada\' has been submitted by SITE OFFICER and is awaiting your review.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:49:44', '2026-05-22 17:49:44'),
(277, 21, 27, '✅ Activity Submitted Successfully', 'Your activity \'dsada\' has been submitted successfully and is now in the approval workflow.\n\nNext step: Adviser Review', 'success', 0, '2026-05-22 17:49:44', '2026-05-22 17:49:44'),
(278, 21, 27, '✅ Activity Approved by Adviser', 'Your activity \'dsada\' has been approved by SITE ADVISER (Adviser).\n\nNext step: Approval from Dean/Unit Head', 'success', 0, '2026-05-22 17:50:05', '2026-05-22 17:50:05'),
(279, 10, 27, '📋 Activity Awaiting Your review', 'Activity \'dsada\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:50:05', '2026-05-22 17:50:05'),
(280, 21, 27, '✅ Activity Approved by Dean/Unit Head', 'Your activity \'dsada\' has been approved by Dean Site (Dean/Unit Head).\n\nNext step: Approval from PSG Council Adviser', 'success', 0, '2026-05-22 17:50:24', '2026-05-22 17:50:24'),
(281, 13, 27, '📋 Activity Awaiting Your review', 'Activity \'dsada\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:50:24', '2026-05-22 17:50:24'),
(282, 14, 27, '📋 Activity Awaiting Your review', 'Activity \'dsada\' by SITE OFFICER is awaiting your review.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:50:24', '2026-05-22 17:50:24'),
(283, 21, 27, '❌ Activity Rejected by PSG Council Adviser', 'Your activity \'dsada\' has been rejected by PSG Council Adviser 1 (PSG Council Adviser).\n\nReason: revise it\n\nYou may revise and resubmit your activity if needed.', 'error', 0, '2026-05-22 17:50:42', '2026-05-22 17:50:42'),
(284, 3, 27, '📋 Activity Awaiting Your approval', 'Activity \'dsada\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:51:08', '2026-05-22 17:51:08'),
(285, 4, 27, '📋 Activity Awaiting Your approval', 'Activity \'dsada\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:51:08', '2026-05-22 17:51:08'),
(286, 5, 27, '📋 Activity Awaiting Your approval', 'Activity \'dsada\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:51:08', '2026-05-22 17:51:08'),
(287, 6, 27, '📋 Activity Awaiting Your approval', 'Activity \'dsada\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:51:08', '2026-05-22 17:51:08'),
(288, 7, 27, '📋 Activity Awaiting Your approval', 'Activity \'dsada\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:51:08', '2026-05-22 17:51:08'),
(289, 25, 27, '📋 Activity Awaiting Your approval', 'Activity \'dsada\' by SITE OFFICER is awaiting your approval.\n\nActivity Date: May 29, 2026\nLocation: BEU\nOrganization: SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'info', 0, '2026-05-22 17:51:08', '2026-05-22 17:51:08'),
(290, 21, 27, '✅ Activity Resubmitted', 'Your activity \'dsada\' has been resubmitted successfully.\n\nNext step: Adviser Review', 'success', 0, '2026-05-22 17:51:08', '2026-05-22 17:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `department` varchar(255) NOT NULL,
  `adviser_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `description`, `department`, `adviser_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Computer Science Society', 'Organization for Computer Science students to promote academic excellence and professional development.', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', NULL, 1, '2025-10-06 08:22:38', '2025-10-06 08:22:38'),
(2, 'Business Club', 'Student organization focused on business development and entrepreneurship.', 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 4, 1, '2025-10-06 08:22:38', '2025-10-06 08:22:38'),
(3, 'Engineering Society', 'Professional organization for engineering students.', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', NULL, 1, '2025-10-06 08:22:38', '2025-10-06 08:22:38'),
(4, 'Student Government', 'Official student government organization representing all students.', 'Office of Student Affairs', NULL, 1, '2025-10-06 08:22:38', '2025-10-06 08:22:38'),
(5, 'Cultural Arts Club', 'Organization promoting cultural arts and performances.', 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION', 5, 1, '2025-10-06 08:22:38', '2025-10-06 08:22:38'),
(6, 'Sports Club', 'Athletic organization for various sports activities.', 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION', 5, 1, '2025-10-06 08:22:38', '2025-10-06 08:22:38'),
(7, 'Nursing Student Association', 'Professional organization for nursing students.', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES', 6, 1, '2025-10-06 08:22:38', '2025-10-06 08:22:38'),
(8, 'Medical Student Society', 'Organization for medical students promoting academic excellence.', 'SCHOOL OF MEDICINE', 7, 1, '2025-10-06 08:22:38', '2025-10-06 08:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `year_level` varchar(255) DEFAULT NULL,
  `role` enum('admin','student','adviser','dean','psg_adviser','director','vp','osa') DEFAULT 'student',
  `school` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `student_id`, `department`, `course`, `year_level`, `role`, `school`, `email_verified_at`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin@spup.edu.ph', NULL, 'IT Department', NULL, NULL, 'admin', NULL, '2025-10-06 08:22:38', '$2y$12$RDXAyyI1JtVe15qbb5Zxb.xR.4AAIFf.o7pSvbN5TERE0z0aDWN2a', 1, NULL, '2025-10-06 08:22:30', '2025-10-06 08:22:38'),
(3, 'SITE ADVISER', 'adviser1@spup.edu.ph', NULL, 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', NULL, NULL, 'adviser', NULL, '2025-10-06 08:22:41', '$2y$12$DZbUMj1EdQqcUg6YOFRKYuG9D0eoDiJsdWRuS/Ek8gYSTmyqQ/JSG', 1, NULL, '2025-10-06 08:22:31', '2026-01-04 00:31:17'),
(4, 'SBAHM ADVISER', 'adviser2@spup.edu.ph', NULL, 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', NULL, NULL, 'adviser', NULL, '2025-10-06 08:22:41', '$2y$12$gxoqSOoHD9zKUdr45sH.s.gaRjRkBfO3d6KOmR/5q7DOLSV9zFnSi', 1, NULL, '2025-10-06 08:22:31', '2026-04-07 16:29:32'),
(5, 'SASTE ADVISER', 'adviser3@spup.edu.ph', NULL, 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION', NULL, NULL, 'adviser', NULL, '2025-10-06 08:22:41', '$2y$12$l2/DDwwveWWWYPq2mqR2GOy2X567KSk.pVdKe1p.i0zIUyAWfeRja', 1, NULL, '2025-10-06 08:22:31', '2026-02-03 04:59:26'),
(6, 'SNAHS ADVISER', 'adviser4@spup.edu.ph', NULL, 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES', NULL, NULL, 'adviser', NULL, '2025-10-06 08:22:42', '$2y$12$u.VjwTiacOe/p.T0kL2PEeqx994pVyPN5Tn76ccS6Y/8HtV/6pi02', 1, NULL, '2025-10-06 08:22:32', '2026-02-03 05:00:13'),
(7, 'SOM ADVISER', 'adviser5@spup.edu.ph', NULL, 'SCHOOL OF MEDICINE', NULL, NULL, 'adviser', NULL, '2025-10-06 08:22:42', '$2y$12$C4ENfar8p22BW3aESI7txeOjJQb85pwtW4v3qxhe8CC1t8xF974Dq', 1, NULL, '2025-10-06 08:22:32', '2026-02-03 05:01:05'),
(8, 'Dean Saste', 'dean.aste@spup.edu.ph', NULL, 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION', NULL, NULL, 'dean', 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION', '2025-10-06 08:22:42', '$2y$12$EL0U4rGBXlcnS6meAnpAM.AOPlCN6e8XdJ2LoItI726ZmvQzTx7km', 1, NULL, '2025-10-06 08:22:32', '2026-04-16 07:35:11'),
(9, 'Dean - Business, Accountancy and Hospitality Management', 'dean.bahm@spup.edu.ph', NULL, 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', NULL, NULL, 'dean', 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', '2025-10-06 08:22:43', '$2y$12$tY7bx4waLPFQbB3oBVAJ4uKiGLXPpsOrv0UsovQ4bQgfQav6QiXt2', 1, NULL, '2025-10-06 08:22:33', '2025-10-06 08:22:43'),
(10, 'Dean Site', 'dean.ite@spup.edu.ph', NULL, 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', NULL, NULL, 'dean', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', '2025-10-06 08:22:43', '$2y$12$p9NNDYx.Gys.6TDsajbhLuFvHbY67vEfdjgNxbGLXmh/28twquTYG', 1, NULL, '2025-10-06 08:22:33', '2026-05-22 17:44:23'),
(11, 'Dean - Nursing and Allied Health Sciences', 'dean.nahs@spup.edu.ph', NULL, 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES', NULL, NULL, 'dean', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES', '2025-10-06 08:22:44', '$2y$12$14pMgIFHBCJTN6CSwqzyhu2uGbdZTep8xhyGLO7X5UAoqJPzfCmsG', 1, NULL, '2025-10-06 08:22:33', '2025-10-06 08:22:44'),
(12, 'Dean - Medicine', 'dean.medicine@spup.edu.ph', NULL, 'SCHOOL OF MEDICINE', NULL, NULL, 'dean', 'SCHOOL OF MEDICINE', '2025-10-06 08:22:44', '$2y$12$E.vtjg4xBioRxp8ERr33fe/1JH0HG4tddmrmxI5EnrOqXkBIQXTHu', 1, NULL, '2025-10-06 08:22:34', '2025-10-06 08:22:44'),
(13, 'PSG Council Adviser 1', 'psg.adviser1@spup.edu.ph', NULL, 'Student Affairs', NULL, NULL, 'psg_adviser', NULL, '2025-10-06 08:22:44', '$2y$12$SZF34nX8MKaU2pjlf5bM2eKUBzYtr76cfW3Kb2kTMy/Bo/22zT.ke', 1, NULL, '2025-10-06 08:22:34', '2025-10-06 08:22:44'),
(14, 'PSG Council Adviser 2', 'psg.adviser2@spup.edu.ph', NULL, 'Student Affairs', NULL, NULL, 'psg_adviser', NULL, '2025-10-06 08:22:45', '$2y$12$gT.rlZaianudk9DlBI9oN.VGfORG0XM.ou3hUIC7tSVyGGG.xyOUu', 1, NULL, '2025-10-06 08:22:35', '2025-10-06 08:22:45'),
(15, 'Director of Student Affairs 1', 'director.sa@spup.edu.ph', NULL, 'Student Affairs and Academic Support Services', NULL, NULL, 'director', NULL, '2025-10-06 08:22:45', '$2y$12$YFoYsuiYYKm6Y5ywUbLv6Oi6UZNlPlorlBm0oGII31TdbyJfZ8bCi', 1, NULL, '2025-10-06 08:22:35', '2025-10-06 08:22:45'),
(16, 'Director of Student Affairs 2', 'director.sa2@spup.edu.ph', NULL, 'Student Affairs and Academic Support Services', NULL, NULL, 'director', NULL, '2025-10-06 08:22:45', '$2y$12$iTKoXt1Dgzru.JrQJBRZAuZOf/nnW5MXKTLRdaYn3qJ5ZthQJYWiS', 1, NULL, '2025-10-06 08:22:35', '2025-10-06 08:22:45'),
(17, 'Vice President for Academics', 'vp.academics@spup.edu.ph', NULL, 'Office of the Vice President for Academics', NULL, NULL, 'vp', NULL, '2025-10-06 08:22:46', '$2y$12$Ppzjadd/3mnBWGjL47rFwOa7DgYZokN7ghoITwtZM52BbE1TZtn6q', 1, NULL, '2025-10-06 08:22:36', '2026-02-03 06:37:08'),
(18, 'Vice President for Academics 2', 'vp.academics2@spup.edu.ph', NULL, 'Office of the Vice President for Academics', NULL, NULL, 'vp', NULL, '2025-10-06 08:22:46', '$2y$12$lJD3hw4ofdN.EhXFv2pJNOt3M5sUFY0pwEkxHe2rBjod6lwfSBgJS', 1, NULL, '2025-10-06 08:22:36', '2025-10-06 08:22:46'),
(19, 'SASTE OFFICER', 'student1@spup.edu.ph', '2021-00001', 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION', 'Bachelor of Elementary Education', '2nd Year', 'student', NULL, '2025-10-06 08:22:39', '$2y$12$wJ7BePNNko/GwdCN4Z08ZO8kK7F2KxndCwul97YsrmY6F6KcDj2Gm', 1, NULL, '2025-10-06 08:22:36', '2026-02-03 05:07:12'),
(20, 'SBAHM OFFICER', 'student2@spup.edu.ph', '2021-00002', 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT', 'Bachelor of Science in Business Administration', '2nd Year', 'student', NULL, '2025-10-06 08:22:39', '$2y$12$0K3OHOWbpVFaTN323C8KPeT5baMv4yS.KQzBq2lxVYpPMIwOQv63y', 1, NULL, '2025-10-06 08:22:37', '2026-02-03 05:08:07'),
(21, 'SITE OFFICER', 'student3@spup.edu.ph', '2021-00003', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING', 'Bachelor of Science in Computer Science', '3rd Year', 'student', NULL, '2025-10-06 08:22:39', '$2y$12$2gpfN6ivAmF6//7g349qsOAnMqHMw5wwqhNVkbuQutIYTg83Ca0Dq', 1, NULL, '2025-10-06 08:22:37', '2026-02-03 05:09:07'),
(22, 'SNAHS OFFICER', 'student4@spup.edu.ph', '2021-00004', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES', 'Bachelor of Science in Nursing', '3rd Year', 'student', NULL, '2025-10-06 08:22:40', '$2y$12$8bCgza6ersAZ5v/jlLxFKuM1uRLl.nAqfCPh2Cz8sJCTxSdheYXsm', 1, NULL, '2025-10-06 08:22:37', '2026-02-03 05:10:10'),
(23, 'SOM OFFICER', 'student5@spup.edu.ph', NULL, 'SCHOOL OF MEDICINE', NULL, NULL, 'student', NULL, '2025-10-06 08:22:40', '$2y$12$gln3vpPcn6EP.kQ6.s35Z.k3aRa0XYsIrOf2WuvfFXDozrkkp0ESa', 1, NULL, '2025-10-06 08:22:38', '2026-02-03 18:04:51'),
(25, 'haha', 'haha@spup.com', NULL, 'SCHOOL OF MEDICINE', NULL, NULL, 'adviser', NULL, NULL, '$2y$12$eSzwz281C7xzurN9LqOqPOhN/lfQlf6jt5vZQtSgm.PKKURnrGETO', 1, NULL, '2026-02-03 09:37:29', '2026-02-03 09:37:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_user_id_foreign` (`user_id`),
  ADD KEY `activities_adviser_id_foreign` (`adviser_id`),
  ADD KEY `activities_osa_id_foreign` (`osa_id`),
  ADD KEY `activities_adviser_noted_by_foreign` (`adviser_noted_by`),
  ADD KEY `activities_dean_noted_by_foreign` (`dean_noted_by`),
  ADD KEY `activities_psg_reviewed_by_foreign` (`psg_reviewed_by`),
  ADD KEY `activities_director_endorsed_by_foreign` (`director_endorsed_by`),
  ADD KEY `activities_vp_approved_by_foreign` (`vp_approved_by`),
  ADD KEY `activities_rejected_by_foreign` (`rejected_by`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_activity_id_foreign` (`activity_id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_activity_id_foreign` (`activity_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizations_adviser_id_foreign` (`adviser_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_student_id_unique` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_adviser_id_foreign` FOREIGN KEY (`adviser_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_adviser_noted_by_foreign` FOREIGN KEY (`adviser_noted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_dean_noted_by_foreign` FOREIGN KEY (`dean_noted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_director_endorsed_by_foreign` FOREIGN KEY (`director_endorsed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_osa_id_foreign` FOREIGN KEY (`osa_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_psg_reviewed_by_foreign` FOREIGN KEY (`psg_reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_vp_approved_by_foreign` FOREIGN KEY (`vp_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_adviser_id_foreign` FOREIGN KEY (`adviser_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
