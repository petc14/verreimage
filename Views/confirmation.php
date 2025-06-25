<?php
/**
 * Page de Confirmation de Commande
 *
 * Cette page est affichée après un paiement réussi.
 * Elle remercie le client, affiche un résumé de la commande et
 * les informations sur les prochaines étapes.
 * Le contenu dynamique ($order_details) est passé par le contrôleur.
 */
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
            <h2>Récapitulatif de la commande n°<?php echo htmlspecialchars($order_details['order_reference'] ?? 'N/A'); ?></h2>
            <div class="summary-line">
                <span>Total payé :</span>
                <strong><?php echo format_price($order_details['total_amount'] ?? 0.00); ?></strong>
            </div>
            <div class="summary-line">
                <span>Adresse de livraison :</span>
                <span>
                    <?php
                        $address = $order_details; // Les détails de livraison sont directement dans $order_details
                        echo htmlspecialchars($address['shipping_firstname'] . ' ' . $address['shipping_lastname']) . '<br>';
                        echo htmlspecialchars($address['shipping_address']) . '<br>';
                        echo htmlspecialchars($address['shipping_zip_code'] . ' ' . $address['shipping_city']);
                    ?>
                </span>
            </div>
            <?php if (!empty($order_items)): ?>
            <h3 style="margin-top: 20px; border-top: 1px dashed var(--border-color); padding-top: 15px;">Articles commandés :</h3>
            <ul>
                <?php foreach ($order_items as $item): ?>
                    <li>Plaque personnalisée (Format: <?php echo htmlspecialchars($item['format']); ?>, Fixation: <?php echo htmlspecialchars($item['fixation']); ?>) - <?php echo format_price($item['price_at_purchase']); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>

        <div class="next-steps">
            <h3>Prochaines étapes</h3>
            <p>1. Notre équipe de graphistes va vérifier votre création (si vous avez choisi cette option).</p>
            <p>2. La fabrication de votre plaque débutera dans notre atelier de Honfleur.</p>
            <p>3. Vous recevrez un email dès l'expédition de votre commande, avec un numéro de suivi.</p>
        </div>

        <a href="<?php echo BASE_URL; ?>boutique" class="btn btn-primary" style="margin-top: 30px;">Continuer mes achats</a>
    </div>
</div>
