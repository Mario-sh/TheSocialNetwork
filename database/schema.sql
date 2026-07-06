-- SCHÉMA BASE DE DONNÉES — Réseau Social
 -- Exécuter : mysql -u root -p < database/schema.sql
-- ============================================================
-- TABLE : utilisateurs
-- ============================================================
CREATE TABLE IF NOT EXISTS utilisateurs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(100) NOT NULL,
    prenom      VARCHAR(100) NOT NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    photo       VARCHAR(255) DEFAULT 'assets/images/default-avatar.png',
    bio         TEXT,
    role        ENUM('utilisateur', 'moderateur', 'administrateur') DEFAULT 'utilisateur',
    token_auth  VARCHAR(255) DEFAULT NULL,
    token_reset VARCHAR(255) DEFAULT NULL,
    actif       TINYINT(1) DEFAULT 1,
    cree_le     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    mis_a_jour  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE : publications (posts)
-- ============================================================
CREATE TABLE IF NOT EXISTS publications (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    auteur_id   INT NOT NULL,
    contenu     TEXT NOT NULL,
    image       VARCHAR(255) DEFAULT NULL,
    cree_le     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE : reactions (likes / dislikes)
-- ============================================================
CREATE TABLE IF NOT EXISTS reactions (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    publication_id  INT NOT NULL,
    utilisateur_id  INT NOT NULL,
    type            ENUM('like', 'dislike') NOT NULL,
    cree_le         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_reaction (publication_id, utilisateur_id),
    FOREIGN KEY (publication_id) REFERENCES publications(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE : commentaires
-- ============================================================
CREATE TABLE IF NOT EXISTS commentaires (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    publication_id  INT NOT NULL,
    auteur_id       INT NOT NULL,
    contenu         TEXT NOT NULL,
    cree_le         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (publication_id) REFERENCES publications(id) ON DELETE CASCADE,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE : amis (demandes d'amitié)
-- ============================================================
CREATE TABLE IF NOT EXISTS amis (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    demandeur_id    INT NOT NULL,
    receveur_id     INT NOT NULL,
    statut          ENUM('en_attente', 'accepte', 'refuse') DEFAULT 'en_attente',
    cree_le         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_amitie (demandeur_id, receveur_id),
    FOREIGN KEY (demandeur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (receveur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE : messages (chat)
-- ============================================================
CREATE TABLE IF NOT EXISTS messages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    expediteur_id   INT NOT NULL,
    destinataire_id INT NOT NULL,
    contenu     TEXT,
    image       VARCHAR(255) DEFAULT NULL,
    lu          TINYINT(1) DEFAULT 0,
    cree_le     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expediteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (destinataire_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- ============================================================
-- DONNÉES DE TEST
-- ============================================================
-- Mot de passe hashé pour "Test1234!" (bcrypt)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Test', 'Utilisateur', 'test@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'utilisateur'),
('Test', 'Moderateur', 'moderateur@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'moderateur'),
('Test', 'Admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrateur');
