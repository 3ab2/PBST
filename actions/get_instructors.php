<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

try {

    if ($id) {
        // Get single instructor with subjects, specialty, and average score
        $stmt = $pdo->prepare("
            SELECT i.*, GROUP_CONCAT(s.name SEPARATOR ', ') as subjects,
                   sp.nom_specialite, sp.id as speciality_id,
                   AVG(o.score) as average_score
            FROM instructors i
            LEFT JOIN instructor_subjects ins ON i.id_instructor = ins.instructor_id
            LEFT JOIN subjects s ON ins.subject_id = s.id_subject
            LEFT JOIN specialites sp ON i.speciality_id = sp.id
            LEFT JOIN observations o ON i.id_instructor = o.instructor_id
            WHERE i.id_instructor = ? AND i.is_active = 1
            GROUP BY i.id_instructor
        ");
        $stmt->execute([$id]);
        $instructor = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($instructor);
    } else {
        // Get all instructors with subjects, specialty, and average score
        $stmt = $pdo->query("
            SELECT i.*, GROUP_CONCAT(s.name SEPARATOR ', ') as subjects,
                   sp.nom_specialite, sp.id as speciality_id,
                   AVG(o.score) as average_score
            FROM instructors i
            LEFT JOIN instructor_subjects ins ON i.id_instructor = ins.instructor_id
            LEFT JOIN subjects s ON ins.subject_id = s.id_subject
            LEFT JOIN specialites sp ON i.speciality_id = sp.id
            LEFT JOIN observations o ON i.id_instructor = o.instructor_id
            WHERE i.is_active = 1
            GROUP BY i.id_instructor
            ORDER BY i.last_name, i.first_name
        ");
        $instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($instructors);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
