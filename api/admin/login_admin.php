<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../config.php';

$donnees = json_decode(file_get_contents('php://input'), true);
$email = trim($donnees['email'] ?? '');
$mot_de_passe = trim($donnees['mot_de_passe'] ?? '');

if (empty($email) || empty($mot_de_passe)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Champs obligatoires']);
    exit;
}

// Cherche l'utilisateur avec un rôle admin ou modérateur
$stmt = $pdo->prepare("
    SELECT id, nom, prenom, email, mot_de_passe, role
    FROM utilisateurs
    WHERE email = ? AND role IN ('administrateur', 'moderateur') AND actif = 1
");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($mot_de_passe, $user['mot_de_passe'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Accès refusé']);
    exit;
}

// Génère un token
$token = bin2hex(random_bytes(32));
$pdo->prepare("UPDATE utilisateurs SET token_auth = ? WHERE id = ?")
    ->execute([$token, $user['id']]);

echo json_encode([
    'success' => true,
    'token' => $token,
    'user' => [
        'id' => $user['id'],
        'nom' => $user['nom'],
        'prenom' => $user['prenom'],
        'role' => $user['role']
    ]
]);
