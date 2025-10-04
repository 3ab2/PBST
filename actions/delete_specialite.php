<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token'])) {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM specialites WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../admin/manage_specialites.php');
    exit;
} else {
    header('Location: ../admin/manage_specialites.php');
    exit;
}
?>
