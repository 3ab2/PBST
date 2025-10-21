<?php
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
    <link rel="icon" type="image/svg+xml" href="/pbst_app/images/bst.png">
    <?php $page_title = $page_title ?? $translations['title']; ?>
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <!-- PWA manifest and theme -->
    <link rel="manifest" href="/pbst_app/manifest.webmanifest">
    <meta name="theme-color" content="#0b5fff">
    <link rel="apple-touch-icon" href="/pbst_app/assets/icons/icon-192.png">
    <link href="<?php echo $bootstrap_css; ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --body-bg: #ffffff;
            --body-color: #000000;
            --military-primary: #2C5F2D;
            --military-secondary: #3D5A3C;
            --military-accent: #8B7355;
            --military-gold: #D4AF37;
            --military-dark: #1A3A1A;
        }

        .dark {
            --body-bg: #121212;
            --body-color: #ffffff;
            --military-primary: #3D5A3C;
            --military-secondary: #4A6B4A;
        }

        body {
            background-color: var(--body-bg) !important;
            color: var(--body-color) !important;
            font-family: 'Poppins', sans-serif;
            transition: background-color 0.3s ease;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/pbst_app/images/bg1.webp') no-repeat center center fixed;
            background-size: cover;
            opacity: 0.5;
            z-index: -1;
        }

        input.form-control,
        textarea.form-control {
            background-color: var(--body-bg) !important;
            color: var(--body-color) !important;
            border-color: #ced4da;
            transition: all 0.3s ease;
        }

        input.form-control::placeholder,
        textarea.form-control::placeholder {
            color: var(--body-color);
            opacity: 0.7;
        }

        input.form-control:focus,
        textarea.form-control:focus {
            background-color: var(--body-bg) !important;
            color: var(--body-color) !important;
            border-color: var(--military-gold);
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, .25);
        }

        .dark .login-form {
            background-color: #1e1e1e !important;
            color: var(--body-color) !important;
            box-shadow: 0 8px 16px rgba(255, 255, 255, 0.1);
        }

        label {
            color: var(--body-color);
        }

        /* Navbar Militaire Styling */
        .navbar {
            background: linear-gradient(135deg, var(--military-primary) 0%, var(--military-secondary) 100%) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border-bottom: 3px solid var(--military-gold);
            padding: 0.8rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand img {
            border-radius: 50%;
            border: 2px solid var(--military-gold);
            padding: 3px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover img {
            border-color: #fff;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.6);
        }

        /* Nav Links Animation */
        .nav-link {
            position: relative;
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.6rem 0.5rem !important;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: var(--military-gold);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: var(--military-gold) !important;
            transform: translateY(-2px);
        }

        .nav-link:hover::before {
            width: 80%;
        }

        .nav-link:active {
            transform: translateY(0);
        }

        /* Dropdown Styling */
        .dropdown-menu {
            background: linear-gradient(135deg, var(--military-primary) 0%, var(--military-secondary) 100%);
            border: 2px solid var(--military-gold);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease;
            padding: 0.6rem 1.2rem;
        }

        .dropdown-item:hover {
            background: rgba(212, 175, 55, 0.2);
            color: var(--military-gold) !important;
            padding-left: 1.5rem;
        }

        /* Icon Buttons */
        #theme-toggle,
        #logout-btn,
        #langDropdown {
            font-size: 1.1rem;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0s ease;
            position: relative;
        }

        #theme-toggle:hover,
        #langDropdown:hover {
            color: var(--military-gold) !important;
         
        }

        #logout-btn:hover {
            color: #ff4444 !important;
         
        }

        #logout-btn:active {
            transform: scale(1.3) rotate(-10deg);
        }

        /* Floating Theme Toggle */
        #floating-theme-toggle {
            position: fixed;
            top: 10px;
            left: 10px;
            right: auto;
            z-index: 1000;
            animation: fadeIn 0.5s ease;
        }

        [dir="rtl"] #floating-theme-toggle {
            right: 10px;
            left: auto;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #floating-theme-toggle #theme-toggle {
            background: linear-gradient(135deg, var(--military-primary) 0%, var(--military-secondary) 100%);
            border: 2px solid var(--military-gold);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        #floating-theme-toggle #theme-toggle:hover {
            transform: rotate(180deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
        }

        #floating-theme-toggle #login-btn {
            background: linear-gradient(135deg, var(--military-primary) 0%, var(--military-secondary) 100%);
            border: 2px solid var(--military-gold);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            color: inherit;
        }

        #floating-theme-toggle #login-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
            text-decoration: none;
            color: inherit;
        }

        #floating-theme-toggle #floating-langDropdown {
            background: linear-gradient(135deg, var(--military-primary) 0%, var(--military-secondary) 100%);
            border: 2px solid var(--military-gold);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            color: inherit;
        }

        #floating-theme-toggle #floating-langDropdown:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
            text-decoration: none;
            color: inherit;
        }

        /* Navbar Toggler */
        .navbar-toggler {
            border: 2px solid var(--military-gold);
            transition: all 0.3s ease;
        }

        .navbar-toggler:hover {
            background: rgba(212, 175, 55, 0.2);
            transform: scale(1.05);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23D4AF37' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Responsive Adjustments */
        @media (max-width: 991px) {
            .navbar-nav {
                padding: 1rem 0;
            }

            .nav-link {
                margin: 0.3rem 0;
            }

            .nav-link::before {
                left: 0;
                transform: translateX(0);
            }

            .nav-link:hover::before {
                width: 100%;
            }
        }

        /* Badge Style for Active Page */
        .nav-item.active .nav-link {
            color: var(--military-gold) !important;
            font-weight: 600;
        }

        .nav-item.active .nav-link::before {
            width: 80%;
        }

        
        /* Military-Style Table Styling */
        table {
            background: linear-gradient(135deg, rgba(76, 91, 65, 0.15) 0%, rgba(58, 73, 47, 0.10) 100%);
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
            border: 2px solid var(--military-secondary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        table::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255, 255, 255, 0.02) 10px,
                rgba(255, 255, 255, 0.02) 20px
            );
            pointer-events: none;
            border-radius: 12px;
        }

        .dark table {
            background: linear-gradient(135deg, rgba(76, 91, 65, 0.30) 0%, rgba(58, 73, 47, 0.25) 100%);
            border-color: var(--military-primary);
        }

        .dark table::before {
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255, 255, 255, 0.05) 10px,
                rgba(255, 255, 255, 0.05) 20px
            );
        }

        table th {
            background: linear-gradient(135deg, var(--military-primary) 0%, var(--military-secondary) 100%);
            color: #fff;
            text-align: center;
            vertical-align: middle;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--military-gold);
        }

        .dark table th {
            background: linear-gradient(135deg, var(--military-secondary) 0%, var(--military-dark) 100%);
            color: #f0f0f0;
        }

        table td, table th {
            text-align: center;
            vertical-align: middle;
            padding: 0.75rem;
            border: none;
        }

        table tbody tr {
            transition: all 0.3s ease;
        }

        table tbody tr:hover {
            background: linear-gradient(135deg, rgba(76, 91, 65, 0.20) 0%, rgba(58, 73, 47, 0.15) 100%);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .dark table tbody tr:hover {
            background: linear-gradient(135deg, rgba(76, 91, 65, 0.35) 0%, rgba(58, 73, 47, 0.30) 100%);
        }

        /* Military-Style Action Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-info {
            background: linear-gradient(135deg, #556B2F 0%, #6B8E23 100%); /* Olive green */
            color: #fff;
            border-color: #4A5D23;
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #6B8E23 0%, #556B2F 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 142, 35, 0.4);
            border-color: var(--military-gold);
        }

        .btn-warning {
            background: linear-gradient(135deg, #BDB76B 0%, #D2B48C 100%); /* Khaki */
            color: #2F4F2F;
            border-color: #A09162;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #D2B48C 0%, #BDB76B 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(189, 183, 107, 0.4);
            border-color: var(--military-gold);
        }

        .btn-danger {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%); /* Saddle brown */
            color: #fff;
            border-color: #6B3A1A;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.4);
            border-color: #DC143C;
        }

        .btn i {
            font-weight: 900;
            margin-right: 0.25rem;
        }

        /* Dark mode button adjustments */
        .dark .btn-info {
            background: linear-gradient(135deg, #4A5D23 0%, #556B2F 100%);
            border-color: #3A4A1C;
        }

        .dark .btn-warning {
            background: linear-gradient(135deg, #A09162 0%, #BDB76B 100%);
            color: #1A1A1A;
        }

        .dark .btn-danger {
            background: linear-gradient(135deg, #6B3A1A 0%, #8B4513 100%);
        }

        /* Responsive table adjustments */
        @media (max-width: 768px) {
            table {
                font-size: 0.9rem;
            }

            table td, table th {
                padding: 0.5rem;
            }

            .btn {
                font-size: 0.8rem;
                padding: 0.375rem 0.75rem;
            }

            .btn i {
                margin-right: 0.125rem;
            }
        }
        /* Military-themed search and filter inputs */
        .form-control, .form-select {
            background-color: #E8F5E8 !important; /* light military green */
            color: #1A3A1A !important; /* darker green text */
            border: 1px solid #4A6B4A !important; /* subtle darker green border */
            border-radius: 0.375rem !important; /* rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important; /* subtle shadow */
            font-family: 'Poppins', sans-serif !important;
            padding: 0.375rem 0.75rem !important; /* consistent padding */
            transition: all 0.3s ease !important;
        }

        .form-control:hover, .form-select:hover {
            background-color: #D4E7D4 !important; /* deeper olive on hover */
        }

        .form-control::placeholder {
            color: #6B8E6B !important; /* soft gray-green placeholder */
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--military-gold) !important;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25), 0 0 4px rgba(0, 100, 0, 0.3) !important; /* light border glow */
        }

        /* Dark mode adjustments for inputs */
        .dark .form-control, .dark .form-select {
            background-color: rgba(76, 91, 65, 0.25) !important;
            color: #ffffff !important;
            border-color: #4A6B4A !important;
        }

        .dark .form-control::placeholder {
            color: #B0C4B0 !important;
        }

        .dark .form-control:hover, .dark .form-select:hover {
            background-color: rgba(76, 91, 65, 0.35) !important;
        }

        /* Optional subtle overlay for search/filter section */
        .mb-3.row.g-3.align-items-center {
            background: rgba(76, 91, 65, 0.05) !important; /* subtle transparent overlay */
            padding: 1rem !important;
            border-radius: 0.5rem !important;
            margin-bottom: 1.5rem !important;
        }

        .dark .mb-3.row.g-3.align-items-center {
            background: rgba(76, 91, 65, 0.1) !important;
        }

        /* Breadcrumb Header Styling */
        .breadcrumb-header {
            background: rgba(34, 49, 34, 0.7);
            color: #f5f5dc;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            border: none;
            outline: none;
        }
        .breadcrumb-header span {
            margin: 0 6px;
            color: #d2d2b4;
        }
        .breadcrumb-header:hover {
            filter: brightness(1.1);
        }
    </style>
</head>

<body>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div id="floating-theme-toggle" style="display: flex; gap: 10px; align-items: center;">
            <button id="theme-toggle" class="btn btn-link"
                aria-label="Toggle dark mode">
                <i class="bi bi-sun-fill"></i>
            </button>
            <a href="/pbst_app/auth/login.php" id="login-btn" class="btn btn-link" aria-label="Login">
                <i class="bi bi-box-arrow-in-right"></i>
            </a>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" type="button" id="floating-langDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false" aria-label="Select language">
                    <i class="bi bi-translate"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="floating-langDropdown">
                    <li><a class="dropdown-item" href="#" data-lang="ar">العربية</a></li>
                    <li><a class="dropdown-item" href="#" data-lang="fr">Français</a></li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['user_id']) || isset($_SESSION['instructor_id'])): ?>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="/pbst_app/index.php">
                    <img src="/pbst_app/images/bst.png" alt="Logo" height="35" class="d-inline-block align-top">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/admin/dashboard_admin.php"><?php echo htmlspecialchars($translations['dashboard']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/admin/manage_stagiaires.php"><?php echo htmlspecialchars($translations['trainees']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/cellule_pedagogique/manage_instructors.php"><?php echo htmlspecialchars($translations['Instructeurs']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/admin/manage_consultations.php"><?php echo htmlspecialchars($translations['consultations']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/admin/manage_permissions.php"><?php echo htmlspecialchars($translations['permissions']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/admin/manage_sanctions.php"><?php echo htmlspecialchars($translations['sanctions']); ?></a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="moreDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo htmlspecialchars($translations['plus'] ?? 'Plus'); ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="moreDropdown">
                                    <li><a class="dropdown-item" href="/pbst_app/admin/manage_notes.php"><?php echo htmlspecialchars($translations['notes']); ?></a></li>
                                    <li><a class="dropdown-item" href="/pbst_app/cellule_pedagogique/manage_observations.php"><?php echo htmlspecialchars($translations['Observations']); ?></a></li>
                                    <li><a class="dropdown-item" href="/pbst_app/cellule_pedagogique/manage_subjects.php"><?php echo htmlspecialchars($translations['Matières']); ?></a></li>
                                    <li><a class="dropdown-item" href="/pbst_app/cellule_pedagogique/stats.php"><?php echo htmlspecialchars($translations['stats'] ?? 'Stats'); ?></a></li>
                                    <li><a class="dropdown-item" href="/pbst_app/admin/manage_stages.php"><?php echo htmlspecialchars($translations['stages']); ?></a></li>
                                    <li><a class="dropdown-item" href="/pbst_app/admin/manage_specialites.php"><?php echo htmlspecialchars($translations['specialities']); ?></a></li>
                                    <li><a class="dropdown-item" href="/pbst_app/admin/manage_users.php"><?php echo htmlspecialchars($translations['users']); ?></a></li>
                                    
                                </ul>
                            </li>
                        </ul>
                    <?php elseif ($_SESSION['role'] == 'secretaire'): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/secretaire/dashboard_secretaire.php"><?php echo htmlspecialchars($translations['dashboard']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/secretaire/manage_stagiaires.php"><?php echo htmlspecialchars($translations['trainees']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/secretaire/manage_stages.php"><?php echo htmlspecialchars($translations['stages']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/secretaire/manage_specialites.php"><?php echo htmlspecialchars($translations['specialities']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/secretaire/manage_notes.php"><?php echo htmlspecialchars($translations['notes']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/secretaire/manage_permissions.php"><?php echo htmlspecialchars($translations['permissions']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/secretaire/manage_sanctions.php"><?php echo htmlspecialchars($translations['sanctions']); ?></a>
                            </li>
                        </ul>
                    <?php elseif ($_SESSION['role'] == 'docteur'): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/docteur/dashboard_docteur.php"><?php echo htmlspecialchars($translations['dashboard']); ?></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="/pbst_app/docteur/manage_consultations.php"><?php echo htmlspecialchars($translations['consultations']); ?></a>
                            </li>
                        </ul>
                    <?php elseif ($_SESSION['role'] == 'cellule_pedagogique'): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'dashboard_cellule_pedagogique.php') !== false) ? 'active' : ''; ?>"><a class="nav-link"
                                    href="/pbst_app/cellule_pedagogique/dashboard_cellule_pedagogique.php"><?php echo htmlspecialchars($translations['Dashboard']); ?></a>
                            </li>
                            <li class="nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'manage_instructors.php') !== false) ? 'active' : ''; ?>"><a class="nav-link"
                                    href="/pbst_app/cellule_pedagogique/manage_instructors.php"><?php echo htmlspecialchars($translations['Instructeurs']); ?></a>
                            </li>
                            <li class="nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'manage_observations.php') !== false) ? 'active' : ''; ?>"><a class="nav-link"
                                    href="/pbst_app/cellule_pedagogique/manage_observations.php"><?php echo htmlspecialchars($translations['Observations']); ?></a>
                            </li>
                            <li class="nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'manage_subjects.php') !== false) ? 'active' : ''; ?>"><a class="nav-link"
                                    href="/pbst_app/cellule_pedagogique/manage_subjects.php"><?php echo htmlspecialchars($translations['Matières']); ?></a>
                            </li>
                            <li class="nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'stats.php') !== false) ? 'active' : ''; ?>"><a class="nav-link"
                                    href="/pbst_app/cellule_pedagogique/stats.php"><?php echo htmlspecialchars($translations['stats'] ?? 'Stats'); ?></a>
                            </li>
                        </ul>
                    <?php endif; ?>
                    <ul class="navbar-nav ms-auto align-items-center gap-3">
                        <li class="nav-item">
                            <button id="theme-toggle" class="btn btn-link nav-link p-0"
                                style="border: none; background: none;"
                                aria-label="Toggle dark mode">
                                <i class="bi bi-sun-fill"></i>
                            </button>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle p-0" href="#" id="langDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false" aria-label="Select language">
                                <i class="bi bi-translate"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                                <li><a class="dropdown-item" href="#" data-lang="ar">العربية</a></li>
                                <li><a class="dropdown-item" href="#" data-lang="fr">Français</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="/pbst_app/auth/logout.php" id="logout-btn" class="nav-link p-0"
                                style="border: none; background: none; text-decoration: none;"
                                aria-label="Logout">
                                <i class="bi bi-box-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
        </nav>
    <?php endif; ?>
    <div class="container mt-4">