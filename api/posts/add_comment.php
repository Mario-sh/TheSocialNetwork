<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../config.php';
require_once '../auth_check.php';

$donnees = json_decode(file_get_contents('php://input'), true);
$post_id = $donnees['post_id'] ?? 0;
$contenu = trim($donnees['contenu'] ?? '');

if (empty($contenu)) {
    echo json_encode(['success' => false, 'message' => 'Commentaire vide']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO commentaires (publication_id, auteur_id, contenu) VALUES (?, ?, ?)");
$stmt->execute([$post_id, $user_id, $contenu]);

echo json_encode(['success' => true, 'message' => 'Commentaire ajouté']);
?>
