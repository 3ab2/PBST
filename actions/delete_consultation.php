<?php
require '../functions.php';
if (!in_array($_SESSION['role'], ['docteur', 'admin'])) {
    die('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf = $_POST['csrf_token'];
    if (!validate_csrf_token($csrf)) {
        die('Invalid CSRF token');
    }

    $id = (int)$_POST['id'];

    // Optionally delete the file if exists
    $stmt = $pdo->prepare("SELECT file FROM consultations WHERE id = ?");
    $stmt->execute([$id]);
    $consultation = $stmt->fetch();
    if ($consultation && $consultation['file']) {
        $file_path = "../uploads/consultations/" . $consultation['file'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM consultations WHERE id = ?");
    $stmt->execute([$id]);

    if ($_SESSION['role'] == 'admin') {
        header('Location: ../admin/manage_consultations.php');
    } else {
        header('Location: ../docteur/manage_consultations.php');
    }
    exit;
} else {
    if ($_SESSION['role'] == 'admin') {
        header('Location: ../admin/manage_consultations.php');
    } else {
        header('Location: ../docteur/manage_consultations.php');
    }
    exit;
}
?>
