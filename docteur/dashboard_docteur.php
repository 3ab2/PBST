<?php
require '../functions.php';
check_role('docteur');
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2>لوحة تحكم الطبيب</h2>
<p>مرحبا بك في لوحة التحكم الخاصة بالطبيب.</p>
<ul>
    <li><a href="manage_consultations.php">إدارة الاستشارات الطبية</a></li>
</ul>
<?php include '../templates/footer.php'; ?>
