<?php
$source = $_POST['source'] ?? $_GET['source'] ?? 'local';

$remoteBase = __DIR__ . '/logs/_remote/';
$remoteDirs = [];
if (is_dir($remoteBase)) {
    foreach (glob($remoteBase . '*', GLOB_ONLYDIR) as $dir) {
        $name = basename($dir);
        if (preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
            $remoteDirs[] = $name;
        }
    }
}

$userDir = __DIR__ . "/logs/" . XL_USER . "/";
if (!file_exists($userDir)) {
    mkdir($userDir, 0777, true);
}
define("UPLOAD_DIR", $userDir);

$deletedCount = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteLogs'])) {
        foreach (glob(UPLOAD_DIR . '*.xml') as $file) {
            if (is_file($file)) {
                unlink($file);
                $deletedCount++;
            }
        }
    }

    if ($source === 'upload' && isset($_FILES['xmlfiles'])) {
        foreach (glob(UPLOAD_DIR . '*.xml') as $file) {
            if (is_file($file)) unlink($file);
        }

        foreach ($_FILES['xmlfiles']['tmp_name'] as $i => $tmpName) {
            if (!empty($tmpName)) {
                $targetPath = UPLOAD_DIR . basename($_FILES['xmlfiles']['name'][$i]);
                move_uploaded_file($tmpName, $targetPath);
            }
        }
    }
}
?>

<div class="layout">
  <!-- Bloc Gauche -->
  <div class="left-panel">
    <img src="pics/natus_corp_321.jpg" alt="Logo Natus" style="max-width: 160px; margin-bottom: 1em;">

    <form method="POST" enctype="multipart/form-data">
      <div style="display:flex;align-items:center;gap:0.5em;flex-wrap:wrap;">
        <strong><?= XL_LANG['log_source'] ?? 'Source des fichiers logs :' ?></strong>
        <select id="server-select">
          <option value="local" selected><?= htmlspecialchars(XL_HOSTNAME) ?></option>
        </select>
        <select id="remote-select" onchange="loadRemoteLogs(this.value)">
          <option value=""><?= XL_LANG['select_remote'] ?? '--' ?></option>
          <?php foreach ($remoteDirs as $dir): ?>
            <option value="<?= htmlspecialchars($dir) ?>"><?= htmlspecialchars($dir) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <br><br>

      <label>
        <input type="radio" name="source" value="local" onclick="toggleUpload('local'); loadSelectedServerLogs();" <?= $source === 'local' ? 'checked' : '' ?>>
        <?= XL_LANG['show_server_logs'] ?? 'Afficher les logs du serveur' ?>
      </label>

      <label>
        <input type="radio" name="source" value="upload" onclick="toggleUpload('upload')" <?= $source === 'upload' ? 'checked' : '' ?>>
        <?= XL_LANG['upload_from_pc'] ?? 'Upload des fichiers depuis mon ordinateur' ?>
      </label>

      <div id="upload-zone" style="<?= $source === 'upload' ? '' : 'display:none;' ?>">
        <br>
        <input type="file" name="xmlfiles[]" id="fileInput" multiple accept=".xml" hidden>
        <label for="fileInput" class="custom-file-button">📁 <?= XL_LANG['choose_files'] ?? 'Choisir des fichiers' ?></label>
        <span id="file-chosen"><?= XL_LANG['no_file_selected'] ?? 'Aucun fichier sélectionné' ?></span>

        <div id="progressContainer">
          <progress id="uploadProgress" value="0" max="100"></progress>
          <div id="progressText">0%</div>
        </div>

        <br><br>
        <button type="submit" name="deleteLogs" value="1"><?= XL_LANG['delete_logs'] ?? 'Supprimer les journaux (logs/)' ?></button>
      </div>
    </form>

    <div id="messageBox"></div>
  </div>

  <!-- Bloc Droit -->
  <div class="right-panel">
    <!-- Optionnel : contenu spécifique -->
  </div>
</div>

<!-- Bloc Bas -->
<div id="logs-container" class="table-wrapper"></div>

<?php if ($deletedCount > 0): ?>
<script>
  showMessage("<?= $deletedCount ?> <?= XL_LANG['logs_deleted'] ?? 'journal(s) supprimé(s).' ?>", "success");
</script>
<?php endif; ?>

<script>
  window.addEventListener("DOMContentLoaded", function () {
    const source = "<?= $source ?>";
    if (source === "upload") {
      loadUploadedLogs();
    } else {
      loadSelectedServerLogs();
    }
  });
</script>
