<?php
// config.php

/**
 * Configuration for NeuroWorks Toolbox
 *
 * @return array<string, mixed>
 */
return [
    // Logs stockés en permanence par le serveur
    'server_logs_dir'  => 'D:/XLTekAudit',

    // Logs uploadés "à chaud" par l’utilisateur de la session
    'upload_logs_dir'  => __DIR__ . '/logs',

    // Logs situés sur un emplacement distant ou réseau
    'remote_logs_dir'  => __DIR__ . '/logs_remote',

    // Path to the SQLite cache database file
    'cache_db'         => __DIR__ . '/cache/logs.db',

    // Path to the XSLT stylesheet used for transforming XML logs
    'xsl_file'         => __DIR__ . '/templates/transformlog_sorted.xsl',

    // Nombre d’entrées par page pour la pagination
    'per_page'         => 50,
];
