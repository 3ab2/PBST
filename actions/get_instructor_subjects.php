<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);

header('Content-Type: application/json');

$instructor_id = $_GET['instructor_id'] ?? null;
if (!$instructor_id) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT s.id_subject, s.name, s.type
        FROM subjects s
        JOIN instructor_subjects isub ON s.id_subject = isub.subject_id
        WHERE isub.instructor_id = ?
        ORDER BY s.type, s.name
    ");
    $stmt->execute([$instructor_id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($subjects);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
