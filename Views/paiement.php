<?php
/**
 * Page de Paiement
 *
 * Dernière étape du processus de commande où l'utilisateur choisit
 * son mode de paiement et finalise sa commande.
 * Le contenu dynamique ($checkout_data, $cart_total) est passé par le contrôleur.
 */
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
        background-color: var(--white-color); /* Ajouté pour consistance */
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

    <div class="payment-options-panel">
        <div class="info-recap">
            <p><strong>Contact :</strong> <?php echo htmlspecialchars($checkout_data['email'] ?? 'N/A'); ?></p>
            <p><strong>Livraison à :</strong> <?php echo htmlspecialchars($checkout_data['shipping_address']['address'] . ', ' . $checkout_data['shipping_address']['zip_code'] . ' ' . $checkout_data['shipping_address']['city']); ?></p>
        </div>

        <div class="payment-method">
            <h2>Paiement</h2>
            <p>Toutes les transactions sont sécurisées et chiffrées.</p>

            <form id="payment-form" method="POST" action="<?php echo BASE_URL; ?>paiement/process">
                <div class="payment-form-placeholder">
                    <p>Le formulaire de paiement sécurisé (Stripe, etc.) s'afficherait ici.</p>
                </div>

                <input type="hidden" name="payment_method" value="credit_card">
            </form>
        </div>
    </div>

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

        <button type="submit" form="payment-form" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
            Payer maintenant
        </button>
    </aside>

</div>