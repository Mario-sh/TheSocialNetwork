<?php
// ============================================================
// auth_check.php — Vérification du token de connexion
// ============================================================
// Importé en haut de CHAQUE fichier PHP protégé
// avec : require_once '../auth_check.php'
//
// Si le token est valide → $user_id et $user_actuel sont disponibles
// Si le token est invalide → la requête est bloquée immédiatement
// ============================================================

// On s'assure que config.php est chargé (connexion BDD)
require_once __DIR__ . '/config.php';

// ── Étape 1 : Lire le token envoyé par JavaScript ────────────
// JavaScript envoie le token dans le header Authorization
// Format : "Bearer abc123xyz"
// On lit ce header et on extrait juste le token

$headers = getallheaders(); // Récupère tous les headers de la requête

// Vérifie que le header Authorization existe
if (!isset($headers['Authorization']) && !isset($headers['authorization'])) {
    http_response_code(401); // 401 = Non autorisé
    echo json_encode(['success' => false, 'message' => 'Token manquant']);
    exit;
}

// Récupère la valeur du header (gère majuscules/minuscules)
$auth_header = $headers['Authorization'] ?? $headers['authorization'];

// Extrait le token en supprimant "Bearer " au début
// "Bearer abc123xyz" → "abc123xyz"
$token = str_replace('Bearer ', '', $auth_header);

// Vérifie que le token n'est pas vide
if (empty($token)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token invalide']);
    exit;
}

// ── Étape 2 : Vérifier le token dans la base de données ──────
// On cherche un utilisateur qui a ce token dans la table utilisateurs

$stmt = $pdo->prepare("
    SELECT id, nom, prenom, email, role, photo
    FROM utilisateurs
    WHERE token_auth = ?
    AND actif = 1
");
$stmt->execute([$token]);
$user_actuel = $stmt->fetch();

// Si aucun utilisateur trouvé → token invalide ou expiré
if (!$user_actuel) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Session expirée, reconnectez-vous']);
    exit;
}

// ── Étape 3 : Rendre l'utilisateur disponible ────────────────
// Si on arrive ici → le token est valide
// On stocke l'ID dans une variable globale
// Les autres fichiers peuvent l'utiliser directement

$user_id = $user_actuel['id'];

//Succès Le fichier qui a importé auth_check.php peut maintenant continuer