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

    // Handle multiple file uploads and removals
    $uploaded_files = [];
    $files_to_remove = json_decode($_POST['files_to_remove'] ?? '[]', true);

    // Remove specified files
    if (!empty($files_to_remove)) {
        foreach ($files_to_remove as $file_id) {
            $stmt = $pdo->prepare("SELECT file_path FROM subject_files WHERE id = ? AND subject_id = ?");
            $stmt->execute([$file_id, $id]);
            $file = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($file && file_exists('../' . $file['file_path'])) {
                unlink('../' . $file['file_path']);
            }
            $stmt = $pdo->prepare("DELETE FROM subject_files WHERE id = ? AND subject_id = ?");
            $stmt->execute([$file_id, $id]);
        }
    }

    // Handle new file uploads
    if (isset($_FILES['files'])) {
        $upload_dir = '../uploads/subjects/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'mp4', 'avi', 'mov', 'jpg', 'jpeg', 'png'];
        $max_size = 20 * 1024 * 1024; // 20MB

        foreach ($_FILES['files']['name'] as $key => $original_name) {
            if ($_FILES['files']['error'][$key] !== UPLOAD_ERR_OK) {
                continue;
            }

            $file_size = $_FILES['files']['size'][$key];
            $file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

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

    // Insert new files
    if (!empty($uploaded_files)) {
        $stmt = $pdo->prepare("INSERT INTO subject_files (subject_id, file_path, file_name, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
        foreach ($uploaded_files as $file) {
            $stmt->execute([$id, $file['file_path'], $file['file_name'], $file['file_type'], $file['file_size']]);
        }
    }

    $stmt = $pdo->prepare("UPDATE subjects SET name = ?, type = ?, stage_id = ? WHERE id_subject = ?");
    $stmt->execute([$subject_name, $category, $stage_id, $id]);

    echo json_encode(['success' => true, 'message' => $translations['subject_updated_successfully']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $translations['database_error'] . ': ' . $e->getMessage()]);
}
?>
