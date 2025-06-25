<?php
// src/Controllers/HomeController.php

namespace Controllers;

use Models\Product;

class HomeController {
    public function index() {
        $productModel = new Product();
        $catalogueThemes = $productModel->getBackgroundCatalogue(); // Pour les thèmes populaires sur l'accueil
        $data = [
            'page_title' => "Accueil - Plaques Funéraires Personnalisées en Verre",
            'page_description' => "Créez un hommage unique avec nos plaques funéraires en verre personnalisées. Fabrication française, qualité garantie 30 ans. Découvrez nos modèles.",
            'catalogue_themes' => array_slice($catalogueThemes, 0, 3, true) // Prendre les 3 premiers thèmes pour l'accueil
        ];
        global $page_title, $page_description; // Pour l'inclusion du header
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('home', $data);
    }

    public function presentation() {
        $data = [
            'page_title' => "Notre Savoir-Faire - L'Excellence du Verre",
            'page_description' => "Découvrez notre procédé breveté d'impression sur verre, notre fabrication 100% française et notre garantie de 30 ans. L'artisanat au service de l'hommage."
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('presentation', $data);
    }

    public function realisations() {
        $productModel = new Product();
        $galleryImages = $productModel->getGalleryImages();
        $data = [
            'page_title' => "Nos Réalisations - Exemples de plaques funéraires",
            'page_description' => "Découvrez la qualité et l'émotion de nos plaques funéraires en verre, créées sur mesure pour nos clients. Puisez l'inspiration parmi nos exemples.",
            'gallery_images' => $galleryImages
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('realisations', $data);
    }

    public function mentionsLegales() {
        $data = [
            'page_title' => "Mentions Légales",
            'page_description' => "Consultez les mentions légales du site Verre & Image, informations sur l'éditeur, l'hébergement et la politique de confidentialité."
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('mentions-legales', $data);
    }
}