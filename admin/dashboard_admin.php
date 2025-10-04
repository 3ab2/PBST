<?php
require '../functions.php';
check_role('admin');
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2><?php echo htmlspecialchars($translations['admin_dashboard']); ?></h2>
<p><?php echo htmlspecialchars($translations['welcome_admin'] ?? 'مرحبا بك في لوحة التحكم الخاصة بالمدير.'); ?></p>
<ul>
    <li><a href="manage_users.php"><?php echo htmlspecialchars($translations['manage_users'] ?? 'إدارة المستخدمين'); ?></a></li>
    <li><a href="manage_stages.php"><?php echo htmlspecialchars($translations['manage_stages'] ?? 'إدارة الدورات'); ?></a></li>
    <li><a href="manage_specialites.php"><?php echo htmlspecialchars($translations['manage_specialites'] ?? 'إدارة التخصصات'); ?></a></li>
    <li><a href="manage_consultations.php"><?php echo htmlspecialchars($translations['manage_consultations'] ?? 'إدارة الاستشارات'); ?></a></li>
    <li><a href="manage_stagiaires.php"><?php echo htmlspecialchars($translations['manage_stagiaires'] ?? 'إدارة المتدربين'); ?></a></li>
    <li><a href="manage_notes.php"><?php echo htmlspecialchars($translations['manage_notes'] ?? 'إدارة الملاحظات'); ?></a></li>
    <li><a href="manage_permissions.php"><?php echo htmlspecialchars($translations['manage_permissions'] ?? 'إدارة الأذونات'); ?></a></li>
    <li><a href="manage_sanctions.php"><?php echo htmlspecialchars($translations['manage_sanctions'] ?? 'إدارة العقوبات'); ?></a></li>
</ul>
<?php include '../templates/footer.php'; ?>
