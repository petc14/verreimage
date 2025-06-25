<?php
// config/init.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Europe/Paris');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Définition des chemins absolus
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('SRC_PATH', ROOT_PATH . '/src');
define('CONTROLLERS_PATH', SRC_PATH . '/Controllers');
define('MODELS_PATH', SRC_PATH . '/Models');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('ASSETS_PATH', PUBLIC_PATH . '/assets');

// Constantes du site
define('SITE_NAME', 'Verre & Image');
define('BASE_URL', 'http://localhost/verre-image-site/public/'); // IMPORTANT: Mettre à jour pour votre environnement

// Autoloading corrigé pour nos classes avec namespaces
spl_autoload_register(function ($class) {
    // Bases de répertoires pour les namespaces
    $namespaces = [
        'Models\\' => MODELS_PATH . '/',
        'Controllers\\' => CONTROLLERS_PATH . '/',
    ];

    foreach ($namespaces as $prefix => $base_dir) {
        // Vérifie si la classe utilise le préfixe de namespace actuel
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            // Récupère le nom de la classe relatif au namespace (sans le préfixe)
            $relative_class = substr($class, $len);

            // Remplace les séparateurs de namespace par les séparateurs de répertoire
            // et ajoute l'extension .php
            $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

            // Si le fichier existe, l'inclure
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Connexion à la base de données via le modèle Database
try {
    \Models\Database::connect(
        'localhost', // DB_SERVER
        'root',      // DB_USERNAME
        '',          // DB_PASSWORD
        'verre_image_db' // DB_NAME
    );
} catch (PDOException $e) {
    die("ERREUR : Impossible de se connecter à la base de données. " . $e->getMessage());
}

// Initialisation du panier (nécessite la session démarrée)
\Models\Cart::initCart();

// Fonctions utilitaires globales
// Moved into Helpers or specific classes in a real MVC.
// For now, keeping a simplified function set.
if (!function_exists('format_price')) {
    function format_price(float $price): string {
        return number_format($price, 2, ',', ' ') . ' €';
    }
}

if (!function_exists('sanitize_input')) {
    function sanitize_input(string $data): string {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}