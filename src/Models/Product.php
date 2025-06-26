<?php
// src/Models/Product.php

namespace Models;

class Product extends Database {
    private $db;

    public function __construct() {
        $this->db = static::connect();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getBackgroundCatalogue() {
        $backgrounds = [];
        $dir = ROOT_PATH . '/public/assets/images/backgrounds/';
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $backgrounds[] = 'assets/images/backgrounds/' . $file;
                }
            }
        }
        return $backgrounds;
    }

    public function getGalleryImages() {
        $images = [];
        $dir = ROOT_PATH . '/public/assets/images/gallery/';
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $images[] = 'assets/images/gallery/' . $file;
                }
            }
        }
        return $images;
    }

    public function saveCustomPlaque(array $data) {
        $stmt = $this->db->prepare(
            "INSERT INTO custom_plaques (format, fixation, background_image, text_content, image_uploads, price, thumbnail_path)
             VALUES (:format, :fixation, :background_image, :text_content, :image_uploads, :price, :thumbnail_path)"
        );
        $stmt->execute([
            'format' => $data['format'],
            'fixation' => $data['fixation'],
            'background_image' => $data['background_image'],
            'text_content' => $data['text_content'],
            'image_uploads' => $data['image_uploads'], // Doit déjà être un JSON string ici
            'price' => $data['price'],
            'thumbnail_path' => $data['thumbnail_path']
        ]);
        return $this->db->lastInsertId();
    }

    public function getCustomPlaqueById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM custom_plaques WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * NOUVELLE MÉTHODE: Récupère plusieurs plaques personnalisées par leurs IDs.
     * @param array $ids Un tableau d'entiers représentant les IDs des plaques.
     * @return array Un tableau associatif des détails des plaques.
     */
    public function getCustomPlaquesByIds(array $ids): array {
        if (empty($ids)) {
            return [];
        }
        // Crée une chaîne de '?' pour les placeholders
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("SELECT * FROM custom_plaques WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}