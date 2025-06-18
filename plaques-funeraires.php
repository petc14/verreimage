<?php
/**
 * Page Boutique / Catalogue des thèmes
 *
 * Affiche tous les thèmes de plaques funéraires sous forme d'un accordéon interactif.
 * Chaque thème est cliquable et révèle les modèles correspondants.
 */

// Définition des informations SEO pour cette page
$page_title = "Nos Modèles de Plaques Funéraires";
$page_description = "Découvrez tous nos thèmes de plaques funéraires personnalisables. Choisissez un modèle et commencez la création d'un hommage unique sur Verre & Image.";

// Inclure l'initialisation et les fonctions
require_once 'config/init.php';
require_once 'src/functions.php';

// Récupère le catalogue des fonds depuis les dossiers d'images
$catalogue_themes = get_background_catalogue();

// Inclure l'en-tête commun
require_once 'src/templates/header.php';
?>

<style>
    /* Styles spécifiques pour la page boutique (accordéon) */
    .page-title-section {
        text-align: center;
        padding: 60px 20px;
        background-color: var(--light-gray);
    }
    .page-title-section h1 { font-size: 2.8em; }
    .page-title-section p {
        font-size: 1.1em;
        max-width: 700px;
        margin: 15px auto 0;
        color: #555;
    }

    .accordion-container {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
        background-color: var(--white-color);
        box-shadow: var(--box-shadow);
    }
    .theme-section {
        border-bottom: 1px solid var(--border-color);
    }
    .theme-section:last-child {
        border-bottom: none;
    }
    .theme-accordion-trigger {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .theme-accordion-trigger:hover {
        background-color: var(--light-gray);
    }
    .theme-title-info {
        font-size: 1.5em;
        font-weight: 700;
        color: var(--dark-blue);
    }
    .theme-counter {
        font-size: 0.8em;
        font-weight: 500;
        color: var(--text-color-light);
        margin-left: 15px;
    }
    .icon-toggle {
        width: 24px;
        height: 24px;
        transition: transform 0.4s ease;
        color: var(--medium-blue);
    }
    .theme-section.open .icon-toggle {
        transform: rotate(180deg);
    }

    .theme-thumbnails {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease-in-out, padding 0.5s ease-in-out;
        padding: 0 25px;
        background-color: var(--light-gray);
    }
    .theme-section.open .theme-thumbnails {
        padding-top: 30px;
        padding-bottom: 30px;
    }
    .models-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    .model-card {
        text-decoration: none;
        color: inherit;
        background-color: var(--white-color);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }
    .model-card:hover {
        transform: translateY(-5px);
    }
    .model-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .model-card h3 {
        font-size: 1.1em;
        padding: 15px;
        margin: 0;
        text-align: center;
    }
</style>

<div class="page-title-section">
    <h1>Nos Modèles de Plaques</h1>
    <p>Parcourez nos collections thématiques. Cliquez sur un thème pour découvrir les modèles, puis choisissez celui qui vous inspire pour commencer la personnalisation.</p>
</div>

<div class="container">
    <?php if (empty($catalogue_themes)): ?>
        <div style="text-align: center;">
            <p>Aucun thème n'est actuellement disponible. Veuillez revenir plus tard.</p>
        </div>
    <?php else: ?>
        <div class="accordion-container">
            <?php foreach ($catalogue_themes as $theme_name => $images): ?>
                <div class="theme-section" id="theme-<?php echo strtolower(htmlspecialchars($theme_name)); ?>">
                    <div class="theme-accordion-trigger">
                        <span class="theme-title-info">
                            <?php echo htmlspecialchars($theme_name); ?>
                            <span class="theme-counter">(<?php echo count($images); ?> modèles)</span>
                        </span>
                        <svg class="icon-toggle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </div>
                    <div class="theme-thumbnails">
                        <div class="models-grid">
                            <?php foreach ($images as $image_path): ?>
                                <?php
                                    $filename = basename($image_path);
                                    $model_name = ucwords(strtolower(str_replace(['_', '-'], ' ', pathinfo($filename, PATHINFO_FILENAME))));
                                    $configurateur_link = BASE_URL . 'configurateur.php?modele=' . urlencode($filename);
                                ?>
                                <a href="<?php echo $configurateur_link; ?>" class="model-card">
                                    <img src="<?php echo BASE_URL . $image_path; ?>" alt="<?php echo htmlspecialchars($model_name); ?>">
                                    <h3><?php echo htmlspecialchars($model_name); ?></h3>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accordions = document.querySelectorAll('.theme-accordion-trigger');

    accordions.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const parent = this.parentElement;
            const content = parent.querySelector('.theme-thumbnails');
            const wasOpen = parent.classList.contains('open');

            // Fermer tous les accordéons
            document.querySelectorAll('.theme-section').forEach(item => {
                item.classList.remove('open');
                item.querySelector('.theme-thumbnails').style.maxHeight = null;
            });

            // Si l'accordéon cliqué n'était pas déjà ouvert, l'ouvrir
            if (!wasOpen) {
                parent.classList.add('open');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });

    // Ouvre un thème spécifique si présent dans l'URL (venant de la page d'accueil)
    const urlParams = new URLSearchParams(window.location.search);
    const themeToOpen = urlParams.get('theme');
    if (themeToOpen) {
        const targetAccordion = document.getElementById('theme-' + themeToOpen.toLowerCase());
        if (targetAccordion) {
            targetAccordion.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
                targetAccordion.querySelector('.theme-accordion-trigger').click();
            }, 500); // Léger délai pour le défilement
        }
    }
});
</script>

<?php
// Inclure le pied de page
require_once 'src/templates/footer.php';
?>
