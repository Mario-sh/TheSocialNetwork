<?php
// ============================================================
// forgot_password.php — Mot de passe oublié
// ============================================================
// Reçoit : email
// Retourne : { success: true } ou { success: false, message }
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

require_once '../config.php';

// ── Étape 1 : Récupérer l'email ──────────────────────────────
$donnees = json_decode(file_get_contents('php://input'), true);
$email   = trim($donnees['email'] ?? '');

// ── Étape 2 : Vérifier que l'email est rempli ────────────────
if (empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email obligatoire']);
    exit;
}

// ── Étape 3 : Vérifier que l'email existe en BDD ─────────────
$stmt = $pdo->prepare("SELECT id, nom, prenom FROM utilisateurs WHERE email = ? AND actif = 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

// IMPORTANT : même si l'email n'existe pas on retourne success
// Pour ne pas révéler quels emails sont enregistrés
if (!$user) {
    echo json_encode([
        'success' => true,
        'message' => 'Si cet email existe, un lien de réinitialisation a été envoyé'
    ]);
    exit;
}

// ── Étape 4 : Générer un token de réinitialisation ───────────
// Token unique valable pour ce reset uniquement
$token_reset = bin2hex(random_bytes(32));

// ── Étape 5 : Stocker le token en BDD ────────────────────────
$stmt = $pdo->prepare("UPDATE utilisateurs SET token_reset = ? WHERE id = ?");
$stmt->execute([$token_reset, $user['id']]);

// ── Étape 6 : Construire le lien de réinitialisation ─────────
$lien_reset = BASE_URL . '/vues/clients/reset_password.html?token=' . $token_reset;

// ── Étape 7 : Envoyer l'email HTML ───────────────────────────
$sujet = "Réinitialisation de votre mot de passe — TheSocialNetwork";

// Template email en HTML
$corps_email = "
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 10px; overflow: hidden; }
        .header { background: #1A6FBF; padding: 30px; text-align: center; }
        .header h1 { color: white; margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .body p { color: #333; line-height: 1.6; }
        .btn { display: block; width: fit-content; margin: 30px auto; background: #1A6FBF; color: white; padding: 14px 30px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>TheSocialNetwork</h1>
        </div>
        <div class='body'>
            <p>Bonjour <strong>{$user['prenom']} {$user['nom']}</strong>,</p>
            <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
            <p>Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>
            <a href='{$lien_reset}' class='btn'>Réinitialiser mon mot de passe</a>
            <p>Ce lien est valable <strong>1 heure</strong>.</p>
            <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
        </div>
        <div class='footer'>
            <p>TheSocialNetwork — Examen Final PHP & AJAX</p>
        </div>
    </div>
</body>
</html>
";

// Envoi de l'email avec la fonction mail() de PHP
// Headers pour envoyer un email HTML
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: TheSocialNetwork <noreply@thesocialnetwork.com>\r\n";

$email_envoye = mail($email, $sujet, $corps_email, $headers);

// ── Étape 8 : Retourner la réponse ───────────────────────────
echo json_encode([
    'success' => true,
    'message' => 'Si cet email existe, un lien de réinitialisation a été envoyé'
]);