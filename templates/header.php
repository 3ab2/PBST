<?php


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

// Set html lang and dir attributes
$html_lang = $translations['lang_code'] ?? 'ar';
$html_dir = $translations['dir'] ?? 'rtl';

// Determine Bootstrap CSS based on direction
$bootstrap_css = $html_dir === 'rtl' ? 
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css' : 
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';

?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($html_lang); ?>" dir="<?php echo htmlspecialchars($html_dir); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="../images/army.png">
    <title><?php echo htmlspecialchars($translations['title']); ?></title>
    <link href="<?php echo $bootstrap_css; ?>" rel="stylesheet">
    <style>
        :root {
            --body-bg: #ffffff;
            --body-color: #000000;
        }
        .dark {
            --body-bg: #121212;
            --body-color: #ffffff;
        }
        body {
            background-color: var(--body-bg) !important;
            color: var(--body-color) !important;
        }
        input.form-control, textarea.form-control {
            background-color: var(--body-bg) !important;
            color: var(--body-color) !important;
            border-color: #ced4da;
        }
        input.form-control::placeholder, textarea.form-control::placeholder {
            color: var(--body-color);
            opacity: 0.7;
        }
        input.form-control:focus, textarea.form-control:focus {
            background-color: var(--body-bg) !important;
            color: var(--body-color) !important;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .dark .login-form {
            background-color: #1e1e1e !important;
            color: var(--body-color) !important;
            box-shadow: 0 8px 16px rgba(255,255,255,0.1);
        }
        label {
            color: var(--body-color);
        }
        #theme-toggle {
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--body-color);
        }
        #logout-btn {
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--body-color);
            transition: transform 0.3s ease;
        }
        #logout-btn:active {
            transform: scale(1.2);
        }
    </style>
</head>
<body>
    <?php if (!isset($_SESSION['user_id'])): ?>
    <div id="floating-theme-toggle" style="position: fixed; top: 10px; <?php echo $html_dir === 'rtl' ? 'right: 10px;' : 'left: 10px;'; ?> z-index: 1000;">
        <button id="theme-toggle" class="btn btn-link" style="border: none; background: none; color: inherit; font-size: 1.2rem;" aria-label="Toggle dark mode">
            <i class="bi bi-sun-fill"></i>
        </button>
    </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../images/army.png" alt="Logo" height="30" class="d-inline-block align-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if ($_SESSION['role'] == 'admin'): ?>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../admin/dashboard_admin.php"><?php echo htmlspecialchars($translations['dashboard']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_users.php"><?php echo htmlspecialchars($translations['users']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_stagiaires.php"><?php echo htmlspecialchars($translations['trainees']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_consultations.php"><?php echo htmlspecialchars($translations['consultations']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_stages.php"><?php echo htmlspecialchars($translations['stages']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_specialites.php"><?php echo htmlspecialchars($translations['specialities']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_notes.php"><?php echo htmlspecialchars($translations['notes']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_permissions.php"><?php echo htmlspecialchars($translations['permissions']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_sanctions.php"><?php echo htmlspecialchars($translations['sanctions']); ?></a></li>
                </ul>
                <?php elseif ($_SESSION['role'] == 'secretaire'): ?>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../secretaire/dashboard_secretaire.php"><?php echo htmlspecialchars($translations['dashboard']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../secretaire/manage_stagiaires.php"><?php echo htmlspecialchars($translations['trainees']); ?></a></li>
                </ul>
                <?php elseif ($_SESSION['role'] == 'docteur'): ?>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../docteur/dashboard_docteur.php"><?php echo htmlspecialchars($translations['dashboard']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../docteur/manage_consultations.php"><?php echo htmlspecialchars($translations['consultations']); ?></a></li>
                </ul>
                <?php endif; ?>
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <button id="theme-toggle" class="btn btn-link nav-link p-0" style="border: none; background: none; color: inherit; font-size: 1.2rem;" aria-label="Toggle dark mode">
                            <i class="bi bi-sun-fill"></i>
                        </button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Select language">
                            <i class="bi bi-translate"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                            <li><a class="dropdown-item" href="#" data-lang="ar">العربية</a></li>
                            <li><a class="dropdown-item" href="#" data-lang="fr">Français</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="../auth/logout.php" id="logout-btn" class="nav-link p-0" style="border: none; background: none; color: inherit; font-size: 1.2rem; text-decoration: none;" aria-label="Logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    </li>
                </ul>
        </div>
    </nav>
    <?php endif; ?>
    <div class="container mt-4">
