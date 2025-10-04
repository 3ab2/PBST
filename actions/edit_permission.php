<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf = $_POST['csrf_token'];
    if (!validate_csrf_token($csrf)) {
        die('Invalid CSRF token');
    }

    $id = (int)$_POST['id'];
    $id_stagiaire = (int)$_POST['id_stagiaire'];
    $type = sanitize_input($_POST['type']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $motif = sanitize_input($_POST['motif']);
    $statut = sanitize_input($_POST['statut']);

    $stmt = $pdo->prepare("UPDATE permissions SET id_stagiaire = ?, type = ?, date_debut = ?, date_fin = ?, motif = ?, statut = ? WHERE id = ?");
    $stmt->execute([$id_stagiaire, $type, $date_debut, $date_fin, $motif, $statut, $id]);

    // Check if it's an AJAX request
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => true]);
    } else {
        header('Location: ../admin/manage_permissions.php');
    }
    exit;
} else {
    header('Location: ../admin/manage_permissions.php');
    exit;
}
?>
