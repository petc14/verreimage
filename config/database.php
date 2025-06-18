<?php
/**
 * Fichier de Connexion à la Base de Données
 *
 * Ce fichier contient les informations de connexion à la base de données
 * et crée une instance de l'objet PDO pour les interactions avec la base de données.
 * Il doit être placé dans le dossier /config/
 */

// --- Constantes de Connexion à la Base de Données ---
// Ces constantes sont utilisées pour sécuriser et faciliter la maintenance des informations de connexion.

/**
 * @const DB_SERVER L'adresse du serveur de base de données (généralement 'localhost' en développement).
 */
define('DB_SERVER', 'localhost');

/**
 * @const DB_USERNAME Le nom d'utilisateur pour se connecter à la base de données.
 */
define('DB_USERNAME', 'root');

/**
 * @const DB_PASSWORD Le mot de passe de l'utilisateur.
 * Laisser vide si vous n'avez pas de mot de passe (configuration par défaut de XAMPP).
 */
define('DB_PASSWORD', ''); 

/**
 * @const DB_NAME Le nom de la base de données que nous avons créée.
 */
define('DB_NAME', 'verre_image_db');


// --- Établissement de la Connexion PDO ---

/**
 * @var PDO|null $pdo L'objet de connexion à la base de données.
 * Nous l'initialisons à null au cas où la connexion échouerait.
 */
$pdo = null;

try {
    /**
     * Tentative de création d'une nouvelle instance de PDO.
     * Le DSN (Data Source Name) contient les informations nécessaires pour la connexion.
     * On définit le jeu de caractères en utf8mb4 pour une compatibilité maximale avec les accents et les émojis.
     */
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    
    /**
     * Définition des attributs de PDO pour une meilleure gestion des erreurs.
     * - PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION: Lance des exceptions en cas d'erreur SQL, 
     * ce qui permet de les attraper dans un bloc try-catch et d'éviter de bloquer le site.
     * - PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC: Définit le mode de récupération par défaut des résultats
     * en tant que tableau associatif (clé => valeur), ce qui est plus facile à manipuler.
     */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    /**
     * Si la connexion échoue, on arrête l'exécution du script et on affiche un message d'erreur clair.
     * Dans un environnement de production, il serait préférable de logger cette erreur dans un fichier
     * plutôt que de l'afficher à l'utilisateur.
     */
    // die() arrête l'exécution du script.
    die("ERREUR : Impossible de se connecter à la base de données. " . $e->getMessage());
}
?>
