<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

header('Content-Type: application/json');

$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => $translations['invalid_input']]);
    exit;
}

try {
    // Check if subject exists and get file path
    $stmt = $pdo->prepare("SELECT id_subject, file FROM subjects WHERE id_subject = ?");
    $stmt->execute([$id]);
    $subject = $stmt->fetch();
    if (!$subject) {
        echo json_encode(['success' => false, 'message' => $translations['subject_not_found']]);
        exit;
    }

    // Delete associated file if exists
    if ($subject['file'] && file_exists('../' . $subject['file'])) {
        unlink('../' . $subject['file']);
    }

    // Delete subject (cascade will handle related records)
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id_subject = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => $translations['subject_deleted_successfully']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $translations['database_error'] . ': ' . $e->getMessage()]);
}
?>
