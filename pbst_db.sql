-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 11:23 AM
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
-- Database: `pbst_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `id` int(11) NOT NULL,
  `id_stagiaire` int(11) NOT NULL,
  `id_docteur` int(11) NOT NULL,
  `date_consultation` date NOT NULL,
  `diagnostic` text DEFAULT NULL,
  `traitement` text DEFAULT NULL,
  `remarques` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`id`, `id_stagiaire`, `id_docteur`, `date_consultation`, `diagnostic`, `traitement`, `remarques`, `file`) VALUES
(24, 15, 6, '2025-10-16', 'Hypertension artérielle (HTA)', 'Médicaments antihypertenseurs', 'Surveillance régulière de la tension.', 'uploads/consultations/1760604255_bst.png'),
(25, 15, 6, '2025-10-16', 'Diabète de type 2', 'Régime alimentaire équilibré', 'Importance de l’éducation thérapeutique du patient.', 'uploads/consultations/1760604394_bst.png'),
(26, 15, 6, '2025-10-16', 'Infection urinaire', 'Hydratation abondante.', 'Éviter la récidive par prévention hygiénique.', 'uploads/consultations/1760604452_bst.png'),
(27, 19, 6, '2025-10-16', 'Gastrite', 'Dollyprane', 'repos 3 jrs', 'uploads/consultations/1760605769_bst.png');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id_instructor` int(11) NOT NULL,
  `cine` varchar(50) NOT NULL,
  `mle` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `speciality_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id_instructor`, `cine`, `mle`, `username`, `first_name`, `last_name`, `email`, `phone`, `bio`, `password`, `is_active`, `created_at`, `updated_at`, `speciality_id`) VALUES
