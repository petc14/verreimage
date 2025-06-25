<?php
// src/Models/Product.php

namespace Models;

use PDO;

class Product {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Scanne les dossiers d'images pour créer un catalogue de fonds par thème.
     * @param string $baseDir Le chemin vers le dossier principal des images.
     * @return array Le catalogue d'images groupées par thème.
     */
    public function getBackgroundCatalogue(string $baseDir = ASSETS_PATH . '/images'): array {
        $catalogue = [];
        if (!is_dir($baseDir)) {
            return $catalogue;
        }

        $themes = scandir($baseDir);
        foreach ($themes as $theme) {
            // Exclut les dossiers non pertinents et les fichiers à la racine
            if (is_dir($baseDir . '/' . $theme) && !in_array($theme, ['.', '..', 'realisations', 'icons', 'temp_thumbnails', 'favicon.png', 'logo.png', 'hero-plaque-rose.jpg', 'plaque-layers.png', 'verre-securite.jpg', 'artisan-workshop.jpg'])) {
                $themePath = $baseDir . '/' . $theme;
                $imagesInTheme = [];
                $files = scandir($themePath);
                foreach ($files as $file) {
                    $filePath = $themePath . '/' . $file;
                    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $imagesInTheme[] = str_replace(PUBLIC_PATH . '/', '', $filePath); // Chemin relatif pour le web
                    }
                }
                if (!empty($imagesInTheme)) {
                    $catalogue[ucfirst($theme)] = $imagesInTheme;
                }
            }
        }
        ksort($catalogue);
        return $catalogue;
    }

    /**
     * Scanne un dossier spécifique et retourne la liste des images de réalisations.
     * @param string $dir Le chemin vers le dossier à scanner.
     * @return array La liste des chemins d'images.
     */
    public function getGalleryImages(string $dir = ASSETS_PATH . '/images/realisations'): array {
        $images = [];
        if (!is_dir($dir)) {
            return $images; // Retourne un tableau vide si le dossier n'existe pas
        }

        $files = scandir($dir);
        foreach ($files as $file) {
            $filePath = $dir . '/' . $file;
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            // On ne garde que les fichiers avec des extensions d'image valides
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $images[] = str_replace(PUBLIC_PATH . '/', '', $filePath); // Chemin relatif pour le web
            }
        }
        return $images;
    }

    /**
     * Enregistre une plaque personnalisée dans la base de données.
     * @param array $data Les détails de la plaque.
     * @return int L'ID de la plaque insérée.
     */
    public function saveCustomPlaque(array $data): int {
        $userId = \Models\User::getUserInfo()['id'] ?? null;
        $stmt = $this->db->prepare("INSERT INTO custom_plaques (user_id, format, fixation, background_image, text_content, image_uploads, price, thumbnail_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $data['format'],
            $data['fixation'],
            $data['background_image'],
            $data['text_content'],
            json_encode($data['image_uploads']), // Stocke comme JSON
            $data['price'],
            $data['thumbnail_path'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function getCustomPlaqueById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM custom_plaques WHERE id = ?");
        $stmt->execute([$id]);
        $plaque = $stmt->fetch();
        if ($plaque && isset($plaque['image_uploads'])) {
            $plaque['image_uploads'] = json_decode($plaque['image_uploads'], true);
        }
        return $plaque ?: null;
    }
}
