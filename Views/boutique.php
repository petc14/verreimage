<?php
/**
 * Page Boutique - Catalogue des thèmes
 *
 * Cette page affiche tous les thèmes de plaques funéraires disponibles.
 * Elle scanne les dossiers d'images pour générer dynamiquement la galerie de thèmes.
 * Chaque thème est un lien vers le configurateur.
 * Le contenu dynamique ($catalogue_themes) est passé par le contrôleur.
 */
?>

<style>
    /* Styles spécifiques pour la page boutique */
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
        color: var(--text-color-light);
    }

    .theme-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        padding-top: 40px;
    }

    .theme-card {
        text-decoration: none;
        color: inherit;
        display: block;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        box-shadow: var(--box-shadow);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .theme-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    }

    .theme-card img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        display: block;
    }

    .theme-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 40px 20px 20px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.85), transparent);
        transition: background 0.3s ease;
    }
    .theme-card:hover .theme-card-overlay {
        background: linear-gradient(to top, rgba(0, 86, 179, 0.8), transparent);
    }

    .theme-card h3 {
        color: var(--white-color);
        font-size: 1.8em;
        margin: 0;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
    }

    .cta-section {
        text-align: center;
        background-color: var(--dark-blue);
        color: var(--white-color);
    }
    .cta-section h2 {
        color: var(--white-color);
    }
    .cta-section .btn {
        margin-top: 20px;
    }
</style>

<div class="page-title-section">
    <h1>Nos Modèles de Plaques</h1>
    <p>Parcourez nos collections thématiques et trouvez l'inspiration pour créer un hommage unique. Cliquez sur un thème pour voir les modèles et commencer la personnalisation.</p>
</div>

<div class="container">
    <?php if (empty($catalogue_themes)): ?>
        <div style="text-align: center;">
            <p>Aucun thème n'est actuellement disponible. Veuillez revenir plus tard.</p>
        </div>
    <?php else: ?>
        <div class="theme-gallery">
            <?php foreach ($catalogue_themes as $theme_name => $images): ?>
                <?php
                    // Créer un lien vers le configurateur en passant le nom du thème
                    // urlencode est utilisé pour s'assurer que les noms avec des espaces ou des caractères spéciaux fonctionnent dans l'URL
                    $configurateur_link = BASE_URL . 'configurateur?theme=' . urlencode($theme_name);
                ?>
                <a href="<?php echo $configurateur_link; ?>" class="theme-card">
                    <img src="<?php echo BASE_URL . $images[0]; ?>" alt="Thème <?php echo htmlspecialchars($theme_name); ?>">
                    <div class="theme-card-overlay">
                        <h3><?php echo htmlspecialchars($theme_name); ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<section class="cta-section">
    <div class="container">
        <h2>Une idée précise en tête ?</h2>
        <p>Si vous ne trouvez pas le modèle parfait, notre configurateur vous permet de partir d'une page blanche et d'importer vos propres images.</p>
        <a href="<?php echo BASE_URL; ?>configurateur" class="btn btn-primary">Créer un modèle 100% personnalisé</a>
    </div>
</section>
