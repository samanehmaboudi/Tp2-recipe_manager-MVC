<?php 

// Chemins de base
define('BASE', '/webDynamiqueAvancer/lesTP/recipe_manager_mvc');
define('ASSET', BASE . '/public');

// Paramètres de la base de données
define('DB_CONFIG', [
    'host' => 'localhost',
    'dbname' => 'recipe_manager',  
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8'
]);

// Mode Debug (true pour afficher les erreurs, false pour cacher)
define('DEBUG_MODE', true);

// Gestion des erreurs en fonction du mode debug
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
