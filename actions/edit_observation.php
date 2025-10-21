<?php
require '../functions.php';
check_role('cellule_pedagogique');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cellule_pedagogique/manage_observations.php?error=' . urlencode('Invalid method'));
    exit;
}

// Validate CSRF token
$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    header('Location: ../cellule_pedagogique/manage_observations.php?error=' . urlencode('Invalid CSRF token'));
    exit;
}

$id = $_POST['id'] ?? null;
$heure_debut = $_POST['heure_debut'] ?? null;
$heure_fin = $_POST['heure_fin'] ?? null;
$rating = $_POST['rating'] ?? null;
$score = $_POST['score'] ?? null;
$comment = sanitize_input($_POST['comment'] ?? '');

if (!$id || !$heure_debut || !$heure_fin || !$rating) {
    header('Location: ../cellule_pedagogique/manage_observations.php?error=' . urlencode('Missing required fields'));
    exit;
}

// Validate that heure_fin is later than heure_debut
if (strtotime($heure_fin) <= strtotime($heure_debut)) {
    header('Location: ../cellule_pedagogique/manage_observations.php?error=' . urlencode('End time must be later than start time'));
    exit;
}

if (!in_array($rating, ['positive', 'negative'])) {
    header('Location: ../cellule_pedagogique/manage_observations.php?error=' . urlencode('Invalid rating'));
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE observations
        SET heure_debut = ?, heure_fin = ?, rating = ?, score = ?, comment = ?
        WHERE id_observation = ?
    ");
    $stmt->execute([$heure_debut, $heure_fin, $rating, $score ?: null, $comment, $id]);
    if ($stmt->rowCount() > 0) {
        header('Location: ../cellule_pedagogique/manage_observations.php?success=edit');
        exit;
    } else {
        header('Location: ../cellule_pedagogique/manage_observations.php?error=' . urlencode('No changes made or observation not found'));
        exit;
    }
} catch (Exception $e) {
    header('Location: ../cellule_pedagogique/manage_observations.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
