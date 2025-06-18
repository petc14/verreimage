<?php
/**
 * Page des Mentions Légales
 *
 * Affiche les informations légales obligatoires concernant l'éditeur du site,
 * l'hébergeur, la propriété intellectuelle et la gestion des données personnelles.
 */

// Définition des informations SEO pour cette page
$page_title = "Mentions Légales";
$page_description = "Consultez les mentions légales du site Verre & Image, informations sur l'éditeur, l'hébergement et la politique de confidentialité.";

// Inclure l'en-tête commun à toutes les pages
require_once 'templates/header.php';
?>

<style>
    /* Styles spécifiques pour la page des mentions légales */
    .legal-page-header {
        text-align: center;
        padding: 60px 20px;
        background-color: var(--light-gray);
    }
    .legal-page-header h1 {
        font-size: 2.8em;
    }
    .legal-content-wrapper {
        background-color: var(--white-color);
        padding: 40px 0;
    }
    .legal-content {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    .legal-content h2 {
        text-align: left;
        font-size: 1.8em;
        margin-top: 40px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border-color);
    }
    .legal-content p, .legal-content li {
        margin-bottom: 10px;
        line-height: 1.8;
        color: #555;
    }
    .legal-content strong {
        color: var(--dark-blue);
    }
</style>

<div class="legal-page-header">
    <h1>Mentions Légales</h1>
</div>

<div class="legal-content-wrapper">
    <div class="container legal-content">
        <p>Conformément aux dispositions des Articles 6-III et 19 de la Loi n°2004-575 du 21 juin 2004 pour la Confiance dans l’économie numérique, dite L.C.E.N., il est porté à la connaissance des utilisateurs et visiteurs du site Verre & Image les présentes mentions légales.</p>

        <h2>1. Éditeur du site</h2>
        <p>
            <strong>Raison sociale :</strong> [Votre Raison Sociale, ex: Verre & Image SAS]<br>
            <strong>Forme juridique :</strong> [Votre forme juridique, ex: SAS]<br>
            <strong>Capital social :</strong> [Votre capital social, ex: 10 000 €]<br>
            <strong>Adresse du siège social :</strong> 1296 Av. du Président Duchesne, 14600 Honfleur<br>
            <strong>Numéro de téléphone :</strong> 01 42 09 52 09<br>
            <strong>Adresse e-mail :</strong> contact@verreimage.com<br>
            <strong>Numéro d’inscription au RCS :</strong> [Votre numéro RCS, ex: RCS Lisieux 123 456 789]<br>
            <strong>Numéro de TVA intracommunautaire :</strong> [Votre numéro de TVA]<br>
            <strong>Directeur de la publication :</strong> [Nom du directeur de la publication]
        </p>

        <h2>2. Hébergement du site</h2>
        <p>
            Le site Verre & Image est hébergé par :<br>
            <strong>Hébergeur :</strong> [Nom de votre hébergeur, ex: OVH, o2switch, etc.]<br>
            <strong>Adresse :</strong> [Adresse de l'hébergeur]<br>
            <strong>Numéro de téléphone :</strong> [Téléphone de l'hébergeur]
        </p>
        
        <h2>3. Propriété intellectuelle</h2>
        <p>L'ensemble de ce site (contenus, textes, images, vidéos et tout le savoir-faire) est la propriété de [Votre Raison Sociale] et de ses créateurs et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle. Toute reproduction ou représentation totale ou partielle de ce site est interdite sans autorisation expresse et préalable.</p>
        
        <h2>4. Données personnelles</h2>
        <p>Les informations recueillies depuis les formulaires de contact et de création de compte font l’objet d’un traitement informatique destiné uniquement à la gestion de la relation client et des commandes. Conformément à la loi "informatique et libertés" du 6 janvier 1978 modifiée, vous bénéficiez d’un droit d’accès, de rectification et de suppression des informations qui vous concernent, que vous pouvez exercer en vous adressant à contact@verreimage.com.</p>
        
         <h2>5. Cookies</h2>
        <p>Le site Verre & Image peut être amené à vous demander l’acceptation des cookies pour des besoins de statistiques et d'affichage. Un cookie est une information déposée sur votre disque dur par le serveur du site que vous visitez. Il contient plusieurs données qui sont stockées sur votre ordinateur dans un simple fichier texte auquel un serveur accède pour lire et enregistrer des informations.</p>
    </div>
</div>

<?php
// Inclure le pied de page commun à toutes les pages
require_once 'templates/footer.php';
?>
