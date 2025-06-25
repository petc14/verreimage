<?php
// src/Controllers/ContactController.php

namespace Controllers;

use Models\ContactMessage;

class ContactController {
    private ContactMessage $contactMessageModel;

    public function __construct() {
        $this->contactMessageModel = new ContactMessage();
    }

    public function index() {
        $data = [
            'page_title' => "Nous Contacter",
            'page_description' => "Contactez l'équipe de Verre & Image pour toute question ou pour une demande de devis personnalisé. Nous sommes à votre écoute.",
            'errors' => $_SESSION['errors'] ?? [],
            'inputs' => $_SESSION['inputs'] ?? [],
            'success_message' => $_SESSION['success_message'] ?? '',
        ];
        unset($_SESSION['errors'], $_SESSION['inputs'], $_SESSION['success_message']);

        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('contact', $data);
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = sanitize_input($_POST['nom'] ?? '');
            $email = sanitize_input($_POST['email'] ?? '');
            $sujet = sanitize_input($_POST['sujet'] ?? '');
            $message = sanitize_input($_POST['message'] ?? '');

            $_SESSION['inputs'] = $_POST;
            $errors = [];

            if (empty($nom)) {
                $errors['nom'] = 'Le nom est obligatoire.';
            }
            if (empty($email)) {
                $errors['email'] = "L'adresse email est obligatoire.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "L'adresse email n'est pas valide.";
            }
            if (empty($sujet)) {
                $errors['sujet'] = 'Le sujet est obligatoire.';
            }
            if (empty($message)) {
                $errors['message'] = 'Le message ne peut pas être vide.';
            }

            if (empty($errors)) {
                if ($this->contactMessageModel->sendEmail($nom, $email, $sujet, $message)) {
                    $_SESSION['success_message'] = "Merci ! Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.";
                } else {
                    $errors['general'] = "Une erreur technique est survenue. Veuillez réessayer plus tard ou nous contacter directement par téléphone.";
                }
            }
            $_SESSION['errors'] = $errors;
        }
        header('Location: ' . BASE_URL . 'contact');
        exit();
    }
}