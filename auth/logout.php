<?php
session_start();
session_destroy();
header('Location: /pbst_app/auth/login.php');
exit;
?>
