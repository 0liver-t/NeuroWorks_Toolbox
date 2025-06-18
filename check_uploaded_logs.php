<?php
session_start();

// Gestion des langues
$accepted_langs = ['fr', 'en', 'it', 'de'];
$lang_code = $_GET['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$lang_code = in_array($lang_code, $accepted_langs) ? $lang_code : 'fr';
require_once __DIR__ . "/lang/{$lang_code}.php";

// Sécurisation du nom utilisateur
if (!isset($_SESSION['username']) || !preg_match('/^[a-zA-Z0-9_\-]+$/', $_SESSION['username'])) {
    http_response_code(403);
    exit($lang['access_denied'] ?? 'Accès non autorisé.');
}

define("LOGS_BASE_PATH", __DIR__ . "/logs/");
$cleanUser = basename($_SESSION['username']);  // Évite toute injection
$userDir = LOGS_BASE_PATH . $cleanUser . "/";

// Création du dossier utilisateur si besoin
if (!is_dir($userDir)) {
    mkdir($userDir, 0755, true);
}

// Vérifie la présence de fichiers .xml
$files = glob($userDir . '*.xml');
echo (count($files) > 0) ? '1' : '0';
