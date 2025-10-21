<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'instructor']);

header('Content-Type: application/json');

$id = $_POST['id'] ?? $_GET['id'] ?? null;

try {
    if ($id) {
        // Get single subject
        $stmt = $pdo->prepare("SELECT s.id_subject, s.name, s.type, s.stage_id, s.file FROM subjects s WHERE s.id_subject = ?");
        $stmt->execute([$id]);
        $subject = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($subject);
    } else {
        // Get all subjects for listing
        $stmt = $pdo->prepare("SELECT s.id_subject, s.name, s.type, s.file, st.intitule AS stage_name FROM subjects s LEFT JOIN stages st ON s.stage_id = st.id ORDER BY s.name");
        $stmt->execute();
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($subjects);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
