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
$stage_id = $_POST['stage_id'] ?? '';

if (empty($subject_name) || empty($category) || empty($stage_id)) {
    echo json_encode(['success' => false, 'message' => $translations['invalid_input']]);
    exit;
}

if (!in_array($category, ['militaire', 'universitaire'])) {
    echo json_encode(['success' => false, 'message' => $translations['invalid_type']]);
    exit;
}

// Handle multiple file uploads
$uploaded_files = [];
if (isset($_FILES['files'])) {
    $upload_dir = '../uploads/subjects/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'mp4', 'avi', 'mov', 'jpg', 'jpeg', 'png'];
    $max_size = 20 * 1024 * 1024; // 20MB

    foreach ($_FILES['files']['name'] as $key => $original_name) {
        if ($_FILES['files']['error'][$key] !== UPLOAD_ERR_OK) {
            continue; // Skip files with errors
        }

        $file_size = $_FILES['files']['size'][$key];
        $file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        // Validate file type and size
        if (!in_array($file_extension, $allowed_types)) {
            echo json_encode(['success' => false, 'message' => $translations['invalid_file_type'] ?? 'Invalid file type']);
            exit;
        }

        if ($file_size > $max_size) {
            echo json_encode(['success' => false, 'message' => $translations['file_too_large'] ?? 'File too large']);
            exit;
        }

        $new_filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $original_name);
        $target_file = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $target_file)) {
            $uploaded_files[] = [
                'file_path' => 'uploads/subjects/' . $new_filename,
                'file_name' => $original_name,
                'file_type' => $file_extension,
                'file_size' => $file_size
            ];
        } else {
            echo json_encode(['success' => false, 'message' => $translations['file_upload_error']]);
            exit;
        }
    }
}

try {
    // Check if subject already exists
    $stmt = $pdo->prepare("SELECT id_subject FROM subjects WHERE name = ? AND type = ?");
    $stmt->execute([$subject_name, $category]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => $translations['subject_already_exists']]);
        exit;
    }

    // Insert subject
    $stmt = $pdo->prepare("INSERT INTO subjects (name, type, stage_id) VALUES (?, ?, ?)");
    $stmt->execute([$subject_name, $category, $stage_id]);
    $subject_id = $pdo->lastInsertId();

    // Insert files into subject_files table
    if (!empty($uploaded_files)) {
        $stmt = $pdo->prepare("INSERT INTO subject_files (subject_id, file_path, file_name, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
        foreach ($uploaded_files as $file) {
            $stmt->execute([$subject_id, $file['file_path'], $file['file_name'], $file['file_type'], $file['file_size']]);
        }
    }

    echo json_encode(['success' => true, 'message' => $translations['subject_added_successfully']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $translations['database_error'] . ': ' . $e->getMessage()]);
}
?>
