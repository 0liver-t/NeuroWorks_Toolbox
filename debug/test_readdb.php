<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Charger la config et le chemin vers cache_db
$config = require dirname(__DIR__) . '/config.php';
$dbFile = $config['cache_db'];

// Connexion PDO à SQLite
try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("❌ Erreur de connexion : " . $e->getMessage());
}

// Compter le total d'entrées
$total = (int)$pdo->query('SELECT COUNT(*) FROM logs')->fetchColumn();
echo "<p><strong>Total d'entrées en base :</strong> {$total}</p>";

// Sélectionner toutes les colonnes pertinentes, triées par date décroissante
$stmt = $pdo->query(
    'SELECT id, time, mach, user, acID, evID, evdesc
     FROM logs
     ORDER BY time DESC
	 LIMIT 15'
);

echo "<table border='1' cellpadding='4'>
        <tr>
          <th>ID</th>
          <th>Time</th>
          <th>Machine</th>
          <th>User</th>
          <th>Action ID</th>
          <th>Event ID</th>
          <th>Description</th>
        </tr>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>' .
         '<td>' . htmlspecialchars($row['id'],     ENT_QUOTES, 'UTF-8') . '</td>' .
         '<td>' . htmlspecialchars($row['time'],   ENT_QUOTES, 'UTF-8') . '</td>' .
         '<td>' . htmlspecialchars($row['mach'],   ENT_QUOTES, 'UTF-8') . '</td>' .
         '<td>' . htmlspecialchars($row['user'],   ENT_QUOTES, 'UTF-8') . '</td>' .
         '<td>' . htmlspecialchars($row['acID'],   ENT_QUOTES, 'UTF-8') . '</td>' .
         '<td>' . htmlspecialchars($row['evID'],   ENT_QUOTES, 'UTF-8') . '</td>' .
         '<td>' . htmlspecialchars($row['evdesc'],ENT_QUOTES, 'UTF-8') . '</td>' .
         '</tr>';
}

echo '</table>';
