<?php
// src/Controllers/UserController.php

namespace Controllers;

use Models\User;

class UserController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        $data = [
            'page_title' => "Connexion / Inscription",
            'page_description' => "Connectez-vous à votre compte Verre & Image pour suivre vos commandes ou créez un compte pour une expérience d'achat simplifiée.",
            'login_errors' => $_SESSION['login_errors'] ?? [],
            'register_errors' => $_SESSION['register_errors'] ?? [],
            'register_inputs' => $_SESSION['register_inputs'] ?? [],
        ];
        unset($_SESSION['login_errors'], $_SESSION['register_errors'], $_SESSION['register_inputs']);

        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('login', $data);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize_input($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['login_errors'] = ["Tous les champs sont obligatoires."];
            } else {
                $user = $this->userModel->findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_first_name'] = $user['first_name'];
                    $_SESSION['user_last_name'] = $user['last_name'];
                    header('Location: ' . BASE_URL . 'mon-compte');
                    exit();
                } else {
                    $_SESSION['login_errors'] = ["Email ou mot de passe incorrect."];
                }
            }
        }
        header('Location: ' . BASE_URL . 'login');
        exit();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = sanitize_input($_POST['first_name'] ?? '');
            $lastName = sanitize_input($_POST['last_name'] ?? '');
            $email = sanitize_input($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $_SESSION['register_inputs'] = $_POST;
            $errors = [];

            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                $errors[] = "Tous les champs sont obligatoires.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide.";
            }
            if (strlen($password) < 6) { // Exemple de règle de mot de passe
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
            }
            if ($this->userModel->findByEmail($email)) {
                $errors[] = "Cet email est déjà utilisé.";
            }

            if (empty($errors)) {
                if ($this->userModel->create($firstName, $lastName, $email, $password)) {
                    // Inscription réussie, tenter de connecter l'utilisateur directement
                    $user = $this->userModel->findByEmail($email);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_first_name'] = $user['first_name'];
                    $_SESSION['user_last_name'] = $user['last_name'];
                    header('Location: ' . BASE_URL . 'mon-compte');
                    exit();
                } else {
                    $errors[] = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                }
            }
            $_SESSION['register_errors'] = $errors;
        }
        header('Location: ' . BASE_URL . 'login');
        exit();
    }

    public function myAccount() {
        if (!User::isLoggedIn()) {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }

        $userInfo = User::getUserInfo();
        $orders = $this->userModel->getUserOrders($userInfo['id']);

        $data = [
            'page_title' => "Mon Compte",
            'page_description' => "Gérez vos informations personnelles et consultez l'historique de vos commandes sur Verre & Image.",
            'user' => $userInfo,
            'orders' => $orders
        ];
        global $page_title, $page_description;
        $page_title = $data['page_title'];
        $page_description = $data['page_description'];
        render('mon_compte', $data);
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . 'accueil');
        exit();
    }
}