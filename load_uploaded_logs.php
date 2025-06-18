<?php
session_start();

// Gestion des langues
$accepted_langs = ['fr', 'en', 'it', 'de'];
$lang_code = $_GET['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$lang_code = in_array($lang_code, $accepted_langs) ? $lang_code : 'fr';
require_once __DIR__ . "/lang/{$lang_code}.php";

// Vérification du nom d'utilisateur
if (!isset($_SESSION['username']) || !preg_match('/^[a-zA-Z0-9_\-]+$/', $_SESSION['username'])) {
    http_response_code(403);
    exit($lang['access_denied'] ?? 'Utilisateur non authentifié ou nom invalide.');
}

$cleanUser = basename($_SESSION['username']);
define("UPLOAD_XML_PATH", __DIR__ . "/logs/" . $cleanUser . "/");
define("XSL_PATH", __DIR__ . "/styles/transformlog_sorted.xsl");

// Vérification du dossier de l'utilisateur
if (!is_dir(UPLOAD_XML_PATH)) {
    http_response_code(500);
    exit($lang['missing_uploaded_folder'] ?? "Erreur : le dossier des fichiers uploadés n'existe pas.");
}

// Création du document XML fusionné
$masterDoc = new DOMDocument('1.0', 'UTF-8');
$root = $masterDoc->createElement("xltechlog");
$masterDoc->appendChild($root);

// Parcours des fichiers XML
$files = glob(UPLOAD_XML_PATH . '*.xml');
if (!$files || count($files) === 0) {
    echo "<p>" . ($lang['no_uploaded_found'] ?? 'Aucun fichier uploadé trouvé.') . "</p>";
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

// Chargement et application XSLT
if (!file_exists(XSL_PATH)) {
    http_response_code(500);
    exit($lang['missing_xslt'] ?? 'Erreur : fichier XSLT manquant.');
}
$xsl = new DOMDocument;
$xsl->load(XSL_PATH);

$processor = new XSLTProcessor();
$processor->importStylesheet($xsl);

echo $processor->transformToXML($masterDoc);
