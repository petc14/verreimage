<?php
/**
 * Page Boutique - Catalogue des thèmes
 *
 * Cette page affiche tous les thèmes de plaques funéraires disponibles.
 * Elle scanne les dossiers d'images pour générer dynamiquement la galerie de thèmes.
 * Chaque thème est un lien vers le configurateur.
 */

// Définition des informations SEO pour cette page
$page_title = "Boutique - Nos Modèles de Plaques";
$page_description = "Découvrez tous nos thèmes de plaques funéraires personnalisables. Choisissez un modèle et commencez la création d'un hommage unique.";

// Ajouter une feuille de style spécifique à cette page
$extra_css = 'boutique.css';

// Inclut l'en-tête commun à toutes les pages
require_once 'src/templates/header.php';

// La fonction get_background_catalogue() est maintenant disponible grâce au fichier init.php
// qui est lui-même inclus dans le header.php.
// Cette fonction scanne le dossier IMAGES et retourne un tableau de thèmes.
$catalogue_themes = get_background_catalogue();
?>


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
                    $configurateur_link = BASE_URL . 'configurateur.php?theme=' . urlencode($theme_name);
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
        <a href="<?php echo BASE_URL; ?>configurateur.php" class="btn btn-primary">Créer un modèle 100% personnalisé</a>
    </div>
</section>

<?php
// Inclut le pied de page commun à toutes les pages
require_once 'src/templates/footer.php';
?>
