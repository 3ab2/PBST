<?php
require '../functions.php';
check_role('secretaire');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf = $_POST['csrf_token'];
    if (!validate_csrf_token($csrf)) {
        die('Invalid CSRF token');
    }

    $id = (int)$_POST['id'];
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

    // Get current photo
    $stmt = $pdo->prepare("SELECT photo FROM stagiaires WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetch();
    $old_photo = $current['photo'];

    $photo = $old_photo;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $new_photo = upload_photo($_FILES['photo'], $old_photo);
        if ($new_photo) {
            $photo = $new_photo;
        }
    }

    $stmt = $pdo->prepare("UPDATE stagiaires SET matricule = ?, nom = ?, prenom = ?, date_naissance = ?, adresse = ?, telephone = ?, email = ?, date_inscription = ?, groupe_sanguin = ?, grade = ?, photo = ?, id_stage = ?, id_specialite = ? WHERE id = ?");
    $stmt->execute([$matricule, $nom, $prenom, $date_naissance, $adresse, $telephone, $email, $date_inscription, $groupe_sanguin, $grade, $photo, $id_stage, $id_specialite, $id]);

    header('Location: ../secretaire/manage_stagiaires.php');
    exit;
} else {
    header('Location: ../secretaire/manage_stagiaires.php');
    exit;
}
?>
