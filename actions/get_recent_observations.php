<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT o.*, i.first_name, i.last_name, s.name AS subject_name,
               CONCAT(i.first_name, ' ', i.last_name) AS instructor_name
        FROM observations o
        JOIN instructors i ON o.instructor_id = i.id_instructor
        JOIN subjects s ON o.subject_id = s.id_subject
        ORDER BY o.obs_date DESC, o.heure_debut DESC
        LIMIT 10
    ");
    $stmt->execute();
    $observations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($observations);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
