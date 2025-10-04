<?php
require '../functions.php';
require '../config.php';


if (isset($_SESSION['user_id'])) {
    header('Location: ../' . $_SESSION['role'] . '/dashboard_' . $_SESSION['role'] . '.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    $csrf = $_POST['csrf_token'];

    if (!validate_csrf_token($csrf)) {
        $errors[] = 'رمز CSRF غير صالح';
    } else {
        $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: ../' . $user['role'] . '/dashboard_' . $user['role'] . '.php');
            exit;
        } else {
            $errors[] = 'بيانات الدخول غير صحيحة';
        }
    }
}

$csrf_token = generate_csrf_token();
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<style>
    body, html {
        height: 100%;
        background: linear-gradient(135deg, #d9d8dbff 0%, #5a5c61ff 100%);
    }
    .login-container {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .login-form {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.3);
        width: 100%;
        max-width: 400px;
        animation: fadeInDown 1s ease;
    }
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .form-icon {
        position: absolute;
        left: 15px;
        top:70%;
        transform: translateY(-50%);
        color: #6a11cb;
    }
    .form-group {
        position: relative;
    }
    .form-control {
        padding-left: 2.5rem;
    }
</style>
<div class="login-container">
    <form method="post" class="login-form" novalidate>
         <style>
    .anime-bg {
        height: 100px;
        width: 100px;
        background-image: url('../images/management.png');
        background-repeat: no-repeat;
        background-size: cover;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 1rem auto;
    }
        </style>
        <div class="anime-bg"></div>
        <h2 class="text-center mb-4">تسجيل الدخول</h2>
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): echo "<p>$error</p>"; endforeach; ?>
            </div>
        <?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <div class="mb-3 form-group">
            <label for="username" class="form-label">اسم المستخدم</label>
            <i class="bi bi-person-fill form-icon"></i>
            <input type="text" class="form-control" id="username" name="username" required autocomplete="username" placeholder="أدخل اسم المستخدم">
        </div>
        <div class="mb-3 form-group">
            <label for="password" class="form-label">كلمة المرور</label>
            <i class="bi bi-lock-fill form-icon"></i>
            <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" placeholder="أدخل كلمة المرور">
        </div>
        <button type="submit" class="btn btn-primary w-100">دخول</button>
    </form>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<?php include '../templates/footer.php'; ?>
