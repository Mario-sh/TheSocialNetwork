<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

// Récupère le contenu selon le type de requête
if (!empty($_POST)) {
    $contenu = trim($_POST['contenu'] ?? '');
} else {
    $donnees = json_decode(file_get_contents('php://input'), true);
    $contenu = trim($donnees['contenu'] ?? '');
}

$a_une_image = isset($_FILES['image']) && $_FILES['image']['error'] === 0;

// Contenu obligatoire seulement si pas d'image
if (empty($contenu) && !$a_une_image) {
    echo json_encode(['success' => false, 'message' => 'Ajoutez un texte ou une image']);
    exit;
}

$image_path = null;

// Upload image si fournie
if ($a_une_image) {
    $ext     = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array(strtolower($ext), $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Format image non autorisé']);
        exit;
    }

    $filename   = 'post_' . $user_id . '_' . time() . '.' . $ext;
    $upload_dir = __DIR__ . '/../../assets/images/';
    $image_path = 'assets/images/' . $filename;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
        echo json_encode(['success' => false, 'message' => 'Échec de l\'upload de l\'image']);
        exit;
    }
}

$stmt = $pdo->prepare("INSERT INTO publications (auteur_id, contenu, image) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $contenu, $image_path]);

echo json_encode(['success' => true, 'message' => 'Post créé']);
