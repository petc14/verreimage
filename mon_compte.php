<?php
/**
 * Page "Mon Compte"
 *
 * Espace personnel de l'utilisateur où il peut voir ses commandes
 * et gérer ses informations.
 */

// Inclure l'initialisation pour accéder aux sessions et fonctions
require_once 'config/init.php';
require_once 'src/functions.php';

// Vérifier si l'utilisateur est connecté. Si non, le rediriger vers la page de connexion.
if (!is_user_logged_in()) {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

// Gérer la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . BASE_URL . 'index.php');
    exit();
}

// Récupérer les informations de l'utilisateur depuis la session
$user = get_user_info();

// Récupérer l'historique des commandes de l'utilisateur depuis la base de données
// (Cette partie sera plus complète lorsque nous aurons la logique de commande)
$orders = []; // Initialisation en tant que tableau vide pour l'instant
// Exemple de requête que nous utiliserons plus tard :
// $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
// $stmt->execute([$user['id']]);
// $orders = $stmt->fetchAll();


// Définition des informations SEO
$page_title = "Mon Compte";
$page_description = "Gérez vos informations personnelles et consultez l'historique de vos commandes sur Verre & Image.";

// Inclure l'en-tête
require_once 'src/templates/header.php';
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

        <!-- Colonne de navigation du compte -->
        <aside class="account-nav">
            <ul>
                <li><a href="#" class="active">Tableau de bord</a></li>
                <li><a href="#">Mes commandes</a></li>
                <li><a href="#">Mes adresses</a></li>
                <li><a href="#">Détails du compte</a></li>
                <li><a href="?logout=1">Déconnexion</a></li>
            </ul>
        </aside>

        <!-- Contenu principal du compte -->
        <main class="account-content">
            <div class="content-panel">
                <h1>Tableau de bord</h1>
                <p>Bonjour, <strong><?php echo htmlspecialchars($user['first_name'] ?? 'Client'); ?></strong> !</p>
                <p>Depuis votre tableau de bord, vous pouvez consulter vos commandes récentes, gérer vos adresses de livraison et de facturation, et modifier votre mot de passe et les détails de votre compte.</p>
                
                <h2 style="margin-top: 40px; font-size: 1.8em;">Commandes récentes</h2>
                
                <?php if (empty($orders)): ?>
                    <p style="margin-top: 20px;">Vous n'avez passé aucune commande pour le moment.</p>
                    <a href="<?php echo BASE_URL; ?>boutique.php" class="btn btn-primary" style="margin-top:15px;">Parcourir la boutique</a>
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
                                    <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($order['order_status'])); ?></td>
                                    <td><?php echo format_price($order['total_amount']); ?></td>
                                    <td><a href="#" class="btn btn-secondary btn-sm">Voir</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

            </div>
        </main>

    </div>
</div>

<?php
// Inclure le pied de page
require_once 'src/templates/footer.php';
?>
