<?php
require 'config.php';

session_start();

// Determine language from session or default to Arabic
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
} else {
    $lang = 'ar';
    $_SESSION['lang'] = $lang;
}

// Load language file
$lang_file = __DIR__ . '/lang/' . $lang . '.php';
if (file_exists($lang_file)) {
    $translations = include $lang_file;
} else {
    $translations = include __DIR__ . '/lang/ar.php';
}

// Role check function
function check_role($required_role) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.php');
        exit;
    }
    if (is_array($required_role)) {
        if (!in_array($_SESSION['role'], $required_role)) {
            header('Location: ../auth/login.php');
            exit;
        }
    } else {
        if ($_SESSION['role'] !== $required_role) {
            header('Location: ../auth/login.php');
            exit;
        }
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
