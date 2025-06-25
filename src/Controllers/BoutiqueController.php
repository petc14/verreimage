<?php
// src/Controllers/BoutiqueController.php

namespace Controllers;

use Models\Product;

class BoutiqueController {
    public function index() {
        $productModel = new Product();
        $catalogueThemes = $productModel->getBackgroundCatalogue();

        $data = [
            'page_title' => "Boutique - Nos Modèles de Plaques",
            'page_description' => "Découvrez tous nos thèmes de plaques funéraires personnalisables. Choisissez un modèle et commencez la création d'un hommage unique.",
            'catalogue_themes' => $catalogueThemes
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('boutique', $data);
    }
}