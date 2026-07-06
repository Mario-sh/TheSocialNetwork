<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Liste tous les articles avec auteur et stats
    $stmt = $pdo->query("
        SELECT p.id, p.contenu, p.image, p.cree_le,
               u.nom, u.prenom,
               (SELECT COUNT(*) FROM reactions WHERE publication_id = p.id AND type = 'like') as likes,
               (SELECT COUNT(*) FROM commentaires WHERE publication_id = p.id) as commentaires
        FROM publications p
        JOIN utilisateurs u ON p.auteur_id = u.id
        ORDER BY p.cree_le DESC");
    echo json_encode(['success' => true, 'publications' => $stmt->fetchAll()]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donnees = json_decode(file_get_contents('php://input'), true);
    if ($donnees['action'] === 'supprimer') {
        $pdo->prepare("DELETE FROM publications WHERE id = ?")
            ->execute([$donnees['post_id']]);
        echo json_encode(['success' => true]);
    }
}