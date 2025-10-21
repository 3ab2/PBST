-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 12:40 PM
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
(6, 1, 6, '2025-10-04', 'ff', 'ff', 'ff', 'uploads/consultations/1759608663_3.jpg'),
(10, 2, 6, '2025-10-04', 'ss', 'ss', 'ss', 'uploads/consultations/1759608833_2.jpg'),
(11, 2, 6, '2025-10-04', 'f', 'df', 'f', 'uploads/consultations/1759609642_3.jpg'),
(13, 2, 6, '2025-10-06', 'jhfdshf', 'wfw', 'dsgt', 'uploads/consultations/1759737147_5.jpg'),
(15, 6, 6, '2025-10-07', 'فيه الباسر', 'خاصو القويلبات', 'Repos', 'uploads/consultations/1759824757_1CAD0F1F-B24D-4D8A-B9BF-89736535EB34.png'),
(19, 6, 6, '2025-10-07', 'hhhh', 'hhhh', 'hhh', 'uploads/consultations/1759841802_management.png'),
(22, 2, 6, '2025-10-09', 'jh3', 'flkwq', 'repos', 'uploads/consultations/1760003948_bst.png'),
(23, 6, 6, '2025-10-15', 'sarcopenie', 'il a besoin de l&#039;acide lactique', 'Repos 4 jrs', 'uploads/consultations/1760521334_stagiaire_1 (2).pdf');

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
(2, 1, 'vacance', '2000-09-09', '2025-10-10', 'fsf', 'acceptee'),
(4, 6, 'exceptionnelle', '2025-10-06', '2025-10-31', 'A', 'acceptee'),
(5, 7, 'samedi & dimanche', '2025-10-06', '2025-10-25', 'b', 'acceptee'),
(6, 1, 'samedi & dimanche', '2025-10-06', '2025-11-02', 'n', 'acceptee'),
(7, 8, 'exceptionnelle', '2025-10-07', '2025-10-16', 'Filicitation de monsieur le colonnel', 'acceptee'),
(8, 7, 'exceptionnelle', '2025-10-17', '2025-10-31', '', 'acceptee'),
(9, 9, 'exceptionnelle', '2025-10-10', '2025-10-31', 'D', 'acceptee'),
(10, 6, 'samedi & dimanche', '2025-10-07', '2025-10-31', 'HH', 'acceptee'),
(11, 8, 'samedi & dimanche', '2025-10-08', '2025-11-01', '', 'acceptee');

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
(5, 1, 'LD 40 Jrs', '', '2025-10-06', NULL),
(7, 2, 'LD 8 Jrs', '', '2025-10-06', NULL),
(8, 6, 'LD 15 Jrs', '', '2025-10-06', NULL),
(9, 8, 'LD 15 Jrs', '', '2025-10-07', 6),
(10, 8, 'LD 15 Jrs', 'hh', '2025-10-07', 5),
(11, 1, 'LD 15 Jrs', '', '2025-10-08', NULL);

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
(1, 1, 'kaymchi kaytmcha machi pas de gym', '2025-10-03', 4),
(3, 1, 'hhhh', '2025-10-04', 4),
(4, 6, 'il a creer une application', '2025-10-06', 6),
(5, 6, 'SA', '2025-10-07', 5),
(6, 8, 'J', '2025-10-07', NULL);

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
(4, 'Informatique/Reseaux', ''),
(5, 'Technitien / SIC', ''),
(8, 'SSE', '');

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
(8, 'DDR', '2025-10-18', '2025-10-26'),
(10, 'ASF', '2025-10-08', '2025-10-26');

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
(1, 'M0000', 'MEED', 'OUDGHIRI', '2005-04-02', 'FEZ', '07675434652', 'meed@gmail.com', 'A+', '68dfda3508663.jpg', '', '2025-10-03', 3, 1),
(2, 'A1111', 'ABDO', 'ELKERCH', '2003-03-01', 'MEKNES', '0879721842', 'abdo@gmail.com', 'AB-', '68dfda9e501e8.jpg', '', '2025-10-03', 2, 3),
(6, 'B6653', 'HAHA', 'PEDRI', '2003-01-01', 'SMARA', '0687543622', 'abdouuu@gmail.com', 'AB-', '68e3eb4cb426f.jpg', 'Lieutenant', '2025-10-03', 3, 3),
(7, 'G2222', 'ADJT', 'GOUT', '0000-00-00', 'd', '0879721842', 'sadik@gmail.com', 'A+', '68e4335d37049.png', 'Adjudant', '2025-10-06', 4, 8),
(8, '98827277', 'AIT', 'HAMOU', '1998-10-07', 'SALE TABRIKT', '06837373628', 'aithamou@gmail.com', 'AB-', '68e4d10613933.jpeg', '', '2025-10-07', 7, 5),
(9, 'L9876444', 'OMAR', 'RADI', '2025-10-07', 'TANGER', '0686354537', 'omar@gmail.com', 'A+', '68e4ef97391c5.png', 'Lieutenant', '2025-10-07', 7, 8),
(11, 'S34567', 'naruto', 'uzumaki', '2001-06-07', 'konoha', '0767876543', 'uzumakisanae@gmail.com', 'A+', '68e50e986e0ba.png', '', '2025-10-07', 3, 8),
(12, 'Z234556', 'ZAKI', 'FIGO', '2025-10-08', 'ff', '0767876543', 'meed@gmail.com', 'B-', '68e78f5a9fb96.png', 'Sous-Lieutenant', '2025-10-08', 1, 8),
(13, '24-30-24/6465746', 'ELHAMDOUCHI', 'SANAE', '2004-02-15', 'KHMISSAT', '0687878474', 'sanae@gmail.com', 'A+', '68ef6b1ecfecd.png', 'Sergent', '2025-10-15', 7, 4),
(14, 'A87876837382', 'ES-SEBAIY', 'AZIZA', '2005-06-28', 'TIFLET', '0687543622', 'aziza@gmail.com', 'O-', '68ef6bba4bedc.jpg', 'Sergent', '2023-01-01', 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','secretaire','docteur') NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nom`, `prenom`, `email`, `created_at`) VALUES
(4, 'Colonnel erradi', '$2y$10$pUqI9Gl4EzalT.MVmMyBmOYsRaKxi.HqE8umeYwN3U1sMsiayQ2dm', 'admin', 'Abdelhafid', 'Erradi', 'colonelerradi@gmail.com', '2025-10-03 10:43:18'),
(5, 'Sgt elkarch', '$2y$10$N0xtYiL96pF6Xar6qAWQpeEKmHOeUuic5xO.QAce.GDVkXwohl7hC', 'secretaire', 'abdelhamid', 'elkarch', 'elkarchabdo@gmail.com', '2025-10-03 10:43:18'),
(6, 'Dr khaldoun', '$2y$10$h7xTNn95ZMHXchIaIsPXBe454qJupjo1p6kaT1F3ZcyTawDkIICae', 'docteur', 'mohammed', 'khaldoun', 'dr.khaldoun@gmail.com', '2025-10-03 10:43:18');

--

-- Update users table to include new roles
ALTER TABLE users MODIFY COLUMN role ENUM('admin','secretaire','docteur','cellule_pedagogique','instructor') NOT NULL;

-- Create instructors table
CREATE TABLE instructors (
  id_instructor INT AUTO_INCREMENT PRIMARY KEY,
  cine VARCHAR(50) NOT NULL UNIQUE,
  mle VARCHAR(50) NOT NULL UNIQUE,
  username VARCHAR(50) NOT NULL UNIQUE,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(150),
  phone VARCHAR(30),
  bio TEXT,
  password VARCHAR(255) NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  speciality_id INT  NULL,
);

-- Create subjects table
CREATE TABLE subjects (
  id_subject INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  type ENUM('militaire','universitaire') NOT NULL,
  stage_id INT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(name, type),
  CONSTRAINT fk_subjects_stage FOREIGN KEY (stage_id) REFERENCES stages(id) ON DELETE SET NULL
);

-- Create instructor_subjects table
CREATE TABLE instructor_subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  instructor_id INT NOT NULL,
  subject_id INT NOT NULL,
  is_primary TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_isub_instructor FOREIGN KEY (instructor_id) REFERENCES instructors(id_instructor) ON DELETE CASCADE,
  CONSTRAINT fk_isub_subject FOREIGN KEY (subject_id) REFERENCES subjects(id_subject) ON DELETE CASCADE,
  UNIQUE(instructor_id, subject_id)
);

