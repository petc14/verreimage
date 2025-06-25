<?php
/**
 * Page d'Accueil
 *
 * C'est la page principale du site, la première que les visiteurs voient.
 * Elle doit être attrayante et guider l'utilisateur vers les sections clés.
 * Le contenu dynamique (comme $catalogue_themes) est passé par le contrôleur.
 */
?>

<style>
    /* Styles spécifiques pour la page d'accueil pour éviter de surcharger le CSS principal */
    .hero {
        background: linear-gradient(rgba(0, 35, 73, 0.6), rgba(0, 35, 73, 0.6)), url('<?php echo BASE_URL; ?>assets/images/hero-plaque-rose.jpg') no-repeat center center/cover;
        min-height: 90vh;
    }
    .hero .btn-secondary {
        color: var(--white-color);
        border-color: var(--white-color);
    }
    .hero .btn-secondary:hover {
        background-color: var(--white-color);
        color: var(--dark-blue);
    }

    .section-bg-texture {
        background-color: var(--light-gray);
    }

    .engagements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        text-align: center;
    }
    .engagement-card {
        background: var(--white-color);
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .engagement-card .icon {
        color: var(--medium-blue);
        margin-bottom: 15px;
    }
    .engagement-card .icon svg { width: 50px; height: 50px; }
    .engagement-card h3 { font-size: 1.4em; margin-bottom: 10px; }

    .artisanat-section {
        display: flex;
        align-items: center;
        gap: 50px;
    }
    .artisanat-section .content { flex: 3; }
    .artisanat-section .image-content { flex: 2; }
    .artisanat-section .image-content img { width: 100%; border-radius: 12px; box-shadow: var(--box-shadow); }

    .theme-card {
        text-decoration: none;
    }
    .theme-card .gallery-item { /* Réutilisé de la classe .gallery-item si définie dans style.min.css pour la shadow */
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: var(--box-shadow);
    }
    .theme-card .theme-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 40px 20px 20px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        color: white;
        font-family: var(--heading-font);
        font-size: 1.8em;
        transition: all 0.3s ease;
    }
    .theme-card:hover .theme-overlay {
        background: linear-gradient(to top, rgba(0, 86, 179, 0.8), transparent);
    }

</style>

<section class="hero">
    <div class="hero-content">
        <h1>Un Hommage Unique et Durable en Verre</h1>
        <p>Immortalisez vos plus beaux souvenirs sur une plaque funéraire personnalisée. Une qualité photo exceptionnelle, fabriquée en France et garantie 30 ans.</p>
        <div class="hero-buttons">
            <a href="<?php echo BASE_URL; ?>boutique" class="btn btn-primary">Voir nos modèles</a>
            <a href="<?php echo BASE_URL; ?>contact" class="btn btn-secondary">Demander un devis</a>
        </div>
    </div>
</section>

<section class="section-bg-texture">
    <div class="container">
        <h2>Nos Engagements</h2>
        <div class="engagements-grid">
            <div class="engagement-card">
                <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></div>
                <h3>Qualité Garantie 30 Ans</h3>
                <p>Nos impressions sur verre sont conçues pour résister au temps et aux intempéries.</p>
            </div>
            <div class="engagement-card">
                <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg></div>
                <h3>Savoir-Faire Français</h3>
                <p>Chaque plaque est fabriquée avec soin dans notre atelier de Honfleur.</p>
            </div>
            <div class="engagement-card">
                <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5h-6a6 6 0 0 1 6-6v1.5m-6 7.5v-1.5m6-6v-1.5a6 6 0 0 0-6-6v1.5m6 6h-6m6 0a6 6 0 0 0 6-6h-6m6 0v-1.5a6 6 0 0 0-6-6v-1.5m-6 13.5a6 6 0 0 1 6-6v-1.5" /></svg></div>
                <h3>Accompagnement Humain</h3>
                <p>Notre équipe vous écoute et vous conseille pour créer un hommage qui a du sens.</p>
            </div>
        </div>
    </div>
</section>

<div class="container artisanat-section">
    <div class="content">
        <h2>L'artisanat au coeur de votre hommage</h2>
        <p>Derrière chaque plaque, il y a une histoire et le soin de nos artisans. Dans notre atelier, nous prenons le temps de parfaire chaque détail pour que le résultat final soit à la hauteur de vos souvenirs.</p>
        <a href="<?php echo BASE_URL; ?>presentation" class="btn btn-secondary" style="margin-top: 20px;">Découvrir notre savoir-faire</a>
    </div>
    <div class="image-content">
        <img src="<?php echo BASE_URL; ?>assets/images/artisan-workshop.jpg" alt="Artisan travaillant dans l'atelier Verre & Image.">
    </div>
</div>

<div class="container">
    <h2>Découvrez nos thèmes populaires</h2>
    <div class="theme-gallery">
        <?php foreach ($catalogue_themes as $theme_name => $images): ?>
            <a href="<?php echo BASE_URL; ?>boutique?theme=<?php echo urlencode($theme_name); ?>" class="theme-card">
                <div class="gallery-item">
                    <img src="<?php echo BASE_URL . $images[0]; ?>" alt="Thème <?php echo htmlspecialchars($theme_name); ?>">
                    <div class="theme-overlay"><h3><?php echo htmlspecialchars($theme_name); ?></h3></div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <div style="text-align: center; margin-top: 40px;">
        <a href="<?php echo BASE_URL; ?>boutique" class="btn btn-primary">Voir tous nos modèles</a>
    </div>
</div>
