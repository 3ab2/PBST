<?php
require '../functions.php';
check_role('admin');
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2>لوحة تحكم المدير</h2>
<p>مرحبا بك في لوحة التحكم الخاصة بالمدير.</p>
<ul>
    <li><a href="manage_users.php">إدارة المستخدمين</a></li>
    <li><a href="manage_stages.php">إدارة الدورات</a></li>
    <li><a href="manage_specialites.php">إدارة التخصصات</a></li>
    <li><a href="manage_consultations.php">إدارة الاستشارات</a></li>
    <li><a href="manage_stagiaires.php">إدارة المتدربين</a></li>
    <li><a href="manage_notes.php">إدارة الملاحظات</a></li>
    <li><a href="manage_permissions.php">إدارة الأذونات</a></li>
    <li><a href="manage_sanctions.php">إدارة العقوبات</a></li>
</ul>
<?php include '../templates/footer.php'; ?>
