-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS pbst_db
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE pbst_db;

-- جدول المستخدمين (admin, secretaire, docteur)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','secretaire','docteur') NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول الدورات (stages)
CREATE TABLE stages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    intitule VARCHAR(150) NOT NULL,
    date_debut DATE,
    date_fin DATE
);

-- جدول التخصصات (specialites)
CREATE TABLE specialites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_specialite VARCHAR(150) NOT NULL,
    description TEXT
);

-- جدول المتدربين (stagiaires)
CREATE TABLE stagiaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(50) UNIQUE NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE,
    adresse TEXT,
    telephone VARCHAR(20),
    email VARCHAR(100),
    date_inscription DATE DEFAULT CURRENT_DATE,
    groupe_sanguin ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-'),
    photo VARCHAR(255), 
    grade ENUM('Lieutenant','Sous-Lieutenant','Adjudant Chef','Adjudant' ,
    'Sergent Chef' ,'Sergent' ,'Caporal Chef' ,'Caporal','2 eme Classe', '1er Classe'),
    id_stage INT NOT NULL,
    id_specialite INT NOT NULL,
    FOREIGN KEY (id_stage) REFERENCES stages(id) ON DELETE CASCADE,
    FOREIGN KEY (id_specialite) REFERENCES specialites(id) ON DELETE CASCADE
);

-- جدول الاستشارات الطبية (consultations)
CREATE TABLE consultations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_stagiaire INT NOT NULL,
    id_docteur INT NOT NULL,
    date_consultation DATE NOT NULL,
    diagnostic TEXT,
    traitement TEXT,
    remarques TEXT,
    FOREIGN KEY (id_stagiaire) REFERENCES stagiaires(id) ON DELETE CASCADE,
    FOREIGN KEY (id_docteur) REFERENCES users(id) ON DELETE CASCADE
);

-- جدول الأذونات (permissions)
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_stagiaire INT NOT NULL,
    type ENUM('samedi & dimanche','exceptionnelle','vacance') NOT NULL,
    date_debut DATE,
    date_fin DATE,
    motif TEXT,
    statut ENUM('acceptee','refusee','en_attente') DEFAULT 'en_attente',
    FOREIGN KEY (id_stagiaire) REFERENCES stagiaires(id) ON DELETE CASCADE
);

-- جدول الملاحظات (remarques)
CREATE TABLE remarques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_stagiaire INT NOT NULL,
    remarque TEXT NOT NULL,
    date_remarque DATE DEFAULT CURRENT_DATE,
    auteur_id INT,
    FOREIGN KEY (id_stagiaire) REFERENCES stagiaires(id) ON DELETE CASCADE,
    FOREIGN KEY (auteur_id) REFERENCES users(id) ON DELETE SET NULL
);

-- جدول العقوبات (punitions)
CREATE TABLE punitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_stagiaire INT NOT NULL,
    type ENUM('samedi & dimanche','piquet','permanence','chef de poste','Garde','Corvet','LD 4 Jrs','LD 8 Jrs',
    'LD 10 Jrs','LD 15 Jrs','LD 25 Jrs','LD 30 Jrs','LD 40 Jrs') NOT NULL,
    description TEXT,
    date_punition DATE DEFAULT CURRENT_DATE,
    auteur_id INT,
    FOREIGN KEY (id_stagiaire) REFERENCES stagiaires(id) ON DELETE CASCADE,
    FOREIGN KEY (auteur_id) REFERENCES users(id) ON DELETE SET NULL
);
