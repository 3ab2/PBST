<?php
require '../functions.php';
check_role('secretaire');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf = $_POST['csrf_token'];
    if (!validate_csrf_token($csrf)) {
        die('Invalid CSRF token');
    }

    $id = (int)$_POST['id'];

    // Get photo to delete
    $stmt = $pdo->prepare("SELECT photo FROM stagiaires WHERE id = ?");
    $stmt->execute([$id]);
    $stagiaire = $stmt->fetch();
    if ($stagiaire && $stagiaire['photo']) {
        $photo_path = "../uploads/stagiaires/" . $stagiaire['photo'];
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM stagiaires WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../secretaire/manage_stagiaires.php');
    exit;
} else {
    header('Location: ../secretaire/manage_stagiaires.php');
    exit;
}
?>
