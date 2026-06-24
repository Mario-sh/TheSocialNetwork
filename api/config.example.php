<?php
// ============================================================
// config.example.php — Modèle de configuration
// ============================================================
// 1. Copie ce fichier : cp config.example.php config.php
// 2. Remplis tes informations dans config.php
// 3. Ne JAMAIS committer config.php sur GitHub
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'reseau_social');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', 'http://localhost/TheSocialNetwork');
define('SECRET_KEY', 'CHANGE_THIS_SECRET_KEY_PLEASE');

define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USER', 'votre.email@gmail.com');
define('MAIL_PASS', 'votre_mot_de_passe_app');
define('MAIL_PORT', 587);

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données']);
    exit;
}