<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);

$accepted_langs = ['fr', 'en', 'it', 'de'];
$lang_code = $_GET['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$lang_code = in_array($lang_code, $accepted_langs) ? $lang_code : 'fr';

// Correction ici : charger le fichier et récupérer le tableau $lang
$lang = require __DIR__ . "/lang/{$lang_code}.php";

$hostname = gethostname();

if (!isset($_SESSION['username']) || !preg_match('/^[a-zA-Z0-9_\-]+$/', $_SESSION['username'])) {
    header("Location: login.php?lang=" . urlencode($lang_code));
    exit;
}

// Définitions globales accessibles dans les includes
define("XL_LANG_CODE", $lang_code);
define("XL_LANG", $lang);
define("XL_HOSTNAME", $hostname);
define("XL_USER", $_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_code) ?>">
<head>
  <meta charset="UTF-8">
  <title>NeuroWorks Toolbox</title>

  <!-- Feuilles de style -->
  <link rel="stylesheet" href="styles/jquery.dataTables.min.css">
  <link rel="stylesheet" href="styles/buttons.dataTables.min.css">
  <link rel="stylesheet" href="styles/all.min.css">
  <link rel="stylesheet" href="styles/journaux.css"> <!-- À charger en dernier -->

  <!-- Scripts -->
  <script src="scripts/jquery-3.6.0.min.js"></script>
  <script src="scripts/jquery.dataTables.min.js"></script>
  <script src="scripts/datetime-moment.js"></script>
  <script src="scripts/moment.min.js"></script>
  <script src="scripts/dataTables.buttons.min.js"></script>
  <script src="scripts/jszip.min.js"></script>
  <script src="scripts/pdfmake.min.js"></script>
  <script src="scripts/vfs_fonts.js"></script>
  <script src="scripts/buttons.html5.min.js"></script>
  <script src="scripts/buttons.print.min.js"></script>
  <script src="scripts/journaux.js" defer></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Changement de langue
      const langSelect = document.getElementById("lang-select");
      if (langSelect) {
        langSelect.value = "<?= htmlspecialchars($lang_code) ?>";
        langSelect.addEventListener("change", () => {
          const url = new URL(window.location.href);
          url.searchParams.set("lang", langSelect.value);
          window.location.href = url.toString();
        });
      }

      // Onglets
      const tabs = document.querySelectorAll(".tab");
      const contents = document.querySelectorAll(".tab-content");

      tabs.forEach(tab => {
        tab.addEventListener("click", () => {
          tabs.forEach(t => t.classList.remove("active"));
          contents.forEach(c => c.classList.remove("active"));

          tab.classList.add("active");
          const targetId = tab.getAttribute("data-tab");
          document.getElementById(targetId).classList.add("active");
        });
      });
    });
  </script>
</head>
<body>

<!-- Bandeau utilisateur/langue/logo -->
<div class="global-header">
  <div class="logo-block">
    <img src="pics/medium-logo.png" alt="Logo Natus">
  </div>

  <div class="user-bar">
    <div class="language-switcher">
      <label for="lang-select"><i class="fa fa-globe"></i> <?= XL_LANG['language'] ?? 'Langue' ?> :</label>
      <select id="lang-select">
        <option value="fr">Français</option>
        <option value="en">English</option>
        <option value="it">Italiano</option>
        <option value="de">Deutsch</option>
      </select>
    </div>
    <div class="user-info">
      <p><strong><?= XL_LANG['connected_to'] ?? 'Connecté à :' ?></strong> <?= htmlspecialchars(XL_HOSTNAME) ?> - </p>
      <p><strong> <?= XL_LANG['user'] ?? 'Utilisateur' ?></strong> : <?= htmlspecialchars(XL_USER) ?></p>
      <form action="logout.php" method="post">
        <input type="hidden" name="lang" value="<?= htmlspecialchars(XL_LANG_CODE) ?>">
        <button type="submit"><?= XL_LANG['logout'] ?? 'Se déconnecter' ?></button>
      </form>
    </div>
  </div>
</div>

<!-- Navigation par onglets -->
<div class="tabs">
  <div class="tab active" data-tab="tab-logs">🗂️ <?= XL_LANG['tab_logs'] ?? 'Journaux' ?></div>
  <div class="tab" data-tab="tab-check">🔌 <?= XL_LANG['tab_checker'] ?? 'Vérificateur' ?></div>
</div>

<!-- Contenu des onglets -->
<div class="tab-content active" id="tab-logs">
  <?php include 'logs_embed.php'; ?>
</div>

<div class="tab-content" id="tab-check">
  <?php include 'ports_checker.php'; ?>
</div>

<script>
  // Langue globale JS
  window.lang = <?= json_encode($lang) ?>;
  window.langCode = "<?= $lang_code ?>";
</script>

</body>
</html>
