<?php
// cache_setup.php
// Re‐indexe tous les <activity> de server_logs_dir dans la table logs (nouveau schéma),
// crée la base et la table si nécessaire, et affiche le nombre d’entrées insérées.

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Charger la configuration
$config  = require __DIR__ . '/config.php';
$logDir  = $config['server_logs_dir'];
$dbPath  = $config['cache_db'];

// -------- Fonction principale d'indexation --------
function rebuild_logs_cache($logDir, $dbPath) {
    // 2. Ouvrir SQLite (créera logs.db si nécessaire)
    $db = new SQLite3($dbPath);

    // 2.1. Créer la table et l’index si pas encore présents
    $db->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS logs (
  id       INTEGER   PRIMARY KEY AUTOINCREMENT,
  time     DATETIME  NOT NULL,
  mach     TEXT,
  user     TEXT,
  acID     TEXT,
  evID     TEXT,
  evdesc   TEXT
);
CREATE INDEX IF NOT EXISTS idx_logs_time
  ON logs(time);
SQL
    );

    // 2.2. Mode WAL et début de transaction
    $db->exec('PRAGMA journal_mode = WAL;');
    $db->exec('BEGIN TRANSACTION');

    // 3. Vider la table logs
    $db->exec('DELETE FROM logs');

    // 4. Préparer la requête d’insertion
    $stmt = $db->prepare(<<<'SQL'
INSERT INTO logs (time, mach, user, acID, evID, evdesc)
VALUES (:time, :mach, :user, :acID, :evID, :evdesc)
SQL
    );

    // 5. Scanner récursivement les XML de server_logs_dir
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($logDir, FilesystemIterator::SKIP_DOTS)
    );

    $insertedCount = 0;

    foreach ($iterator as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'xml') {
            continue;
        }
        $path = $file->getPathname();

        // 5.1 Lire et convertir l’encodage (UTF-16LE → UTF-8) avec iconv
        $raw = file_get_contents($path);
        // Supprimer éventuellement le BOM UTF-16LE (0xFF 0xFE)
        if (substr($raw, 0, 2) === "\xFF\xFE") {
            $raw = substr($raw, 2);
        }
        $utf8 = @iconv('UTF-16LE', 'UTF-8//IGNORE', $raw);
        if ($utf8 === false) {
            // Si échec, on essaie sans conversion
            $utf8 = $raw;
        }

        // 5.2 Charger le XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($utf8);
        libxml_clear_errors();
        if (!$xml) {
            // Optionnel : log une erreur ou afficher un warning
            continue;
        }

        // 5.3 Parcourir chaque <activity>
        foreach ($xml->activity as $act) {
            // 5.3.1 Time → DATETIME SQLite
            $timeRaw = (string)$act->time;
            $dt = DateTime::createFromFormat('m/d/Y H:i:s', $timeRaw);
            if (!$dt) {
                // Fallback si le format diffère
                $dt = new DateTime($timeRaw);
            }
            $timeSql = $dt->format('Y-m-d H:i:s');

            // 5.3.2 Autres champs
            $mach   = (string)$act->mach;
            $user   = (string)$act->user;
            $acID   = (string)$act->acID;
            $evID   = (string)$act->evID;
            $evdesc = (string)$act->evdesc;

            // 5.3.3 Bind & execute
            $stmt->bindValue(':time',   $timeSql, SQLITE3_TEXT);
            $stmt->bindValue(':mach',   $mach,    SQLITE3_TEXT);
            $stmt->bindValue(':user',   $user,    SQLITE3_TEXT);
            $stmt->bindValue(':acID',   $acID,    SQLITE3_TEXT);
            $stmt->bindValue(':evID',   $evID,    SQLITE3_TEXT);
            $stmt->bindValue(':evdesc', $evdesc,  SQLITE3_TEXT);
            $res = $stmt->execute();

            if ($res) {
                $insertedCount++;
            }
        }
    }

    // 6. Valider la transaction
    $db->exec('COMMIT');
    return $insertedCount;
}

// -------- Détection AJAX (POST) --------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'rebuild') {
    try {
        $inserted = rebuild_logs_cache($logDir, $dbPath);

        // Compte le nombre de lignes de la table logs
        $pdo = new PDO('sqlite:' . $dbPath);
        $count = (int)$pdo->query('SELECT COUNT(*) FROM logs')->fetchColumn();

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'inserted' => $inserted, 'count' => $count]);
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json', true, 500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// -------- Appel direct (navigateur/CLI) --------
try {
    $inserted = rebuild_logs_cache($logDir, $dbPath);

    $pdo = new PDO('sqlite:' . $dbPath);
    $count = (int)$pdo->query('SELECT COUNT(*) FROM logs')->fetchColumn();

    echo "<h3>Cache mis à jour : <strong>{$inserted}</strong> entrées insérées.</h3>";
    echo "<p>Nombre de lignes dans la table <b>logs</b> : <strong>$count</strong></p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>Erreur lors de l'indexation : " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>
