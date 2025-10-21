<?php
require '../functions.php';
check_role('cellule_pedagogique');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

// Validate CSRF token
$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$instructor_id = $_POST['instructor_id'] ?? null;
$subject_id = $_POST['subject_id'] ?? null;
$obs_date = $_POST['obs_date'] ?? null;
$heure_debut = $_POST['heure_debut'] ?? null;
$heure_fin = $_POST['heure_fin'] ?? null;
$rating = $_POST['rating'] ?? null;
$score = $_POST['score'] ?? null;
$comment = sanitize_input($_POST['comment'] ?? '');

if (!$instructor_id || !$subject_id || !$obs_date || !$heure_debut || !$heure_fin || !$rating) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Validate that heure_fin is later than heure_debut
if (strtotime($heure_fin) <= strtotime($heure_debut)) {
    echo json_encode(['success' => false, 'message' => 'End time must be later than start time']);
    exit;
}

if (!in_array($rating, ['positive', 'negative'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid rating']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO observations (instructor_id, subject_id, observed_by_user_id, obs_date, heure_debut, heure_fin, rating, score, comment, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $instructor_id,
        $subject_id,
        $_SESSION['user_id'],
        $obs_date,
        $heure_debut,
        $heure_fin,
        $rating,
        $score ?: null,
        $comment
    ]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Observation added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add observation']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
