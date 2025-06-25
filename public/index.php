<?php
// public/index.php

require_once dirname(__DIR__) . '/config/init.php';

use Controllers\HomeController;
use Controllers\BoutiqueController;
use Controllers\ConfigurateurController;
use Controllers\PanierController;
use Controllers\PaiementController;
use Controllers\UserController;
use Controllers\ContactController;

// Fonction pour rendre les vues
function render($view, $data = []) {
    extract($data); // Extrait les données pour les rendre disponibles dans la vue
    require VIEWS_PATH . '/layouts/header.php';
    require VIEWS_PATH . '/' . $view . '.php';
    require VIEWS_PATH . '/layouts/footer.php';
}

// Fonction pour rendre les vues du configurateur (sans le header/footer habituel)
function renderConfigurator($view, $data = []) {
    extract($data);
    require VIEWS_PATH . '/' . $view . '.php';
}

// Routage simple
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$requestUri = str_replace('verre-image-site/public', '', $requestUri); // Ajuster si BASE_URL est un sous-dossier
$requestUri = trim($requestUri, '/');

// Définir les routes
switch ($requestUri) {
    case '':
    case 'accueil':
        (new HomeController())->index();
        break;
    case 'boutique':
        (new BoutiqueController())->index();
        break;
    case 'presentation':
        (new HomeController())->presentation(); // Assuming presentation is a static page handled by HomeController
        break;
    case 'realisations':
        (new HomeController())->realisations(); // Assuming realisations is a static page handled by HomeController
        break;
    case 'mentions-legales':
        (new HomeController())->mentionsLegales(); // Assuming mentions-legales is a static page handled by HomeController
        break;
    case 'configurateur':
        (new ConfigurateurController())->index();
        break;
    case 'panier':
        (new PanierController())->index();
        break;
    case 'panier/add':
        (new PanierController())->addToCart();
        break;
    case 'panier/remove':
        (new PanierController())->removeFromCart();
        break;
    case 'commande':
        (new PaiementController())->checkout();
        break;
    case 'paiement':
        (new PaiementController())->payment();
        break;
    case 'confirmation':
        (new PaiementController())->confirmation();
        break;
    case 'contact':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new ContactController())->send();
        } else {
            (new ContactController())->index();
        }
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new UserController())->login();
        } else {
            (new UserController())->showLogin();
        }
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new UserController())->register();
        }
        break;
    case 'mon-compte':
        (new UserController())->myAccount();
        break;
    case 'logout':
        (new UserController())->logout();
        break;
    default:
        http_response_code(404);
        echo "404 - Page non trouvée"; // Ou rediriger vers une page d'erreur 404
        break;
}
?>