(12, 'CINE001', 'MLE001', 'user1', 'ELKARCH', 'ABDELHAMID', 'user1@example.com', '0600000001', 'Bio 1', 'password_hash1', 1, '2025-10-21 12:01:58', '2025-10-22 08:35:48', 1),
(13, 'CINE002', 'MLE002', 'user2', 'First2', 'Last2', 'user2@example.com', '0600000002', 'Bio 2', 'password_hash2', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 2),
(14, 'CINE003', 'MLE003', 'user3', 'First3', 'Last3', 'user3@example.com', '0600000003', 'Bio 3', 'password_hash3', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 3),
(15, 'CINE004', 'MLE004', 'user4', 'First4', 'Last4', 'user4@example.com', '0600000004', 'Bio 4', 'password_hash4', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 4),
(16, 'CINE005', 'MLE005', 'user5', 'First5', 'Last5', 'user5@example.com', '0600000005', 'Bio 5', 'password_hash5', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 5),
(17, 'CINE006', 'MLE006', 'user6', 'First6', 'Last6', 'user6@example.com', '0600000006', 'Bio 6', 'password_hash6', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 1),
(18, 'CINE007', 'MLE007', 'user7', 'First7', 'Last7', 'user7@example.com', '0600000007', 'Bio 7', 'password_hash7', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 2),
(19, 'CINE008', 'MLE008', 'user8', 'First8', 'Last8', 'user8@example.com', '0600000008', 'Bio 8', 'password_hash8', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 3),
(20, 'CINE009', 'MLE009', 'user9', 'First9', 'Last9', 'user9@example.com', '0600000009', 'Bio 9', 'password_hash9', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 4),
(21, 'CINE010', 'MLE010', 'user10', 'First10', 'Last10', 'user10@example.com', '0600000010', 'Bio 10', 'password_hash10', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 5),
(22, 'CINE011', 'MLE011', 'user11', 'First11', 'Last11', 'user11@example.com', '0600000011', 'Bio 11', 'password_hash11', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 1),
(23, 'CINE012', 'MLE012', 'user12', 'BILEL', 'SAFEE', 'user12@example.com', '0600000012', 'Bio 12', 'password_hash12', 1, '2025-10-21 12:01:58', '2025-10-22 08:40:42', 2),
(24, 'CINE013', 'MLE013', 'user13', 'First13', 'Last13', 'user13@example.com', '0600000013', 'Bio 13', 'password_hash13', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 3),
(25, 'CINE014', 'MLE014', 'user14', 'First14', 'Last14', 'user14@example.com', '0600000014', 'Bio 14', 'password_hash14', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 4),
(26, 'CINE015', 'MLE015', 'user15', 'MOHAMMED', 'IDRISSI', 'user15@example.com', '0600000015', 'Bio 15', 'password_hash15', 1, '2025-10-21 12:01:58', '2025-10-22 08:42:50', 5),
(27, 'CINE016', 'MLE016', 'user16', 'First16', 'Last16', 'user16@example.com', '0600000016', 'Bio 16', 'password_hash16', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 1),
(28, 'CINE017', 'MLE017', 'user17', 'First17', 'Last17', 'user17@example.com', '0600000017', 'Bio 17', 'password_hash17', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 2),
(29, 'CINE018', 'MLE018', 'user18', 'First18', 'Last18', 'user18@example.com', '0600000018', 'Bio 18', 'password_hash18', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 3),
(30, 'CINE019', 'MLE019', 'user19', 'First19', 'Last19', 'user19@example.com', '0600000019', 'Bio 19', 'password_hash19', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 4),
(31, 'CINE020', 'MLE020', 'user20', 'First20', 'Last20', 'user20@example.com', '0600000020', 'Bio 20', 'password_hash20', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 5),
(32, 'CINE021', 'MLE021', 'user21', 'First21', 'Last21', 'user21@example.com', '0600000021', 'Bio 21', 'password_hash21', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 1),
(33, 'CINE022', 'MLE022', 'user22', 'First22', 'Last22', 'user22@example.com', '0600000022', 'Bio 22', 'password_hash22', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 2),
(34, 'CINE023', 'MLE023', 'user23', 'First23', 'Last23', 'user23@example.com', '0600000023', 'Bio 23', 'password_hash23', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 3),
(35, 'CINE024', 'MLE024', 'user24', 'First24', 'Last24', 'user24@example.com', '0600000024', 'Bio 24', 'password_hash24', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 4),
(36, 'CINE025', 'MLE025', 'user25', 'First25', 'Last25', 'user25@example.com', '0600000025', 'Bio 25', 'password_hash25', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 5),
(37, 'CINE026', 'MLE026', 'user26', 'First26', 'Last26', 'user26@example.com', '0600000026', 'Bio 26', 'password_hash26', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 1),
(38, 'CINE027', 'MLE027', 'user27', 'First27', 'Last27', 'user27@example.com', '0600000027', 'Bio 27', 'password_hash27', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 2),
(39, 'CINE028', 'MLE028', 'user28', 'First28', 'Last28', 'user28@example.com', '0600000028', 'Bio 28', 'password_hash28', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 3),
(40, 'CINE029', 'MLE029', 'user29', 'First29', 'Last29', 'user29@example.com', '0600000029', 'Bio 29', 'password_hash29', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 4),
(41, 'CINE030', 'MLE030', 'user30', 'First30', 'Last30', 'user30@example.com', '0600000030', 'Bio 30', 'password_hash30', 1, '2025-10-21 12:01:58', '2025-10-21 12:01:58', 5);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_instructor_stats`
--

CREATE TABLE `monthly_instructor_stats` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `positive_count` int(11) DEFAULT 0,
  `negative_count` int(11) DEFAULT 0,
  `total` int(11) DEFAULT 0,
  `positive_ratio` float DEFAULT 0,
  `computed_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_instructor_stats`
--

INSERT INTO `monthly_instructor_stats` (`id`, `instructor_id`, `year`, `month`, `positive_count`, `negative_count`, `total`, `positive_ratio`, `computed_at`) VALUES
(18, 12, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(19, 13, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(20, 14, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(21, 15, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(22, 16, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(23, 17, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(24, 18, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(25, 19, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(26, 20, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(27, 21, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(28, 22, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(29, 23, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(30, 24, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(31, 25, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(32, 26, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(33, 27, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(34, 28, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(35, 29, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(36, 30, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(37, 31, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(38, 32, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(39, 33, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(40, 34, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(41, 35, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(42, 36, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(43, 37, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(44, 38, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(45, 39, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(46, 40, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(47, 41, 2025, 9, 0, 0, 0, 0, '2025-10-22 00:29:10'),
(49, 12, 2025, 11, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(50, 13, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(51, 14, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(52, 15, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(53, 16, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(54, 17, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(55, 18, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(56, 19, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(57, 20, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(58, 21, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(59, 22, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(60, 23, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(61, 24, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(62, 25, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(63, 26, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(64, 27, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(65, 28, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(66, 29, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(67, 30, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(68, 31, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(69, 32, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(70, 33, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(71, 34, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(72, 35, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(73, 36, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(74, 37, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(75, 38, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(76, 39, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(77, 40, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(78, 41, 2025, 11, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(80, 12, 2025, 10, 14, 2, 16, 0.875, '2025-10-22 00:31:27'),
(81, 13, 2025, 10, 0, 1, 1, 0, '2025-10-22 00:31:27'),
(82, 14, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(83, 15, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(84, 16, 2025, 10, 0, 1, 1, 0, '2025-10-22 00:31:27'),
(85, 17, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(86, 18, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(87, 19, 2025, 10, 0, 1, 1, 0, '2025-10-22 00:31:27'),
(88, 20, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(89, 21, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(90, 22, 2025, 10, 1, 1, 2, 0.5, '2025-10-22 00:31:27'),
(91, 23, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(92, 24, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(93, 25, 2025, 10, 0, 1, 1, 0, '2025-10-22 00:31:27'),
(94, 26, 2025, 10, 2, 0, 2, 1, '2025-10-22 00:31:27'),
(95, 27, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(96, 28, 2025, 10, 0, 1, 1, 0, '2025-10-22 00:31:27'),
(97, 29, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(98, 30, 2025, 10, 1, 0, 1, 1, '2025-10-22 00:31:27'),
(99, 31, 2025, 10, 0, 1, 1, 0, '2025-10-22 00:31:27'),
(100, 32, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(101, 33, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(102, 34, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(103, 35, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(104, 36, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(105, 37, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(106, 38, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(107, 39, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(108, 40, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(109, 41, 2025, 10, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(111, 12, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(112, 13, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(113, 14, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(114, 15, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(115, 16, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(116, 17, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(117, 18, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(118, 19, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(119, 20, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(120, 21, 2025, 2, 17, 0, 17, 1, '2025-10-22 00:31:27'),
(121, 22, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(122, 23, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(123, 24, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(124, 25, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(125, 26, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(126, 27, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(127, 28, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(128, 29, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(129, 30, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(130, 31, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(131, 32, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(132, 33, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(133, 34, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(134, 35, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(135, 36, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(136, 37, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(137, 38, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(138, 39, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(139, 40, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27'),
(140, 41, 2025, 2, 0, 0, 0, 0, '2025-10-22 00:31:27');

-- --------------------------------------------------------

--
-- Table structure for table `observations`
--

CREATE TABLE `observations` (
  `id_observation` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `observed_by_user_id` int(11) DEFAULT NULL,
  `obs_date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `rating` enum('positive','negative') NOT NULL,
  `score` tinyint(4) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `heure_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `observations`
--

INSERT INTO `observations` (`id_observation`, `instructor_id`, `subject_id`, `observed_by_user_id`, `obs_date`, `heure_debut`, `rating`, `score`, `comment`, `created_at`, `heure_fin`) VALUES
(56, 12, 1, 5, '2025-10-01', '09:00:00', 'positive', 9, 'Très bonne séance.', '2025-10-21 12:17:28', '00:00:00'),
(57, 13, 2, 6, '2025-10-01', '10:00:00', 'negative', 5, 'Besoin d’amélioration.', '2025-10-21 12:17:28', '00:00:00'),
(58, 14, 1, 15, '2025-10-02', '11:00:00', 'positive', 8, 'Bien organisé.', '2025-10-21 12:17:28', '00:00:00'),
(59, 15, 3, 17, '2025-10-02', '12:00:00', 'positive', 10, 'Excellent.', '2025-10-21 12:17:28', '00:00:00'),
(60, 16, 2, 5, '2025-10-03', '09:30:00', 'negative', 6, 'Quelques erreurs.', '2025-10-21 12:17:28', '00:00:00'),
(61, 17, 1, 6, '2025-10-03', '10:30:00', 'positive', 7, 'Correct.', '2025-10-21 12:17:28', '00:00:00'),
(62, 18, 3, 15, '2025-10-04', '11:15:00', 'positive', 9, 'Très professionnel.', '2025-10-21 12:17:28', '00:00:00'),
(63, 19, 2, 17, '2025-10-04', '12:45:00', 'negative', 4, 'Manque de préparation.', '2025-10-21 12:17:28', '00:00:00'),
(64, 20, 3, 5, '2025-10-05', '09:00:00', 'positive', 8, 'Bien expliqué.', '2025-10-21 12:17:28', '00:00:00'),
(65, 21, 2, 6, '2025-10-05', '10:00:00', 'positive', 9, 'Très clair.', '2025-10-21 12:17:28', '00:00:00'),
(66, 22, 1, 15, '2025-10-06', '11:30:00', 'negative', 5, 'Peu structuré.', '2025-10-21 12:17:28', '00:00:00'),
(67, 23, 3, 17, '2025-10-06', '12:30:00', 'positive', 10, 'Excellent.', '2025-10-21 12:17:28', '00:00:00'),
(68, 24, 1, 5, '2025-10-07', '09:15:00', 'positive', 9, 'Bonne méthode.', '2025-10-21 12:17:28', '00:00:00'),
(69, 25, 3, 6, '2025-10-07', '10:45:00', 'negative', 6, 'Besoin d’amélioration.', '2025-10-21 12:17:28', '00:00:00'),
(70, 26, 2, 15, '2025-10-08', '11:00:00', 'positive', 8, 'Bien fait.', '2025-10-21 12:17:28', '00:00:00'),
(71, 27, 1, 17, '2025-10-08', '12:15:00', 'positive', 9, 'Très professionnel.', '2025-10-21 12:17:28', '00:00:00'),
(72, 28, 2, 5, '2025-10-09', '09:30:00', 'negative', 5, 'Quelques erreurs.', '2025-10-21 12:17:28', '00:00:00'),
(73, 29, 1, 6, '2025-10-09', '10:30:00', 'positive', 8, 'Correct.', '2025-10-21 12:17:28', '00:00:00'),
(74, 30, 3, 15, '2025-10-10', '11:45:00', 'positive', 9, 'Très clair.', '2025-10-21 12:17:28', '00:00:00'),
(75, 31, 2, 17, '2025-10-10', '12:45:00', 'negative', 4, 'Manque de préparation.', '2025-10-21 12:17:28', '18:00:00'),
(76, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:29', '17:50:00'),
(77, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:30', '17:50:00'),
(78, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:31', '17:50:00'),
(79, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:31', '17:50:00'),
(80, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:32', '17:50:00'),
(81, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:34', '17:50:00'),
(82, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:34', '17:50:00'),
(83, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:34', '17:50:00'),
(84, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:36', '17:50:00'),
(85, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:36', '17:50:00'),
(86, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:45', '17:50:00'),
(87, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:46', '17:50:00'),
(88, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:46', '17:50:00'),
(89, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:47', '17:50:00'),
(90, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:47', '17:50:00'),
(91, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:47', '17:50:00'),
(92, 21, 1, 15, '2025-02-21', '14:46:00', 'positive', 8, 'RAS', '2025-10-21 14:47:47', '17:50:00'),
(93, 12, 15, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:52:54', '17:55:00'),
(94, 12, 15, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:52:55', '17:55:00'),
(95, 12, 15, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:52:56', '17:55:00'),
(96, 12, 15, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:52:56', '17:55:00'),
(97, 12, 15, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:52:56', '17:55:00'),
(98, 12, 15, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:52:56', '17:55:00'),
(99, 12, 15, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:52:57', '17:55:00'),
(100, 12, 13, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:54:40', '17:55:00'),
(101, 12, 13, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:54:43', '17:55:00'),
(102, 12, 13, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:54:43', '17:55:00'),
(103, 12, 13, 15, '2025-10-21', '14:52:00', 'positive', 9, 'RAS', '2025-10-21 14:54:43', '17:55:00'),
(104, 12, 10, 15, '2025-10-21', '14:55:00', 'negative', 1, 'RAS', '2025-10-21 14:55:43', '16:57:00'),
(105, 12, 10, 15, '2025-10-21', '14:55:00', 'negative', 1, 'RAS', '2025-10-21 14:55:44', '16:57:00'),
(106, 22, 3, 15, '2025-10-21', '14:58:00', 'positive', 8, 'ras', '2025-10-21 14:58:48', '16:00:00'),
(107, 12, 15, 15, '2025-10-21', '15:23:00', 'positive', 10, 'RAS', '2025-10-21 15:23:37', '16:25:00'),
(108, 12, 15, 15, '2025-11-08', '15:24:00', 'positive', 5, 'hh', '2025-10-21 15:24:47', '15:30:00'),
(109, 12, 15, 15, '2025-10-21', '15:26:00', 'positive', 8, 'h', '2025-10-21 15:26:45', '15:30:00'),
(110, 26, 10, 15, '2025-10-21', '00:14:00', 'positive', 10, 'Le tout est bien', '2025-10-21 21:12:00', '02:16:00');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `id_stagiaire` int(11) NOT NULL,
  `type` enum('samedi & dimanche','exceptionnelle','vacance') NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `motif` text DEFAULT NULL,
  `statut` enum('acceptee','refusee','en_attente') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `id_stagiaire`, `type`, `date_debut`, `date_fin`, `motif`, `statut`) VALUES
(13, 15, '', '2025-10-16', '2025-10-17', 'MARIAGE', 'acceptee'),
(14, 15, 'exceptionnelle', '2025-10-16', '2025-10-26', 'Problemes administratives', 'acceptee'),
(15, 15, 'exceptionnelle', '2025-10-16', '2025-10-31', 'PTC', 'acceptee'),
(16, 19, 'samedi & dimanche', '2025-10-16', '2025-10-17', 'WEEK-END', 'acceptee'),
(17, 15, 'samedi & dimanche', '2025-10-16', '2025-10-17', 'VISTE FAMILLE', 'acceptee');

-- --------------------------------------------------------

--
-- Table structure for table `punitions`
--

CREATE TABLE `punitions` (
  `id` int(11) NOT NULL,
  `id_stagiaire` int(11) NOT NULL,
  `type` enum('samedi & dimanche','piquet','permanence','chef de poste','Garde','Corvet','LD 4 Jrs','LD 8 Jrs','LD 10 Jrs','LD 15 Jrs','LD 25 Jrs','LD 30 Jrs','LD 40 Jrs') NOT NULL,
  `description` text DEFAULT NULL,
  `date_punition` date DEFAULT curdate(),
  `auteur_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `punitions`
