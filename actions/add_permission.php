<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token'])) {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $id_stagiaire = $_POST['id_stagiaire'];
    $type = $_POST['type'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $motif = sanitize_input($_POST['motif']);
    $statut = $_POST['statut'];

    $stmt = $pdo->prepare("INSERT INTO permissions (id_stagiaire, type, date_debut, date_fin, motif, statut) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_stagiaire, $type, $date_debut, $date_fin, $motif, $statut]);

    header('Location: ../admin/manage_permissions.php');
    exit;
} else {
    header('Location: ../admin/manage_permissions.php');
    exit;
}
?>
