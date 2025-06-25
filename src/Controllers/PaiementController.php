<?php
// src/Controllers/PaiementController.php

namespace Controllers;

use Models\Cart;
use Models\Order;
use Models\User;

class PaiementController {
    public function checkout() {
        if (Cart::getCartItemsCount() === 0) {
            header('Location: ' . BASE_URL . 'panier');
            exit();
        }

        // Pré-remplir les champs si l'utilisateur est connecté
        $user = User::getUserInfo();
        $checkoutData = $_SESSION['checkout_data'] ?? [];

        // Si des données de commande précédentes existent en session, les utiliser
        if (!empty($checkoutData['shipping_address'])) {
            $shipping_firstname = $checkoutData['shipping_address']['first_name'];
            $shipping_lastname = $checkoutData['shipping_address']['last_name'];
            $shipping_address = $checkoutData['shipping_address']['address'];
            $shipping_zip_code = $checkoutData['shipping_address']['zip_code'];
            $shipping_city = $checkoutData['shipping_address']['city'];
            $email = $checkoutData['shipping_address']['email'] ?? ''; // Assurez-vous que l'email est stocké avec l'adresse
        } elseif ($user) {
            // Utiliser les infos de l'utilisateur connecté si pas de données de session
            $shipping_firstname = $user['first_name'];
            $shipping_lastname = $user['last_name'];
            $email = $user['email'];
            // Les autres champs seraient récupérés de la BDD si vous aviez une table d'adresses
            $shipping_address = '';
            $shipping_zip_code = '';
            $shipping_city = '';
        } else {
            // Champs vides pour un invité
            $shipping_firstname = '';
            $shipping_lastname = '';
            $shipping_address = '';
            $shipping_zip_code = '';
            $shipping_city = '';
            $email = '';
        }

        $data = [
            'page_title' => "Finaliser ma Commande",
            'page_description' => "Renseignez vos informations de livraison et de facturation pour finaliser votre commande de plaque personnalisée.",
            'cart_items' => Cart::getCartContents(),
            'cart_total' => Cart::getCartTotal(),
            'shipping_firstname' => $shipping_firstname,
            'shipping_lastname' => $shipping_lastname,
            'shipping_address' => $shipping_address,
            'shipping_zip_code' => $shipping_zip_code,
            'shipping_city' => $shipping_city,
            'email' => $email,
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('commande', $data);
    }

    public function payment() {
        if (Cart::getCartItemsCount() === 0) {
            header('Location: ' . BASE_URL . 'panier');
            exit();
        }

        // Traitement de la soumission du formulaire de commande
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer et nettoyer les données du formulaire
            $shipping_address = [
                'first_name' => sanitize_input($_POST['shipping_firstname'] ?? ''),
                'last_name' => sanitize_input($_POST['shipping_lastname'] ?? ''),
                'address' => sanitize_input($_POST['shipping_address'] ?? ''),
                'zip_code' => sanitize_input($_POST['shipping_zip'] ?? ''),
                'city' => sanitize_input($_POST['shipping_city'] ?? ''),
                'email' => sanitize_input($_POST['email'] ?? '')
            ];

            $billing_address = [];
            if (isset($_POST['use_shipping_for_billing'])) {
                $billing_address = $shipping_address; // Utiliser la même adresse
            } else {
                $billing_address = [
                    'first_name' => sanitize_input($_POST['billing_firstname'] ?? ''),
                    'last_name' => sanitize_input($_POST['billing_lastname'] ?? ''),
                    'address' => sanitize_input($_POST['billing_address'] ?? ''),
                    'zip_code' => sanitize_input($_POST['billing_zip'] ?? ''),
                    'city' => sanitize_input($_POST['billing_city'] ?? ''),
                ];
            }

            // Stocker temporairement les données en session pour la page de paiement et confirmation
            $_SESSION['checkout_data'] = [
                'shipping_address' => $shipping_address,
                'billing_address' => $billing_address,
                'email' => $shipping_address['email'], // Assurez-vous que l'email est accessible ici
                'total_amount' => Cart::getCartTotal(),
            ];

            // Rediriger vers la page de paiement
            header('Location: ' . BASE_URL . 'paiement');
            exit();
        }

        // Logique pour l'affichage de la page de paiement
        $checkout_data = $_SESSION['checkout_data'] ?? null;
        if (!$checkout_data) {
            header('Location: ' . BASE_URL . 'commande'); // Si pas de données de checkout, rediriger
            exit();
        }

        $data = [
            'page_title' => "Paiement de votre commande",
            'page_description' => "Choisissez votre mode de paiement sécurisé pour finaliser votre commande de plaque personnalisée sur Verre & Image.",
            'checkout_data' => $checkout_data,
            'cart_total' => Cart::getCartTotal()
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('paiement', $data);
    }

    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checkoutData = $_SESSION['checkout_data'] ?? null;
            if (!$checkoutData) {
                header('Location: ' . BASE_URL . 'commande');
                exit();
            }

            $cartItems = Cart::getCartContents();
            if (empty($cartItems)) {
                header('Location: ' . BASE_URL . 'panier');
                exit();
            }

            // Simulation d'un paiement réussi
            $paymentMethod = sanitize_input($_POST['payment_method'] ?? 'simulated_card');
            $checkoutData['payment_method'] = $paymentMethod;

            $orderModel = new Order();
            $orderReference = $orderModel->createOrder($checkoutData, $cartItems);

            $_SESSION['last_order_reference'] = $orderReference;
            Cart::clearCart(); // Vider le panier après commande
            unset($_SESSION['checkout_data']); // Nettoyer les données de commande temporaires

            header('Location: ' . BASE_URL . 'confirmation');
            exit();
        }
        header('Location: ' . BASE_URL . 'paiement'); // Rediriger si accès direct ou méthode non POST
        exit();
    }


    public function confirmation() {
        // Dans une vraie application, on récupérerait la commande depuis la BDD via $_SESSION['last_order_id']
        // Pour cet exemple, on utilise la référence stockée et on simule si elle n'existe pas.
        $orderReference = $_SESSION['last_order_reference'] ?? 'VI-'.time();
        unset($_SESSION['last_order_reference']); // Nettoyer la session

        $orderModel = new Order();
        $orderDetails = $orderModel->getOrderDetails($orderReference);

        // Si la commande n'est pas trouvée (ex: accès direct à la page de confirmation)
        if (!$orderDetails) {
            // Simuler des données si la commande n'est pas trouvée pour éviter une page blanche
            $orderDetails = [
                'order_reference' => $orderReference,
                'total_amount' => 0.00, // Ou un total par défaut
                'shipping_firstname' => 'Client',
                'shipping_lastname' => 'Invité',
                'shipping_address' => '1 Rue Exemple',
                'shipping_zip_code' => '75000',
                'shipping_city' => 'Paris',
            ];
            // Optionnel: vous pourriez aussi rediriger vers l'accueil ou le panier
            // header('Location: ' . BASE_URL . 'index'); exit();
        }

        $data = [
            'page_title' => "Confirmation de votre commande",
            'page_description' => "Merci pour votre commande. Retrouvez ici le récapitulatif de votre achat de plaque personnalisée.",
            'order_details' => $orderDetails,
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('confirmation', $data);
    }
}