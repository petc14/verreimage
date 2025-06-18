<?php
/**
 * Page "Nos Réalisations"
 *
 * Affiche une galerie des plaques créées pour les clients.
 * Les images sont chargées dynamiquement depuis un dossier dédié.
 */

// Définition des informations SEO pour cette page
$page_title = "Nos Réalisations - Exemples de plaques funéraires";
$page_description = "Découvrez la qualité et l'émotion de nos plaques funéraires en verre, créées sur mesure pour nos clients. Puisez l'inspiration parmi nos exemples.";

// Inclure l'initialisation et les fonctions de base
require_once 'config/init.php';
require_once 'includes/fonctions.php';

// --- Logique pour récupérer les images de la galerie ---
/**
 * Scanne un dossier spécifique et retourne la liste des images.
 * @param string $dir Le chemin vers le dossier à scanner.
 * @return array La liste des chemins d'images.
 */
function get_gallery_images(string $dir = 'IMAGES/realisations'): array {
    $images = [];
    if (!is_dir($dir)) {
        return $images; // Retourne un tableau vide si le dossier n'existe pas
    }

    $files = scandir($dir);
    foreach ($files as $file) {
        $file_path = $dir . '/' . $file;
        $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        
        // On ne garde que les fichiers avec des extensions d'image valides
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $images[] = $file_path;
        }
    }
    return $images;
}

$gallery_images = get_gallery_images();

// Inclure l'en-tête
require_once 'templates/header.php';
?>

<style>
    /* Styles spécifiques pour la page Réalisations */
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
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
    }
    .gallery-item {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--box-shadow);
        cursor: pointer;
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }
    .gallery-item:hover img {
        transform: scale(1.05);
    }
    
    .testimonials-section {
        background-color: var(--light-gray);
    }
    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    .testimonial-card {
        background: var(--white-color);
        padding: 30px;
        border-radius: 8px;
    }
    .testimonial-card p {
        font-style: italic;
        margin-bottom: 15px;
    }
    .testimonial-card .author {
        font-weight: bold;
        color: var(--medium-blue);
    }
</style>

<div class="page-title-section">
    <h1>Nos Réalisations</h1>
    <p>Chaque plaque est une histoire. Découvrez quelques-uns des hommages que nous avons eu le privilège de créer pour nos clients.</p>
</div>

<div class="container">
    <?php if (empty($gallery_images)): ?>
        <div style="text-align: center;">
            <p>Aucune réalisation n'est actuellement visible. Découvrez nos modèles pour commencer votre propre création.</p>
            <a href="<?php echo BASE_URL; ?>boutique.php" class="btn btn-primary" style="margin-top: 20px;">Voir nos modèles</a>
        </div>
    <?php else: ?>
        <div class="gallery-grid">
            <?php foreach ($gallery_images as $image_path): ?>
                <?php
                    // Créer un texte alternatif descriptif à partir du nom du fichier
                    $alt_text = "Réalisation Verre & Image - " . ucfirst(str_replace(['_', '-'], ' ', pathinfo($image_path, PATHINFO_FILENAME)));
                ?>
                <div class="gallery-item">
                    <img src="<?php echo BASE_URL . $image_path; ?>" alt="<?php echo htmlspecialchars($alt_text); ?>">
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<section class="testimonials-section">
    <div class="container">
        <h2 style="text-align: center;">Ils nous ont fait confiance</h2>
        <div class="testimonials-grid" style="margin-top: 40px;">
            <div class="testimonial-card">
                <p>"Un immense merci pour votre écoute et la qualité exceptionnelle de votre travail. La plaque est magnifique, bien au-delà de nos espérances."</p>
                <div class="author">- Famille Dubois</div>
            </div>
             <div class="testimonial-card">
                <p>"Le portrait de notre mère a été magnifiquement restauré. Votre professionnalisme et votre rapidité nous ont beaucoup touchés en ces moments difficiles."</p>
                <div class="author">- Sophie L.</div>
            </div>
             <div class="testimonial-card">
                <p>"La plaque est arrivée très bien emballée et dans les délais. La qualité est irréprochable. Nous recommandons vivement vos services."</p>
                <div class="author">- Alain Martin</div>
            </div>
        </div>
    </div>
</section>

<?php
// Inclure le pied de page
require_once 'templates/footer.php';
?>
