<?php
// Ce fichier est chargé dynamiquement dans l'onglet "Journaux" via index.php

if (!defined('XL_LANG')) {
  http_response_code(403);
  exit("Accès direct non autorisé.");
}

// Libellés traduits
$labels = [
  'title' => XL_LANG['port_checker_title'] ?? "Vérificateur d’ouverture de ports",
  'intro' => XL_LANG['port_checker_intro'] ?? "Le test ci-dessous vérifie si certains ports sont ouverts sur votre serveur web.",
  'protocol' => XL_LANG['port_checker_protocol'] ?? "Protocole",
  'range'    => XL_LANG['port_checker_range'] ?? "Port(s)",
  'app'      => XL_LANG['port_checker_application'] ?? "Application",
  'comment'  => XL_LANG['port_checker_comment'] ?? "Commentaire",
  'status'   => XL_LANG['port_checker_status'] ?? "Statut",
  'pending'  => XL_LANG['port_checker_pending'] ?? "⏳",
  'open'     => XL_LANG['port_checker_open'] ?? "✅ Ouvert",
  'closed'   => XL_LANG['port_checker_closed'] ?? "❌ Fermé",
  'error'    => XL_LANG['port_checker_error'] ?? "⚠️ Erreur"
];


// Récupère l'IP du client
$client_ip = $_SERVER['REMOTE_ADDR'];
// IP du serveur web
$server_ip = $_SERVER['SERVER_ADDR'] ?? gethostbyname(gethostname());

// tableau des ports/services