-- Create lessons table
CREATE TABLE lessons (
  id_lesson INT AUTO_INCREMENT PRIMARY KEY,
  instructor_id INT NOT NULL,
  subject_id INT NOT NULL,
  title VARCHAR(255),
  file_path VARCHAR(500) NOT NULL,
  file_name VARCHAR(255),
  file_size BIGINT,
  uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  is_published TINYINT(1) DEFAULT 1,
  CONSTRAINT fk_lesson_instructor FOREIGN KEY (instructor_id) REFERENCES instructors(id_instructor) ON DELETE CASCADE,
  CONSTRAINT fk_lesson_subject FOREIGN KEY (subject_id) REFERENCES subjects(id_subject) ON DELETE CASCADE,
  INDEX(instructor_id), INDEX(subject_id)
);

-- Create observations table
CREATE TABLE observations (
  id_observation INT AUTO_INCREMENT PRIMARY KEY,
  instructor_id INT NOT NULL,
  subject_id INT NOT NULL,
  observed_by_user_id INT NULL,
  obs_date DATE NOT NULL,
  heure_debut TIME NOT NULL,
  heure_fin TIME NOT NULL,
  rating ENUM('positive','negative') NOT NULL,
  score TINYINT NULL,
  comment TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_obs_instructor FOREIGN KEY (instructor_id) REFERENCES instructors(id_instructor) ON DELETE CASCADE,
  CONSTRAINT fk_obs_subject FOREIGN KEY (subject_id) REFERENCES subjects(id_subject) ON DELETE CASCADE,
  CONSTRAINT fk_obs_user FOREIGN KEY (observed_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX(instructor_id), INDEX(obs_date), INDEX(observed_by_user_id)
);

-- Create subject_files table
CREATE TABLE subject_files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  subject_id INT NOT NULL,
  file_path VARCHAR(500) NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  file_type VARCHAR(50) NOT NULL,
  file_size BIGINT NOT NULL,
  uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_subject_files_subject FOREIGN KEY (subject_id) REFERENCES subjects(id_subject) ON DELETE CASCADE,
  INDEX(subject_id)
);

