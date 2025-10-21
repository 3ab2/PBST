<?php
require '../functions.php';
check_role('cellule_pedagogique');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

header('Content-Type: application/json');

$id = $_POST['id'] ?? '';
$subject_name = sanitize_input($_POST['subject_name'] ?? '');
$category = $_POST['category'] ?? '';

if (empty($id) || empty($subject_name) || empty($category)) {
    echo json_encode(['success' => false, 'message' => $translations['invalid_input']]);
    exit;
}

try {
    // Check if subject exists
    $stmt = $pdo->prepare("SELECT id_subject FROM subjects WHERE id_subject = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => $translations['subject_not_found']]);
        exit;
    }

    // Check if name/type combination already exists (excluding current subject)
    $stmt = $pdo->prepare("SELECT id_subject FROM subjects WHERE name = ? AND type = ? AND id_subject != ?");
    $stmt->execute([$subject_name, $category, $id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => $translations['subject_already_exists']]);
        exit;
    }

    if (!in_array($category, ['militaire', 'universitaire'])) {
        echo json_encode(['success' => false, 'message' => $translations['invalid_type']]);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE subjects SET name = ?, type = ? WHERE id_subject = ?");
    $stmt->execute([$subject_name, $category, $id]);

    echo json_encode(['success' => true, 'message' => $translations['subject_updated_successfully']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $translations['database_error'] . ': ' . $e->getMessage()]);
}
?>
