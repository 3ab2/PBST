<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf = $_POST['csrf_token'];
    if (!validate_csrf_token($csrf)) {
        die('Invalid CSRF token');
    }

    $id = (int)$_POST['id'];
    $nom_specialite = sanitize_input($_POST['nom_specialite']);
    $description = sanitize_input($_POST['description']);

    $stmt = $pdo->prepare("UPDATE specialites SET nom_specialite = ?, description = ? WHERE id = ?");
    $stmt->execute([$nom_specialite, $description, $id]);

    header('Location: ../admin/manage_specialites.php');
    exit;
} else {
    header('Location: ../admin/manage_specialites.php');
    exit;
}
?>
