<?php
// src/Models/User.php

namespace Models;

use PDO;

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(string $firstName, string $lastName, string $email, string $password): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$firstName, $lastName, $email, $hashedPassword]);
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function getUserInfo(): ?array {
        if (self::isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'] ?? null,
                'first_name' => $_SESSION['user_first_name'] ?? null,
                'last_name' => $_SESSION['user_last_name'] ?? null,
            ];
        }
        return null;
    }

    // Example function to get user orders (needs 'orders' table)
    public function getUserOrders(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
