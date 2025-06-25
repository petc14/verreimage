<?php
// src/Controllers/PanierController.php

namespace Controllers;

use Models\Cart;
use Models\Product; // Pour récupérer les détails de la plaque personnalisée

class PanierController {
    public function index() {
        if (Cart::getCartItemsCount() === 0) {
            header('Location: ' . BASE_URL . 'boutique'); // Rediriger vers la boutique si le panier est vide
            exit();
        }

        $cartItems = Cart::getCartContents();
        $cartTotal = Cart::getCartTotal();
        $cartCount = Cart::getCartItemsCount();

        // Récupérer les détails complets des plaques si nécessaire pour l'affichage
        $productModel = new Product();
        foreach ($cartItems as $id => &$item) {
            $plaqueDetails = $productModel->getCustomPlaqueById($id);
            if ($plaqueDetails) {
                // Utiliser le chemin de la miniature enregistrée ou un placeholder
                $item['details']['thumbnail'] = BASE_URL . ($plaqueDetails['thumbnail_path'] ?? 'assets/images/placeholder-plaque.jpg');
                $item['details']['format'] = $plaqueDetails['format'];
                $item['details']['fixation'] = $plaqueDetails['fixation'];
            }
        }
        unset($item); // Rompre la référence sur le dernier élément

        $data = [
            'page_title' => "Mon Panier",
            'page_description' => "Consultez le récapitulatif de votre création de plaque personnalisée et finalisez votre commande sur Verre & Image.",
            'cart_items' => $cartItems,
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('panier', $data); // CHANGEMENT ICI: 'page_panier' devient 'panier'
    }

    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $format = sanitize_input($_POST['format'] ?? '30x22');
            $fixation = sanitize_input($_POST['fixation'] ?? 'plaque_seule');
            $background = sanitize_input($_POST['background_image'] ?? 'assets/images/placeholder-plaque.jpg');
            $textContent = sanitize_input($_POST['text_content'] ?? '');
            $imageUploads = json_decode($_POST['image_uploads'] ?? '[]', true); // Assurez-vous que c'est un tableau JSON
            $price = floatval($_POST['price'] ?? 175);
            $thumbnailPath = sanitize_input($_POST['thumbnail_path'] ?? null); // Si générée côté client

            // Enregistrer la plaque personnalisée dans la BDD et obtenir son ID
            $productModel = new Product();
            $customizationId = $productModel->saveCustomPlaque([
                'format' => $format,
                'fixation' => $fixation,
                'background_image' => $background,
                'text_content' => $textContent,
                'image_uploads' => $imageUploads,
                'price' => $price,
                'thumbnail_path' => $thumbnailPath
            ]);

            // Ajouter la plaque au panier
            Cart::addToCart($customizationId, $price, [
                'format' => $format,
                'fixation' => $fixation,
                'thumbnail' => $thumbnailPath ? (BASE_URL . $thumbnailPath) : (BASE_URL . 'assets/images/placeholder-plaque.jpg')
            ]);

            // Rediriger vers le panier
            header('Location: ' . BASE_URL . 'panier');
            exit();
        }
        header('Location: ' . BASE_URL . 'boutique'); // Rediriger si accès direct
        exit();
    }

    public function removeFromCart() {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $itemId = (int)$_GET['id'];
            Cart::removeFromCart($itemId);
        }
        header('Location: ' . BASE_URL . 'panier');
        exit();
    }
}
