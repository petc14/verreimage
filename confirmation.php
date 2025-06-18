<?php
/**
 * Page de Confirmation de Commande
 *
 * Cette page est affichée après un paiement réussi.
 * Elle remercie le client, affiche un résumé de la commande et
 * les informations sur les prochaines étapes.
 */

// Définition des informations SEO
$page_title = "Confirmation de votre commande";
$page_description = "Merci pour votre commande. Retrouvez ici le récapitulatif de votre achat de plaque personnalisée.";

// Inclure l'initialisation et les fonctions
require_once 'config/init.php';
require_once 'src/cart.php';

// Simuler la récupération des informations de la dernière commande depuis la session
// Dans une application réelle, ces informations viendraient de la base de données après le paiement.
$last_order_id = $_SESSION['last_order_id'] ?? 'VI-12345'; // Exemple
$checkout_data = $_SESSION['checkout_data'] ?? null;
$cart_total = get_cart_total();

// Si aucune information de commande n'est trouvée, rediriger vers l'accueil
if (!$checkout_data || $cart_total === 0.0) {
    // header('Location: ' . BASE_URL . 'index.php');
    // exit();
    // Pour l'exemple, nous créons des données factices si la session est vide
    $checkout_data = [
        'shipping_address' => [
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'address' => '123 Rue de la République',
            'zip_code' => '75001',
            'city' => 'Paris',
        ]
    ];
}


// Vider le panier après la confirmation de commande
clear_cart();


// Inclure l'en-tête
require_once 'src/templates/header.php';
?>

<style>
    /* Styles spécifiques pour la page de confirmation */
    .confirmation-container {
        max-width: 800px;
        margin: 40px auto;
        background-color: var(--white-color);
        padding: 40px;
        border-radius: 8px;
        box-shadow: var(--box-shadow);
        text-align: center;
    }
    .confirmation-icon {
        color: var(--success-green);
        font-size: 4em;
        margin-bottom: 20px;
    }
    .confirmation-container h1 {
        font-size: 2.2em;
        margin-bottom: 15px;
    }
    .order-summary {
        text-align: left;
        margin-top: 30px;
        padding: 20px;
        background-color: var(--light-gray);
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }
    .order-summary h2 {
        font-size: 1.5em;
        margin-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 10px;
    }
    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .next-steps {
        margin-top: 30px;
        text-align: left;
    }
</style>

<div class="container">
    <div class="confirmation-container">
        <div class="confirmation-icon">&#10004;</div>
        <h1>Merci pour votre commande !</h1>
        <p>Votre commande a bien été enregistrée. Un email de confirmation vous a été envoyé.</p>

        <div class="order-summary">
            <h2>Récapitulatif de la commande n°<?php echo htmlspecialchars($last_order_id); ?></h2>
            <div class="summary-line">
                <span>Total payé :</span>
                <strong><?php echo format_price($cart_total); ?></strong>
            </div>
            <div class="summary-line">
                <span>Adresse de livraison :</span>
                <span>
                    <?php 
                        $address = $checkout_data['shipping_address'];
                        echo htmlspecialchars($address['first_name'] . ' ' . $address['last_name']) . '<br>';
                        echo htmlspecialchars($address['address']) . '<br>';
                        echo htmlspecialchars($address['zip_code'] . ' ' . $address['city']);
                    ?>
                </span>
            </div>
        </div>

        <div class="next-steps">
            <h3>Prochaines étapes</h3>
            <p>1. Notre équipe de graphistes va vérifier votre création (si vous avez choisi cette option).</p>
            <p>2. La fabrication de votre plaque débutera dans notre atelier de Honfleur.</p>
            <p>3. Vous recevrez un email dès l'expédition de votre commande, avec un numéro de suivi.</p>
        </div>

        <a href="<?php echo BASE_URL; ?>boutique.php" class="btn btn-primary" style="margin-top: 30px;">Continuer mes achats</a>
    </div>
</div>

<?php
// Inclure le pied de page
require_once 'src/templates/footer.php';
?>
