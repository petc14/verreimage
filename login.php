<?php
/**
 * Page de Connexion et d'Inscription
 *
 * Permet aux utilisateurs de se connecter à leur compte ou de créer un nouveau compte.
 * Gère la soumission des deux formulaires.
 */

// Définition des informations SEO
$page_title = "Connexion / Inscription";
$page_description = "Connectez-vous à votre compte Verre & Image pour suivre vos commandes ou créez un compte pour une expérience d'achat simplifiée.";

// Inclure l'initialisation et les fonctions
require_once 'config/init.php';

// Si l'utilisateur est déjà connecté, le rediriger vers la page "mon compte"
if (is_user_logged_in()) {
    header('Location: ' . BASE_URL . 'mon_compte.php');
    exit();
}

$login_errors = [];
$register_errors = [];

// Gérer la soumission des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- Traitement du formulaire de connexion ---
    if (isset($_POST['login'])) {
        $email = sanitize_input($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $login_errors[] = "Tous les champs sont obligatoires.";
        } else {
            // Rechercher l'utilisateur dans la base de données
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie : enregistrer les infos en session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_first_name'] = $user['first_name'];
                
                // Rediriger vers la page "mon compte"
                header('Location: ' . BASE_URL . 'mon_compte.php');
                exit();
            } else {
                $login_errors[] = "Email ou mot de passe incorrect.";
            }
        }
    }

    // --- Traitement du formulaire d'inscription ---
    if (isset($_POST['register'])) {
        // ... Logique d'inscription à implémenter ...
        $register_errors[] = "La fonction d'inscription n'est pas encore activée.";
    }
}


// Inclure l'en-tête du site
require_once 'templates/header.php';
?>

<style>
    /* Styles spécifiques pour la page de connexion/inscription */
    .login-page-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 40px;
        align-items: flex-start;
        padding: 40px 0;
    }
    .form-panel {
        background-color: var(--white-color);
        padding: 40px;
        border-radius: 8px;
        box-shadow: var(--box-shadow);
    }
    .form-panel h2 {
        text-align: left;
        font-size: 2em;
        margin-bottom: 25px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
    }
    .error-list {
        list-style: none;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
    }
</style>

<div class="container">
    <div class="login-page-container">

        <!-- Panneau de Connexion -->
        <div class="form-panel">
            <h2>Connexion</h2>
            <p style="margin-bottom: 20px;">Déjà client ? Connectez-vous pour accéder à votre espace personnel.</p>
            
            <?php if (!empty($login_errors)): ?>
                <ul class="error-list">
                    <?php foreach ($login_errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="login_email">Adresse e-mail</label>
                    <input type="email" id="login_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="login_password">Mot de passe</label>
                    <input type="password" id="login_password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary" style="width: 100%;">Se connecter</button>
            </form>
        </div>

        <!-- Panneau d'Inscription -->
        <div class="form-panel" style="background-color: var(--light-gray);">
            <h2>Nouveau Client ?</h2>
            <p style="margin-bottom: 20px;">Créez un compte pour sauvegarder vos créations, suivre vos commandes et simplifier vos futurs achats.</p>

            <?php if (!empty($register_errors)): ?>
                 <ul class="error-list">
                    <?php foreach ($register_errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="POST" action="login.php">
                 <div class="form-group">
                    <label for="register_firstname">Prénom</label>
                    <input type="text" id="register_firstname" name="first_name" required>
                </div>
                 <div class="form-group">
                    <label for="register_lastname">Nom</label>
                    <input type="text" id="register_lastname" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="register_email">Adresse e-mail</label>
                    <input type="email" id="register_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="register_password">Mot de passe</label>
                    <input type="password" id="register_password" name="password" required>
                </div>
                <button type="submit" name="register" class="btn btn-secondary" style="width: 100%;">Créer mon compte</button>
            </form>
        </div>

    </div>
</div>

<?php
// Inclure le pied de page
require_once 'templates/footer.php';
?>
