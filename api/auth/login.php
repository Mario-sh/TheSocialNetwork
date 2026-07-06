<?php
// login.php — Connexion d'un utilisateur
// Reçoit : email, mot_de_passe
// Retourne : { success, token, user } ou { success: false, message }

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// On accepte uniquement les requêtes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Connexion à la base de données
require_once '../config.php';

// ── Étape 1 : Récupérer les données envoyées par JavaScript ──
$donnees = json_decode(file_get_contents('php://input'), true);

$email        = trim($donnees['email'] ?? '');
$mot_de_passe = trim($donnees['mot_de_passe'] ?? '');

// ── Étape 2 : Vérifier que les champs sont remplis ───────────
if (empty($email) || empty($mot_de_passe)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email et mot de passe obligatoires']);
    exit;
}

// ── Étape 3 : Chercher l'utilisateur dans la BDD ─────────────
// On cherche par email — l'email est unique dans la table
$stmt = $pdo->prepare("
    SELECT id, nom, prenom, email, mot_de_passe, role, photo
    FROM utilisateurs
    WHERE email = ?
    AND actif = 1
");
$stmt->execute([$email]);
$user = $stmt->fetch();

// Si aucun utilisateur trouvé avec cet email
if (!$user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
    exit;
}

// ── Étape 4 : Vérifier le mot de passe ───────────────────────
// password_verify() compare le mot de passe tapé
// avec le hash stocké en BDD
// "Test1234!" == "$2y$10$92IXUNpkjO0rOQ5..." → true ou false
if (!password_verify($mot_de_passe, $user['mot_de_passe'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
    exit;
}

// ── Étape 5 : Générer un token unique ────────────────────────
// bin2hex(random_bytes(32)) génère une chaîne aléatoire de 64 caractères
// Exemple : "a3f8b2c1d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0"
$token = bin2hex(random_bytes(32));

// ── Étape 6 : Stocker le token dans la BDD ───────────────────
// On sauvegarde le token dans la colonne token_auth de l'utilisateur
// Comme ça auth_check.php peut le retrouver à chaque requête
$stmt = $pdo->prepare("
    UPDATE utilisateurs
    SET token_auth = ?
    WHERE id = ?
");
$stmt->execute([$token, $user['id']]);

// ── Étape 7 : Retourner la réponse au JavaScript ─────────────
http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Connexion réussie',
    'token'   => $token,
    'user'    => [
        'id'     => $user['id'],
        'nom'    => $user['nom'],
        'prenom' => $user['prenom'],
        'email'  => $user['email'],
        'role'   => $user['role'],
        'photo'  => $user['photo']
    ]
]);