<?php
/**
 * Fichier de Traitement du Formulaire de Contact
 *
 * Ce fichier reçoit les données soumises via le formulaire de contact.
 * Il valide les informations, construit un email et l'envoie.
 * Ensuite, il redirige l'utilisateur vers la page de contact avec un message de statut.
 * Il doit être placé dans le dossier /includes/ ou à la racine selon la configuration finale.
 * Pour notre structure, nous le mettons à la racine pour simplifier l'action du formulaire.
 */

// Inclure la configuration de base pour accéder aux sessions et fonctions
require_once 'config/init.php';
require_once 'includes/fonctions.php';

// Initialisation des variables pour les messages
$_SESSION['errors'] = [];
$_SESSION['inputs'] = [];
$_SESSION['success_message'] = '';

// Vérifier que le formulaire a été soumis via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Assainir et récupérer les entrées du formulaire
    // La fonction sanitize_input() est définie dans fonctions.php
    $nom = sanitize_input($_POST['nom'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $sujet = sanitize_input($_POST['sujet'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');

    // Conserver les entrées en session pour les réafficher en cas d'erreur
    $_SESSION['inputs'] = $_POST;

    // 2. Valider les informations
    if (empty($nom)) {
        $_SESSION['errors']['nom'] = 'Le nom est obligatoire.';
    }
    if (empty($email)) {
        $_SESSION['errors']['email'] = "L'adresse email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['email'] = "L'adresse email n'est pas valide.";
    }
    if (empty($sujet)) {
        $_SESSION['errors']['sujet'] = 'Le sujet est obligatoire.';
    }
    if (empty($message)) {
        $_SESSION['errors']['message'] = 'Le message ne peut pas être vide.';
    }

    // 3. Si aucune erreur n'est trouvée, construire et envoyer l'email
    if (empty($_SESSION['errors'])) {
        
        // Adresse email de destination
        $to = "contact@verreimage.com";
        
        // Sujet de l'email que vous recevrez
        $email_subject = "Nouveau message de {$nom} : {$sujet}";
        
        // Corps de l'email
        $email_body = "Vous avez reçu un nouveau message depuis le formulaire de contact de votre site.\n\n";
        $email_body .= "--------------------------------------------------\n";
        $email_body .= "Nom : {$nom}\n";
        $email_body .= "Email : {$email}\n";
        $email_body .= "Sujet : {$sujet}\n\n";
        $email_body .= "Message :\n{$message}\n";
        $email_body .= "--------------------------------------------------\n";
        
        // Entêtes de l'email (Headers)
        $headers = "From: {$nom} <{$email}>\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Envoi de l'email avec la fonction mail() de PHP
        // NOTE: Pour que cela fonctionne, votre serveur d'hébergement doit être configuré pour envoyer des emails.
        // En local avec XAMPP, une configuration supplémentaire est nécessaire.
        if (mail($to, $email_subject, $email_body, $headers)) {
            // Message de succès
            $_SESSION['success_message'] = "Merci ! Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.";
            unset($_SESSION['inputs']); // Vider les champs saisis après succès
        } else {
            // En cas d'échec de la fonction mail()
            $_SESSION['errors']['general'] = "Une erreur technique est survenue. Veuillez réessayer plus tard ou nous contacter directement par téléphone.";
        }
    }
}

// 4. Rediriger l'utilisateur vers la page de contact
// Cela évite le renvoi du formulaire si l'utilisateur rafraîchit la page.
header('Location: ' . BASE_URL . 'contact.php');
exit();
?>
