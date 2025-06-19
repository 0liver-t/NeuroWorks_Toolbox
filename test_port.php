<?php
// Vérification des paramètres
if (!isset($_GET['protocol']) || !isset($_GET['range'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Paramètres manquants.']);
    exit;
}

$protocol = strtolower($_GET['protocol']);
$range = $_GET['range'];

// Sanitize protocole
if (!in_array($protocol, ['tcp', 'udp'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Protocole non supporté.']);
    exit;
}

// Récupère l'IP du client
$target = $_SERVER['REMOTE_ADDR'];

function is_port_open($ip, $port, $protocol) {
    if ($protocol === 'tcp') {
        $connection = @fsockopen($ip, $port, $errno, $errstr, 1.0);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    } elseif ($protocol === 'udp') {
        $sock = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if (!$sock) return false;

        socket_set_option($sock, SOL_SOCKET, SO_RCVTIMEO, ["sec" => 1, "usec" => 0]);
        $message = "Ping";
        @socket_sendto($sock, $message, strlen($message), 0, $ip, $port);
        $buf = '';
        $from = '';
        $portOut = 0;
        $recv = @socket_recvfrom($sock, $buf, 512, 0, $from, $portOut);
        socket_close($sock);
        return $recv !== false;
    }

    return false;
}

// Analyse la plage
$ports = [];
if (strpos($range, '-') !== false) {
    [$start, $end] = explode('-', $range);
    $start = intval($start);
    $end = intval($end);
    if ($start > $end || $start < 1 || $end > 65535) {
        http_response_code(400);
        echo json_encode(['error' => 'Plage invalide.']);
        exit;
    }
    for ($i = $start; $i <= $end; $i++) {
        $ports[] = $i;
    }
} else {
    $port = intval($range);
    if ($port < 1 || $port > 65535) {
        http_response_code(400);
        echo json_encode(['error' => 'Port invalide.']);
        exit;
    }
    $ports[] = $port;
}

// Teste les ports
$open = false;
foreach ($ports as $p) {
    if (is_port_open($target, $p, $protocol)) {
        $open = true;
        break;
    }
}

header('Content-Type: application/json');
echo json_encode(['open' => $open]);