-- Create monthly_instructor_stats table
CREATE TABLE monthly_instructor_stats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  instructor_id INT NOT NULL,
  year INT NOT NULL,
  month INT NOT NULL,
  positive_count INT DEFAULT 0,
  negative_count INT DEFAULT 0,
  total INT DEFAULT 0,
  positive_ratio FLOAT DEFAULT 0.0,
  computed_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE(instructor_id, year, month),
  CONSTRAINT fk_mis_instructor FOREIGN KEY (instructor_id) REFERENCES instructors(id_instructor) ON DELETE CASCADE
);

-- Seed subjects
INSERT INTO subjects (name, type) VALUES
('tactique','militaire'),('reglement','militaire'),('IST','militaire'),
('sécurité militaire','militaire'),('armement','militaire'),('topographie','militaire'),
('génie informatique','universitaire'),('cyber sécurité','universitaire'),
('réseau informatique','universitaire'),('infographie','universitaire'),
('technique','universitaire'),('exploitation','universitaire'),
('SSE','universitaire'),('Efficacité énergétique','universitaire');

-- Sample instructors (passwords hashed with password_hash)
INSERT INTO instructors ( cine, mle,username, first_name, last_name, email, phone, bio, password, specialite) VALUES
( 'CINE001', 'MLE001','ahmed.benali', 'Ahmed', 'Benali', 'ahmed.benali@example.com', '0612345678', 'Experienced instructor in military tactics.', '$2y$10$dummy.hash.for.demo', 'Tactique Militaire'),
( 'CINE002', 'MLE002', 'fatima.zahra','Fatima', 'Zahra', 'fatima.zahra@example.com', '0623456789', 'Expert in computer science.', '$2y$10$dummy.hash.for.demo', 'Informatique');

-- Link instructors to subjects
INSERT INTO instructor_subjects (instructor_id, subject_id, is_primary) VALUES
(1, 1, 1), (1, 2, 0), -- Ahmed teaches tactique (primary) and reglement
(2, 7, 1), (2, 8, 0); -- Fatima teaches génie informatique (primary) and cyber sécurité

-- Migrate specialite to speciality_id foreign key
ALTER TABLE instructors ADD COLUMN speciality_id INT;

-- Populate speciality_id by matching existing specialite to specialites.nom_specialite
UPDATE instructors SET speciality_id = (
    SELECT s.id FROM specialites s WHERE s.nom_specialite = instructors.specialite
) WHERE specialite IS NOT NULL AND specialite != '';

-- Drop the old specialite column
ALTER TABLE instructors DROP COLUMN specialite;

-- Add foreign key constraint
ALTER TABLE instructors ADD CONSTRAINT fk_instructor_specialite
    FOREIGN KEY (speciality_id) REFERENCES specialites(id)


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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `punitions`
--
ALTER TABLE `punitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `remarques`
--
ALTER TABLE `remarques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `specialites`
--
ALTER TABLE `specialites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stages`
--
ALTER TABLE `stages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stagiaires`
--
ALTER TABLE `stagiaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
