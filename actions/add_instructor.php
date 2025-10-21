<?php
require '../functions.php';
check_role('cellule_pedagogique');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

header('Content-Type: application/json');

$name = trim($_POST['first_name'] . ' ' . $_POST['last_name'] ?? '');
$cine = trim($_POST['cine'] ?? '');
$mle = trim($_POST['mle'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$bio = trim($_POST['bio'] ?? '');
$speciality_id = trim($_POST['speciality_id'] ?? '');

if ($first_name === '' || $last_name === '' || $username === '' || $password === '' || ($cine === '' && $mle === '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid input: missing required fields']);
    exit;
}

// Hash password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {

    // Check if CINE or MLE already exists (if provided)
    if ($cine !== '' || $mle !== '') {
        $stmt = $pdo->prepare("SELECT id_instructor FROM instructors WHERE cine = ? OR mle = ?");
        $stmt->execute([$cine, $mle]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'CINE or MLE already exists']);
            exit;
        }
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id_instructor FROM instructors WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO instructors (cine, mle, username, first_name, last_name, email, phone, bio, password, speciality_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        sanitize_input($cine),
        sanitize_input($mle),
        sanitize_input($username),
        sanitize_input($first_name),
        sanitize_input($last_name),
        sanitize_input($email),
        sanitize_input($phone),
        sanitize_input($bio),
        $hashedPassword,
        $speciality_id ? (int)$speciality_id : null
    ]);

    echo json_encode(['success' => true, 'message' => 'Instructor added successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
