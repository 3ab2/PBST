<?php
require '../functions.php';
check_role('secretaire');
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2>لوحة تحكم السكرتير</h2>
<p>مرحبا بك في لوحة التحكم الخاصة بالسكرتير.</p>
<ul>
    <li><a href="manage_stagiaires.php">إدارة المتدربين</a></li>
</ul>
<?php include '../templates/footer.php'; ?>
