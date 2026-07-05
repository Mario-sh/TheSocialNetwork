//mise à jour de respond_request
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

$donnees    = json_decode(file_get_contents('php://input'), true);
$demande_id = $donnees['demande_id'] ?? 0;
$statut     = $donnees['statut']     ?? '';

if (empty($demande_id) || empty($statut)) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

if (!in_array($statut, ['accepte', 'refuse'])) {
    echo json_encode(['success' => false, 'message' => 'Statut invalide']);
    exit;
}

$stmt = $pdo->prepare("
    UPDATE amis SET statut = ?
    WHERE id = ? AND receveur_id = ?
");
$stmt->execute([$statut, $demande_id, $user_id]);

echo json_encode(['success' => true, 'message' => 'Réponse enregistrée']);