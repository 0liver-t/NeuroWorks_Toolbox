<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testeur de Ports - Services Natus</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .controls {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            align-items: center;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        label {
            font-weight: 600;
            color: #34495e;
            font-size: 0.9em;
        }

        input, select, button {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        button {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        .btn-test-all {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            flex: 1;
            min-width: 150px;
        }

        .btn-test-all:hover {
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-clear {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
        }

        .btn-clear:hover {
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
        }

        .results {
            margin-top: 30px;
        }

        .result-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .result-item:hover {
            transform: translateX(5px);
        }

        .result-success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
        }

        .result-error {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .result-testing {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 15px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .port-info {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .port-details {
            font-weight: 600;
            font-size: 1.1em;
            margin-bottom: 5px;
        }

        .service-name {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background: #ecf0f1;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 20px;
            display: none;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            width: 0%;
            transition: width 0.3s ease;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -100px 0; }
            100% { background-position: 100px 0; }
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 150px;
        }

        .stat-number {
            font-size: 2em;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .input-group {
                width: 100%;
            }
            
            .stats {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔌 Testeur de Ports - Services Natus</h1>
        
        <div class="controls">
            <div class="input-group">
                <label for="hostname">Nom d'hôte/IP:</label>
                <input type="text" id="hostname" placeholder="localhost" value="localhost">
            </div>
            
            <div class="input-group">
                <label for="portSelect">Port:</label>
                <select id="portSelect">
                    <option value="">Sélectionner un port</option>
                </select>
            </div>
            
            <div class="input-group">
                <label for="customPort">Port personnalisé:</label>
                <input type="number" id="customPort" placeholder="8080" min="1" max="65535">
            </div>
            
            <button onclick="testSinglePort()">Tester Port</button>
            <button class="btn-test-all" onclick="testAllPorts()">Tester Tous les Ports</button>
            <button class="btn-clear" onclick="clearResults()">Effacer</button>
        </div>

        <div class="progress-bar" id="progressBar">
            <div class="progress-fill" id="progressFill"></div>
        </div>

        <div class="stats" id="stats" style="display: none;">
            <div class="stat-card">
                <div class="stat-number" id="totalPorts">0</div>
                <div class="stat-label">Ports testés</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="openPorts">0</div>
                <div class="stat-label">Ports ouverts</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="closedPorts">0</div>
                <div class="stat-label">Ports fermés</div>
            </div>
        </div>

        <div class="results" id="results"></div>
    </div>

    <script>
        // Liste des ports depuis le fichier fourni
        const portsData = [
            {port: 1433, protocol: 'TCP', service: 'SQL Server Express', description: 'MS SQL instance that communicates with XLDB'},
            {port: 2001, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2001, protocol: 'TCP', service: 'XLAnalyzerSrv', description: 'Performs analysis on study data'},
            {port: 2002, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2003, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2004, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2005, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2006, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2007, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2008, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2009, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2010, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2010, protocol: 'TCP', service: 'NWStorage', description: 'Writes EEG/PSG patient study files to filesystem'},
            {port: 2010, protocol: 'TCP', service: 'XLAnalyzerSrv', description: 'Performs analysis on study data'},
            {port: 2011, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2011, protocol: 'TCP', service: 'NWStorage', description: 'Writes EEG/PSG patient study files to filesystem'},
            {port: 2012, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2013, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2014, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2015, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2016, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2017, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2018, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2019, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2020, protocol: 'TCP', service: 'NWChat', description: 'Short text messages using Wave application'},
            {port: 2020, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2021, protocol: 'TCP', service: 'NWChat', description: 'Short text messages using Wave application'},
            {port: 2021, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2022, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2023, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2024, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2040, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2041, protocol: 'TCP', service: 'NWSignal', description: 'Receives raw data from Natus EEG/PSG hardware'},
            {port: 2100, protocol: 'TCP', service: 'NWSentry', description: 'Monitors health of Natus applications'},
            {port: 2101, protocol: 'TCP', service: 'NWSentry', description: 'Monitors health of Natus applications'},
            {port: 2200, protocol: 'TCP', service: 'Natus Database (XLDB)', description: 'Distributed database view of EEG/PSG studies'},
            {port: 443, protocol: 'TCP', service: 'Natus Gateway Service API', description: 'AutoSCORE license and usage information'},
            {port: 443, protocol: 'TCP', service: 'Persyst Diagnostic Service', description: 'Third-party software for uploading log files'},
            {port: 5000, protocol: 'TCP', service: 'Natus Licensing Service API', description: 'AutoSCORE license and usage information'},
            {port: 5093, protocol: 'TCP', service: 'Sentinel RMS License Manager', description: 'AutoSCORE license management'},
            {port: 5554, protocol: 'TCP', service: 'NWDiscoveryApp', description: 'Discovery app for monitoring stations'},
            {port: 5555, protocol: 'TCP', service: 'NWDiscoveryApp', description: 'Discovery app for monitoring stations'},
            {port: 5595, protocol: 'TCP', service: 'NWGateway', description: 'Gateway service for acquisition station'},
            {port: 5596, protocol: 'TCP', service: 'NWGateway', description: 'Gateway service for acquisition station'},
            {port: 5597, protocol: 'TCP', service: 'NWGateway', description: 'Gateway service for acquisition station'},
            {port: 5598, protocol: 'TCP', service: 'NWGateway', description: 'Gateway service for acquisition station'},
            {port: 5599, protocol: 'TCP', service: 'NWGateway', description: 'Gateway service for acquisition station'},
            {port: 5610, protocol: 'TCP', service: 'XLAlarmsSvc', description: 'Monitors notifications from other stations'},
            {port: 5611, protocol: 'TCP', service: 'XLNetCOMBridge', description: 'Communication bridge service'},
            {port: 5612, protocol: 'TCP', service: 'XLEvtMsgSvc', description: 'Event message service'},
            {port: 5620, protocol: 'TCP', service: 'XLEvtMsgSvc', description: 'Event message service'},
            {port: 5621, protocol: 'TCP', service: 'XLEvtMsgSvc', description: 'Event message service'},
            {port: 5624, protocol: 'TCP', service: 'Natus Database (XLDB)', description: 'Distributed database view of EEG/PSG studies'},
            {port: 5625, protocol: 'TCP', service: 'Natus Database (XLDB)', description: 'Distributed database view of EEG/PSG studies'},
            {port: 5711, protocol: 'TCP', service: 'NWGateway', description: 'Gateway service for acquisition station'},
            {port: 6661, protocol: 'TCP', service: 'Mirth Connect', description: 'Third-party software for HL7 integration'},
            {port: 80, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 50000, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 50001, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 50002, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 50003, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 52000, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 52001, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 554, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5301, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5302, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5303, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5304, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5305, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5306, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5307, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5308, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5500, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5501, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 5502, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 60000, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 60001, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 60002, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 60003, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 62000, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'},
            {port: 62001, protocol: 'TCP', service: 'XLMediaServer', description: 'Video data acquisition and storage'}
        ];

        // Variables globales pour les statistiques
        let totalTested = 0;
        let openCount = 0;
        let closedCount = 0;
        let isTestingAll = false;

        // Initialiser la liste des ports
        function initializePortList() {
            const portSelect = document.getElementById('portSelect');
            const uniquePorts = [...new Set(portsData.map(p => p.port))].sort((a, b) => a - b);
            
            uniquePorts.forEach(port => {
                const services = portsData.filter(p => p.port === port);
                const serviceNames = [...new Set(services.map(s => s.service))].join(', ');
                const option = document.createElement('option');
                option.value = port;
                option.textContent = `${port} - ${serviceNames}`;
                portSelect.appendChild(option);
            });
        }

        // Tester un port spécifique
        async function testPort(hostname, port) {
            return new Promise((resolve) => {
                const timeout = 5000;
                const startTime = Date.now();
                
                const img = new Image();
                const timer = setTimeout(() => {
                    resolve({
                        port: port,
                        isOpen: false,
                        responseTime: Date.now() - startTime,
                        error: 'Timeout'
                    });
                }, timeout);
                
                img.onload = () => {
                    clearTimeout(timer);
                    resolve({
                        port: port,
                        isOpen: true,
                        responseTime: Date.now() - startTime,
                        error: null
                    });
                };
                
                img.onerror = () => {
                    clearTimeout(timer);
                    // Essayer avec WebSocket comme alternative
                    testWebSocket(hostname, port).then(resolve);
                };
                
                img.src = `http://${hostname}:${port}/favicon.ico?t=${Date.now()}`;
            });
        }

        // Test WebSocket comme alternative
        async function testWebSocket(hostname, port) {
            return new Promise((resolve) => {
                const startTime = Date.now();
                const ws = new WebSocket(`ws://${hostname}:${port}`);
                
                const timeout = setTimeout(() => {
                    ws.close();
                    resolve({
                        port: port,
                        isOpen: false,
                        responseTime: Date.now() - startTime,
                        error: 'Connection failed'
                    });
                }, 3000);
                
                ws.onopen = () => {
                    clearTimeout(timeout);
                    ws.close();
                    resolve({
                        port: port,
                        isOpen: true,
                        responseTime: Date.now() - startTime,
                        error: null
                    });
                };
                
                ws.onerror = () => {
                    clearTimeout(timeout);
                    resolve({
                        port: port,
                        isOpen: false,
                        responseTime: Date.now() - startTime,
                        error: 'WebSocket connection failed'
                    });
                };
            });
        }

        // Tester un port unique
        async function testSinglePort() {
            const hostname = document.getElementById('hostname').value || 'localhost';
            const selectedPort = document.getElementById('portSelect').value;
            const customPort = document.getElementById('customPort').value;
            
            const port = customPort || selectedPort;
            
            if (!port) {
                alert('Veuillez sélectionner un port ou entrer un port personnalisé');
                return;
            }
            
            const resultDiv = document.getElementById('results');
            const portData = portsData.find(p => p.port == port) || {service: 'Service personnalisé', description: 'Port personnalisé'};
            
            // Afficher l'état de test
            const testingDiv = document.createElement('div');
            testingDiv.className = 'result-item result-testing';
            testingDiv.innerHTML = `
                <div class="status-indicator"></div>
                <div class="port-info">
                    <div class="port-details">Port ${port} (${portData.protocol || 'TCP'}) - Test en cours...</div>
                    <div class="service-name">${portData.service} <span class="loading-spinner"></span></div>
                </div>
            `;
            resultDiv.appendChild(testingDiv);
            
            try {
                const result = await testPort(hostname, port);
                
                // Supprimer l'élément de test
                resultDiv.removeChild(testingDiv);
                
                // Afficher le résultat
                displayResult(result, portData);
                
                // Mettre à jour les statistiques
                updateStats(result.isOpen);
                
            } catch (error) {
                resultDiv.removeChild(testingDiv);
                displayResult({port: port, isOpen: false, error: error.message}, portData);
                updateStats(false);
            }
        }

        // Tester tous les ports
        async function testAllPorts() {
            if (isTestingAll) return;
            
            isTestingAll = true;
            const hostname = document.getElementById('hostname').value || 'localhost';
            const uniquePorts = [...new Set(portsData.map(p => p.port))].sort((a, b) => a - b);
            
            clearResults();
            
            // Afficher la barre de progression
            const progressBar = document.getElementById('progressBar');
            const progressFill = document.getElementById('progressFill');
            progressBar.style.display = 'block';
            
            // Afficher les statistiques
            document.getElementById('stats').style.display = 'flex';
            
            let completedTests = 0;
            const totalTests = uniquePorts.length;
            
            // Traiter les ports par lots pour éviter la surcharge
            for (let i = 0; i < uniquePorts.length; i += 5) {
                const batch = uniquePorts.slice(i, i + 5);
                const promises = batch.map(async (port) => {
                    const portData = portsData.find(p => p.port === port);
                    
                    try {
                        const result = await testPort(hostname, port);
                        displayResult(result, portData);
                        updateStats(result.isOpen);
                        
                        completedTests++;
                        const progress = (completedTests / totalTests) * 100;
                        progressFill.style.width = `${progress}%`;
                        
                        return result;
                    } catch (error) {
                        const result = {port: port, isOpen: false, error: error.message};
                        displayResult(result, portData);
                        updateStats(false);
                        
                        completedTests++;
                        const progress = (completedTests / totalTests) * 100;
                        progressFill.style.width = `${progress}%`;
                        
                        return result;
                    }
                });
                
                await Promise.all(promises);
                
                // Petite pause entre les lots
                await new Promise(resolve => setTimeout(resolve, 100));
            }
            
            // Cacher la barre de progression
            setTimeout(() => {
                progressBar.style.display = 'none';
                progressFill.style.width = '0%';
            }, 1000);
            
            isTestingAll = false;
        }

        // Afficher le résultat d'un test
        function displayResult(result, portData) {
            const resultDiv = document.getElementById('results');
            const resultItem = document.createElement('div');
            
            const statusClass = result.isOpen ? 'result-success' : 'result-error';
            const statusText = result.isOpen ? 'OUVERT' : 'FERMÉ';
            const statusIcon = result.isOpen ? '✅' : '❌';
            
            resultItem.className = `result-item ${statusClass}`;
            resultItem.innerHTML = `
                <div class="status-indicator">${statusIcon}</div>
                <div class="port-info">
                    <div class="port-details">Port ${result.port} (${portData.protocol || 'TCP'}) - ${statusText}</div>
                    <div class="service-name">${portData.service} - ${portData.description}</div>
                    ${result.responseTime ? `<div class="service-name">Temps de réponse: ${result.responseTime}ms</div>` : ''}
                    ${result.error ? `<div class="service-name">Erreur: ${result.error}</div>` : ''}
                </div>
            `;
            
            resultDiv.appendChild(resultItem);
        }

        // Mettre à jour les statistiques
        function updateStats(isOpen) {
            totalTested++;
            if (isOpen) {
                openCount++;
            } else {
                closedCount++;
            }
            
            document.getElementById('totalPorts').textContent = totalTested;
            document.getElementById('openPorts').textContent = openCount;
            document.getElementById('closedPorts').textContent = closedCount;
        }

        // Effacer les résultats
        function clearResults() {
            document.getElementById('results').innerHTML = '';
            document.getElementById('stats').style.display = 'none';
            document.getElementById('progressBar').style.display = 'none';
            document.getElementById('progressFill').style.width = '0%';
            
            // Réinitialiser les statistiques
            totalTested = 0;
            openCount = 0;
            closedCount = 0;
            updateStats(false);
        }

        // Initialiser l'application
        document.addEventListener('DOMContentLoaded', function() {
            initializePortList();
            
            // Permettre le test avec la touche Entrée
            document.getElementById('customPort').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    testSinglePort();
                }
            });
            
            // Effacer le port personnalisé quand on sélectionne un port dans la liste
            document.getElementById('portSelect').addEventListener('change', function() {
                if (this.value) {
                    document.getElementById('customPort').value = '';
                }
            });
            
            // Effacer la sélection quand on tape un port personnalisé
            document.getElementById('customPort').addEventListener('input', function() {
                if (this.value) {
                    document.getElementById('portSelect').value = '';
                }
            });
        });

        // Fonction utilitaire pour tester la connectivité réseau de base
        async function testNetworkConnectivity() {
            const hostname = document.getElementById('hostname').value || 'localhost';
            
            try {
                const response = await fetch(`http://${hostname}`, {
                    mode: 'no-cors',
                    method: 'HEAD'
                });
                return true;
            } catch (error) {
                return false;
            }
        }

        // Fonction pour exporter les résultats
        function exportResults() {
            const results = document.querySelectorAll('.result-item');
            let csvContent = "Port,Protocol,Service,Status,Response Time,Error\n";
            
            results.forEach(result => {
                const portDetails = result.querySelector('.port-details').textContent;
                const serviceName = result.querySelector('.service-name').textContent;
                const isSuccess = result.classList.contains('result-success');
                
                const port = portDetails.match(/Port (\d+)/)[1];
                const protocol = portDetails.match(/\(([^)]+)\)/)[1];
                const status = isSuccess ? 'OUVERT' : 'FERMÉ';
                
                csvContent += `${port},${protocol},"${serviceName}",${status},,\n`;
            });
            
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `port_test_results_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Ajouter un bouton d'export (sera ajouté dynamiquement)
        function addExportButton() {
            const controls = document.querySelector('.controls');
            const exportBtn = document.createElement('button');
            exportBtn.textContent = 'Exporter CSV';
            exportBtn.onclick = exportResults;
            exportBtn.style.background = 'linear-gradient(135deg, #8e44ad, #7d3c98)';
            controls.appendChild(exportBtn);
        }

        // Fonction pour filtrer les résultats
        function filterResults(type) {
            const results = document.querySelectorAll('.result-item');
            results.forEach(result => {
                if (type === 'all') {
                    result.style.display = 'flex';
                } else if (type === 'open' && result.classList.contains('result-success')) {
                    result.style.display = 'flex';
                } else if (type === 'closed' && result.classList.contains('result-error')) {
                    result.style.display = 'flex';
                } else {
                    result.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>