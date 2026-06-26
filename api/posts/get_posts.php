<?php
header('Content-Type:application/json');
header('Acess-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_chek.php';


$stmt = $pdo->prepare("SELECT p.id,p.contenu,p.image,p.cree_le,u.nom,u.prenom,u.photo, (SELECT COUNT(*) FROM reactions WHERE publication_id =p.id AND type='like')AS likes,

          (SELECT COUNT(*) FROM reactions WHERE publication_id =p.id AND type='dislike')AS dislikes,

          (SELECT type FROM reactions WHERE publication_id =p.id AND utilisateur_id = ?) AS mon_reaction

        FROM publications p JOIN utilisateurs u ON p.auteur_id=u.id ORDER BYp.cree_le DESC ");

$stmt->execute([$user_id]);

$posts= $stmt->fetchAll();

echo json_encode([
  'success' =>true,
  'posts' => $posts
]);

//>
