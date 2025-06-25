<?php
// src/Controllers/ConfigurateurController.php

namespace Controllers;

use Models\Product;

class ConfigurateurController {
    public function index() {
        $productModel = new Product();
        $catalogueFonds = $productModel->getBackgroundCatalogue();

        $data = [
            'catalogue_fonds' => $catalogueFonds
        ];
        // Le configurateur a son propre header/footer, donc on utilise renderConfigurator
        renderConfigurator('configurateur', $data);
    }
}
