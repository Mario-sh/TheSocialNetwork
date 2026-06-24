<?php
// ============================================================
// register.php — Inscription d'un nouvel utilisateur
// ============================================================
// Reçoit : nom, prenom, email, mot_de_passe
// Retourne : { success: true } ou { success: false, message: "..." }
// ============================================================

// Autorise les requêtes venant du frontend
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
// JavaScript envoie les données en JSON dans le body
// php://input lit ce body brut
$donnees = json_decode(file_get_contents('php://input'), true);

// Récupère chaque champ et supprime les espaces inutiles
$nom            = trim($donnees['nom'] ?? '');
$prenom         = trim($donnees['prenom'] ?? '');
$email          = trim($donnees['email'] ?? '');
$mot_de_passe   = trim($donnees['mot_de_passe'] ?? '');

// ── Étape 2 : Vérifier que tous les champs sont remplis ──────
if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires']);
    exit;
}

// ── Étape 3 : Vérifier que l'email est valide ────────────────
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email invalide']);
    exit;
}

// ── Étape 4 : Vérifier que l'email n'existe pas déjà ─────────
// On cherche dans la BDD si cet email est déjà utilisé
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    // Un utilisateur avec cet email existe déjà
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé']);
    exit;
}

// ── Étape 5 : Sécuriser le mot de passe ──────────────────────
// On ne stocke JAMAIS un mot de passe en clair
// password_hash() le transforme en quelque chose d'illisible
// "azerty123" → "$2y$10$92IXUNpkjO0rOQ5..."
$mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

// ── Étape 6 : Créer le compte dans la BDD ────────────────────
$stmt = $pdo->prepare("
    INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$nom, $prenom, $email, $mot_de_passe_hash]);

// Récupère l'ID du nouvel utilisateur créé
$nouvel_id = $pdo->lastInsertId();

// ── Étape 7 : Retourner la réponse au JavaScript ─────────────
http_response_code(201); // 201 = créé avec succès
echo json_encode([
    'success' => true,
    'message' => 'Compte créé avec succès',
    'user_id' => $nouvel_id
]);