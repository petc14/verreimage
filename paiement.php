<?php
/**
 * Page de Paiement
 *
 * Dernière étape du processus de commande où l'utilisateur choisit
 * son mode de paiement et finalise sa commande.
 */

// Définition des informations SEO
$page_title = "Paiement de votre commande";
$page_description = "Choisissez votre mode de paiement sécurisé pour finaliser votre commande de plaque personnalisée sur Verre & Image.";

// Inclure l'initialisation et les fonctions
require_once 'config/init.php';
require_once 'src/cart.php';

// Vérifier si l'utilisateur vient bien de la page de commande
if (!isset($_SESSION['checkout_data'])) {
    header('Location: ' . BASE_URL . 'commande.php');
    exit();
}

// Récupérer les informations pour le récapitulatif
$checkout_data = $_SESSION['checkout_data'];
$shipping_address = $checkout_data['shipping_address'];
$cart_total = get_cart_total();

// Gérer la soumission (simulation de paiement)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    
    // Dans une application réelle, ici se trouverait l'intégration avec une API de paiement (Stripe, PayPal...).
    // Après un paiement réussi, on enregistrerait la commande en base de données.
    
    // Exemple de logique à implémenter :
    // 1. Créer la commande dans la table `orders`.
    // 2. Récupérer l'ID de la commande (`last_order_id`).
    // 3. Stocker cet ID en session pour l'afficher sur la page de confirmation.
    $_SESSION['last_order_id'] = 'VI-' . time(); // Génère un faux numéro de commande

    // Rediriger vers la page de confirmation
    header('Location: ' . BASE_URL . 'confirmation.php');
    exit();
}

// Inclure l'en-tête
require_once 'src/templates/header.php';
?>

<style>
    /* Styles spécifiques pour la page de paiement */
    .payment-layout {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        align-items: flex-start;
    }
    .payment-options-panel {
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
        top: 120px;
    }
    .info-recap {
        background-color: var(--light-gray);
        border: 1px solid var(--border-color);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }
    .info-recap p { margin: 0; }
    
    .payment-method {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 20px;
    }
    .payment-method h2 {
        font-size: 1.8em;
        margin-bottom: 20px;
    }
    .payment-form-placeholder {
        background-color: #f0f0f0;
        border: 1px dashed #ccc;
        padding: 40px;
        text-align: center;
        color: #777;
        border-radius: 4px;
    }
</style>

<div class="container payment-layout">
    
    <!-- Panneau de gauche : Options de paiement -->
    <div class="payment-options-panel">
        <div class="info-recap">
            <p><strong>Contact :</strong> <?php echo htmlspecialchars($checkout_data['shipping_address']['email'] ?? 'N/A'); ?></p>
            <p><strong>Livraison à :</strong> <?php echo htmlspecialchars($shipping_address['address'] . ', ' . $shipping_address['zip_code'] . ' ' . $shipping_address['city']); ?></p>
        </div>

        <div class="payment-method">
            <h2>Paiement</h2>
            <p>Toutes les transactions sont sécurisées et chiffrées.</p>

            <form id="payment-form" method="POST" action="paiement.php">
                <!-- Ici, on intégrerait les champs de l'API de paiement comme Stripe Elements -->
                <div class="payment-form-placeholder">
                    <p>Le formulaire de paiement sécurisé (Stripe, etc.) s'afficherait ici.</p>
                </div>

                <!-- Pour la simulation, nous utilisons un simple bouton -->
                <input type="hidden" name="payment_method" value="credit_card">
            </form>
        </div>
    </div>

    <!-- Panneau de droite : Récapitulatif de la commande -->
    <aside class="order-summary-panel">
        <h2>Récapitulatif</h2>
        
        <div class="summary-line">
            <span>Sous-total</span>
            <span><?php echo format_price($cart_total); ?></span>
        </div>
        <div class="summary-line">
            <span>Livraison</span>
            <span>Gratuite</span>
        </div>
        
        <hr style="margin: 20px 0;">
        
        <div class="summary-line" style="font-size: 1.2em;">
            <strong>Total</strong>
            <strong><?php echo format_price($cart_total); ?></strong>
        </div>
        
        <!-- Le bouton soumet le formulaire de gauche -->
        <button type="submit" form="payment-form" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
            Payer maintenant
        </button>
    </aside>

</div>

<?php
// Inclure le pied de page
require_once 'src/templates/footer.php';
?>
