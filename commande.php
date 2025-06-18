<?php
/**
 * Page de Commande (Checkout)
 *
 * Cette page permet à l'utilisateur de renseigner ses adresses de livraison et de facturation.
 * Elle gère également la distinction entre un utilisateur connecté et un invité.
 */

// Définition des informations SEO pour cette page
$page_title = "Finaliser ma Commande";
$page_description = "Renseignez vos informations de livraison et de facturation pour finaliser votre commande de plaque personnalisée.";

// Inclure l'initialisation (session, BDD, fonctions de base)
require_once 'config/init.php';
// Inclure les fonctions de gestion du panier
require_once 'src/cart.php';

// Rediriger vers la page panier si le panier est vide
if (get_cart_items_count() === 0) {
    header('Location: ' . BASE_URL . 'page_panier.php');
    exit();
}

// Gérer la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ici, nous ajouterions la logique de validation des champs du formulaire.
    // Pour cet exemple, nous allons simplement stocker les informations en session.
    $_SESSION['checkout_data'] = [
        'shipping_address' => [
            'first_name' => sanitize_input($_POST['shipping_firstname']),
            'last_name' => sanitize_input($_POST['shipping_lastname']),
            'address' => sanitize_input($_POST['shipping_address']),
            'zip_code' => sanitize_input($_POST['shipping_zip']),
            'city' => sanitize_input($_POST['shipping_city']),
        ],
        'billing_address' => isset($_POST['use_shipping_for_billing']) ? 'same' : [
            'first_name' => sanitize_input($_POST['billing_firstname']),
            'last_name' => sanitize_input($_POST['billing_lastname']),
            'address' => sanitize_input($_POST['billing_address']),
            'zip_code' => sanitize_input($_POST['billing_zip']),
            'city' => sanitize_input($_POST['billing_city']),
        ],
    ];

    // Rediriger vers la page de paiement (que nous créerons ensuite)
    header('Location: ' . BASE_URL . 'paiement.php');
    exit();
}


// Inclure l'en-tête du site
require_once 'src/templates/header.php';
?>

<style>
    /* Styles spécifiques pour la page de commande */
    .checkout-layout {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        align-items: flex-start;
    }
    .checkout-form-panel {
        flex: 3;
        min-width: 400px;
    }
    .order-summary-panel {
        flex: 2;
        min-width: 300px;
        background-color: var(--white-color);
        padding: 30px;
        border-radius: 8px;
        box-shadow: var(--box-shadow);
        position: sticky;
        top: 120px; /* Espace pour le header */
    }
    .form-section {
        margin-bottom: 30px;
    }
    .form-section h2 {
        font-size: 1.8em;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 10px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .form-group input[type="text"],
    .form-group input[type="email"] {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
    }
    .form-row {
        display: flex;
        gap: 20px;
    }
    .form-row .form-group {
        flex: 1;
    }
</style>

<div class="container checkout-layout">
    
    <!-- Panneau de gauche : Formulaires -->
    <div class="checkout-form-panel">
        <div class="form-section">
            <h2>Adresse de Livraison</h2>
            <form id="checkout-form" method="POST" action="commande.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="shipping_firstname">Prénom</label>
                        <input type="text" id="shipping_firstname" name="shipping_firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_lastname">Nom</label>
                        <input type="text" id="shipping_lastname" name="shipping_lastname" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="shipping_address">Adresse</label>
                    <input type="text" id="shipping_address" name="shipping_address" placeholder="Numéro et nom de rue" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="shipping_zip">Code Postal</label>
                        <input type="text" id="shipping_zip" name="shipping_zip" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_city">Ville</label>
                        <input type="text" id="shipping_city" name="shipping_city" required>
                    </div>
                </div>
                 <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" placeholder="Pour le suivi de votre commande" required>
                </div>
            </form>
        </div>
        
        <div class="form-section">
            <h2>Adresse de Facturation</h2>
             <div class="form-group">
                <input type="checkbox" id="use_shipping_for_billing" name="use_shipping_for_billing" checked>
                <label for="use_shipping_for_billing">Utiliser la même adresse que pour la livraison</label>
            </div>
            <!-- Le formulaire de facturation sera affiché par JS si la case est décochée -->
            <div id="billing-address-form" style="display: none;">
                 <div class="form-row">
                    <div class="form-group">
                        <label for="billing_firstname">Prénom</label>
                        <input type="text" id="billing_firstname" name="billing_firstname">
                    </div>
                    <div class="form-group">
                        <label for="billing_lastname">Nom</label>
                        <input type="text" id="billing_lastname" name="billing_lastname">
                    </div>
                </div>
                <!-- ... autres champs pour l'adresse de facturation ... -->
            </div>
        </div>

    </div>

    <!-- Panneau de droite : Récapitulatif de la commande -->
    <aside class="order-summary-panel">
        <h2>Récapitulatif</h2>
        <!-- Les articles du panier seront affichés ici -->
        <?php foreach(get_cart_contents() as $item): ?>
            <div class="summary-line">
                <span>Plaque personnalisée</span>
                <span><?php echo format_price($item['price']); ?></span>
            </div>
        <?php endforeach; ?>
        
        <hr style="margin: 20px 0;">
        
        <div class="summary-line">
            <span>Sous-total</span>
            <span><?php echo format_price(get_cart_total()); ?></span>
        </div>
        <div class="summary-line">
            <span>Livraison</span>
            <span>Gratuite</span>
        </div>
        
        <hr style="margin: 20px 0;">
        
        <div class="summary-line" style="font-size: 1.2em;">
            <strong>Total</strong>
            <strong><?php echo format_price(get_cart_total()); ?></strong>
        </div>
        
        <!-- Le bouton soumet le formulaire de gauche -->
        <button type="submit" form="checkout-form" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
            Procéder au paiement
        </button>
    </aside>

</div>

<script>
    // Script pour afficher/masquer le formulaire de facturation
    const billingCheckbox = document.getElementById('use_shipping_for_billing');
    const billingForm = document.getElementById('billing-address-form');

    billingCheckbox.addEventListener('change', function() {
        billingForm.style.display = this.checked ? 'none' : 'block';
    });
</script>

<?php
// Inclure le pied de page
require_once 'src/templates/footer.php';
?>
