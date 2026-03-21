-- Base de données TerangaHomes
CREATE DATABASE IF NOT EXISTS terangahomes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE terangahomes;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    role ENUM('visiteur', 'utilisateur', 'proprietaire', 'admin') DEFAULT 'utilisateur',
    avatar VARCHAR(255),
    bio TEXT,
    email_verified BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
);

-- Table des catégories d'annonces
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des annonces
CREATE TABLE annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    type ENUM('location', 'vente', 'hotel', 'voiture') NOT NULL,
    categorie_id INT,
    user_id INT NOT NULL,
    ville VARCHAR(100) NOT NULL,
    quartier VARCHAR(100),
    adresse TEXT,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    superficie DECIMAL(8,2),
    chambres INT,
    salles_bain INT,
    parking BOOLEAN DEFAULT FALSE,
    meuble BOOLEAN DEFAULT FALSE,
    climatisation BOOLEAN DEFAULT FALSE,
    wifi BOOLEAN DEFAULT FALSE,
    piscine BOOLEAN DEFAULT FALSE,
    images JSON,
    statut ENUM('active', 'inactive', 'vendu', 'loue') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_type (type),
    INDEX idx_ville (ville),
    INDEX idx_prix (prix),
    INDEX idx_statut (statut),
    INDEX idx_user (user_id),
    INDEX idx_featured (featured),
    FULLTEXT idx_search (titre, description, ville)
);

-- Table des favoris
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    annonce_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, annonce_id),
    INDEX idx_user (user_id),
    INDEX idx_annonce (annonce_id)
);

-- Table des commentaires
CREATE TABLE commentaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    user_id INT NOT NULL,
    contenu TEXT NOT NULL,
    note DECIMAL(3,2) CHECK (note >= 0 AND note <= 5),
    statut ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_annonce (annonce_id),
    INDEX idx_user (user_id),
    INDEX idx_statut (statut)
);

-- Table des messages
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    annonce_id INT,
    sujet VARCHAR(200),
    contenu TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_deleted_sender BOOLEAN DEFAULT FALSE,
    is_deleted_receiver BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE SET NULL,
    INDEX idx_sender (sender_id),
    INDEX idx_receiver (receiver_id),
    INDEX idx_annonce (annonce_id),
    INDEX idx_read (is_read)
);

-- Table des réservations
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    user_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    statut ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_annonce (annonce_id),
    INDEX idx_user (user_id),
    INDEX idx_statut (statut),
    INDEX idx_dates (date_debut, date_fin)
);

-- Table des abonnements
CREATE TABLE abonnements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('basic', 'premium', 'pro') DEFAULT 'basic',
    prix_mensuel DECIMAL(8,2),
    fonctionnalités JSON,
    date_debut DATE NOT NULL,
    date_fin DATE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_type (type),
    INDEX idx_active (is_active)
);

-- Table des paiements
CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reservation_id INT,
    montant DECIMAL(10,2) NOT NULL,
    methode ENUM('carte', 'mobile_money', 'virement', 'espece') NOT NULL,
    statut ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_reservation (reservation_id),
    INDEX idx_statut (statut)
);

-- Insertion des données initiales
INSERT INTO categories (nom, description, icon) VALUES
('Appartements', 'Appartements à louer ou à vendre', 'building'),
('Maisons', 'Maisons individuelles et villas', 'home'),
('Terrains', 'Terrains à bâtir', 'map'),
('Bureaux', 'Bureaux et espaces commerciaux', 'briefcase'),
('Hôtels', 'Chambres d\'hôtel et résidences', 'hotel'),
('Voitures', 'Location de véhicules', 'car');

-- Insertion de l'administrateur par défaut
INSERT INTO users (nom, prenom, email, password, role, email_verified, is_active) VALUES
('Admin', 'TerangaHomes', 'admin@terangahomes.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE, TRUE);

-- Création des dossiers pour les uploads
-- (À exécuter manuellement ou via le script d'installation)
-- mkdir uploads
-- mkdir uploads/images
-- mkdir uploads/avatars
-- chmod 755 uploads -R
