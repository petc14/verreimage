<?php
/**
 * Page de Commande (Checkout)
 *
 * Cette page permet à l'utilisateur de renseigner ses adresses de livraison et de facturation.
 * Elle gère également la distinction entre un utilisateur connecté et un invité.
 * Le contenu dynamique ($cart_items, $cart_total, $shipping_*, $email) est passé par le contrôleur.
 */
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

    <div class="checkout-form-panel">
        <div class="form-section">
            <h2>Adresse de Livraison</h2>
            <form id="checkout-form" method="POST" action="<?php echo BASE_URL; ?>paiement">
                <div class="form-row">
                    <div class="form-group">
                        <label for="shipping_firstname">Prénom</label>
                        <input type="text" id="shipping_firstname" name="shipping_firstname" value="<?php echo htmlspecialchars($shipping_firstname ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_lastname">Nom</label>
                        <input type="text" id="shipping_lastname" name="shipping_lastname" value="<?php echo htmlspecialchars($shipping_lastname ?? ''); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="shipping_address">Adresse</label>
                    <input type="text" id="shipping_address" name="shipping_address" value="<?php echo htmlspecialchars($shipping_address ?? ''); ?>" placeholder="Numéro et nom de rue" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="shipping_zip">Code Postal</label>
                        <input type="text" id="shipping_zip" name="shipping_zip" value="<?php echo htmlspecialchars($shipping_zip_code ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_city">Ville</label>
                        <input type="text" id="shipping_city" name="shipping_city" value="<?php echo htmlspecialchars($shipping_city ?? ''); ?>" required>
                    </div>
                </div>
                 <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" placeholder="Pour le suivi de votre commande" required>
                </div>
            </form>
        </div>

        <div class="form-section">
            <h2>Adresse de Facturation</h2>
             <div class="form-group">
                <input type="checkbox" id="use_shipping_for_billing" name="use_shipping_for_billing" form="checkout-form" checked>
                <label for="use_shipping_for_billing">Utiliser la même adresse que pour la livraison</label>
            </div>
            <div id="billing-address-form" style="display: none;">
                 <div class="form-row">
                    <div class="form-group">
                        <label for="billing_firstname">Prénom</label>
                        <input type="text" id="billing_firstname" name="billing_firstname" form="checkout-form">
                    </div>
                    <div class="form-group">
                        <label for="billing_lastname">Nom</label>
                        <input type="text" id="billing_lastname" name="billing_lastname" form="checkout-form">
                    </div>
                </div>
                <div class="form-group">
                    <label for="billing_address">Adresse</label>
                    <input type="text" id="billing_address" name="billing_address" form="checkout-form" placeholder="Numéro et nom de rue">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="billing_zip">Code Postal</label>
                        <input type="text" id="billing_zip" name="billing_zip" form="checkout-form">
                    </div>
                    <div class="form-group">
                        <label for="billing_city">Ville</label>
                        <input type="text" id="billing_city" name="billing_city" form="checkout-form">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <aside class="order-summary-panel">
        <h2>Récapitulatif</h2>
        <?php foreach($cart_items as $item): ?>
            <div class="summary-line">
                <span>Plaque personnalisée</span>
                <span><?php echo format_price($item['price']); ?></span>
            </div>
        <?php endforeach; ?>

        <hr style="margin: 20px 0;">

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

        <button type="submit" form="checkout-form" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
            Procéder au paiement
        </button>
    </aside>

</div>

<script>
    // Script pour afficher/masquer le formulaire de facturation
    document.addEventListener('DOMContentLoaded', function() {
        const billingCheckbox = document.getElementById('use_shipping_for_billing');
        const billingForm = document.getElementById('billing-address-form');
        const billingFormInputs = billingForm.querySelectorAll('input');

        function toggleBillingForm() {
            if (billingCheckbox.checked) {
                billingForm.style.display = 'none';
                billingFormInputs.forEach(input => input.removeAttribute('required'));
            } else {
                billingForm.style.display = 'block';
                billingFormInputs.forEach(input => input.setAttribute('required', 'required'));
            }
        }

        billingCheckbox.addEventListener('change', toggleBillingForm);

        // Appel initial pour définir l'état correct au chargement de la page
        toggleBillingForm();
    });
</script>
