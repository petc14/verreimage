<?php
// src/Models/Database.php

namespace Models;

use PDO;
use PDOException;

class Database {
    private static ?PDO $pdo = null;

    public static function connect(string $server, string $username, string $password, string $dbName): PDO {
        if (self::$pdo === null) {
            $dsn = "mysql:host={$server};dbname={$dbName};charset=utf8mb4";
            try {
                self::$pdo = new PDO($dsn, $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // En production, il est préférable de logger l'erreur plutôt que de l'afficher directement
                throw new PDOException("Connexion à la base de données échouée : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    public static function getInstance(): PDO {
        if (self::$pdo === null) {
            // Cela ne devrait pas arriver si connect() est appelé au démarrage de l'application
            throw new Exception("La connexion à la base de données n'a pas été initialisée.");
        }
        return self::$pdo;
    }
}
