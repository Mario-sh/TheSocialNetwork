<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

// Compter chaque table
$nb_users = $pdo->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$nb_posts = $pdo->query("SELECT COUNT(*) FROM publications")->fetchColumn();
$nb_messages = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$nb_reactions = $pdo->query("SELECT COUNT(*) FROM reactions")->fetchColumn();

// Derniers utilisateurs inscrits
$stmt = $pdo->query("SELECT id, nom, prenom, email, role, cree_le FROM utilisateurs ORDER BY cree_le DESC LIMIT 5");
$derniers_users = $stmt->fetchAll();

// Derniers articles publiés
$stmt = $pdo->query("
    SELECT p.id, p.contenu, p.cree_le, u.nom, u.prenom
    FROM publications p JOIN utilisateurs u ON p.auteur_id = u.id
    ORDER BY p.cree_le DESC LIMIT 5");
$derniers_posts = $stmt->fetchAll();

echo json_encode([
    'success' => true,
    'stats' => [
        'utilisateurs' => $nb_users,
        'publications' => $nb_posts,
        'messages' => $nb_messages,
        'reactions' => $nb_reactions,
        'derniers_users' => $derniers_users,
        'derniers_posts' => $derniers_posts,
    ]
]);