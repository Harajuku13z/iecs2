<?php

/**
 * Point d'entrée de secours pour Laravel
 * Ce fichier redirige toutes les requêtes vers le dossier public/
 * 
 * Utilisez ce fichier si le DocumentRoot pointe vers la racine du projet
 * et que le .htaccess ne fonctionne pas correctement.
 */

// Chemin vers le dossier public
$publicPath = __DIR__ . '/public';

// Si on accède directement à index.php, rediriger vers public/
if (php_sapi_name() === 'cli-server') {
    // Pour le serveur de développement PHP
    if (file_exists($publicPath . $_SERVER['REQUEST_URI'])) {
        return false;
    }
}

// Inclure le fichier index.php du dossier public
if (file_exists($publicPath . '/index.php')) {
    require $publicPath . '/index.php';
} else {
    http_response_code(500);
    die('Erreur: Le dossier public/ n\'existe pas ou n\'est pas accessible.');
}

