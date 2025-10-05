<?php
require '../functions.php';
$allowed_roles = ['admin'];
if (in_array($_GET['type'] ?? '', ['specialites', 'stages', 'stagiaires', 'notes', 'permissions', 'sanctions'])) {
    $allowed_roles[] = 'secretaire';
}
if (in_array($_GET['type'] ?? '', ['consultations', 'consultations_specialty', 'patients'])) {
    $allowed_roles[] = 'docteur';
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
    default:
        echo json_encode(['error' => 'Invalid type']);
}
?>
