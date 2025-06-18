<?php
/**
 * Page du Configurateur de Plaque
 *
 * C'est l'outil principal de personnalisation du site.
 * Il a son propre en-tête et pied de page pour une expérience en plein écran.
 * Le PHP est utilisé en amont pour préparer les données (catalogue d'images).
 * Tout le reste est géré par JavaScript côté client.
 */

// Inclut l'initialisation pour accéder aux fonctions et constantes
require_once 'config/init.php';
// La fonction get_background_catalogue est dans fonctions.php, qui est appelé par init.php
$catalogue_fonds = get_background_catalogue();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurateur de Plaque | <?php echo SITE_NAME; ?></title>
    
    <!-- Lien vers le CSS dédié au configurateur -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/configurateur.css">
    
    <link rel="icon" href="<?php echo BASE_URL; ?>IMAGES/favicon.png" type="image/png">
    
    <!-- Librairies JavaScript externes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
</head>
<body id="configurateur-page">

<div id="configurateur-main">
    <header class="configurateur-header">
        <a href="index.php" class="logo"><img src="IMAGES/logo.png" alt="Logo Verre & Image"></a>
        <nav class="stepper">
            <div class="step active" data-step="1">Format</div>
            <div class="step" data-step="2">Choix du fond</div>
            <div class="step" data-step="3">Choix du texte</div>
            <div class="step" data-step="4">Récapitulatif</div>
        </nav>
        <a href="index.php" class="close-btn">&times;</a>
    </header>

    <div class="configurateur-body">
        <aside class="preview-panel-3d">
            <div id="canvas-container"></div>
            <div class="view-controls">
                <button class="btn-control" id="rotate-left" title="Rotation Gauche"><img src="IMAGES/icon-rotate-left.png"></button>
                <button class="btn-control" id="rotate-right" title="Rotation Droite"><img src="IMAGES/icon-rotate-right.png"></button>
                <button class="btn-control" id="zoom-in" title="Zoom avant"><img src="IMAGES/icon-zoom-in.png"></button>
                <button class="btn-control" id="zoom-out" title="Zoom arrière"><img src="IMAGES/icon-zoom-out.png"></button>
            </div>
            <!-- Canvas 2D pour la personnalisation, caché de l'utilisateur -->
            <canvas id="fabric-canvas" style="display: none;"></canvas>
        </aside>

        <div class="controls-panel-config">
            <div class="panel-content">
                <!-- Etape 1: Format -->
                <div id="panel-step-1" class="panel-step active">
                    <h2 class="panel-title">Format de la plaque</h2>
                    <div class="option-group" data-group="taille">
                         <h3 class="option-group-title">Choisissez la taille <span class="info-icon" title="Dimensions de la plaque en verre.">ⓘ</span></h3>
                        <div class="option-grid grid-3">
                            <div class="option-box" data-value="24x18" data-price="155"><span class="label">24x18 cm</span></div>
                            <div class="option-box selected" data-value="30x22" data-price="175"><span class="label">30x22 cm</span><span class="price">+20€</span></div>
                            <div class="option-box" data-value="40x30" data-price="205"><span class="label">40x30 cm</span><span class="price">+50€</span></div>
                        </div>
                    </div>
                     <div class="option-group" data-group="fixation">
                        <h3 class="option-group-title">Fixation et pierre <span class="info-icon" title="Choisissez un support pour votre plaque.">ⓘ</span></h3>
                        <div class="option-grid grid-2">
                            <div class="option-box" data-value="fixation_murale" data-price="15"><img src="IMAGES/icon-fixation-murale.png"><span class="label">Fixation Murale</span><span class="price">+15€</span></div>
                            <div class="option-box" data-value="marbre_blanc" data-price="30"><img src="IMAGES/icon-marbre-blanc.png"><span class="label">Marbre Blanc</span><span class="price">+30€</span></div>
                            <div class="option-box selected" data-value="plaque_seule" data-price="0"><img src="IMAGES/icon-plaque-seule.png"><span class="label">Plaque Seule</span></div>
                        </div>
                    </div>
                </div>

                <!-- Etape 2: Fond -->
                <div id="panel-step-2" class="panel-step">
                    <h2 class="panel-title">Choix du Fond</h2>
                    <div id="fonds-container">
                        <?php foreach($catalogue_fonds as $theme => $images): ?>
                            <div class="option-group">
                                <h3 class="option-group-title"><?php echo $theme; ?></h3>
                                <div class="option-grid grid-3">
                                    <?php foreach($images as $path): ?>
                                    <div class="option-box" data-img-src="<?php echo $path; ?>"><img src="<?php echo $path; ?>" style="width:100%; height: 50px; object-fit:cover;"></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Etape 3: Texte & Photos -->
                 <div id="panel-step-3" class="panel-step">
                     <h2 class="panel-title">Choix du texte</h2>
                     <div class="option-group">
                        <h3 class="option-group-title">Votre Texte</h3>
                        <textarea id="text-input-1" placeholder="Écrivez votre message..." rows="3"></textarea>
                     </div>
                      <div class="option-group">
                        <h3 class="option-group-title">Ajout de photos</h3>
                        <label for="photo-upload" class="upload-zone">
                            <p>🖼️ Importer</p>
                            <span>Ou faites glisser vos images</span>
                        </label>
                        <input type="file" id="photo-upload" accept="image/*" multiple style="display:none;">
                     </div>
                </div>

                <!-- Etape 4: Récapitulatif -->
                <div id="panel-step-4" class="panel-step">
                    <h2 class="panel-title">Choix des options</h2>
                    <div class="option-group">
                        <div class="checkbox-option selected" data-group="verification">
                            <input type="checkbox" id="verification-checkbox" data-price="10" checked>
                            <label for="verification-checkbox" class="label-price">
                                <span class="label">Vérification par nos graphistes</span><br>
                                <span class="price">10€</span>
                            </label>
                        </div>
                    </div>
                     <div class="option-group">
                        <div class="checkbox-option" data-group="designer">
                           <input type="checkbox" id="designer-checkbox" data-price="10">
                            <label for="designer-checkbox" class="label-price">
                                <span class="label">Faire designer ma plaque</span><br>
                                <span class="price">10€</span>
                            </label>
                        </div>
                        <textarea placeholder="Laissez vos commentaires..." id="designer-comment" style="display:none;"></textarea>
                    </div>
                </div>
            </div>

            <div class="config-footer">
                <div id="summary-section">
                    <div class="summary-line">
                        <span class="label">Total</span>
                        <span class="value" id="total-price">185,00€</span>
                    </div>
                </div>
                <div class="navigation-buttons">
                    <button id="btn-back" class="btn-config btn-back" disabled>Retour</button>
                    <button id="btn-continue" class="btn-config btn-continue">Continuer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Le script est inclus à la fin pour de meilleures performances -->
<script src="<?php echo BASE_URL; ?>js/configurateur.js"></script>

</body>
</html>
