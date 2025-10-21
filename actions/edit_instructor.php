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

    // Check if CINE or MLE already exists (excluding current instructor)
    $stmt = $pdo->prepare("SELECT id_instructor FROM instructors WHERE (cine = ? OR mle = ?) AND id_instructor != ?");
    $stmt->execute([$_POST['cine'], $_POST['mle'], $id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'CINE or MLE already exists']);
        exit;
    }

    // Check if username already exists (excluding current instructor)
    $stmt = $pdo->prepare("SELECT id_instructor FROM instructors WHERE username = ? AND id_instructor != ?");
    $stmt->execute([$_POST['username'], $id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }

    $update_fields = [];
    $params = [];

    $fields = ['cine', 'mle', 'username', 'first_name', 'last_name', 'email', 'phone', 'bio'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $update_fields[] = "$field = ?";
            $params[] = sanitize_input($_POST[$field]);
        }
    }
    if (isset($_POST['speciality_id'])) {
        $update_fields[] = "speciality_id = ?";
        $params[] = $_POST['speciality_id'] ? (int)$_POST['speciality_id'] : null;
    }

    if (!empty($_POST['password'])) {
        $update_fields[] = "password = ?";
        $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    if (empty($update_fields)) {
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        exit;
    }

    $params[] = $id;
    $stmt = $pdo->prepare("UPDATE instructors SET " . implode(', ', $update_fields) . " WHERE id_instructor = ?");
    $stmt->execute($params);

    echo json_encode(['success' => true, 'message' => 'Instructor updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
