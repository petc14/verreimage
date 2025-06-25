<?php
/**
 * Page "Mon Compte"
 *
 * Espace personnel de l'utilisateur où il peut voir ses commandes
 * et gérer ses informations.
 * Le contenu dynamique ($user, $orders) est passé par le contrôleur.
 */

// is_user_logged_in() et get_user_info() sont maintenant des méthodes statiques de Models\User
use Models\User;

// format_price() est une fonction globale définie dans init.php
?>

<style>
    /* Styles spécifiques pour la page "Mon Compte" */
    .account-layout {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        align-items: flex-start;
    }
    .account-nav {
        flex: 0 0 250px;
        background-color: var(--white-color);
        padding: 20px;
        border-radius: 8px;
        box-shadow: var(--box-shadow);
    }
    .account-nav ul {
        list-style: none;
        padding: 0;
    }
    .account-nav ul li a {
        display: block;
        padding: 12px 15px;
        text-decoration: none;
        color: var(--dark-blue);
        font-weight: bold;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    .account-nav ul li a:hover, .account-nav ul li a.active {
        background-color: var(--light-gray);
    }
    .account-content {
        flex: 1;
        min-width: 400px;
    }
    .content-panel {
        background-color: var(--white-color);
        padding: 40px;
        border-radius: 8px;
        box-shadow: var(--box-shadow);
    }
    .content-panel h1 {
        font-size: 2.2em;
        margin-bottom: 25px;
    }
    .order-history-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .order-history-table th, .order-history-table td {
        text-align: left;
        padding: 12px;
        border-bottom: 1px solid var(--border-color);
    }
    .order-history-table th {
        background-color: var(--light-gray);
    }
</style>

<div class="container">
    <div class="account-layout">

        <aside class="account-nav">
            <ul>
                <li><a href="<?php echo BASE_URL; ?>mon-compte" class="active">Tableau de bord</a></li>
                <li><a href="<?php echo BASE_URL; ?>mon-compte#mes-commandes">Mes commandes</a></li>
                <li><a href="<?php echo BASE_URL; ?>mon-compte#mes-adresses">Mes adresses</a></li>
                <li><a href="<?php echo BASE_URL; ?>mon-compte#details-compte">Détails du compte</a></li>
                <li><a href="<?php echo BASE_URL; ?>logout">Déconnexion</a></li>
            </ul>
        </aside>

        <main class="account-content">
            <div class="content-panel">
                <h1>Tableau de bord</h1>
                <p>Bonjour, <strong><?php echo htmlspecialchars($user['first_name'] ?? 'Client'); ?> <?php echo htmlspecialchars($user['last_name'] ?? ''); ?></strong> !</p>
                <p>Depuis votre tableau de bord, vous pouvez consulter vos commandes récentes, gérer vos adresses de livraison et de facturation, et modifier votre mot de passe et les détails de votre compte.</p>

                <h2 id="mes-commandes" style="margin-top: 40px; font-size: 1.8em;">Commandes récentes</h2>

                <?php if (empty($orders)): ?>
                    <p style="margin-top: 20px;">Vous n'avez passé aucune commande pour le moment.</p>
                    <a href="<?php echo BASE_URL; ?>boutique" class="btn btn-primary" style="margin-top:15px;">Parcourir la boutique</a>
                <?php else: ?>
                    <table class="order-history-table">
                        <thead>
                            <tr>
                                <th>Commande</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['order_reference']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($order['order_status'])); ?></td>
                                    <td><?php echo format_price($order['total_amount']); ?></td>
                                    <td><a href="<?php echo BASE_URL; ?>commande/details?ref=<?php echo htmlspecialchars($order['order_reference']); ?>" class="btn btn-secondary btn-sm">Voir</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <h2 id="mes-adresses" style="margin-top: 40px; font-size: 1.8em;">Mes adresses</h2>
                <p>Vos adresses de livraison et de facturation sauvegardées.</p>
                <a href="#" class="btn btn-secondary" style="margin-top:15px;">Gérer les adresses</a>


                <h2 id="details-compte" style="margin-top: 40px; font-size: 1.8em;">Détails du compte</h2>
                <p>Modifiez vos informations personnelles et votre mot de passe.</p>
                 <a href="#" class="btn btn-secondary" style="margin-top:15px;">Modifier les détails</a>

            </div>
        </main>

    </div>
</div>