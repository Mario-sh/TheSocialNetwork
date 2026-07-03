<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

$donnees = json_decode(file_get_contents('php://input'), true);
$contenu = trim($donnees['contenu'] ?? '');

if (empty($contenu)) {
    echo json_encode(['success' => false, 'message' => 'Contenu obligatoire']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO publications (auteur_id, contenu) VALUES (?, ?)");
$stmt->execute([$user_id, $contenu]);

echo json_encode(['success' => true, 'message' => 'Post créé']);
?>
