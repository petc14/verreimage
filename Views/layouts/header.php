<?php
/**
 * Fichier de l'En-tête (Header)
 *
 * Ce fichier est inclus au début de chaque page du site principal.
 * Il inclut le fichier d'initialisation, gère le titre et la description SEO de la page,
 * et affiche toute la structure de l'en-tête, y compris le logo et le menu de navigation.
 * Il doit être placé dans le dossier /views/layouts/
 */

// Les variables $page_title et $page_description sont passées via la fonction render()
// et extraites pour être disponibles ici.
// Si elles ne sont pas définies par le contrôleur, des valeurs par défaut sont utilisées.
$page_title = $page_title ?? 'Accueil';
$page_description = $page_description ?? 'Spécialiste de la plaque funéraire en verre personnalisée. Qualité photo, fabrication française à Honfleur et garantie 30 ans.';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.min.css">

    <?php if (isset($extra_css)): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL . 'assets/css/' . htmlspecialchars($extra_css); ?>">
    <?php endif; ?>

    <link rel="icon" href="<?php echo BASE_URL; ?>assets/images/favicon.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="<?php echo BASE_URL; ?>accueil" class="logo">
                <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo Verre & Image">
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>accueil">Accueil</a></li>
                    <li><a href="<?php echo BASE_URL; ?>boutique">Boutique</a></li>
                    <li><a href="<?php echo BASE_URL; ?>presentation">Notre Savoir-Faire</a></li>
                    <li><a href="<?php echo BASE_URL; ?>realisations">Nos Réalisations</a></li>
                    <li><a href="<?php echo BASE_URL; ?>contact">Contact</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <a href="<?php echo BASE_URL; ?>configurateur" class="btn btn-primary">CRÉER EN LIGNE</a>
                <a href="<?php echo BASE_URL; ?>panier" class="header-icon" title="Panier">
                    <img src="<?php echo BASE_URL; ?>assets/images/icons/icon-panier.png" alt="Panier">
                    </a>
                <a href="<?php echo BASE_URL; ?>login" class="header-icon" title="Mon Compte">
                    <img src="<?php echo BASE_URL; ?>assets/images/icons/icon-compte.png" alt="Mon Compte">
                </a>
            </div>
            <button class="burger-menu" id="burger-menu">&#9776;</button>
        </div>
    </header>
    <main>
