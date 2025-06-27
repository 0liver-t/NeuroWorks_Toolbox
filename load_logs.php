<?php
// load_logs.php
// Serve DataTables server-side requests

// 1) Activer l’affichage des erreurs pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// 2) Load config
$config = require __DIR__ . '/config.php';

// 3) Read DataTables params
$draw   = intval($_GET['draw'] ?? 1);
$start  = intval($_GET['start'] ?? 0);
$length = intval($_GET['length'] ?? $config['per_page']);
$searchValue = trim($_GET['search']['value'] ?? '');
$orderCol  = intval($_GET['order'][0]['column'] ?? 0);
$orderDir  = strtoupper($_GET['order'][0]['dir'] ?? 'DESC');

// 4) Map column index to DB column
$columns = ['time', 'mach', 'user', 'acID', 'evID', 'evdesc'];
$orderColumn = $columns[$orderCol] ?? 'time';

try {
    // 5) Connect PDO
    $pdo = new PDO('sqlite:' . $config['cache_db']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 6) Build WHERE clause for search
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

    // 7) Total records without filtering
    $recordsTotal = (int)$pdo->query('SELECT COUNT(*) FROM logs')->fetchColumn();

    // 8) Total records with filtering
    $countSql = "SELECT COUNT(*) FROM logs {$where}";
    $stmt = $pdo->prepare($countSql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, PDO::PARAM_STR);
    }
    $stmt->execute();
    $recordsFiltered = (int)$stmt->fetchColumn();

    // 9) Fetch paginated data
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

    // 10) Return JSON
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
