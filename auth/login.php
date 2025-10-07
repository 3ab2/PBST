<?php
require '../functions.php';
require '../config.php';

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
$lang_file = __DIR__ . '/../lang/' . $lang . '.php';
if (file_exists($lang_file)) {
    $translations = include $lang_file;
} else {
    $translations = include __DIR__ . '/../lang/ar.php';
}


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
        $errors[] = $translations['login_csrf_invalid'];
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
            $errors[] = $translations['login_invalid_credentials'];
        }
    }
}

$csrf_token = generate_csrf_token();
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>
<div class="position-absolute top-0 end-0 p-3">
    <div class="dropdown">
        <button class="btn btn-outline-success dropdown-toggle" type="button" id="langDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo htmlspecialchars($translations['language']); ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" style="background-color: #549d754f;" aria-labelledby="langDropdown">
            <li><a class="dropdown-item" href="?lang=ar"><?php echo htmlspecialchars($translations['arabic']); ?></a>
            </li>
            <li><a class="dropdown-item" href="?lang=fr"><?php echo htmlspecialchars($translations['french']); ?></a>
            </li>
        </ul>
    </div>
</div>
<style>
    body,
    html {
        height: 100%;
        background-image: url('../images/bg.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
      
    }
    body{
          opacity: 0.8;
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
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
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
        top: 70%;
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
                height: 120px;
                width: 120px;
                background-image: url('../images/bst.png');
                
                background-repeat: no-repeat;
                background-size: cover;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0 auto 1rem auto;
                border-radius: 30px;
            }
        </style>
        <div class="anime-bg"></div>
        <h2 class="text-center mb-4"><?php echo htmlspecialchars($translations['login_title']); ?></h2>
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error):
                    echo "<p>$error</p>"; endforeach; ?>
            </div>
        <?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <div class="mb-3 form-group">
            <label for="username"
                class="form-label"><?php echo htmlspecialchars($translations['login_username_label']); ?></label>
            <i class="bi bi-person-fill form-icon"></i>
            <input type="text" class="form-control" id="username" name="username" required autocomplete="username"
                placeholder="<?php echo htmlspecialchars($translations['login_username_placeholder']); ?>">
        </div>
        <div class="mb-3 form-group">
            <label for="password"
                class="form-label"><?php echo htmlspecialchars($translations['login_password_label']); ?></label>
            <i class="bi bi-lock-fill form-icon"></i>
            <input type="password" class="form-control" id="password" name="password" required
                autocomplete="current-password"
                placeholder="<?php echo htmlspecialchars($translations['login_password_placeholder']); ?>">
        </div>
        <button type="submit"
            class="btn btn-primary w-100"><?php echo htmlspecialchars($translations['login_button']); ?></button>
        <div class="text-center mt-3">
            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                title="<?php echo htmlspecialchars($translations['forgot_password_tooltip']); ?>"
                style="color: #72787fff; text-decoration: none;">
                 <?php echo htmlspecialchars($translations['forgot_password_link']); ?>
            </a>
        </div>
    </form>

</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php include '../templates/footer.php'; ?>