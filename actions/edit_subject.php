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
$stage_id = $_POST['stage_id'] ?? '';

if (empty($id) || empty($subject_name) || empty($category) || empty($stage_id)) {
    echo json_encode(['success' => false, 'message' => $translations['invalid_input']]);
    exit;
}

try {
    // Check if subject exists and get current file
    $stmt = $pdo->prepare("SELECT id_subject, file FROM subjects WHERE id_subject = ?");
    $stmt->execute([$id]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$subject) {
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

    // Handle file upload
    $file_path = $subject['file']; // Keep existing file by default
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/subjects/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $original_name = basename($_FILES['file']['name']);
        $new_filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $original_name);
        $target_file = $upload_dir . $new_filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            // Delete old file if exists
            if ($file_path && file_exists('../' . $file_path)) {
                unlink('../' . $file_path);
            }
            $file_path = 'uploads/subjects/' . $new_filename;
        } else {
            echo json_encode(['success' => false, 'message' => $translations['file_upload_error']]);
            exit;
        }
    }

    $stmt = $pdo->prepare("UPDATE subjects SET name = ?, type = ?, stage_id = ?, file = ? WHERE id_subject = ?");
    $stmt->execute([$subject_name, $category, $stage_id, $file_path, $id]);

    echo json_encode(['success' => true, 'message' => $translations['subject_updated_successfully']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $translations['database_error'] . ': ' . $e->getMessage()]);
}
?>
