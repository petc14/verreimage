<?php
/**
 * Page Présentation
 *
 * Décrit le savoir-faire de l'entreprise, la qualité des matériaux,
 * et les garanties offertes.
 * Le contenu dynamique est passé par le contrôleur (bien que cette page soit principalement statique).
 */
?>

<style>
    /* Styles spécifiques pour la page Présentation */
    .page-title-section {
        text-align: center;
        padding: 60px 20px;
        background-color: var(--light-gray);
    }
    .page-title-section h1 {
        font-size: 2.8em;
    }
    .page-title-section p {
        font-size: 1.1em;
        max-width: 700px;
        margin: 15px auto 0;
        color: #555;
    }

    .feature-section {
        display: flex;
        align-items: center;
        gap: 50px;
        margin-bottom: 60px;
    }
    /* Alterne la position de l'image */
    .feature-section:nth-child(even) {
        flex-direction: row-reverse;
    }
    .feature-content {
        flex: 1;
    }
    .feature-content h2 {
        text-align: left;
        font-size: 2.2em;
        margin-bottom: 20px;
    }
    .feature-image {
        flex: 1;
    }
    .feature-image img {
        width: 100%;
        border-radius: 8px;
        box-shadow: var(--box-shadow);
    }
    .feature-list {
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }
    .feature-list li {
        padding-left: 30px;
        position: relative;
        margin-bottom: 10px;
    }
    .feature-list li::before {
        content: '✔';
        color: var(--success-green);
        position: absolute;
        left: 0;
        font-weight: bold;
    }
    .cta-section {
        text-align: center;
        background-color: var(--dark-blue);
        color: var(--white-color);
        padding: 60px 20px;
    }
    .cta-section h2 {
        color: var(--white-color);
    }

    @media (max-width: 768px) {
        .feature-section, .feature-section:nth-child(even) {
            flex-direction: column;
        }
    }
</style>

<div class="page-title-section">
    <h1>L'Artisanat au Cœur de l'Hommage</h1>
    <p>Nous allions un savoir-faire traditionnel français à une technologie brevetée pour créer des plaques funéraires d'une qualité et d'une durabilité exceptionnelles.</p>
</div>

<div class="container">

    <section class="feature-section">
        <div class="feature-content">
            <h2>Un Procédé Breveté, Garanti 30 Ans</h2>
            <p>Notre technique d'impression unique nous permet d'insérer l'image en haute définition au cœur même du verre. Ce procédé protège les couleurs des UV et de l'humidité, assurant une pérennité que nous garantissons pendant 30 ans.</p>
            <ul class="feature-list">
                <li>Couleurs éclatantes et fidèles</li>
                <li>Résistance exceptionnelle aux intempéries</li>
                <li>Protection anti-UV et anti-rayures</li>
            </ul>
        </div>
        <div class="feature-image">
            <img src="<?php echo BASE_URL; ?>assets/images/plaque-layers.jpg" alt="Schéma des différentes couches d'une plaque en verre Verre & Image">
        </div>
    </section>

    <section class="feature-section">
        <div class="feature-content">
            <h2>La Qualité d'un Verre de Sécurité</h2>
            <p>Nous utilisons un verre feuilleté de 9mm d'épaisseur, conçu pour résister aux chocs, au gel et aux fortes variations de température. Contrairement au verre classique, il ne peut éclater en morceaux, garantissant ainsi sécurité et longévité à votre hommage.</p>
        </div>
        <div class="feature-image">
            <img src="<?php echo BASE_URL; ?>assets/images/verre-securite.jpg" alt="Gros plan sur la tranche d'un verre de sécurité épais">
        </div>
    </section>

    <section class="feature-section">
        <div class="feature-content">
            <h2>Fabrication 100% Française</h2>
            <p>De la conception graphique dans nos bureaux à la fabrication dans notre atelier de Honfleur, chaque plaque est entièrement réalisée en France. Ce choix nous permet de maîtriser chaque étape de la production et de vous garantir une qualité irréprochable.</p>
            <a href="<?php echo BASE_URL; ?>contact" class="btn btn-secondary" style="margin-top: 20px;">Visiter notre atelier</a>
        </div>
        <div class="feature-image">
            <img src="<?php echo BASE_URL; ?>assets/images/artisan-workshop.jpg" alt="Artisan français travaillant sur une plaque personnalisée">
        </div>
    </section>

</div>

<section class="cta-section">
    <h2>Prêt à créer un hommage unique ?</h2>
    <p>Découvrez nos modèles ou lancez-vous directement dans la création.</p>
    <a href="<?php echo BASE_URL; ?>configurateur" class="btn btn-primary" style="margin-top: 20px;">Commencer la personnalisation</a>
</section>