$services = [
    [
        'port' => '5624-5625, 2200',
        'protocol' => 'TCP, UDP',
        'service' => 'Natus Database (XLDB)',
        'desc' => "The Natus Database (XLDB) is a Natus application that provides a distributed database view of live, completed, and archived EEG/PSG patient studies. It synchronizes data with local MSSQL Express instance and central MSQL Server, as well as other remote MSSQL Express instances running on computers with Natus EEG/PSG application software. It also uses a UDP communication channel to discover Natus proprietary IP-based hardware devices like the Natus Base Unit and other amplifiers."
    ],
    [
        'port' => '2001-2024, 2040-2041',
        'protocol' => 'TCP',
        'service' => 'NWSignal',
        'desc' => "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    ],
    [
        'port' => '2010-2011',
        'protocol' => 'TCP',
        'service' => 'NWStorage',
        'desc' => "The NWStorage service opens TCP connections to communicate with other applications and services. It writes EEG/PSG patient study files to a specified filesystem location and receive data from multiple EEG/PSG applications and services."
    ],
    [
        'port' => '2100-2101',
        'protocol' => 'TCP',
        'service' => 'NWSentry',
        'desc' => "The NWSentry service opens TCP connections to monitor the health of Natus applications or services and acts as a software watchdog."
    ],
    [
        'port' => '2020-2021',
        'protocol' => 'TCP',
        'service' => 'NWChat',
        'desc' => "The NWChat service for short text messages using the Wave application via the 'lips' icon in the top right corner of the screen."
    ],
    [
        'port' => '5610-5610',
        'protocol' => 'TCP',
        'service' => 'XLAlarmSvc',
        'desc' => "The XLAlarmSvc service monitors notifications from other stations and an SQL."
    ],
    [
        'port' => '2001, 2010, 5612, 5620-5621',
        'protocol' => 'TCP',
        'service' => 'XLAnalyzerSrv, XLEvtMsgSvc',
        'desc' => "XLAnalyzerSrv: The XLAnalyzerSrv opens TCP connections to communicate with other applications and services. It performs analysis on study data.\nXLEvtMsgSvc: The XLEvtMsgSvc opens TCP connections to communicate with other applications and services."
    ],
    [
        'port' => '80, 554, 5301-5308',
        'protocol' => 'TCP, UDP, TCP',
        'service' => 'XLMediaServer',
        'desc' => "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    ],
    [
        'port' => '5500-5502, 50000-50003, 52000-52001, 60000-60003, 62000-62001',
        'protocol' => 'TCP, TCP, TCP, UDP, UDP',
        'service' => 'XLNetCOMBridge',
        'desc' => "The XLNetCOMBridge service TCP connections to communicate with other applications and services"
    ],
    [
        'port' => '5611-5611',
        'protocol' => 'TCP',
        'service' => 'XLNetCOMBridge',
        'desc' => "The XLNetCOMBridge service TCP connections to communicate with other applications and services"
    ],
    [
        'port' => '1433',
        'protocol' => 'TCP',
        'service' => 'SQL Server Express or Standard (MSSQLSERVER)',
        'desc' => "The local or remote MS SQL instance that communicates with XLDB."
    ],
    [
        'port' => '6661',
        'protocol' => 'TCP',
        'service' => 'Mirth Connect',
        'desc' => "Mirth® Connect third-party software by NextGen Healthcare use for HL7 integration."
    ],
    [
        'port' => '443',
        'protocol' => 'TCP',
        'service' => 'Persyst Diagnostic Service',
        'desc' => "Optional third-party software, the Persyst Diagnostic Service provides a method of uploading log files to Persyst."
    ],
    [
        'port' => '443',
        'protocol' => 'TCP',
        'service' => 'Natus Gateway Service API',
        'desc' => "Implemented in NW10GMA2, this service can be configured to listen for autoSCORE license and usage information from other Neuroworks systems on this port."
    ],
    [
        'port' => '5000',
        'protocol' => 'TCP',
        'service' => 'Natus Licensing Service API',
        'desc' => "Implemented in NW10GMA2, this service receives local autoSCORE license and usage information from the local Neuroworks application on this port. (REST calls based on JSON encapsulated in http/https requests)."
    ],
    [
        'port' => '5093',
        'protocol' => 'TCP',
        'service' => 'Sentinel RMS License Manager',
        'desc' => "Integrated in NW10GMA2, this service listens on this port for autoSCORE license and usage information from the Natus Licensing Service API."
    ],
    [
        'port' => '5711, 5595, 5596, 5597, 5598, 5599',
        'protocol' => 'TCP',
        'service' => 'NWGateway',
        'desc' => "Implemented in NW10GMA4, the NWGateway is a service that is deployed on acquisition station. NWGateway can host multiple proxies to communicate with different clients. CNS proxy implemented in NW10GMA4 enables data flow between non-NW client like the Moberg CNS and NW components. It uses port 5711 and 5596 to communicate internally with Neuroworks, and ports 5595, 5597, 5598, and 5599 to communicate externally with Moberg CNS monitors."
    ],
    [
        'port' => '5554, 5555',
        'protocol' => 'TCP',
        'service' => 'NWDiscoveryApp',
        'desc' => "Implemented in NW10GMA4, the Discovery app is deployed on Monitoring station. It uses port 5554 for AUTH requests, and port 5555 to send out active list info. It implements following functionalities:\n• Obtain the list of machines that are monitored by current station\n• Retrieve the study info and patient info of the live study from the monitored stations\n• Serialize the information for transmitting via network\n• Work as a server which provides the info to the requesters that connect"
    ],
];


// Fonction pour "déplier" toutes les plages de ports en liste d'entiers
function expand_ports($str) {
    $out = [];
    foreach (preg_split('/,/', $str) as $part) {
        if (preg_match('/(\d+)-(\d+)/', $part, $m)) {
            for ($i = $m[1]; $i <= $m[2]; ++$i) $out[] = (int)$i;
        } elseif (preg_match('/\d+/', $part, $m)) {
            $out[] = (int)$m[0];
        }
    }
    return $out;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérificateur de ports</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 1.5em;}
        th, td { border: 1px solid #aaa; padding: 7px 8px; font-size: 0.97em; }
        th { background: #048693; color: #fff; }
        td .test-btn { padding: 0.4em 1.2em; font-weight: bold; background: #048693; color: #fff; border-radius: 5px; border: none; cursor: pointer;}
        td .test-btn:disabled { opacity: 0.6; }
        .status-ok { color: #080; font-weight: bold;}
        .status-fail { color: #b00; font-weight: bold;}
        .status-pending { color: #888; font-style: italic;}
        .port-result-block { display:inline-block; min-width:68px; margin-right:0.7em; }
    </style>
</head>
<body>
    <h2>
        Vérification de connectivité TCP/UDP sur le serveur web
        <br>
        <small>
            (Poste client : <?= htmlspecialchars($client_ip) ?> / Serveur web : <?= htmlspecialchars($server_ip) ?>)
        </small>
    </h2>
    <p>Cliquer sur <b>Tester</b> pour chaque service : tous les ports de la plage seront testés.</p>

    <table>
        <tr>
            <th>Port(s)</th>
            <th>Protocole</th>
            <th>Service</th>
            <th>Description</th>
            <th>Test</th>
            <th>Résultat</th>
        </tr>
        <?php foreach ($services as $idx => $svc): 
            $all_ports = expand_ports($svc['port']);
            $ports_json = htmlspecialchars(json_encode($all_ports));
            // On considère TCP si TCP existe dans la ligne, sinon UDP
            $proto = (stripos($svc['protocol'], 'TCP') !== false) ? 'tcp' : 'udp';
        ?>
        <tr>
            <td><?= htmlspecialchars($svc['port']) ?></td>
            <td><?= htmlspecialchars($svc['protocol']) ?></td>
            <td><?= htmlspecialchars($svc['service']) ?></td>
            <td><?= htmlspecialchars($svc['desc']) ?></td>
            <td>
                <?php if ($all_ports): ?>
                <button class="test-btn" data-ports='<?= $ports_json ?>' data-proto="<?= $proto ?>" data-row="<?= $idx ?>">Tester</button>
                <?php else: ?> - <?php endif; ?>
            </td>
            <td id="status-<?= $idx ?>"></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.test-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const ports = JSON.parse(btn.getAttribute('data-ports'));
                const proto = btn.getAttribute('data-proto');
                const row = btn.getAttribute('data-row');
                const statusCell = document.getElementById('status-' + row);

                btn.disabled = true;
                statusCell.innerHTML = '<span class="status-pending">Test en cours...</span>';

                let results = [];
                function testNext(idx) {
                    if(idx >= ports.length) {
                        // Affiche tous les résultats
                        statusCell.innerHTML = results.map(r => 
                            `<span class="port-result-block">${r.port}: <span class="${r.status === 'open' ? 'status-ok' : 'status-fail'}">${r.status === 'open' ? 'Ouvert' : 'Fermé'}</span></span>`
                        ).join(' ');
                        btn.disabled = false;
                        return;
                    }
                    fetch(`test_port.php?port=${ports[idx]}&proto=${proto}`)
                        .then(r => r.json())
                        .then(res => {
                            results.push({ port: ports[idx], status: res.status });
                            testNext(idx+1);
                        })
                        .catch(e => {
                            results.push({ port: ports[idx], status: "fail" });
                            testNext(idx+1);
                        });
                }
                testNext(0);
            });
        });
    });
    </script>
</body>
</html>
