
//mise à jour de update_profile
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

$action = $_POST['action'] ?? 'infos';

// ── Changer le mot de passe ───────────────────────────────
if ($action === 'changer_mdp') {
    $donnees     = json_decode(file_get_contents('php://input'), true);
    $mdp_actuel  = trim($donnees['mdp_actuel']  ?? '');
    $mdp_nouveau = trim($donnees['mdp_nouveau'] ?? '');

    if (empty($mdp_actuel) || empty($mdp_nouveau)) {
        echo json_encode(['success' => false, 'message' => 'Champs obligatoires']);
        exit;
    }

    // Vérifie l'ancien mot de passe
    $stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!password_verify($mdp_actuel, $user['mot_de_passe'])) {
        echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect']);
        exit;
    }

    $hash = password_hash($mdp_nouveau, PASSWORD_BCRYPT);
    $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?")
        ->execute([$hash, $user_id]);

    echo json_encode(['success' => true, 'message' => 'Mot de passe changé']);
    exit;
}

// ── Modifier les infos + photo ────────────────────────────
$nom    = trim($_POST['nom']    ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$bio    = trim($_POST['bio']    ?? '');

if (empty($nom) || empty($prenom)) {
    echo json_encode(['success' => false, 'message' => 'Nom et prénom obligatoires']);
    exit;
}

$photo_path = null;

// Upload de la photo si fournie
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $ext      = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array(strtolower($ext), $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Format image non autorisé']);
        exit;
    }

    $filename   = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
    $upload_dir = '../assets/images/';
    $photo_path = 'assets/images/' . $filename;

    move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $filename);
}

// Mise à jour en BDD
if ($photo_path) {
    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, bio = ?, photo = ? WHERE id = ?");
    $stmt->execute([$nom, $prenom, $bio, $photo_path, $user_id]);
} else {
    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, bio = ? WHERE id = ?");
    $stmt->execute([$nom, $prenom, $bio, $user_id]);
}

echo json_encode([
    'success' => true,
    'message' => 'Profil mis à jour',
    'photo'   => $photo_path
]);