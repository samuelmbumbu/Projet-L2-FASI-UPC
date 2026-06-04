CREATE DATABASE IF NOT EXISTS syanfraud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE syanfraud;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS fraud_logs;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(120) NOT NULL,
    username VARCHAR(80) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    pin_hash VARCHAR(255) DEFAULT NULL,
    photo_profil VARCHAR(255) DEFAULT NULL,
    role ENUM('client','admin') DEFAULT 'client',
    solde_compte DECIMAL(14,2) DEFAULT 25000.00,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    montant DECIMAL(14,2) NOT NULL,
    type_transaction VARCHAR(50) NOT NULL,
    lieu VARCHAR(50) NOT NULL,
    beneficiaire VARCHAR(100) NOT NULL,
    heure INT NOT NULL,
    frequence INT DEFAULT 0,
    temps_validation DECIMAL(8,2) DEFAULT 0,
    solde_compte DECIMAL(14,2) DEFAULT 0,
    anciennete_compte INT DEFAULT 0,
    montant_moyen DECIMAL(14,2) DEFAULT 0,
    ecart_montant DECIMAL(12,4) DEFAULT 0,
    tentatives_pin INT DEFAULT 1,
    beneficiaire_nouveau BOOLEAN DEFAULT TRUE,
    jour_semaine INT DEFAULT 1,
    probabilite DECIMAL(8,4) DEFAULT 0,
    resultat ENUM('validée','bloquée') DEFAULT 'validée',
    explication TEXT,
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE fraud_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    transaction_id INT NOT NULL,
    niveau_risque DECIMAL(8,4) NOT NULL,
    message TEXT,
    statut ENUM('non traitée','traitée') DEFAULT 'non traitée',
    decision_admin ENUM('fraude confirmée','fausse alerte','surveillance') DEFAULT NULL,
    commentaire_admin TEXT,
    traite_par INT DEFAULT NULL,
    date_traitement DATETIME DEFAULT NULL,
    date_alerte DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (traite_par) REFERENCES users(id) ON DELETE SET NULL
);
