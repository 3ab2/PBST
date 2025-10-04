<?php
require '../functions.php';
check_role('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf = $_POST['csrf_token'];
    if (!validate_csrf_token($csrf)) {
        die('Invalid CSRF token');
    }

    $id = (int)$_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM permissions WHERE id = ?");
    $stmt->execute([$id]);

    // Check if it's an AJAX request
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => true]);
    } else {
        header('Location: ../admin/manage_permissions.php');
    }
    exit;
} else {
    header('Location: ../admin/manage_permissions.php');
    exit;
}
?>
