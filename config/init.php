<?php
/**
 * Fichier d'Initialisation du Site
 *
 * Ce fichier est inclus au début de chaque page principale.
 * Il gère le démarrage des sessions, la configuration de l'environnement,
 * et l'inclusion des fichiers de configuration essentiels comme la connexion à la base de données.
 * Il doit être placé dans le dossier /config/
 */

// --- Démarrage de la Session ---
// Il est crucial de démarrer la session au tout début pour pouvoir utiliser les variables $_SESSION
// sur l'ensemble du site (ex: pour le panier, la connexion utilisateur, etc.).
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- Configuration de l'Environnement ---

/**
 * Définition du fuseau horaire.
 * Important pour que toutes les dates (commandes, inscriptions...) soient cohérentes.
 */
date_default_timezone_set('Europe/Paris');

/**
 * Définition des paramètres d'affichage des erreurs.
 * En phase de développement, il est utile de voir toutes les erreurs.
 * En production, il faudrait changer 'E_ALL' par 0 et logger les erreurs dans un fichier.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);


// --- Inclusion des Fichiers de Configuration ---

/**
 * Inclusion du fichier de connexion à la base de données.
 * __DIR__ est une "constante magique" qui retourne le chemin absolu du dossier où se trouve ce fichier (le dossier /config/).
 * Utiliser require_once s'assure que le fichier est inclus une seule fois, même si plusieurs fichiers tentent de l'inclure.
 */
require_once __DIR__ . '/database.php';

/**
 * Inclusion du fichier contenant les fonctions utilitaires.
 */
require_once __DIR__ . '/../includes/fonctions.php';

/**
 * Inclusion du fichier de gestion du panier.
 */
require_once __DIR__ . '/../includes/panier.php';


// --- Constantes Globales du Site ---
// Définir des constantes pour les informations récurrentes facilite la maintenance.

/**
 * @const SITE_NAME Le nom de votre site, utilisé par exemple dans le titre des pages.
 */
define('SITE_NAME', 'Verre & Image');

/**
 * @const BASE_URL L'URL de base de votre site.
 * Très important pour construire des liens absolus pour les CSS, JS, images et liens de navigation.
 * Adaptez cette URL si votre site n'est pas à la racine de localhost.
 */
define('BASE_URL', 'http://localhost/verre-image-site/');

?>
