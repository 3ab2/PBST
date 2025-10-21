<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);

header('Content-Type: application/json');

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? null;

$query = "
    SELECT CONCAT(i.first_name, ' ', i.last_name) AS name,
           COUNT(CASE WHEN o.rating='positive' THEN 1 END) AS positive_count,
           AVG(o.score) AS avg_score
    FROM instructors i
    LEFT JOIN observations o ON o.instructor_id = i.id_instructor
    WHERE YEAR(o.obs_date) = ?
";

$params = [$year];

if ($month) {
    $query .= " AND MONTH(o.obs_date) = ?";
    $params[] = $month;
}

$query .= "
    GROUP BY i.id_instructor
    ORDER BY positive_count DESC, avg_score DESC
    LIMIT 1
";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
