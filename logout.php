<?php
session_start();
$lang = $_POST['lang'] ?? $_GET['lang'] ?? 'fr';
session_destroy();
header("Location: login.php?lang=" . urlencode($lang));
exit;
