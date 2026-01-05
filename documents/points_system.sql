-- Système de points pour JPO MMI
-- Tables pour la géamification des stands

-- Table des stands
CREATE TABLE stands (
    id_stand INT PRIMARY KEY AUTO_INCREMENT,
    nom_stand VARCHAR(255) NOT NULL,
    description TEXT,
    points_disponibles VARCHAR(50) DEFAULT '3,6,9', -- Points que ce stand peut donner
    actif TINYINT(1) DEFAULT 1,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des membres (étudiants qui gèrent les stands)
CREATE TABLE membres (
    id_membre INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    code_membre VARCHAR(50) UNIQUE NOT NULL, -- Code unique pour identifier le membre
    id_stand INT,
    actif TINYINT(1) DEFAULT 1,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_stand) REFERENCES stands(id_stand) ON DELETE SET NULL
);

-- Table des visiteurs
CREATE TABLE visiteurs (
    id_visiteur INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    qr_code VARCHAR(100) UNIQUE NOT NULL, -- QR code unique du visiteur
    total_points INT DEFAULT 0,
    actif TINYINT(1) DEFAULT 1,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des transactions de points
CREATE TABLE transactions_points (
    id_transaction INT PRIMARY KEY AUTO_INCREMENT,
    id_visiteur INT NOT NULL,
    id_membre INT NOT NULL,
    id_stand INT NOT NULL,
    points INT NOT NULL,
    commentaire TEXT,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME,
    modifie TINYINT(1) DEFAULT 0,
    FOREIGN KEY (id_visiteur) REFERENCES visiteurs(id_visiteur) ON DELETE CASCADE,
    FOREIGN KEY (id_membre) REFERENCES membres(id_membre) ON DELETE CASCADE,
    FOREIGN KEY (id_stand) REFERENCES stands(id_stand) ON DELETE CASCADE
);

-- Données de test
INSERT INTO stands (nom_stand, description, points_disponibles) VALUES
('Stand Créa', 'Stand création graphique et design', '3,6,9'),
('Stand Dev', 'Stand développement web', '3,6,9'),
('Stand Com', 'Stand communication digitale', '3,6,9');

-- Exemples de membres
INSERT INTO membres (nom, prenom, code_membre, id_stand) VALUES
('Dupont', 'Marie', 'MEMBRE001', 1),
('Martin', 'Lucas', 'MEMBRE002', 2),
('Bernard', 'Sophie', 'MEMBRE003', 3);

-- Exemples de visiteurs
INSERT INTO visiteurs (nom, prenom, email, qr_code) VALUES
('Visiteur', 'Test', 'test@example.com', 'QR001'),
('Lemoine', 'Pierre', 'pierre@example.com', 'QR002');
