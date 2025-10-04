<?php
require '../functions.php';
check_role('secretaire');
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2><?php echo htmlspecialchars($translations['secretaire_dashboard_title']); ?></h2>
<p><?php echo htmlspecialchars($translations['secretaire_dashboard_welcome']); ?></p>
<ul>
    <li><a href="manage_stagiaires.php"><?php echo htmlspecialchars($translations['manage_stagiaires_link']); ?></a></li>
</ul>
<?php include '../templates/footer.php'; ?>
