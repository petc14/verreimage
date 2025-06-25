<?php
/**
 * Page Panier
 *
 * Affiche le contenu du panier de l'utilisateur.
 * Permet de voir les articles, de les supprimer et de procéder à la commande.
 * Le contenu dynamique ($cart_items, $cart_total, $cart_count) est passé par le contrôleur.
 */
?>

<style>
    /* Styles spécifiques pour la page panier */
    .page-title-section {
        text-align: center;
        padding: 60px 20px;
        background-color: var(--light-gray);
    }
    .page-title-section h1 {
        font-size: 2.8em;
    }

    .cart-layout {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        align-items: flex-start;
    }
    .cart-items-panel {
        flex: 3;
        min-width: 400px;
    }
    .cart-summary-panel {
        flex: 2;
        min-width: 300px;
        background-color: var(--white-color);
        padding: 30px;
        border-radius: 8px;
        box-shadow: var(--box-shadow);
        position: sticky;
        top: 120px; /* Espace pour le header */
    }
    .cart-item {
        display: flex;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .cart-item-image img {
        width: 120px;
        height: 90px;
        object-fit: cover;
        border-radius: 4px;
    }
    .cart-item-details {
        flex-grow: 1;
    }
    .cart-item-details h3 {
        font-size: 1.2em;
        margin: 0 0 10px 0;
    }
    .cart-item-details p {
        font-size: 0.9em;
        color: #555;
        margin: 0;
    }
    .cart-item-actions {
        text-align: right;
    }
    .cart-item-actions .price {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 15px;
    }
    .remove-btn {
        color: #dc3545;
        text-decoration: none;
        font-size: 0.9em;
    }
    .remove-btn:hover {
        text-decoration: underline;
    }
    .empty-cart-message {
        text-align: center;
        padding: 50px;
        background-color: var(--white-color);
        border-radius: 8px;
        box-shadow: var(--box-shadow);
    }
</style>

<div class="page-title-section">
    <h1>Mon Panier</h1>
</div>

<div class="container">
    <?php if ($cart_count === 0): ?>
        <div class="empty-cart-message">
            <h2>Votre panier est vide.</h2>
            <p>Vous n'avez pas encore ajouté de création à votre panier.</p>
            <a href="<?php echo BASE_URL; ?>boutique" class="btn btn-primary" style="margin-top: 20px;">Découvrir nos modèles</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <div class="cart-items-panel">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-image">
                            <img src="<?php echo $item['details']['thumbnail'] ?? BASE_URL . 'assets/images/placeholder-plaque.jpg'; ?>" alt="Aperçu de la plaque personnalisée">
                        </div>
                        <div class="cart-item-details">
                            <h3>Plaque Personnalisée</h3>
                            <p><strong>Format:</strong> <?php echo htmlspecialchars($item['details']['format'] ?? 'N/A'); ?></p>
                            <p><strong>Fixation:</strong> <?php echo htmlspecialchars($item['details']['fixation'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="cart-item-actions">
                            <div class="price"><?php echo format_price($item['price']); ?></div>
                            <a href="<?php echo BASE_URL; ?>panier/remove?id=<?php echo $item['id']; ?>" class="remove-btn">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <aside class="cart-summary-panel">
                <h2>Récapitulatif</h2>
                <div class="summary-line">
                    <span>Sous-total (<?php echo $cart_count; ?> article<?php echo $cart_count > 1 ? 's' : ''; ?>)</span>
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
                <a href="<?php echo BASE_URL; ?>commande" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                    Valider ma commande
                </a>
            </aside>
        </div>
    <?php endif; ?>
</div>
