<?php
require '../functions.php';
$allowed_roles = ['admin'];
if (in_array($_GET['type'] ?? '', ['specialites', 'stages', 'stagiaires', 'notes', 'permissions', 'sanctions'])) {
    $allowed_roles[] = 'secretaire';
}
if (in_array($_GET['type'] ?? '', ['consultations', 'consultations_specialty', 'patients'])) {
    $allowed_roles[] = 'docteur';
}
if (in_array($_GET['type'] ?? '', ['instructors', 'subjects', 'observations', 'instructor_stats', 'observation_trends'])) {
    $allowed_roles[] = 'cellule_pedagogique';
}
if (!in_array($_SESSION['role'], $allowed_roles)) {
    header('Location: ../auth/login.php');
    exit;
}

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';

switch ($type) {
    case 'users':
        $data = $pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;
    case 'stages':
        $data = $pdo->query("SELECT COUNT(*) as total FROM stages")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['total' => $data[0]['total']]);
        break;
    case 'specialites':
        $data = $pdo->query("SELECT nom_specialite, COUNT(*) as count FROM specialites GROUP BY nom_specialite")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;
    case 'consultations':
        $data = $pdo->query("SELECT DATE_FORMAT(date_consultation, '%Y-%m') as month, COUNT(*) as count FROM consultations GROUP BY month ORDER BY month")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;
    case 'stagiaires':
        $data = $pdo->query("SELECT sp.nom_specialite, COUNT(*) as count FROM stagiaires s JOIN specialites sp ON s.id_specialite = sp.id GROUP BY sp.nom_specialite")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;
    case 'notes':
        $data = $pdo->query("SELECT COUNT(*) as total FROM remarques")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['total' => $data[0]['total']]);
        break;
    case 'permissions':
        $data = $pdo->query("SELECT statut, COUNT(*) as count FROM permissions GROUP BY statut")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;
    case 'sanctions':
        $data = $pdo->query("SELECT COUNT(*) as total FROM punitions")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['total' => $data[0]['total']]);
        break;
    case 'consultations_specialty':
        $data = $pdo->query("SELECT sp.nom_specialite, COUNT(*) as count FROM consultations c JOIN stagiaires s ON c.id_stagiaire = s.id JOIN specialites sp ON s.id_specialite = sp.id GROUP BY sp.nom_specialite")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;
    case 'patients':
        $data = $pdo->query("SELECT COUNT(DISTINCT id_stagiaire) as total FROM consultations")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['total' => $data[0]['total']]);
        break;
    case 'instructors':
        $data = $pdo->query("SELECT COUNT(*) as total FROM instructors WHERE is_active = 1")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['total' => $data[0]['total']]);
        break;
    case 'subjects':
        $data = $pdo->query("SELECT type, COUNT(*) as count FROM subjects GROUP BY type")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;
    case 'observations':
        $data = $pdo->query("SELECT COUNT(*) as total FROM observations")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['total' => $data[0]['total']]);
        break;
    case 'instructor_stats':
        $data = $pdo->query("
            SELECT i.first_name, i.last_name, COUNT(o.id_observation) as observation_count,
                   SUM(CASE WHEN o.rating = 'positive' THEN 1 ELSE 0 END) as positive_count,
                   SUM(CASE WHEN o.rating = 'negative' THEN 1 ELSE 0 END) as negative_count
            FROM instructors i
            LEFT JOIN observations o ON i.id_instructor = o.instructor_id
            WHERE i.is_active = 1
            GROUP BY i.id_instructor, i.first_name, i.last_name
            ORDER BY observation_count DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;
    case 'observation_trends':
        $data = $pdo->query("
            SELECT DATE_FORMAT(obs_date, '%Y-%m') as month, COUNT(*) as count,
                   SUM(CASE WHEN rating = 'positive' THEN 1 ELSE 0 END) as positive_count,
                   SUM(CASE WHEN rating = 'negative' THEN 1 ELSE 0 END) as negative_count
            FROM observations
            GROUP BY month
            ORDER BY month
        ")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
        break;
    default:
        echo json_encode(['error' => 'Invalid type']);
}
?>
