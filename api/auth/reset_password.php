<?php
// reset_password.php — Réinitialisation du mot de passe
// Reçoit : token, mdp_nouveau
// Retourne : { success: true } ou { success: false, message }

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

require_once '../config.php';

// ── Étape 1 : Récupérer les données ──────────────────────────
$donnees    = json_decode(file_get_contents('php://input'), true);
$token      = trim($donnees['token']      ?? '');
$mdp_nouveau = trim($donnees['mdp_nouveau'] ?? '');

// ── Étape 2 : Vérifier que les données sont présentes ────────
if (empty($token) || empty($mdp_nouveau)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

// ── Étape 3 : Vérifier la longueur du mot de passe ───────────
if (strlen($mdp_nouveau) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Mot de passe trop court']);
    exit;
}

// ── Étape 4 : Chercher l'utilisateur avec ce token ───────────
// Le token_reset a été stocké par forgot_password.php
$stmt = $pdo->prepare("
    SELECT id
    FROM utilisateurs
    WHERE token_reset = ?
    AND actif = 1
");
$stmt->execute([$token]);
$user = $stmt->fetch();

// Si aucun utilisateur trouvé → token invalide ou déjà utilisé
if (!$user) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Lien invalide ou déjà utilisé'
    ]);
    exit;
}

// ── Étape 5 : Hasher le nouveau mot de passe ─────────────────
$mdp_hash = password_hash($mdp_nouveau, PASSWORD_BCRYPT);

// ── Étape 6 : Mettre à jour le mot de passe et vider le token
// On vide token_reset pour que le lien ne puisse pas être réutilisé
$stmt = $pdo->prepare("
    UPDATE utilisateurs
    SET mot_de_passe = ?,
        token_reset  = NULL
    WHERE id = ?
");
$stmt->execute([$mdp_hash, $user['id']]);

// ── Étape 7 : Retourner la réponse ───────────────────────────
echo json_encode([
    'success' => true,
    'message' => 'Mot de passe modifié avec succès'
]);