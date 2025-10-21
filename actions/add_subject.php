<?php
require '../functions.php';
check_role('cellule_pedagogique');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

header('Content-Type: application/json');

$subject_name = sanitize_input($_POST['subject_name'] ?? '');
$category = $_POST['category'] ?? '';

if (empty($subject_name) || empty($category)) {
    echo json_encode(['success' => false, 'message' => $translations['invalid_input']]);
    exit;
}

if (!in_array($category, ['militaire', 'universitaire'])) {
    echo json_encode(['success' => false, 'message' => $translations['invalid_type']]);
    exit;
}

try {
    // Check if subject already exists
    $stmt = $pdo->prepare("SELECT id_subject FROM subjects WHERE name = ? AND type = ?");
    $stmt->execute([$subject_name, $category]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => $translations['subject_already_exists']]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO subjects (name, type) VALUES (?, ?)");
    $stmt->execute([$subject_name, $category]);

    echo json_encode(['success' => true, 'message' => $translations['subject_added_successfully']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $translations['database_error'] . ': ' . $e->getMessage()]);
}
?>
