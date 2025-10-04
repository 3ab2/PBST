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
    $id_stagiaire = (int)$_POST['id_stagiaire'];
    $id_docteur = isset($_POST['id_docteur']) ? (int)$_POST['id_docteur'] : $_SESSION['user_id'];
    $date_consultation = $_POST['date_consultation'];
    $diagnostic = sanitize_input($_POST['diagnostic']);
    $traitement = sanitize_input($_POST['traitement']);
    $remarques = sanitize_input($_POST['remarques']);

    $stmt = $pdo->prepare("UPDATE consultations SET id_stagiaire = ?, id_docteur = ?, date_consultation = ?, diagnostic = ?, traitement = ?, remarques = ? WHERE id = ?");
    $stmt->execute([$id_stagiaire, $id_docteur, $date_consultation, $diagnostic, $traitement, $remarques, $id]);

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
