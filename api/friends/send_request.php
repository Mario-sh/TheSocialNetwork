<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

$donnees     = json_decode(file_get_contents('php://input'), true);
$receveur_id = $donnees['user_id'] ?? 0;

if (empty($receveur_id)) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur invalide']);
    exit;
}

// Vérifie qu'une invitation n'existe pas déjà
$stmt = $pdo->prepare("SELECT id FROM amis WHERE demandeur_id = ? AND receveur_id = ?");
$stmt->execute([$user_id, $receveur_id]);

if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Invitation déjà envoyée']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO amis (demandeur_id, receveur_id) VALUES (?, ?)");
$stmt->execute([$user_id, $receveur_id]);

echo json_encode(['success' => true, 'message' => 'Invitation envoyée']);