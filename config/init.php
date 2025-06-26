<?php
// config/init.php

// Démarrer la session en tout début
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Chemin racine du projet
define('ROOT_PATH', dirname(__DIR__));

// URL de base du site
// IMPORTANT : À MODIFIER POUR L'URL DE VOTRE SITE EN PRODUCTION !
define('BASE_URL', 'http://localhost/verre-image-site/public/');

// Autoloading des classes
spl_autoload_register(function ($class) {
    // Convertit le namespace en chemin de fichier
    $prefixes = [
        'Models\\' => ROOT_PATH . '/src/Models/',
        'Controllers\\' => ROOT_PATH . '/src/Controllers/'
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return; // Classe trouvée, arrêter l'autoloading
            }
        }
    }
    
    // Inclure vendor/autoload.php si Composer est utilisé
    // Assurez-vous que ce fichier existe si vous utilisez Composer pour des dépendances
    if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
        require_once ROOT_PATH . '/vendor/autoload.php';
    }
});

// Gestion des erreurs
// Pour le développement :
ini_set('display_errors', 1);
error_reporting(E_ALL);

// POUR LA PRODUCTION, DÉCOMMENTER CES LIGNES et COMMENTER LES PRÉCÉDENTES :
/*
ini_set('display_errors', 0); // Ne pas afficher les erreurs aux utilisateurs
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); // Affiche toutes les erreurs sauf Notices et Warnings pour le journal
ini_set('log_errors', 1); // Active la journalisation des erreurs
ini_set('error_log', ROOT_PATH . '/logs/php_errors.log'); // Spécifie le fichier de log
// Assurez-vous que le dossier 'logs' existe à la racine et est inscriptible par le serveur web
*/


// Connexion à la base de données
try {
    \Models\Database::connect(
        'mysql', // DB_CONNECTION
        'localhost', // DB_HOST
        'verre_image', // DB_DATABASE
        'root', // DB_USERNAME
        '' // DB_PASSWORD
    );
} catch (PDOException $e) {
    // GESTION D'ERREUR ROBUSTE POUR LA CONNEXION À LA BDD
    error_log('Erreur de connexion à la base de données: ' . $e->getMessage()); // Journaliser l'erreur

    // Afficher un message générique à l'utilisateur et arrêter l'exécution
    http_response_code(500); // Code de statut HTTP 500 (Internal Server Error)
    echo '<!DOCTYPE html>
          <html lang="fr">
          <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Erreur de service</title>
              <style>
                  body { font-family: sans-serif; text-align: center; margin-top: 50px; }
                  .container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
                  h1 { color: #d33; }
              </style>
          </head>
          <body>
              <div class="container">
                  <h1>Oups ! Une erreur est survenue.</h1>
                  <p>Nous rencontrons des difficultés techniques. Veuillez réessayer ultérieurement.</p>
                  <p>Si le problème persiste, veuillez nous contacter.</p>
              </div>
          </body>
          </html>';
    exit(); // Arrêter l'exécution du script
}

// Initialisation du panier
\Models\Cart::initCart();

// Fonction d'aide pour le rendu des vues
function render($view, $data = []) {
    // Permet d'accéder aux variables du tableau $data directement par leur nom dans la vue
    // Attention: extract peut créer des collisions de noms de variables. Pour un projet plus grand,
    // il est souvent préférable d'accéder aux variables via $data['nom_variable'].
    extract($data);
    require_once ROOT_PATH . '/Views/layouts/header.php';
    require_once ROOT_PATH . '/Views/' . $view . '.php';
    require_once ROOT_PATH . '/Views/layouts/footer.php';
}

function renderConfigurator($view, $data = []) {
    extract($data);
    require_once ROOT_PATH . '/Views/' . $view . '.php';
}

// Fonction de nettoyage des entrées (sécurité)
function sanitize_input($data) {
    if (is_array($data)) { // Gérer les tableaux récursivement
        return array_map('sanitize_input', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Fonction de formatage des prix
function format_price($price) {
    return number_format($price, 2, ',', ' ') . ' €';
}