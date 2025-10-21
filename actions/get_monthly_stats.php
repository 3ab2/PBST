<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);

header('Content-Type: application/json');

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? null;

$query = "
    SELECT
        o.instructor_id,
        i.first_name,
        i.last_name,
        COUNT(CASE WHEN o.rating = 'positive' THEN 1 END) AS positive_count,
        COUNT(CASE WHEN o.rating = 'negative' THEN 1 END) AS negative_count,
        COUNT(o.id_observation) AS total,
        AVG(o.score) AS avg_score
    FROM observations o
    JOIN instructors i ON o.instructor_id = i.id_instructor
    WHERE YEAR(o.obs_date) = ?
";

$params = [$year];

if ($month) {
    $query .= " AND MONTH(o.obs_date) = ?";
    $params[] = $month;
}

$query .= "
    GROUP BY o.instructor_id, i.first_name, i.last_name
    ORDER BY positive_count DESC, avg_score DESC
";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($stats);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
