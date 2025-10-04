<?php
require 'config.php';

session_start();

// Role check function
function check_role($required_role) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $required_role) {
        header('Location: ../auth/login.php');
        exit;
    }
}

// CSRF token generation
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF token validation
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Input sanitization
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Photo upload function
function upload_photo($file, $old_photo = null) {
    $target_dir = "../uploads/stagiaires/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 3 * 1024 * 1024; // 3MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }

    if ($file['size'] > $max_size) {
        return false;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_name = uniqid() . '.' . $ext;
    $target_file = $target_dir . $new_name;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        if ($old_photo && file_exists($target_dir . $old_photo)) {
            unlink($target_dir . $old_photo);
        }
        return $new_name;
    }
    return false;
}
?>
