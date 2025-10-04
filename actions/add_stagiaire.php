<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf = $_POST['csrf_token'];
    if (!validate_csrf_token($csrf)) {
        die('Invalid CSRF token');
    }

    $matricule = sanitize_input($_POST['matricule']);
    $nom = sanitize_input($_POST['nom']);
    $prenom = sanitize_input($_POST['prenom']);
    $date_naissance = $_POST['date_naissance'];
    $adresse = sanitize_input($_POST['adresse']);
    $telephone = sanitize_input($_POST['telephone']);
    $email = sanitize_input($_POST['email']);
    $date_inscription = $_POST['date_inscription'];
    $groupe_sanguin = $_POST['groupe_sanguin'];
    $grade = sanitize_input($_POST['grade']);
    $id_stage = (int)$_POST['id_stage'];
    $id_specialite = (int)$_POST['id_specialite'];

    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $photo = upload_photo($_FILES['photo']);
        if (!$photo) {
            die('خطأ في رفع الصورة');
        }
    }

    $stmt = $pdo->prepare("INSERT INTO stagiaires (matricule, nom, prenom, date_naissance, adresse, telephone, email, date_inscription, groupe_sanguin, grade, photo, id_stage, id_specialite) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$matricule, $nom, $prenom, $date_naissance, $adresse, $telephone, $email, $date_inscription, $groupe_sanguin, $grade, $photo, $id_stage, $id_specialite]);

    // Check if it's an AJAX request
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => true]);
    } else {
        header('Location: ../admin/manage_stagiaires.php');
    }
    exit;
} else {
    header('Location: ../secretaire/manage_stagiaires.php');
    exit;
}
?>
