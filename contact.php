<?php
/**
 * Page de Contact
 *
 * Affiche les informations de contact de l'entreprise et un formulaire
 * pour envoyer un message. Le traitement du formulaire est géré par process-contact.php.
 */

// Définition des informations SEO pour cette page
$page_title = "Nous Contacter";
$page_description = "Contactez l'équipe de Verre & Image pour toute question ou pour une demande de devis personnalisé. Nous sommes à votre écoute.";

// Inclure l'initialisation et les fonctions
require_once 'config/init.php';

// Récupérer les éventuels messages d'erreur ou de succès, et les anciennes entrées
$errors = $_SESSION['errors'] ?? [];
$inputs = $_SESSION['inputs'] ?? [];
$success_message = $_SESSION['success_message'] ?? '';

// Vider les variables de session pour qu'elles ne s'affichent qu'une seule fois
unset($_SESSION['errors'], $_SESSION['inputs'], $_SESSION['success_message']);

// Inclure l'en-tête du site
require_once 'src/templates/header.php';
?>

<style>
    /* Styles spécifiques pour la page de contact */
    .contact-layout {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 40px;
        align-items: flex-start;
    }
    .contact-info-panel, .contact-form-panel {
        background-color: var(--white-color);
        padding: 40px;
        border-radius: 8px;
        box-shadow: var(--box-shadow);
    }
    .contact-info-panel h2, .contact-form-panel h2 {
        text-align: left;
        font-size: 2em;
        margin-bottom: 25px;
    }
    .info-block {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 25px;
    }
    .info-block .icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        background-color: var(--light-gray);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--medium-blue);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-family: inherit;
    }
    .form-message {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
    }
    .form-message.success { background-color: #d4edda; color: #155724; }
    .form-message.error { background-color: #f8d7da; color: #721c24; }
</style>

<div class="container">
    <div class="contact-layout">

        <!-- Panneau de gauche : Informations de contact -->
        <div class="contact-info-panel">
            <h2>Nous Contacter</h2>
            <p style="margin-bottom: 30px;">Nous sommes à votre disposition pour toute question ou demande de devis. N'hésitez pas à nous joindre.</p>

            <div class="info-block">
                <div class="icon">&#9742;</div>
                <div>
                    <strong>Téléphone</strong><br>
                    <a href="tel:0142095209">01 42 09 52 09</a>
                </div>
            </div>
            <div class="info-block">
                <div class="icon">&#9993;</div>
                <div>
                    <strong>Email</strong><br>
                    <a href="mailto:contact@verreimage.com">contact@verreimage.com</a>
                </div>
            </div>
            <div class="info-block">
                <div class="icon">&#128205;</div>
                <div>
                    <strong>Atelier</strong><br>
                    1296 Av. du Président Duchesne<br>
                    14600 HONFLEUR
                </div>
            </div>
        </div>

        <!-- Panneau de droite : Formulaire de contact -->
        <div class="contact-form-panel">
            <h2>Envoyer un message</h2>
            
            <?php if ($success_message): ?>
                <div class="form-message success"><?php echo $success_message; ?></div>
            <?php elseif (!empty($errors)): ?>
                 <div class="form-message error">Veuillez corriger les erreurs ci-dessous.</div>
            <?php endif; ?>

            <form id="contact-form" method="POST" action="process-contact.php" novalidate>
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($inputs['nom'] ?? ''); ?>" required>
                    <?php if (isset($errors['nom'])): ?><span style="color:red;font-size:0.8em;"><?php echo $errors['nom']; ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($inputs['email'] ?? ''); ?>" required>
                     <?php if (isset($errors['email'])): ?><span style="color:red;font-size:0.8em;"><?php echo $errors['email']; ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="sujet">Sujet</label>
                    <input type="text" id="sujet" name="sujet" value="<?php echo htmlspecialchars($inputs['sujet'] ?? ''); ?>" required>
                     <?php if (isset($errors['sujet'])): ?><span style="color:red;font-size:0.8em;"><?php echo $errors['sujet']; ?></span><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="message">Votre message</label>
                    <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($inputs['message'] ?? ''); ?></textarea>
                     <?php if (isset($errors['message'])): ?><span style="color:red;font-size:0.8em;"><?php echo $errors['message']; ?></span><?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Envoyer le message</button>
            </form>
        </div>

    </div>
</div>

<?php
// Inclure le pied de page
require_once 'src/templates/footer.php';
?>
