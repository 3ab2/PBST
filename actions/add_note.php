<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token'])) {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $id_stagiaire = $_POST['id_stagiaire'];
    $remarque = sanitize_input($_POST['remarque']);
    $date_remarque = $_POST['date_remarque'];
    $auteur_id = $_POST['auteur_id'] ?: null;

    $stmt = $pdo->prepare("INSERT INTO remarques (id_stagiaire, remarque, date_remarque, auteur_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_stagiaire, $remarque, $date_remarque, $auteur_id]);

    header('Location: ../admin/manage_notes.php');
    exit;
} else {
    header('Location: ../admin/manage_notes.php');
    exit;
}
?>
