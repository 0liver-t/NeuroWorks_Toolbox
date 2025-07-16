<?php
// offline-scanner/test-port.php
header('Content-Type: application/json; charset=utf-8');
$host     = $_GET['host']   ?? '127.0.0.1';
$port     = intval($_GET['port'] ?? 0);
$proto    = strtoupper($_GET['proto']?? 'TCP');
$timeout  = 5;   // en secondes
$isOpen   = false;

if ($port < 1 || $port > 65535) {
    echo json_encode(['error'=>'Port invalide']); exit;
}

if ($proto === 'TCP') {
    $fp = @fsockopen($host, $port, $errNo, $errStr, $timeout);
    if ($fp) { $isOpen = true; fclose($fp); }
} else {
    // Tentative brute via UDP wrapper
    $fp = @fsockopen("udp://{$host}", $port, $errNo, $errStr, $timeout);
    if ($fp) {
        stream_set_timeout($fp, $timeout);
        fwrite($fp, "\0");
        $meta = stream_get_meta_data($fp);
        if (!$meta['timed_out']) { $isOpen = true; }
        fclose($fp);
    }
}

echo json_encode([
  'port'   => $port,
  'proto'  => $proto,
  'isOpen' => $isOpen,
]);
