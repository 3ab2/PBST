<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token'])) {
    if (!validate_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $id = $_POST['id'];
    $username = sanitize_input($_POST['username']);
    $role = $_POST['role'];
    $nom = sanitize_input($_POST['nom']);
    $prenom = sanitize_input($_POST['prenom']);
    $email = sanitize_input($_POST['email']);

    // Update password only if provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, role = ?, nom = ?, prenom = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $password, $role, $nom, $prenom, $email, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ?, nom = ?, prenom = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $role, $nom, $prenom, $email, $id]);
    }

    header('Location: ../admin/manage_users.php');
    exit;
} else {
    header('Location: ../admin/manage_users.php');
    exit;
}
?>
