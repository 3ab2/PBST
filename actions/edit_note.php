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
    $remarque = sanitize_input($_POST['remarque']);
    $date_remarque = $_POST['date_remarque'];
    $auteur_id = !empty($_POST['auteur_id']) ? (int)$_POST['auteur_id'] : null;

    $stmt = $pdo->prepare("UPDATE remarques SET id_stagiaire = ?, remarque = ?, date_remarque = ?, auteur_id = ? WHERE id = ?");
    $stmt->execute([$id_stagiaire, $remarque, $date_remarque, $auteur_id, $id]);

    // Check if it's an AJAX request
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => true]);
    } else {
        header('Location: ../admin/manage_notes.php');
    }
    exit;
} else {
    header('Location: ../admin/manage_notes.php');
    exit;
}
?>
