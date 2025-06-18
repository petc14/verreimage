<?php
/**
 * Fichier de Fonctions Utilitaires
 *
 * Ce fichier regroupe des fonctions PHP réutilisables sur l'ensemble du site
 * pour des tâches courantes comme le formatage des prix, la gestion des utilisateurs,
 * la sécurisation des entrées, etc.
 * Il doit être placé dans le dossier /includes/
 */

// S'assurer que le fichier est inclus et non accédé directement
if (!defined('BASE_URL')) {
    die('Accès direct non autorisé.');
}


/**
 * Formate un nombre en une chaîne de caractères représentant un prix en euros.
 *
 * @param float $price Le prix à formater (ex: 155.5).
 * @return string Le prix formaté (ex: "155,50 €").
 */
function format_price(float $price): string {
    return number_format($price, 2, ',', ' ') . ' €';
}


/**
 * Vérifie si un utilisateur est actuellement connecté.
 *
 * @return bool True si l'utilisateur est connecté, false sinon.
 */
function is_user_logged_in(): bool {
    return isset($_SESSION['user_id']);
}


/**
 * Récupère les informations de l'utilisateur connecté depuis la session.
 *
 * @return array|null Les informations de l'utilisateur ou null s'il n'est pas connecté.
 */
function get_user_info(): ?array {
    if (is_user_logged_in()) {
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'] ?? null,
            'first_name' => $_SESSION['user_first_name'] ?? null,
        ];
    }
    return null;
}


/**
 * Affiche le contenu d'une variable de manière lisible puis arrête l'exécution du script.
 * Très utile pour le débogage.
 *
 * @param mixed $data La variable à inspecter.
 * @return void
 */
function dd($data): void {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}


/**
 * Nettoie une chaîne de caractères pour éviter les injections XSS simples.
 *
 * @param string $data La chaîne de caractères à nettoyer.
 * @return string La chaîne nettoyée.
 */
function sanitize_input(string $data): string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}


/**
 * Scanne les dossiers d'images pour créer un catalogue de fonds par thème.
 *
 * @param string $base_dir Le chemin vers le dossier principal des images.
 * @return array Le catalogue d'images groupées par thème.
 */
function get_background_catalogue(string $base_dir = 'IMAGES'): array {
    $catalogue = [];
    if (!is_dir($base_dir)) {
        return $catalogue;
    }

    $themes = scandir($base_dir);
    foreach ($themes as $theme) {
        // Exclut les dossiers non pertinents et les fichiers à la racine
        if (is_dir($base_dir . '/' . $theme) && !in_array($theme, ['.', '..', 'realisations']) && !is_file($base_dir . '/' . $theme)) {
            $theme_path = $base_dir . '/' . $theme;
            $images_in_theme = [];
            $files = scandir($theme_path);
            foreach ($files as $file) {
                $file_path = $theme_path . '/' . $file;
                $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $images_in_theme[] = $file_path;
                }
            }
            if (!empty($images_in_theme)) {
                $catalogue[ucfirst($theme)] = $images_in_theme;
            }
        }
    }
    ksort($catalogue);
    return $catalogue;
}

?>
