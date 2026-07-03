<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../config.php';
require_once '../auth_check.php';

// Récupère l'ID du post depuis l'URL
$post_id = $_GET['post_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT c.contenu, c.cree_le, u.nom, u.prenom
    FROM commentaires c
    JOIN utilisateurs u ON c.auteur_id = u.id
    WHERE c.publication_id = ?
    ORDER BY c.cree_le ASC
");

$stmt->execute([$post_id]);
$commentaires = $stmt->fetchAll();

echo json_encode(['success' => true, 'commentaires' => $commentaires]);
?> 
