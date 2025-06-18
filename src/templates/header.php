<?php
/**
 * Fichier de l'En-tête (Header)
 *
 * Ce fichier est inclus au début de chaque page du site principal.
 * Il inclut le fichier d'initialisation, gère le titre et la description SEO de la page,
 * et affiche toute la structure de l'en-tête, y compris le logo et le menu de navigation.
 * Il doit être placé dans le dossier /templates/
 */

// Inclut la configuration de base (session, BDD, constantes)
require_once __DIR__ . '/../../config/init.php';

// --- Gestion du SEO de la page ---
// Le titre et la description peuvent être définis dans chaque page avant d'inclure ce header.
// Si non définis, des valeurs par défaut sont utilisées.
$page_title = isset($page_title) ? $page_title : 'Accueil';
$page_description = isset($page_description) ? $page_description : 'Spécialiste de la plaque funéraire en verre personnalisée. Qualité photo, fabrication française à Honfleur et garantie 30 ans.';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    
    <!-- Lien vers la feuille de style principale -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    
    <!-- Lien vers une feuille de style additionnelle si définie dans la page -->
    <?php if (isset($extra_css)): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL . 'css/' . $extra_css; ?>">
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo BASE_URL; ?>IMAGES/favicon.png" type="image/png">
    
    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="<?php echo BASE_URL; ?>index.php" class="logo">
                <img src="<?php echo BASE_URL; ?>IMAGES/logo.png" alt="Logo Verre & Image">
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>index.php">Accueil</a></li>
                    <li><a href="<?php echo BASE_URL; ?>boutique.php">Boutique</a></li>
                    <li><a href="<?php echo BASE_URL; ?>presentation.php">Notre Savoir-Faire</a></li>
                    <li><a href="<?php echo BASE_URL; ?>realisations.php">Nos Réalisations</a></li>
                    <li><a href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <a href="<?php echo BASE_URL; ?>configurateur.php" class="btn btn-primary">CRÉER EN LIGNE</a>
                <a href="<?php echo BASE_URL; ?>page_panier.php" class="header-icon" title="Panier">
                    <img src="<?php echo BASE_URL; ?>IMAGES/icon-panier.png" alt="Panier">
                    <!-- Un compteur d'articles sera ajouté ici dynamiquement plus tard -->
                </a>
                <a href="<?php echo BASE_URL; ?>login.php" class="header-icon" title="Mon Compte">
                    <img src="<?php echo BASE_URL; ?>IMAGES/icon-compte.png" alt="Mon Compte">
                </a>
            </div>
            <button class="burger-menu" id="burger-menu">&#9776;</button>
        </div>
    </header>
    <main>
