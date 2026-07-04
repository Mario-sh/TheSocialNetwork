<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Liste tous les utilisateurs
    $stmt = $pdo->query("SELECT id, nom, prenom, email, role, cree_le FROM utilisateurs ORDER BY cree_le DESC");
    echo json_encode(['success' => true, 'utilisateurs' => $stmt->fetchAll()]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donnees = json_decode(file_get_contents('php://input'), true);
    $action = $donnees['action'] ?? '';

    if ($action === 'supprimer') {
        $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?")
            ->execute([$donnees['user_id']]);
        echo json_encode(['success' => true]);

    } elseif ($action === 'changer_role') {
        // Seulement l'administrateur peut changer les rôles
        if ($user_actuel['role'] !== 'administrateur') {
            echo json_encode(['success' => false, 'message' => 'Non autorisé']);
            exit;
        }
        $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?")
            ->execute([$donnees['role'], $donnees['user_id']]);
        echo json_encode(['success' => true]);
    }
}