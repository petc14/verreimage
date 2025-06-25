<?php
/**
 * Page de Connexion et d'Inscription
 *
 * Permet aux utilisateurs de se connecter à leur compte ou de créer un nouveau compte.
 * Affiche les messages d'erreur et pré-remplit les champs si nécessaire.
 * Le contenu dynamique ($login_errors, $register_errors, $register_inputs) est passé par le contrôleur.
 */
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

        <div class="form-panel">
            <h2>Connexion</h2>
            <p style="margin-bottom: 20px;">Déjà client ? Connectez-vous pour accéder à votre espace personnel.</p>

            <?php if (!empty($login_errors)): ?>
                <ul class="error-list">
                    <?php foreach ($login_errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>login">
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

        <div class="form-panel" style="background-color: var(--light-gray);">
            <h2>Nouveau Client ?</h2>
            <p style="margin-bottom: 20px;">Créez un compte pour sauvegarder vos créations, suivre vos commandes et simplifier vos futurs achats.</p>

            <?php if (!empty($register_errors)): ?>
                 <ul class="error-list">
                    <?php foreach ($register_errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>register">
                 <div class="form-group">
                    <label for="register_firstname">Prénom</label>
                    <input type="text" id="register_firstname" name="first_name" value="<?php echo htmlspecialchars($register_inputs['first_name'] ?? ''); ?>" required>
                </div>
                 <div class="form-group">
                    <label for="register_lastname">Nom</label>
                    <input type="text" id="register_lastname" name="last_name" value="<?php echo htmlspecialchars($register_inputs['last_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="register_email">Adresse e-mail</label>
                    <input type="email" id="register_email" name="email" value="<?php echo htmlspecialchars($register_inputs['email'] ?? ''); ?>" required>
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
