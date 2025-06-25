<?php
// src/Models/Order.php

namespace Models;

use PDO;

class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Crée une nouvelle commande dans la base de données.
     * @param array $orderData Données de la commande.
     * @param array $cartItems Articles du panier.
     * @return string La référence de la commande créée.
     */
    public function createOrder(array $orderData, array $cartItems): string {
        $orderRef = 'VI-' . time();
        $userId = User::getUserInfo()['id'] ?? null;

        $stmt = $this->db->prepare("
            INSERT INTO orders (
                user_id, order_reference, total_amount,
                shipping_firstname, shipping_lastname, shipping_address, shipping_zip_code, shipping_city,
                billing_firstname, billing_lastname, billing_address, billing_zip_code, billing_city,
                email, order_status, payment_method
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $userId,
            $orderRef,
            $orderData['total_amount'],
            $orderData['shipping_address']['first_name'],
            $orderData['shipping_address']['last_name'],
            $orderData['shipping_address']['address'],
            $orderData['shipping_address']['zip_code'],
            $orderData['shipping_address']['city'],
            $orderData['billing_address']['first_name'],
            $orderData['billing_address']['last_name'],
            $orderData['billing_address']['address'],
            $orderData['billing_address']['zip_code'],
            $orderData['billing_address']['city'],
            $orderData['email'], // Assurez-vous que l'email est inclus dans $orderData
            'pending', // Statut initial
            $orderData['payment_method'] ?? 'simulated' // Ou un réel méthode si intégrée
        ]);

        $orderId = $this->db->lastInsertId();

        // Insérer les articles de la commande
        $itemStmt = $this->db->prepare("INSERT INTO order_items (order_id, custom_plaque_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            $itemStmt->execute([
                $orderId,
                $item['id'], // custom_plaque_id
                1, // quantity (toujours 1 pour une plaque unique)
                $item['price']
            ]);
        }

        return $orderRef;
    }

    public function getOrderDetails(string $orderReference): ?array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_reference = ?");
        $stmt->execute([$orderReference]);
        return $stmt->fetch() ?: null;
    }

    public function getOrderItems(int $orderId): array {
        $stmt = $this->db->prepare("
            SELECT oi.*, cp.format, cp.fixation, cp.background_image, cp.thumbnail_path
            FROM order_items oi
            JOIN custom_plaques cp ON oi.custom_plaque_id = cp.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
}
