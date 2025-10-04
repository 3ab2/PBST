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

    $id_stagiaire = (int)$_POST['id_stagiaire'];
    $date_consultation = $_POST['date_consultation'];
    $diagnostic = sanitize_input($_POST['diagnostic']);
    $traitement = sanitize_input($_POST['traitement']);
    $remarques = sanitize_input($_POST['remarques']);
    $id_docteur = isset($_POST['id_docteur']) ? (int)$_POST['id_docteur'] : $_SESSION['user_id'];

    $file_path = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/consultations/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_name = time() . '_' . basename($_FILES['file']['name']);
        $file_path = $upload_dir . $file_name;
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            die('Failed to upload file');
        }
        $file_path = 'uploads/consultations/' . $file_name; // relative path for DB
    }

    $stmt = $pdo->prepare("INSERT INTO consultations (id_stagiaire, id_docteur, date_consultation, diagnostic, traitement, remarques, file) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_stagiaire, $id_docteur, $date_consultation, $diagnostic, $traitement, $remarques, $file_path]);

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
