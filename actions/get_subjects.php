<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'instructor']);

header('Content-Type: application/json');

$id = $_POST['id'] ?? $_GET['id'] ?? null;

try {
    if ($id) {
        // Get single subject
        $stmt = $pdo->prepare("SELECT id_subject, name, type, file FROM subjects WHERE id_subject = ?");
        $stmt->execute([$id]);
        $subject = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($subject);
    } else {
        // Get all subjects
        $stmt = $pdo->query("SELECT id_subject, name, type, file FROM subjects ORDER BY type, name");
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($subjects);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
