<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

$type = $_GET['type'] ?? 'messages';

if ($type === 'conversations') {
    // Retourne la liste des conversations
    $stmt = $pdo->prepare("
        SELECT DISTINCT u.id, u.nom, u.prenom, u.photo,
        (SELECT contenu FROM messages
         WHERE (expediteur_id = ? AND destinataire_id = u.id)
         OR (expediteur_id = u.id AND destinataire_id = ?)
         ORDER BY cree_le DESC LIMIT 1) as dernier_message,
        (SELECT COUNT(*) FROM messages
         WHERE expediteur_id = u.id
         AND destinataire_id = ?
         AND lu = 0) as non_lus
        FROM utilisateurs u
        JOIN messages m ON (m.expediteur_id = u.id OR m.destinataire_id = u.id)
        WHERE (m.expediteur_id = ? OR m.destinataire_id = ?)
        AND u.id != ?
    ");
    $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id, $user_id]);
    echo json_encode(['success' => true, 'conversations' => $stmt->fetchAll()]);

} else {
    // Retourne les messages d'une conversation
    $dest_id = $_GET['destinataire_id'] ?? 0;

    if (empty($dest_id)) {
        echo json_encode(['success' => false, 'message' => 'Destinataire manquant']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT m.id, m.contenu, m.image, m.cree_le,
               m.expediteur_id, u.nom, u.prenom
        FROM messages m
        JOIN utilisateurs u ON m.expediteur_id = u.id
        WHERE (m.expediteur_id = ? AND m.destinataire_id = ?)
        OR (m.expediteur_id = ? AND m.destinataire_id = ?)
        ORDER BY m.cree_le ASC
    ");
    $stmt->execute([$user_id, $dest_id, $dest_id, $user_id]);
    echo json_encode(['success' => true, 'messages' => $stmt->fetchAll()]);
}