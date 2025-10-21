<?php
require '../functions.php';
check_role('cellule_pedagogique');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

header('Content-Type: application/json');

$id = trim($_POST['id'] ?? '');

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Invalid input or missing ID']);
    exit;
}

try {
    // Check if instructor exists
    $stmt = $pdo->prepare("SELECT id_instructor FROM instructors WHERE id_instructor = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Instructor not found']);
        exit;
    }

    // Delete instructor (cascade will handle related records)
    $stmt = $pdo->prepare("DELETE FROM instructors WHERE id_instructor = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Instructor deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
