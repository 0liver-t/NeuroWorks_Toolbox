<?php
session_start();

// Gestion des langues
$accepted_langs = ['fr', 'en', 'it', 'de'];
$lang_code = $_GET['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$lang_code = in_array($lang_code, $accepted_langs) ? $lang_code : 'fr';

require_once __DIR__ . "/lang/{$lang_code}.php";

// Redirige si déjà connecté
if (isset($_SESSION['username'])) {
    header("Location: index.php?lang=" . urlencode($lang_code));
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username && preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        $_SESSION['username'] = $username;

        $userDir = __DIR__ . '/users/' . $username;
        if (!file_exists($userDir)) {
            mkdir($userDir, 0755, true);
        }

        header("Location: index.php?lang=" . urlencode($lang_code));
        exit;
    } else {
        $error = $lang['username_invalid'] ?? "Nom d'utilisateur invalide.";
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['login_title'] ?? 'Connexion' ?></title>
  <link rel="icon" type="image/png" href="pics/favicon-natus.png">

  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="styles/all.min.css">

  <script>
    window.lang = <?= json_encode($lang) ?>;
    window.langCode = "<?= $lang_code ?>";
  </script>

  <style>
    .error {
      color: red;
      font-size: 0.9em;
      margin-top: -1em;
      margin-bottom: 1em;
      text-align: left;
    }

    input.invalid {
      border: 2px solid red;
      background-color: #ffecec;
    }

    .language-switcher {
      text-align: right;
      margin-bottom: 1em;
      font-size: 0.95em;
    }

    .language-switcher label {
      margin-right: 0.5em;
      font-weight: bold;
    }

    .language-switcher select {
      padding: 0.3em;
      border-radius: 4px;
      border: 1px solid #ccc;
      font-size: 0.95em;
    }
  </style>
</head>

<body>
  <div class="login-box">
    <div class="language-switcher">
      <label for="lang-select"><i class="fa fa-globe"></i> <?= $lang['language'] ?? 'Langue' ?> :</label>
      <select id="lang-select">
        <option value="fr">Français</option>
        <option value="en">English</option>
        <option value="it">Italiano</option>
        <option value="de">Deutsch</option>
      </select>
    </div>

    <img src="pics/medium-logo.png" alt="Natus Logo">

    <form action="login.php?lang=<?= $lang_code ?>" method="post" id="login-form">
      <div class="input-group">
        <i class="fa fa-user"></i>
        <input type="text" name="username" id="username"
               placeholder="<?= $lang['username'] ?? 'Username' ?>"
               value="<?= htmlspecialchars($username) ?>"
               class="<?= $error ? 'invalid' : '' ?>" required>
      </div>

      <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <p id="client-error" class="error" style="display: none;"></p>

      <button type="submit"><?= $lang['login_button'] ?? 'Login' ?></button>
    </form>
  </div>

  <script>
    document.getElementById('login-form').addEventListener('submit', function (e) {
      const input = document.getElementById('username');
      const error = document.getElementById('client-error');
      const value = input.value.trim();
      const regex = /^[a-zA-Z0-9_-]+$/;

      input.classList.remove('invalid');
      error.style.display = 'none';

      if (!value) {
        e.preventDefault();
        input.classList.add('invalid');
        error.textContent = window.lang.enter_username;
        error.style.display = 'block';
      } else if (!regex.test(value)) {
        e.preventDefault();
        input.classList.add('invalid');
        error.textContent = window.lang.allowed_characters;
        error.style.display = 'block';
      }
    });

    document.addEventListener("DOMContentLoaded", function () {
      const langSelect = document.getElementById("lang-select");
      const currentLang = document.documentElement.lang || 'fr';
      langSelect.value = currentLang;

      langSelect.addEventListener("change", function () {
        const newLang = langSelect.value;
        const url = new URL(window.location.href);
        url.searchParams.set('lang', newLang);
        window.location.href = url.toString();
      });
    });
  </script>
</body>
</html>
