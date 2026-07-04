<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

$donnees         = json_decode(file_get_contents('php://input'), true);
$destinataire_id = $donnees['destinataire_id'] ?? 0;
$contenu         = trim($donnees['contenu']     ?? '');

if (empty($destinataire_id)) {
    echo json_encode(['success' => false, 'message' => 'Destinataire manquant']);
    exit;
}

if (empty($contenu)) {
    echo json_encode(['success' => false, 'message' => 'Message vide']);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO messages (expediteur_id, destinataire_id, contenu)
    VALUES (?, ?, ?)
");
$stmt->execute([$user_id, $destinataire_id, $contenu]);

echo json_encode(['success' => true, 'message' => 'Message envoyé']);