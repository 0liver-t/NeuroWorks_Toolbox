<?php
// load_logs.php
// Serve DataTables server-side requests

ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Load config
$config = require __DIR__ . '/config.php';
$sources = $config['sources'];
$perPage = $config['per_page'] ?? 100;

// Gère la source
$source = $_GET['source'] ?? $_POST['source'] ?? 'local';

if ($source === 'user') {
    // Source personnalisée pour chaque utilisateur
    $username = $_SESSION['username'] ?? null;
    if (!$username) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => true, 'message' => 'Utilisateur non connecté.']);
        exit;
    }
    $dbPath = __DIR__ . "/cache/{$username}.db";
} elseif (isset($sources[$source])) {
    $dbPath = $sources[$source]['cache_db'] ?? null;
} else {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => true, 'message' => 'Source inconnue.']);
    exit;
}

if (empty($dbPath)) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => true, 'message' => 'Chemin base SQLite non configuré.']);
    exit;
}

// Read DataTables params
$draw   = intval($_GET['draw'] ?? 1);
$start  = intval($_GET['start'] ?? 0);
$length = intval($_GET['length'] ?? ($config['per_page'] ?? 100));

// Si l'appel ne vient pas d'un DataTables serverSide mais d'une URL ?per_page=..., alors surcharge length
if (isset($_GET['per_page'])) {
    $length = intval($_GET['per_page']);
    $start = 0; // facultatif, pour être sûr de tout prendre depuis le début
}

$searchValue = trim($_GET['search']['value'] ?? '');
$orderCol  = intval($_GET['order'][0]['column'] ?? 0);
$orderDir  = strtoupper($_GET['order'][0]['dir'] ?? 'DESC');

$columns = ['time', 'mach', 'user', 'acID', 'evID', 'evdesc'];
$orderColumn = $columns[$orderCol] ?? 'time';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $where = '';
    $params = [];
    if ($searchValue !== '') {
        $parts = [];
        foreach ($columns as $col) {
            $parts[] = "$col LIKE :search";
        }
        $where = 'WHERE ' . implode(' OR ', $parts);
        $params[':search'] = "%{$searchValue}%";
    }

    $recordsTotal = (int)$pdo->query('SELECT COUNT(*) FROM logs')->fetchColumn();

    $countSql = "SELECT COUNT(*) FROM logs {$where}";
    $stmt = $pdo->prepare($countSql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, PDO::PARAM_STR);
    }
    $stmt->execute();
    $recordsFiltered = (int)$stmt->fetchColumn();

    $dataSql = "SELECT time, mach, user, acID, evID, evdesc
                FROM logs
                {$where}
                ORDER BY {$orderColumn} {$orderDir}
                LIMIT :length OFFSET :start";
    $stmt = $pdo->prepare($dataSql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, PDO::PARAM_STR);
    }
    $stmt->bindValue(':length', $length, PDO::PARAM_INT);
    $stmt->bindValue(':start',  $start,  PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'draw'            => $draw,
        'recordsTotal'    => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data'            => $data,
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => true, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
exit;
