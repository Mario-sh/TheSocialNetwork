<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

$donnees = json_decode(file_get_contents('php://input'), true);
$post_id = $donnees['post_id'] ?? 0;
$type = $donnees['type'] ?? 'like';

// Vérifie si une réaction existe déjà
$stmt = $pdo->prepare("SELECT id, type FROM reactions WHERE publication_id = ? AND utilisateur_id = ?");
$stmt->execute([$post_id, $user_id]);
$reaction = $stmt->fetch();

if ($reaction) {
    if ($reaction['type'] === $type) {
        $pdo->prepare("DELETE FROM reactions WHERE id = ?")->execute([$reaction['id']]);
        $mon_reaction = null;
    } else {
        $pdo->prepare("UPDATE reactions SET type = ? WHERE id = ?")->execute([$type, $reaction['id']]);
        $mon_reaction = $type;
    }
} else {
    $pdo->prepare("INSERT INTO reactions (publication_id, utilisateur_id, type) VALUES (?, ?, ?)")->execute([$post_id, $user_id, $type]);
    $mon_reaction = $type;
}

// Compter les nouvelles réactions pour mettre à jour l'interface
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reactions WHERE publication_id = ? AND type = 'like'");
$stmt->execute([$post_id]);
$likes = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM reactions WHERE publication_id = ? AND type = 'dislike'");
$stmt->execute([$post_id]);
$dislikes = $stmt->fetchColumn();

echo json_encode(['success' => true, 'likes' => $likes, 'dislikes' => $dislikes, 'mon_reaction' => $mon_reaction]);
?>
