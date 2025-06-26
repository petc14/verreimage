<?php
// src/Controllers/PanierController.php

namespace Controllers;

use Models\Cart;
use Models\Product;

class PanierController {
    public function index() {
        if (Cart::getCartItemsCount() === 0) {
            header('Location: ' . BASE_URL . 'boutique'); // Rediriger vers la boutique si le panier est vide
            exit();
        }

        $cartItems = Cart::getCartContents();
        $cartTotal = Cart::getCartTotal();
        $cartCount = Cart::getCartItemsCount();

        // NOUVELLE LOGIQUE POUR ÉVITER LES REQUÊTES N+1
        $customPlaqueIds = [];
        foreach ($cartItems as $id => $item) {
            // On suppose que tous les IDs du panier sont des IDs de plaques personnalisées pour cette optimisation
            $customPlaqueIds[] = $id;
        }

        $productModel = new Product();
        $plaqueDetailsMap = [];
        if (!empty($customPlaqueIds)) {
            // Appeler la nouvelle méthode getCustomPlaquesByIds pour récupérer tous les détails en une seule requête
            $plaqueDetails = $productModel->getCustomPlaquesByIds($customPlaqueIds);
            foreach ($plaqueDetails as $detail) {
                $plaqueDetailsMap[$detail['id']] = $detail;
            }
        }

        foreach ($cartItems as $id => &$item) {
            if (isset($plaqueDetailsMap[$id])) {
                $plaque = $plaqueDetailsMap[$id];
                // Utiliser le chemin de la miniature enregistrée ou un placeholder
                $item['details']['thumbnail'] = BASE_URL . ($plaque['thumbnail_path'] ?? 'assets/images/placeholder-plaque.jpg');
                $item['details']['format'] = $plaque['format'];
                $item['details']['fixation'] = $plaque['fixation'];
                $item['details']['text_content'] = $plaque['text_content']; // Ajouter pour affichage si besoin
                // Décoder image_uploads pour affichage si nécessaire, s'il a été stocké en JSON
                $item['details']['image_uploads'] = json_decode($plaque['image_uploads'] ?? '[]', true); 
            } else {
                // Gérer le cas où la plaque n'est pas trouvée (par exemple, si elle a été supprimée de la BDD)
                // Ici, on utilise des valeurs par défaut
                $item['details']['thumbnail'] = BASE_URL . 'assets/images/placeholder-plaque.jpg';
                $item['details']['format'] = 'N/A';
                $item['details']['fixation'] = 'N/A';
                $item['details']['text_content'] = 'Plaque personnalisée introuvable';
                $item['details']['image_uploads'] = [];
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
        render('panier', $data);
    }

    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $format = sanitize_input($_POST['format'] ?? '30x22');
            $fixation = sanitize_input($_POST['fixation'] ?? 'plaque_seule');
            $background = sanitize_input($_POST['background_image'] ?? 'assets/images/placeholder-plaque.jpg');
            $price = floatval($_POST['price'] ?? 175);
            $thumbnailPath = sanitize_input($_POST['thumbnail_path'] ?? null);

            // CORRECTION: Récupérer et décoder l'ensemble des données Fabric.js
            $fabricObjectsDataJson = $_POST['fabric_objects_data'] ?? '[]';
            $fabricObjectsData = json_decode($fabricObjectsDataJson, true);

            // Extraire le contenu texte et les chemins d'images des données Fabric.js décodées
            $textContent = '';
            $imageUploads = [];

            foreach ($fabricObjectsData as $obj) {
                if ($obj['type'] === 'i-text' && isset($obj['text'])) {
                    $textContent .= $obj['text'] . "\n"; // Concaténer le texte de tous les objets texte
                } elseif ($obj['type'] === 'image' && isset($obj['src'])) {
                    // Stocker les chemins d'images. Supprimer BASE_URL du chemin si présent.
                    // IMPORTANT: Assurez-vous que les chemins stockés sont relatifs à la racine du site
                    $imageUploads[] = str_replace(BASE_URL, '', $obj['src']);
                }
            }
            $textContent = trim($textContent); // Nettoyer les espaces en début/fin

            // Enregistrer la plaque personnalisée dans la BDD et obtenir son ID
            $productModel = new Product();
            $customizationId = $productModel->saveCustomPlaque([
                'format' => $format,
                'fixation' => $fixation,
                'background_image' => $background,
                'text_content' => $textContent, // Correctement rempli à partir de fabricObjectsData
                'image_uploads' => json_encode($imageUploads), // Encodé en JSON pour le stockage
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