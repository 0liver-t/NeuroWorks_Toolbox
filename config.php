<?php
return [
    'sources' => [
        'local' => [
            'label'    => 'Logs locaux',
            'logs_dir' => 'D:/XLTekAudit',
            'cache_db' => __DIR__ . '/cache/logs.db',
        ],
        'site1' => [
            'label'    => 'Toulouse serveur',
            'logs_dir' => __DIR__ . '/logs_remote/toulouse',
            'cache_db' => __DIR__ . '/cache/toulouse.db',
        ],
        'site2' => [
            'label'    => 'Toulouse adulte',
            'logs_dir' => __DIR__ . '/logs_remote/toulouse_adulte',
            'cache_db' => __DIR__ . '/cache/Toulouse_adulte.db',
        ],
        'site3' => [
            'label'    => 'Toulouse sommeil',
            'logs_dir' => __DIR__ . '/logs_remote/toulouse_sommeil',
            'cache_db' => __DIR__ . '/cache/Toulouse_sommeil.db',
        ],
        // ... Ajouter d’autres sources ici si besoin
    ],
];
