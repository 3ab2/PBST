<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token'])) {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $id_stagiaire = $_POST['id_stagiaire'];
    $type = sanitize_input($_POST['type']);
    $description = sanitize_input($_POST['description']);
    $date_punition = $_POST['date_punition'];
    $auteur_id = $_POST['auteur_id'] ?: null;

    $stmt = $pdo->prepare("INSERT INTO punitions (id_stagiaire, type, description, date_punition, auteur_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_stagiaire, $type, $description, $date_punition, $auteur_id]);

    header('Location: ../admin/manage_sanctions.php');
    exit;
} else {
    header('Location: ../admin/manage_sanctions.php');
    exit;
}
?>