--

INSERT INTO `punitions` (`id`, `id_stagiaire`, `type`, `description`, `date_punition`, `auteur_id`) VALUES
(13, 15, 'LD 40 Jrs', 'RETARD (5min)', '2025-10-16', NULL),
(14, 15, '', 'mal coiffure', '2025-10-16', NULL),
(15, 15, 'chef de poste', 'Refus d&#039;ordre', '2025-10-16', NULL),
(16, 19, 'piquet', 'Absent Rassemblement', '2025-10-16', NULL),
(17, 15, 'piquet', 'RETARD', '2025-10-16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `remarques`
--

CREATE TABLE `remarques` (
  `id` int(11) NOT NULL,
  `id_stagiaire` int(11) NOT NULL,
  `remarque` text NOT NULL,
  `date_remarque` date DEFAULT curdate(),
  `auteur_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remarques`
--

INSERT INTO `remarques` (`id`, `id_stagiaire`, `remarque`, `date_remarque`, `auteur_id`) VALUES
(8, 15, 'Montre un excellent sens des responsabilités.', '2025-10-16', NULL),
(9, 15, 'Travaille de manière autonome et efficace.', '2025-10-16', NULL),
(10, 15, 'Doit améliorer la gestion du temps', '2025-10-16', NULL),
(11, 19, 'Execelent !!', '2025-10-16', NULL),
(12, 15, 'RETARD', '2025-10-16', NULL),
(13, 16, 'd', '2025-10-18', 5);

-- --------------------------------------------------------

--
-- Table structure for table `specialites`
--

CREATE TABLE `specialites` (
  `id` int(11) NOT NULL,
  `nom_specialite` varchar(150) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specialites`
--

INSERT INTO `specialites` (`id`, `nom_specialite`, `description`) VALUES
(1, 'Informatique/developement', ''),
(2, 'Informatique/CyberSecurity', ''),
(3, 'Informatique/Infographie', ''),
(4, 'Informatique/Reseaux', 'Technologie, communication, sécurité.'),
(5, 'Technitien / SIC', 'pour les stagiaires technitiens'),
(8, 'SSE', 'Systeme de Surveillance Electronique'),
(11, 'RADAR', 'radar');

-- --------------------------------------------------------

--
-- Table structure for table `stages`
--

CREATE TABLE `stages` (
  `id` int(11) NOT NULL,
  `intitule` varchar(150) NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stages`
--

INSERT INTO `stages` (`id`, `intitule`, `date_debut`, `date_fin`) VALUES
(1, 'CAT1', '2025-09-29', '2025-10-31'),
(2, 'CAT2', '2025-10-17', '2025-10-31'),
(3, 'SPU', '2025-10-17', '2025-10-28'),
(4, 'CCS', '2025-09-30', '2025-11-02'),
(7, 'HTT', '2025-10-07', '2025-10-31'),
(10, 'ASF', '2025-10-08', '2025-10-26'),
(11, 'BS', '2025-10-16', '2025-10-26'),
(12, 'BCM', '2025-10-16', '2025-10-26'),
(13, 'CCP', '2025-10-18', '2025-10-31');

-- --------------------------------------------------------

--
-- Table structure for table `stagiaires`
--

CREATE TABLE `stagiaires` (
  `id` int(11) NOT NULL,
  `matricule` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `groupe_sanguin` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `grade` enum('Lieutenant','Sous-Lieutenant','Adjudant Chef','Adjudant','Sergent Chef','Sergent','Caporal Chef','Caporal','2 eme Classe','1er Classe') DEFAULT NULL,
  `date_inscription` date DEFAULT curdate(),
  `id_stage` int(11) NOT NULL,
  `id_specialite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stagiaires`
--

INSERT INTO `stagiaires` (`id`, `matricule`, `nom`, `prenom`, `date_naissance`, `adresse`, `telephone`, `email`, `groupe_sanguin`, `photo`, `grade`, `date_inscription`, `id_stage`, `id_specialite`) VALUES
(15, 'A0707679268', 'ABDELHAMID', 'ELKARCH', '2006-01-11', 'MEKNES', '0706984671', 'elkarchabdo@gmail.com', 'A+', '68f0a82778ba9.png', 'Sergent', '2025-10-16', 3, 1),
(16, 'M0798765423', 'MOHAMMED', 'OUDRHIRI', '2005-06-17', 'FES', '0631730697', 'medidrissi@gmail.com', 'A-', '68f0a8bcb24d1.png', 'Sergent', '2025-10-16', 3, 2),
(17, 'S0798765432', 'SANAE', 'EL HAMDOUCHI', '2004-06-05', 'KHEMISSAT', '0798765432', 'sandouchi@gmail.com', 'A+', '68f0a930455d8.png', 'Sergent', '2025-10-16', 4, 3),
(18, 'Z9876543212', 'AZIZA', 'AS-SBAIY', '2005-02-04', 'TIFLET', '0687543622', 'azizasbaiy@gmail.com', 'B-', '68f0a991887cb.png', 'Sergent', '2025-10-16', 7, 4),
(19, 'J907076698', 'OMAR', 'OUDGHIRI', '2002-01-01', 'MEKNES', '0767876543', 'abdo@gmail.com', 'O+', '68f0b58068ad0.png', 'Sergent', '2025-10-16', 3, 1),
(20, 'M0000', 'MOHAMMED', 'ELKERCH', '2025-10-18', 'MEKNES', '0687543622', 'elkarchabdo@gmail.com', 'B+', '68f0bab01315b.png', '', '2025-10-16', 12, 8);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id_subject` int(11) NOT NULL,
  `stage_id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `type` enum('militaire','universitaire') NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id_subject`, `stage_id`, `code`, `name`, `type`, `file`, `created_at`) VALUES
(1, 7, NULL, 'tactique', 'militaire', 'uploads/subjects/1761057575_UML.pdf', '2025-10-19 03:47:38'),
(2, 11, NULL, 'reglement', 'militaire', 'uploads/subjects/1761057561_Administration BD 2023.ppt', '2025-10-19 03:47:38'),
(3, 10, NULL, 'IST', 'militaire', 'uploads/subjects/1761057550_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(4, 13, NULL, 'sécurité militaire', 'militaire', 'uploads/subjects/1761057571_UML.pdf', '2025-10-19 03:47:38'),
(6, 4, NULL, 'topographie', 'militaire', 'uploads/subjects/1761057584_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(7, 11, NULL, 'génie informatique', 'universitaire', 'uploads/subjects/1761057598_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(8, 10, NULL, 'cyber sécurité', 'universitaire', 'uploads/subjects/1761057589_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(9, 13, NULL, 'réseau informatique', 'universitaire', 'uploads/subjects/1761057604_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(10, 4, NULL, 'infographie', 'universitaire', 'uploads/subjects/1761057601_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(11, 2, NULL, 'technique', 'universitaire', 'uploads/subjects/1761057608_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(12, 12, NULL, 'exploitation', 'universitaire', 'uploads/subjects/1761057595_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(13, 7, NULL, 'SSE', 'universitaire', 'uploads/subjects/1761057606_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(14, 1, NULL, 'Efficacité énergétique', 'universitaire', 'uploads/subjects/1761057592_PHYSIQUE ELECTRICITE 2eme  annee.pdf', '2025-10-19 03:47:38'),
(15, 1, NULL, 'armement', 'militaire', 'uploads/subjects/1761057516_UML.pdf', '2025-10-20 08:52:43'),
(20, 12, NULL, 'francais', 'militaire', 'uploads/subjects/1761078135_UML.pdf', '2025-10-21 21:15:36'),
(21, 7, NULL, 'Anglais', 'universitaire', 'uploads/subjects/1761078237_UML.pdf', '2025-10-21 21:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `subject_files`
--

CREATE TABLE `subject_files` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_files`
--

INSERT INTO `subject_files` (`id`, `subject_id`, `file_path`, `file_name`, `file_type`, `file_size`, `uploaded_at`) VALUES
(3, 15, 'uploads/subjects/1761087628_68f8108c3f664_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:00:28'),
(4, 15, 'uploads/subjects/1761087628_68f8108c3fa32_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:00:28'),
(5, 15, 'uploads/subjects/1761087628_68f8108c467b5_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:00:28'),
(6, 20, 'uploads/subjects/1761087636_68f81094725b8_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:00:36'),
(7, 20, 'uploads/subjects/1761087636_68f8109472845_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:00:36'),
(8, 20, 'uploads/subjects/1761087636_68f8109472dcf_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:00:36'),
(9, 3, 'uploads/subjects/1761087640_68f81098ab49f_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:00:40'),
(10, 3, 'uploads/subjects/1761087640_68f81098ab763_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:00:40'),
(11, 3, 'uploads/subjects/1761087640_68f81098aba3e_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:00:40'),
(12, 2, 'uploads/subjects/1761087696_68f810d0d62f8_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:01:36'),
(13, 2, 'uploads/subjects/1761087696_68f810d0d666d_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:01:36'),
(14, 2, 'uploads/subjects/1761087696_68f810d0d97f5_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:01:36'),
(15, 4, 'uploads/subjects/1761087700_68f810d478b18_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:01:40'),
(16, 4, 'uploads/subjects/1761087700_68f810d479083_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:01:40'),
(17, 4, 'uploads/subjects/1761087700_68f810d479543_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:01:40'),
(18, 1, 'uploads/subjects/1761087702_68f810d6cb6ad_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:01:42'),
(19, 1, 'uploads/subjects/1761087702_68f810d6cb960_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:01:42'),
(20, 1, 'uploads/subjects/1761087702_68f810d6cbc61_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:01:42'),
(21, 6, 'uploads/subjects/1761087705_68f810d988c5b_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:01:45'),
(22, 6, 'uploads/subjects/1761087705_68f810d988f32_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:01:45'),
(23, 6, 'uploads/subjects/1761087705_68f810d98910a_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:01:45'),
(24, 21, 'uploads/subjects/1761087711_68f810df261cb_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:01:51'),
(25, 21, 'uploads/subjects/1761087711_68f810df26704_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:01:51'),
(26, 21, 'uploads/subjects/1761087711_68f810df26c27_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:01:51'),
(27, 8, 'uploads/subjects/1761087714_68f810e277602_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:01:54'),
(28, 8, 'uploads/subjects/1761087714_68f810e277a28_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:01:54'),
(29, 8, 'uploads/subjects/1761087714_68f810e27e448_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:01:54'),
(30, 14, 'uploads/subjects/1761087717_68f810e5d6209_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:01:57'),
(31, 14, 'uploads/subjects/1761087717_68f810e5d671c_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:01:57'),
(32, 14, 'uploads/subjects/1761087717_68f810e5d6bb8_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:01:57'),
(33, 12, 'uploads/subjects/1761087720_68f810e81eb51_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:02:00'),
(34, 12, 'uploads/subjects/1761087720_68f810e81edec_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:02:00'),
(35, 12, 'uploads/subjects/1761087720_68f810e81efee_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:02:00'),
(36, 7, 'uploads/subjects/1761087722_68f810ea807ea_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:02:02'),
(37, 7, 'uploads/subjects/1761087722_68f810ea80c22_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:02:02'),
(38, 7, 'uploads/subjects/1761087722_68f810ea811d8_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:02:02'),
(39, 10, 'uploads/subjects/1761087725_68f810ed07f57_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:02:05'),
(40, 10, 'uploads/subjects/1761087725_68f810ed082a7_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:02:05'),
(41, 10, 'uploads/subjects/1761087725_68f810ed08935_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:02:05'),
(42, 9, 'uploads/subjects/1761087727_68f810efcf37c_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:02:07'),
(43, 9, 'uploads/subjects/1761087727_68f810efcf6d2_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:02:07'),
(44, 9, 'uploads/subjects/1761087727_68f810efe0924_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:02:07'),
(45, 13, 'uploads/subjects/1761087731_68f810f3ac138_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:02:11'),
(46, 13, 'uploads/subjects/1761087731_68f810f3ac454_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:02:11'),
(47, 13, 'uploads/subjects/1761087731_68f810f3ac8d7_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:02:11'),
(48, 11, 'uploads/subjects/1761087734_68f810f685194_AdministrationBD2023.ppt', 'Administration BD 2023.ppt', 'ppt', 5256704, '2025-10-21 23:02:14'),
(49, 11, 'uploads/subjects/1761087734_68f810f6854b8_PHYSIQUEELECTRICITE2emeannee.pdf', 'PHYSIQUE ELECTRICITE 2eme  annee.pdf', 'pdf', 1997053, '2025-10-21 23:02:14'),
(50, 11, 'uploads/subjects/1761087734_68f810f6856c6_UML.pdf', 'UML.pdf', 'pdf', 1362546, '2025-10-21 23:02:14'),
(133, 15, 'uploads/subjects/1761090293_68f81af54eb2b_MEMOENTOTACTIQUE.doc', 'MEMOENTO TACTIQUE.doc', 'doc', 1296384, '2025-10-21 23:44:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','secretaire','docteur','cellule_pedagogique','instructor') NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nom`, `prenom`, `email`, `created_at`) VALUES
(5, 'secretaire', '$2y$10$N0xtYiL96pF6Xar6qAWQpeEKmHOeUuic5xO.QAce.GDVkXwohl7hC', 'secretaire', 'abdelhamid', 'elkarch', 'elkarchabdo@gmail.com', '2025-10-03 10:43:18'),
(6, 'docteur', '$2y$10$h7xTNn95ZMHXchIaIsPXBe454qJupjo1p6kaT1F3ZcyTawDkIICae', 'docteur', 'mohammed', 'khaldoun', 'dr.khaldoun@gmail.com', '2025-10-03 10:43:18'),
(15, 'cellule', '$2y$10$k1DXMgMkuL.2l5s22MPP.eqRJiy..lUkVuxeyeKSo3c5GjPqyjT0q', 'cellule_pedagogique', 'Cellule', 'Pedagogique', 'cellule@example.com', '2025-10-19 14:28:04'),
(17, 'admin', '$2y$10$9VuGgTBIdm9mXE9MnAQW4ufcsJ5/OsiAg5pULZ38bRguz0DW0BFmy', 'admin', 'Administrator', 'System', 'admin@pbst.com', '2025-10-20 15:28:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_stagiaire` (`id_stagiaire`),
  ADD KEY `id_docteur` (`id_docteur`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id_instructor`),
  ADD UNIQUE KEY `cine` (`cine`),
  ADD UNIQUE KEY `mle` (`mle`),
  ADD KEY `fk_speciality` (`speciality_id`);

--
-- Indexes for table `monthly_instructor_stats`
--
ALTER TABLE `monthly_instructor_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instructor_id` (`instructor_id`,`year`,`month`);

--
-- Indexes for table `observations`
--
ALTER TABLE `observations`
  ADD PRIMARY KEY (`id_observation`),
  ADD KEY `fk_obs_subject` (`subject_id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `obs_date` (`obs_date`),
  ADD KEY `observed_by_user_id` (`observed_by_user_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_stagiaire` (`id_stagiaire`);

--
-- Indexes for table `punitions`
--
ALTER TABLE `punitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_stagiaire` (`id_stagiaire`),
  ADD KEY `auteur_id` (`auteur_id`);

--
-- Indexes for table `remarques`
--
ALTER TABLE `remarques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_stagiaire` (`id_stagiaire`),
  ADD KEY `auteur_id` (`auteur_id`);

--
-- Indexes for table `specialites`
--
ALTER TABLE `specialites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stages`
--
ALTER TABLE `stages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stagiaires`
--
ALTER TABLE `stagiaires`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD KEY `id_stage` (`id_stage`),
  ADD KEY `id_specialite` (`id_specialite`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id_subject`),
  ADD UNIQUE KEY `name` (`name`,`type`),
  ADD KEY `fk_stage_subject` (`stage_id`);

--
-- Indexes for table `subject_files`
--
ALTER TABLE `subject_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

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
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id_instructor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `monthly_instructor_stats`
--
ALTER TABLE `monthly_instructor_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `observations`
--
ALTER TABLE `observations`
  MODIFY `id_observation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `punitions`
--
ALTER TABLE `punitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `remarques`
--
ALTER TABLE `remarques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `specialites`
--
ALTER TABLE `specialites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `stages`
--
ALTER TABLE `stages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stagiaires`
--
ALTER TABLE `stagiaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id_subject` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `subject_files`
--
ALTER TABLE `subject_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_ibfk_1` FOREIGN KEY (`id_stagiaire`) REFERENCES `stagiaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultations_ibfk_2` FOREIGN KEY (`id_docteur`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `fk_speciality` FOREIGN KEY (`speciality_id`) REFERENCES `specialites` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `monthly_instructor_stats`
--
ALTER TABLE `monthly_instructor_stats`
  ADD CONSTRAINT `fk_mis_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id_instructor`) ON DELETE CASCADE;

--
-- Constraints for table `observations`
--
ALTER TABLE `observations`
  ADD CONSTRAINT `fk_obs_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id_instructor`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_obs_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id_subject`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_obs_user` FOREIGN KEY (`observed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`id_stagiaire`) REFERENCES `stagiaires` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `punitions`
--
ALTER TABLE `punitions`
  ADD CONSTRAINT `punitions_ibfk_1` FOREIGN KEY (`id_stagiaire`) REFERENCES `stagiaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `punitions_ibfk_2` FOREIGN KEY (`auteur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `remarques`
--
ALTER TABLE `remarques`
  ADD CONSTRAINT `remarques_ibfk_1` FOREIGN KEY (`id_stagiaire`) REFERENCES `stagiaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `remarques_ibfk_2` FOREIGN KEY (`auteur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stagiaires`
--
ALTER TABLE `stagiaires`
  ADD CONSTRAINT `stagiaires_ibfk_1` FOREIGN KEY (`id_stage`) REFERENCES `stages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stagiaires_ibfk_2` FOREIGN KEY (`id_specialite`) REFERENCES `specialites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `fk_stage_subject` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subject_files`
--
ALTER TABLE `subject_files`
  ADD CONSTRAINT `subject_files_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id_subject`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
