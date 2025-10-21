<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);

header('Content-Type: application/json');

$instructor_id = $_GET['instructor_id'] ?? null;
$month = $_GET['month'] ?? null; // YYYY-MM
$rating = $_GET['rating'] ?? null;

$query = "
    SELECT o.*, i.first_name, i.last_name, s.name AS subject_name,
           CONCAT(i.first_name, ' ', i.last_name) AS instructor_name
    FROM observations o
    JOIN instructors i ON o.instructor_id = i.id_instructor
    JOIN subjects s ON o.subject_id = s.id_subject
    WHERE 1=1
";

$params = [];

if ($instructor_id) {
    $query .= " AND o.instructor_id = ?";
    $params[] = $instructor_id;
}

if ($month) {
    $query .= " AND DATE_FORMAT(o.obs_date, '%Y-%m') = ?";
    $params[] = $month;
}

if ($rating) {
    $query .= " AND o.rating = ?";
    $params[] = $rating;
}

$query .= " ORDER BY o.obs_date DESC, o.heure_debut DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $observations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($observations);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
