<?php
/**
 * Fichier de Gestion du Panier
 *
 * Ce fichier contient un ensemble de fonctions pour gérer le panier d'achat
 * en utilisant les sessions PHP. Il permet d'ajouter, modifier, supprimer
 * et consulter les articles du panier.
 * Il est placé dans le dossier /src/ pour centraliser la logique
 */

// S'assurer que le fichier est inclus et non accédé directement
if (session_status() == PHP_SESSION_NONE) {
    // Si ce fichier est appelé par un script qui n'a pas démarré de session,
    // on le démarre pour éviter les erreurs. C'est une sécurité.
    session_start();
}


/**
 * Initialise le panier dans la session s'il n'existe pas encore.
 * C'est la première fonction à appeler pour s'assurer que le panier est prêt.
 *
 * @return void
 */
function init_cart(): void {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

/**
 * Ajoute un produit (une plaque personnalisée) au panier.
 * Chaque plaque étant unique, nous utilisons son ID de personnalisation comme clé pour la stocker.
 *
 * @param int $customization_id L'ID unique de la personnalisation de la plaque.
 * @param float $price Le prix final de la plaque.
 * @param array $details Un tableau contenant les détails de la plaque (ex: format, fixation, miniature).
 * @return void
 */
function add_to_cart(int $customization_id, float $price, array $details): void {
    init_cart();
    
    // Ajoute ou met à jour l'article dans le panier.
    $_SESSION['cart'][$customization_id] = [
        'id' => $customization_id,
        'price' => $price,
        'details' => $details, // ex: ['format' => '30x22', 'thumbnail' => 'path/to/img.png']
        'added_at' => time() // Stocke le moment de l'ajout
    ];
}

/**
 * Supprime un article du panier en utilisant son ID de personnalisation.
 *
 * @param int $customization_id L'ID de l'article à supprimer.
 * @return void
 */
function remove_from_cart(int $customization_id): void {
    init_cart();
    if (isset($_SESSION['cart'][$customization_id])) {
        unset($_SESSION['cart'][$customization_id]);
    }
}

/**
 * Récupère l'intégralité du contenu du panier.
 *
 * @return array Le contenu du panier, qui est un tableau d'articles.
 */
function get_cart_contents(): array {
    init_cart();
    return $_SESSION['cart'];
}

/**
 * Calcule le nombre total d'articles (de plaques uniques) dans le panier.
 *
 * @return int Le nombre d'articles.
 */
function get_cart_items_count(): int {
    init_cart();
    return count($_SESSION['cart']);
}

/**
 * Calcule le montant total du panier en additionnant le prix de chaque article.
 *
 * @return float Le montant total.
 */
function get_cart_total(): float {
    init_cart();
    $total = 0.0;
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['price'])) {
            $total += $item['price'];
        }
    }
    return $total;
}

/**
 * Vide entièrement le panier.
 * Utile après une commande réussie.
 *
 * @return void
 */
function clear_cart(): void {
    $_SESSION['cart'] = [];
}

?>
