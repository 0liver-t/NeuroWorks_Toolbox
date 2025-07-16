
## Structure et modularité

- **index.php** : Structure principale de la page, gestion des onglets, inclusions des modules.
- **logs_embed.php** : Contenu du module Journaux (onglet logs).
- **port-scanner.php** : Contenu du module Vérificateur (scanner de ports).
- **scripts/index.js** : Logique centrale de gestion d’onglets et du cycle de vie des modules.
- **scripts/journaux.js** : Toute la logique DataTable et affichage pour les journaux. Expose `window.logsInit` et `window.logsDestroy`.
- **scripts/port-scanner.js** : Logique de scan de port, exposant `window.portScannerInit` et `window.portScannerDestroy`.
- **styles/journaux.css** et **styles/port-scanner.css** : Styles propres à chaque module, scope CSS pour éviter les conflits.
- **README.md** : Ce document.

## Ajout d’un nouvel onglet/module

1. Crée ton nouveau fichier dans `scripts/` (ex: `utilisateurs.js`) et dans `styles/` (ex: `utilisateurs.css`).
2. Expose deux fonctions globales :
    ```js
    window.utilisateursInit = function() { /* ... */ }
    window.utilisateursDestroy = function() { /* ... */ }
    ```
3. Dans `scripts/index.js`, ajoute :
    ```js
    "tab-utilisateurs": {
        init: window.utilisateursInit,
        destroy: window.utilisateursDestroy
    }
    ```
4. Dans `index.php`, ajoute :
    - Un nouvel onglet dans `.tabs`
    - Un nouveau `<div class="tab-content" id="tab-utilisateurs">...</div>`
    - Les `<link rel="stylesheet">` et `<script src="...">` nécessaires.

## Conseils

- **Chaque module doit avoir son propre conteneur parent HTML et ses propres IDs/classes pour éviter les collisions.**
- **Ne laisse pas de code JS inline dans index.php** : tout doit passer par les fichiers de module.
- **Scope bien ton CSS avec une classe racine unique par module** (déjà fait ici).

## Contact

Auteur : [À renseigner]
