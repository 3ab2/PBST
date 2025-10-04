<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="../images/army.png">
    <title>PBST Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body>
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
                    <li class="nav-item"><a class="nav-link" href="../admin/dashboard_admin.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_users.php">المستخدمين</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_stagiaires.php"> المتدربين</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_consultations.php"> الاستشارات</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_stages.php">الدورات</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_specialites.php">التخصصات</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_notes.php">الملاحظات</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_permissions.php">الأذونات</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/manage_sanctions.php">العقوبات</a></li>
                   
                </ul>
                <?php elseif ($_SESSION['role'] == 'secretaire'): ?>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../secretaire/dashboard_secretaire.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../secretaire/manage_stagiaires.php">إدارة المتدربين</a></li>
                </ul>
                <?php elseif ($_SESSION['role'] == 'docteur'): ?>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../docteur/dashboard_docteur.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../docteur/manage_consultations.php">إدارة الاستشارات</a></li>
                </ul>
                <?php endif; ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../auth/logout.php">تسجيل الخروج</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    <div class="container mt-4">
