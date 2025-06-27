<?php
require_once "config.php";
$config = require "config.php";
?>
<!-- Barre de recherche globale -->
<div id="search-bar" style="margin-bottom: 1em; display: flex; align-items: center; gap: 0.7em;">
  <div class="input-with-clear" style="position:relative; display:inline-block;">
    <span id="search-loupe"><i class="fa fa-search"></i></span>
    <input type="text" id="search-input"
           autocomplete="off" style="padding-right:2.5em; padding-left:2.3em; min-width:250px;">
    <span id="search-clear" title="Effacer" style="display:none;">
      <i class="fa fa-times-circle"></i>
    </span>
  </div>
  <button id="search-btn">
    <i class="fa fa-search"></i> <?= XL_LANG['search_btn'] ?? 'Rechercher' ?>
  </button>
  <button id="reload-db-btn" title="<?= XL_LANG['reload_db_title'] ?? 'Recharger la base de données' ?>">
    <i class="fa fa-sync-alt"></i> <?= XL_LANG['reload_db'] ?? 'Recharger la base' ?>
  </button>
  <span id="loader" style="display:none;vertical-align:middle;">
    <i class="fa fa-spinner fa-spin"></i> <?= XL_LANG['loading'] ?? 'Chargement...' ?>
  </span>
  <div id="messageBox" style="display:none;"></div>
</div>


<!-- Message d’erreur AJAX -->
<div id="logs-error" style="color:#b00;font-weight:bold;display:none;margin-bottom:1em;"></div>
<!-- Tableau des logs -->
<table id="logs-table" class="display" style="width:100%">
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
