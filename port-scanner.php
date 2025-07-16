<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Testeur de Ports - Services Natus</title>
  <link rel="stylesheet" href="/styles/port-scanner.css">
</head>
<body>
<div class="portscanner-root">
	<div class="container">
	  
	  <div class="controls">
		<div>
		  <label for="hostname">Nom d'hôte/IP:</label>
		  <input type="text" id="hostname" placeholder="Nom d'hôte ou IP">
		</div>
		<div>
		  <label for="protocolSelect">Protocole :</label>
		  <select id="protocolSelect">
			<option value="TCP">TCP</option>
			<option value="UDP">UDP</option>
		  </select>
		</div>
		<div>
		  <label for="portSelect">Port :</label>
		  <select id="portSelect"></select>
		</div>
		<div>
		  <label for="customPort">Port personnalisé:</label>
		  <input type="number" id="customPort" min="1" max="65535" placeholder="Ex: 8080" class="input-short">
		</div>
		<div>
		  <label for="timeoutInput">Timeout (ms):</label>
		  <input type="number" id="timeoutInput" min="100" max="30000" step="100" value="5000" title="Délai d’attente en ms" class="input-short">
		</div>
		<div class="button-group">
		  <button id="clearBtn">EFFACER</button>
		  <button id="scanBtn">TESTER PORT</button>
		  <button id="scanAllBtn">TESTER TOUS LES PORTS</button>
		  <button id="stopBtn" disabled>ARRÊTER</button>
		</div>
	  </div>
	  <div class="stats" id="statsBar">
		<div class="stat">
		  <div class="stat-number" id="statTotal">0</div>
		  <div class="stat-label">Ports testés</div>
		</div>
		<div class="stat">
		  <div class="stat-number" id="statOpen">0</div>
		  <div class="stat-label">Ports ouverts</div>
		</div>
		<div class="stat">
		  <div class="stat-number" id="statClosed">0</div>
		  <div class="stat-label">Ports fermés</div>
		</div>
	  </div>
	  <div id="waitingBar"><span id="waitingText">Scan en cours<span id="dots">...</span></span></div>
	  <div class="results" id="results"></div>
	</div>
</div>
<script src="/scripts/port-scanner.js"></script>


</body>
</html>
