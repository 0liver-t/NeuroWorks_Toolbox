<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$config = require __DIR__ . '/config.php';
$sources = $config['sources'];
$source = $_GET['source'] ?? $_POST['source'] ?? 'local';

// Gestion spéciale pour la source "user"
if ($source === 'user') {
    $username = $_SESSION['username'] ?? null;
    if (!$username) {
        echo json_encode(['exists' => false, 'message' => 'Utilisateur non connecté.']);
        exit;
    }
    $logDir = __DIR__ . "/logs/{$username}";
    $dbPath = __DIR__ . "/cache/{$username}.db";
} elseif (isset($sources[$source])) {
    $logDir = $sources[$source]['logs_dir'];
    $dbPath = $sources[$source]['cache_db'];
} else {
    echo json_encode(['exists' => false, 'message' => 'Source inconnue.']);
    exit;
}

// Création du dossier s'il n'existe pas
$created = false;
if (!is_dir($logDir)) {
    $created = @mkdir($logDir, 0775, true); // true pour récursif
}

// Recherche récursive de fichiers XML dans tous les sous-dossiers
function countXmlFilesRecursive($dir) {
    $count = 0;
    if (!is_dir($dir)) return 0;
    $rii = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($rii as $file) {
        if ($file->isFile() && strtolower($file->getExtension()) === 'xml') {
            $count++;
        }
    }
    return $count;
}

$num_logs = countXmlFilesRecursive($logDir);

$result = [
    'log_dir_exists' => is_dir($logDir),
    'log_dir_created' => $created,
    'db_exists'      => file_exists($dbPath),
    'has_logs'       => ($num_logs > 0),
    'num_logs'       => $num_logs
];

echo json_encode($result);
