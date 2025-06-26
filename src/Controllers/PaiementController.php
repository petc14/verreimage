<?php
// src/Controllers/PaiementController.php

namespace Controllers;

use Models\Cart;
use Models\Order;
use Models\User;

class PaiementController {
    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['login_errors']['auth'] = 'Vous devez être connecté pour procéder au paiement.';
            header('Location: ' . BASE_URL . 'login');
            exit();
        }

        // Vérifier si le panier n'est pas vide
        if (Cart::getCartItemsCount() === 0) {
            header('Location: ' . BASE_URL . 'panier'); // Rediriger vers le panier s'il est vide
            exit();
        }

        $user = (new User())->findById($_SESSION['user_id']);
        $cartItems = Cart::getCartContents();
        $cartTotal = Cart::getCartTotal();

        $data = [
            'page_title' => "Paiement",
            'page_description' => "Finalisez votre commande sur Verre & Image.",
            'user' => $user,
            'cart_items' => $cartItems,
            'cart_total' => $cartTotal,
            'payment_errors' => $_SESSION['payment_errors'] ?? [],
            'old_inputs' => $_SESSION['old_inputs'] ?? []
        ];

        // Nettoyer les messages d'erreur et anciennes entrées après affichage
        unset($_SESSION['payment_errors']);
        unset($_SESSION['old_inputs']);

        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('paiement', $data);
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentErrors = [];
            $oldInputs = $_POST; // Pour pré-remplir le formulaire en cas d'erreur

            // 1. Validation des champs du formulaire (adresse, contact)
            // ************ TRÈS IMPORTANT : RENFORCER CES VALIDATIONS ************
            // Les validations ci-dessous sont des exemples. Adaptez-les précisément à vos besoins.
            $firstName = sanitize_input($_POST['first_name'] ?? '');
            $lastName = sanitize_input($_POST['last_name'] ?? '');
            $email = sanitize_input($_POST['email'] ?? '');
            $phone = sanitize_input($_POST['phone'] ?? '');
            $address = sanitize_input($_POST['address'] ?? '');
            $city = sanitize_input($_POST['city'] ?? '');
            $zipCode = sanitize_input($_POST['zip_code'] ?? '');
            $country = sanitize_input($_POST['country'] ?? ''); // Assurez-vous que ce champ existe dans le formulaire

            if (empty($firstName)) $paymentErrors['first_name'] = 'Le prénom est requis.';
            if (empty($lastName)) $paymentErrors['last_name'] = 'Le nom est requis.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $paymentErrors['email'] = 'L\'adresse email n\'est pas valide.';
            // Exemple de validation de numéro de téléphone pour la France (ajuster la regex si besoin)
            if (!preg_match('/^(0|\+33)[1-9]([-. ]?\d{2}){4}$/', $phone)) $paymentErrors['phone'] = 'Le numéro de téléphone n\'est pas valide.';
            if (empty($address)) $paymentErrors['address'] = 'L\'adresse est requise.';
            if (empty($city)) $paymentErrors['city'] = 'La ville est requise.';
            if (!preg_match('/^\d{5}$/', $zipCode)) $paymentErrors['zip_code'] = 'Le code postal n\'est pas valide (5 chiffres attendus).';
            if (empty($country)) $paymentErrors['country'] = 'Le pays est requis.';


            // Si des erreurs de validation existent, rediriger
            if (!empty($paymentErrors)) {
                $_SESSION['payment_errors'] = $paymentErrors;
                $_SESSION['old_inputs'] = $oldInputs;
                header('Location: ' . BASE_URL . 'paiement');
                exit();
            }

            // ************ POINT CRITIQUE DE SÉCURITÉ : GESTION DU PAIEMENT ************
            // NE PAS RÉCUPÉRER LES DONNÉES DE CARTE DIRECTEMENT ICI (cardNumber, cardExpMonth, cardExpYear, cardCvc)
            // Ces données DOIVENT être traitées par le frontend du SDK de la passerelle de paiement
            // et vous recevrez un TOKEN de paiement ou une confirmation directe.

            // Exemple pour un token de paiement (comme Stripe)
            $paymentToken = sanitize_input($_POST['payment_token'] ?? ''); // Récupérer le token généré par le JS de la passerelle

            if (empty($paymentToken)) {
                $paymentErrors['payment'] = 'Erreur de paiement: Le token de paiement est manquant.';
                $_SESSION['payment_errors'] = $paymentErrors;
                $_SESSION['old_inputs'] = $oldInputs;
                header('Location: ' . BASE_URL . 'paiement');
                exit();
            }

            // 2. Traitement du paiement via une PASSERELLE DE PAIEMENT (EX: Stripe, PayPal, etc.)
            // Ceci est une SIMULATION. Vous devrez intégrer le SDK de votre passerelle ici.
            $paymentSuccess = false;
            $transactionId = null;

            try {
                // EXEMPLE D'INTÉGRATION STRIPE (nécessite l'installation du SDK Stripe via Composer)
                /*
                require_once ROOT_PATH . '/vendor/autoload.php'; // Assurez-vous que Composer est utilisé
                \Stripe\Stripe::setApiKey('sk_test_VOTRE_CLE_SECRETE_STRIPE'); // À remplacer par votre clé secrète Stripe

                $charge = \Stripe\Charge::create([
                    'amount' => Cart::getCartTotal() * 100, // Les montants sont en centimes/cents pour Stripe
                    'currency' => 'eur',
                    'description' => 'Commande Verre & Image',
                    'source' => $paymentToken, // Le token de paiement provenant du frontend
                    'receipt_email' => $email, // Pour l'envoi de la facture par Stripe
                ]);

                $transactionId = $charge->id;
                $paymentSuccess = $charge->paid;
                */

                // --- Simulation de paiement réussi (À SUPPRIMER EN PRODUCTION !!!) ---
                $paymentSuccess = true;
                $transactionId = 'TRANS_' . uniqid();
                // ---------------------------------------------------------------------

            } catch (\Exception $e) { // Attrapez les exceptions spécifiques à votre passerelle de paiement
                $paymentErrors['payment'] = 'Le paiement a échoué : ' . $e->getMessage();
                error_log("Erreur de paiement: " . $e->getMessage()); // Journaliser l'erreur serveur
                $_SESSION['payment_errors'] = $paymentErrors;
                $_SESSION['old_inputs'] = $oldInputs;
                header('Location: ' . BASE_URL . 'paiement');
                exit();
            }

            if ($paymentSuccess) {
                // 3. Création de la commande si le paiement est réussi
                $orderModel = new Order();
                $orderId = $orderModel->createOrder(
                    $_SESSION['user_id'],
                    Cart::getCartContents(),
                    Cart::getCartTotal(),
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'phone' => $phone,
                        'address' => $address,
                        'city' => $city,
                        'zip_code' => $zipCode,
                        'country' => $country,
                        'transaction_id' => $transactionId // Enregistrer l'ID de transaction de la passerelle
                    ]
                );

                if ($orderId) {
                    Cart::clearCart(); // Vider le panier après commande
                    $_SESSION['last_order_id'] = $orderId; // Stocker l'ID pour la page de confirmation
                    header('Location: ' . BASE_URL . 'confirmation');
                    exit();
                } else {
                    $paymentErrors['order'] = 'Erreur lors de la création de la commande après paiement.';
                    // IMPORTANT: Si la commande n'a pas pu être créée, vous pourriez envisager de
                    // tenter d'annuler le paiement via l'API de la passerelle si c'est possible (remboursement).
                    error_log("Erreur critique: Paiement réussi ($transactionId) mais commande non créée pour l'utilisateur {$_SESSION['user_id']}.");
                }
            } else {
                $paymentErrors['payment'] = 'Le paiement n\'a pas pu être finalisé. Veuillez vérifier vos informations de paiement.';
            }

            // Si des erreurs persistent après le processus de paiement
            if (!empty($paymentErrors)) {
                $_SESSION['payment_errors'] = $paymentErrors;
                $_SESSION['old_inputs'] = $oldInputs;
                header('Location: ' . BASE_URL . 'paiement');
                exit();
            }
        }
        header('Location: ' . BASE_URL . 'paiement'); // Rediriger si accès direct ou méthode non POST
        exit();
    }

    public function success() {
        // Pour s'assurer que la page de confirmation n'est accessible qu'après une commande réussie
        if (!isset($_SESSION['last_order_id'])) {
            header('Location: ' . BASE_URL); // Rediriger vers l'accueil si pas d'ID de commande en session
            exit();
        }
        $orderId = $_SESSION['last_order_id'];
        unset($_SESSION['last_order_id']); // Nettoyer l'ID après utilisation pour éviter le rechargement

        $orderModel = new Order();
        $orderDetails = $orderModel->getOrderDetails($orderId);
        $orderItems = $orderModel->getOrderItems($orderId);

        if (!$orderDetails) {
            // Gérer le cas où l'ID de commande est invalide ou non trouvé
            header('Location: ' . BASE_URL);
            exit();
        }

        $data = [
            'page_title' => "Confirmation de Commande",
            'page_description' => "Votre commande a été confirmée sur Verre & Image.",
            'order_details' => $orderDetails,
            'order_items' => $orderItems
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('confirmation', $data);
    }
}