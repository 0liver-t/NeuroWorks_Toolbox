<?php
// Charger la config
$config = require "config.php";
$sources = $config['sources'];

// Ajout dynamique de la source "logs personnels" pour l'utilisateur connecté
if (session_status() === PHP_SESSION_NONE) session_start();
$username = $_SESSION['username'] ?? null;
if ($username) {
    $userLogsDir = __DIR__ . "/logs/{$username}";
    $userCacheDb = __DIR__ . "/cache/{$username}.db";
    $sources['user'] = [
        'label'    => XL_LANG['my_logs'] ?? 'Mes logs personnels',
        'logs_dir' => $userLogsDir,
        'cache_db' => $userCacheDb,
    ];
}

// Séparation en groupes (local / remote / user)
$locals = [];
$remotes = [];
$users = [];

foreach ($sources as $key => $src) {
    if ($key === 'local')      $type = 'local';
    elseif ($key === 'user')   $type = 'user';
    else                       $type = 'remote';

    if ($type === 'local')   $locals[$key] = $src;
    if ($type === 'remote')  $remotes[$key] = $src;
    if ($type === 'user')    $users[$key] = $src;
}
?>
<!-- Barre de recherche globale -->
<div id="search-bar">
  <div class="source-select-bar">
    <label for="log-source-select">
      <?= XL_LANG['source'] ?? 'Source' ?>&nbsp;:
    </label>
    <select id="log-source-select">
      <?php if ($locals): ?>
        <optgroup label="<?= XL_LANG['logs_local'] ?? 'Logs locaux' ?>">
          <?php foreach ($locals as $key => $src): ?>
            <option value="<?= htmlspecialchars($key) ?>">
              <?php if ($key === 'local'): ?>
                <?= htmlspecialchars($src['logs_dir']) ?>
              <?php else: ?>
                <?= htmlspecialchars($src['label']) ?>
              <?php endif; ?>
            </option>
          <?php endforeach; ?>
        </optgroup>
      <?php endif; ?>

      <?php if ($remotes): ?>
        <optgroup label="<?= XL_LANG['remote_sites'] ?? 'Sites distants' ?>">
          <?php foreach ($remotes as $key => $src): ?>
            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($src['label']) ?></option>
          <?php endforeach; ?>
        </optgroup>
      <?php endif; ?>

      <?php if ($users): ?>
        <optgroup label="<?= XL_LANG['my_logs'] ?? 'Mes logs personnels' ?>">
          <?php foreach ($users as $key => $src): ?>
            <option value="<?= htmlspecialchars($key) ?>">
              <?php if ($key === 'user'): ?>
                <?= htmlspecialchars('/logs/' . $username) ?>
              <?php else: ?>
                <?= htmlspecialchars($src['label']) ?>
              <?php endif; ?>
            </option>
          <?php endforeach; ?>
        </optgroup>
      <?php endif; ?>
    </select>

</div>

  <div class="input-with-clear">
    <span id="search-loupe"><i class="fa fa-search"></i></span>
    <input type="text" id="search-input" autocomplete="off">
    <span id="search-clear" title="Effacer"><i class="fa fa-times-circle"></i></span>
  </div>
  <button id="search-btn">
    <i class="fa fa-search"></i> <?= XL_LANG['search_btn'] ?? 'Rechercher' ?>
  </button>
  <button id="reload-db-btn" title="<?= XL_LANG['reload_db_title'] ?? 'Recharger la base de données' ?>">
    <i class="fa fa-sync-alt"></i> <?= XL_LANG['reload_db'] ?? 'Recharger la base' ?>
  </button>
  <span id="loader"><i class="fa fa-spinner fa-spin"></i> <?= XL_LANG['loading'] ?? 'Chargement...' ?></span>
  <div id="messageBox"></div>
</div>

<!-- Message d’erreur AJAX -->
<div id="logs-error"></div>
	<!-- BLOC UPLOAD LOGS PERSO, MASQUÉ PAR DÉFAUT -->
	<div id="upload-zone" style="display: none; margin-top: 1em;">
	  <form id="upload-form" enctype="multipart/form-data" method="post">
		<input type="file" id="xml-file" name="xml-file[]" accept=".xml" required multiple>

		<button type="submit"><i class="fa fa-upload"></i> Upload</button>
	  </form>
	  <div id="upload-feedback"></div>
	</div>
  </div>
<!-- Tableau des logs -->
<table id="logs-table" class="display">
  <thead>
    <tr>
      <th><?= XL_LANG['date'] ?? 'Date/Heure' ?></th>
      <th><?= XL_LANG['machine'] ?? 'Machine' ?></th>
      <th><?= XL_LANG['user'] ?? 'Utilisateur' ?></th>
      <th><?= XL_LANG['action'] ?? 'Action' ?></th>
      <th><?= XL_LANG['event'] ?? 'Événement' ?></th>
      <th><?= XL_LANG['desc'] ?? 'Description' ?></th>
    </tr>
  </thead>
</table>

<script>
function updateLocalLogPath() {
  var $select = $('#log-source-select');
  var $pathDiv = $('#local-log-path');
  var $option = $select.find('option:selected');
  if ($option.val() === 'local') {
    var path = $option.text();
    if (path) {
      var label = (window.lang && window.lang.logs_local_path) ? window.lang.logs_local_path : 'Chemin des logs locaux : ';
      $pathDiv.text(label + path).show();
    } else {
      $pathDiv.hide();
    }
  } else {
    $pathDiv.hide();
  }
}
$(document).ready(function() {
  updateLocalLogPath();
  $('#log-source-select').on('change', updateLocalLogPath);
});
</script>
