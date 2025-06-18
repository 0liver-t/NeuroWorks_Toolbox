<?php
session_start();

// Gestion des langues
$accepted_langs = ['fr', 'en', 'it', 'de'];
$lang_code = $_GET['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$lang_code = in_array($lang_code, $accepted_langs) ? $lang_code : 'fr';
require_once __DIR__ . "/lang/{$lang_code}.php";

if (!isset($_SESSION['username']) || !preg_match('/^[a-zA-Z0-9_\-]+$/', $_SESSION['username'])) {
    http_response_code(403);
    exit($lang['access_denied'] ?? 'Accès refusé : utilisateur non authentifié.');
}

$folder = $_GET['folder'] ?? '';
if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $folder)) {
    http_response_code(400);
    exit('Invalid folder');
}

define('REMOTE_BASE_PATH', __DIR__ . '/logs/_remote/');
define('XSL_PATH', __DIR__ . '/styles/transformlog_sorted.xsl');

$dirPath = REMOTE_BASE_PATH . $folder . '/';
if (!is_dir($dirPath)) {
    http_response_code(404);
    exit($lang['missing_remote_folder'] ?? "Erreur : le dossier distant n'existe pas.");
}

$masterDoc = new DOMDocument('1.0', 'UTF-8');
$root = $masterDoc->createElement('xltechlog');
$masterDoc->appendChild($root);

$files = glob($dirPath . '*.xml');
if (!$files || count($files) === 0) {
    echo "<p>" . ($lang['no_remote_found'] ?? 'Aucun fichier journal trouvé dans le dossier distant.') . "</p>";
    exit;
}

foreach ($files as $file) {
    $xml = new DOMDocument;
    if (@$xml->load($file)) {
        foreach ($xml->getElementsByTagName('activity') as $activity) {
            $imported = $masterDoc->importNode($activity, true);
            $root->appendChild($imported);
        }
    }
}

if (!file_exists(XSL_PATH)) {
    http_response_code(500);
    exit($lang['missing_xslt'] ?? 'Erreur : fichier XSLT manquant.');
}

$xsl = new DOMDocument;
$xsl->load(XSL_PATH);

$processor = new XSLTProcessor();
$processor->importStylesheet($xsl);

echo $processor->transformToXML($masterDoc);

