<?php
// src/Models/Cart.php

namespace Models;

class Cart {
    /**
     * Initialise le panier dans la session s'il n'existe pas encore.
     */
    public static function initCart(): void {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Ajoute un produit (une plaque personnalisée) au panier.
     * @param int $customizationId L'ID unique de la personnalisation de la plaque.
     * @param float $price Le prix final de la plaque.
     * @param array $details Un tableau contenant les détails de la plaque.
     */
    public static function addToCart(int $customizationId, float $price, array $details): void {
        self::initCart();
        $_SESSION['cart'][$customizationId] = [
            'id' => $customizationId,
            'price' => $price,
            'details' => $details,
            'added_at' => time()
        ];
    }

    /**
     * Supprime un article du panier en utilisant son ID de personnalisation.
     * @param int $customizationId L'ID de l'article à supprimer.
     */
    public static function removeFromCart(int $customizationId): void {
        self::initCart();
        if (isset($_SESSION['cart'][$customizationId])) {
            unset($_SESSION['cart'][$customizationId]);
        }
    }

    /**
     * Récupère l'intégralité du contenu du panier.
     * @return array Le contenu du panier.
     */
    public static function getCartContents(): array {
        self::initCart();
        return $_SESSION['cart'];
    }

    /**
     * Calcule le nombre total d'articles dans le panier.
     * @return int Le nombre d'articles.
     */
    public static function getCartItemsCount(): int {
        self::initCart();
        return count($_SESSION['cart']);
    }

    /**
     * Calcule le montant total du panier.
     * @return float Le montant total.
     */
    public static function getCartTotal(): float {
        self::initCart();
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
     */
    public static function clearCart(): void {
        $_SESSION['cart'] = [];
    }
}
