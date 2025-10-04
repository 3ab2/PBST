<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf = $_POST['csrf_token'];
    if (!validate_csrf_token($csrf)) {
        die('Invalid CSRF token');
    }

    $id = (int)$_POST['id'];
    $intitule = sanitize_input($_POST['intitule']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    $stmt = $pdo->prepare("UPDATE stages SET intitule = ?, date_debut = ?, date_fin = ? WHERE id = ?");
    $stmt->execute([$intitule, $date_debut, $date_fin, $id]);

    header('Location: ../admin/manage_stages.php');
    exit;
} else {
    header('Location: ../admin/manage_stages.php');
    exit;
}
?>
