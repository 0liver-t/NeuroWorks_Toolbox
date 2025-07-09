<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || !preg_match('/^[a-zA-Z0-9_\-]+$/', $_SESSION['username'])) {
    exit("Session expirée. Veuillez vous reconnecter.");
}

if (!isset($_POST['source']) || $_POST['source'] !== 'user') {
    exit("Upload non autorisé.");
}

if (!isset($_FILES['xml-file'])) {
    exit("Aucun fichier reçu.");
}

// 1. Détermine le dossier utilisateur pour logs (conforme à cache_setup.php)
$username = $_SESSION['username'];
$logDir = __DIR__ . "/logs/{$username}/";

if (!is_dir($logDir)) {
    if (!@mkdir($logDir, 0775, true)) {
        exit("Impossible de créer le dossier utilisateur.");
    }
}

// 2. Suppression de tous les anciens fichiers XML dans le dossier
foreach (glob($logDir . '*.xml') as $oldfile) {
    @unlink($oldfile);
}

// 3. Upload de tous les fichiers XML
$total = count($_FILES['xml-file']['name']);
$ok = 0; $errors = 0; $details = [];

for ($i = 0; $i < $total; $i++) {
    $filename = basename($_FILES['xml-file']['name'][$i]);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if ($ext !== 'xml') {
        $errors++;
        $details[] = "$filename : extension refusée";
        continue;
    }
    $targetPath = $logDir . $filename;
    if (move_uploaded_file($_FILES['xml-file']['tmp_name'][$i], $targetPath)) {
        $ok++;
        $details[] = "$filename : OK";
    } else {
        $errors++;
        $details[] = "$filename : erreur upload";
    }
}

echo "<strong>$ok</strong> fichier(s) XML uploadé(s), <strong>$errors</strong> erreur(s).<br>";
if ($errors > 0) {
    echo "<ul>";
    foreach ($details as $d) {
        if (strpos($d, 'OK') === false) echo "<li>$d</li>";
    }
    echo "</ul>";
}