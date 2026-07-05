//mise à jour de get friends
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config.php';
require_once '../auth_check.php';

$type   = $_GET['type']   ?? 'tous';
$search = $_GET['search'] ?? '';

if ($type === 'amis') {
    // Retourne les amis acceptés
    $sql = "
        SELECT u.id, u.nom, u.prenom, u.photo
        FROM utilisateurs u
        JOIN amis a ON (a.demandeur_id = u.id OR a.receveur_id = u.id)
        WHERE (a.demandeur_id = ? OR a.receveur_id = ?)
        AND u.id != ?
        AND a.statut = 'accepte'
    ";
    $params = [$user_id, $user_id, $user_id];

    if (!empty($search)) {
        $sql .= " AND (u.nom LIKE ? OR u.prenom LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['success' => true, 'amis' => $stmt->fetchAll()]);

} elseif ($type === 'invitations') {
    // Retourne les invitations reçues en attente
    $stmt = $pdo->prepare("
        SELECT u.id, u.nom, u.prenom, a.id as invitation_id
        FROM utilisateurs u
        JOIN amis a ON a.demandeur_id = u.id
        WHERE a.receveur_id = ? AND a.statut = 'en_attente'
    ");
    $stmt->execute([$user_id]);
    echo json_encode(['success' => true, 'invitations' => $stmt->fetchAll()]);

} else {
    // Retourne tous les utilisateurs avec leur statut
    $stmt = $pdo->prepare("
        SELECT u.id, u.nom, u.prenom, u.photo,
        (SELECT statut FROM amis
         WHERE (demandeur_id = ? AND receveur_id = u.id)
         OR (demandeur_id = u.id AND receveur_id = ?)) as statut
        FROM utilisateurs u
        WHERE u.id != ?
    ");
    $stmt->execute([$user_id, $user_id, $user_id]);
    echo json_encode(['success' => true, 'utilisateurs' => $stmt->fetchAll()]);
}