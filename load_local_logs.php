<?php
session_start();

// Gestion des langues
$accepted_langs = ['fr', 'en', 'it', 'de'];
$lang_code = $_GET['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$lang_code = in_array($lang_code, $accepted_langs) ? $lang_code : 'fr';
require_once __DIR__ . "/lang/{$lang_code}.php";

// Vérifie que l’utilisateur est bien authentifié
if (!isset($_SESSION['username']) || !preg_match('/^[a-zA-Z0-9_\-]+$/', $_SESSION['username'])) {
    http_response_code(403);
    exit($lang['access_denied'] ?? 'Accès refusé : utilisateur non authentifié.');
}

// Répertoire des fichiers locaux
define("LOCAL_XML_PATH", "D:/XLTekAudit/");
define("XSL_PATH", __DIR__ . "/styles/transformlog_sorted.xsl");

// Vérifie l’existence du dossier
if (!is_dir(LOCAL_XML_PATH)) {
    http_response_code(500);
    exit($lang['missing_local_folder'] ?? "Erreur : le dossier local des journaux n'existe pas.");
}

// ----------- NOUVELLE FONCTION : Parcours récursif de tous les fichiers .xml -----------
function getAllXmlFiles($dir) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $files = [];
    foreach ($rii as $file) {
        if ($file->isFile() && strtolower($file->getExtension()) === 'xml') {
            $files[] = $file->getPathname();
        }
    }
    return $files;
}

// Utilisation de la fonction récursive
$files = getAllXmlFiles(LOCAL_XML_PATH);
if (!$files || count($files) === 0) {
    echo "<p>" . ($lang['no_local_found'] ?? 'Aucun fichier journal trouvé dans le dossier local.') . "</p>";
    exit;
}

// Fusion des balises <activity>
$masterDoc = new DOMDocument('1.0', 'UTF-8');
$root = $masterDoc->createElement("xltechlog");
$masterDoc->appendChild($root);

foreach ($files as $file) {
    $xml = new DOMDocument;
    if (@$xml->load($file)) {
        foreach ($xml->getElementsByTagName('activity') as $activity) {
            $imported = $masterDoc->importNode($activity, true);
            $root->appendChild($imported);
        }
    }
}

// Transformation XSLT
$xsl = new DOMDocument;
if (!file_exists(XSL_PATH)) {
    http_response_code(500);
    exit($lang['missing_xslt'] ?? 'Erreur : fichier XSLT manquant.');
}
$xsl->load(XSL_PATH);

$processor = new XSLTProcessor();
$processor->importStylesheet($xsl);

// Affichage final
echo $processor->transformToXML($masterDoc);
