<?php
/**
 * Page du Configurateur de Plaque
 *
 * C'est l'outil principal de personnalisation du site.
 * Il a son propre en-tête et pied de page pour une expérience en plein écran.
 * Le PHP est utilisé en amont pour préparer les données (catalogue d'images).
 * Tout le reste est géré par JavaScript côté client.
 * Le contenu dynamique ($catalogue_fonds) est passé par le contrôleur.
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurateur de Plaque | <?php echo SITE_NAME; ?></title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/configurateur.min.css">

    <link rel="icon" href="<?php echo BASE_URL; ?>assets/images/favicon.png" type="image/png">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
</head>
<body id="configurateur-page">

<div id="configurateur-main">
    <header class="configurateur-header">
        <a href="<?php echo BASE_URL; ?>accueil" class="logo"><img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo Verre & Image"></a>
        <nav class="stepper">
            <div class="step active" data-step="1">Format</div>
            <div class="step" data-step="2">Choix du fond</div>
            <div class="step" data-step="3">Texte & Photo</div>
            <div class="step" data-step="4">Options & Devis</div>
        </nav>
        <a href="<?php echo BASE_URL; ?>accueil" class="close-btn">&times;</a>
    </header>

    <div class="configurateur-body">
        <aside class="preview-panel-3d">
            <div id="canvas-container"></div>
            <div class="view-controls">
                <button class="btn-control" id="rotate-left" title="Rotation Gauche">
                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 6V3L7 8L12 13V10C15.31 10 18 12.69 18 16C18 17.57 17.43 18.91 16.5 20L17.9 21.4C19.34 19.57 20 17.3 20 16C20 11.58 16.42 8 12 8H12z" />
                    </svg>
                </button>
                <button class="btn-control" id="rotate-right" title="Rotation Droite">
                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 18V21L17 16L12 11V14C8.69 14 6 11.31 6 8C6 6.43 6.57 5.09 7.5 4L6.1 2.6C4.66 4.43 4 6.7 4 8C4 12.42 7.58 16 12 16H12z" />
                    </svg>
                </button>
                <button class="btn-control" id="zoom-in" title="Zoom avant">
                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5 14H14.71L14.43 13.73C15.41 12.59 16 11.11 16 9.5C16 5.91 13.09 3 9.5 3S3 5.91 3 9.5S5.91 16 9.5 16C11.11 16 12.59 15.41 13.73 14.43L14 14.71V15.5L19 20.49L20.49 19L15.5 14ZM9.5 14C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14ZM12 10H10V12H9V10H7V9H9V7H10V9H12V10Z"/>
                    </svg>
                </button>
                <button class="btn-control" id="zoom-out" title="Zoom arrière">
                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5 14H14.71L14.43 13.73C15.41 12.59 16 11.11 16 9.5C16 5.91 13.09 3 9.5 3S3 5.91 3 9.5S5.91 16 9.5 16C11.11 16 12.59 15.41 13.73 14.43L14 14.71V15.5L19 20.49L20.49 19L15.5 14ZM9.5 14C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14ZM7 9H12V10H7V9Z"/>
                    </svg>
                </button>
            </div>
            <canvas id="fabric-canvas" style="display: none;"></canvas>
        </aside>

        <div class="controls-panel-config">
            <div class="panel-content">
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
                            <div class="option-box" data-value="fixation_murale" data-price="15">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V11H13V17ZM13 9H11V7H13V9Z"/>
                                </svg>
                                <span class="label">Fixation Murale</span><span class="price">+15€</span>
                            </div>
                            <div class="option-box" data-value="marbre_blanc" data-price="30">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V5H19V19Z"/>
                                </svg>
                                <span class="label">Marbre Blanc</span><span class="price">+30€</span>
                            </div>
                            <div class="option-box selected" data-value="plaque_seule" data-price="0">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 3H3C1.9 3 1 3.9 1 5V19C1 20.1 1.9 21 3 21H21C22.1 21 23 20.1 23 19V5C23 3.9 22.1 3 21 3ZM21 19H3V5H21V19Z"/>
                                </svg>
                                <span class="label">Plaque Seule</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="panel-step-2" class="panel-step">
                    <h2 class="panel-title">Choix du Fond</h2>
                    <div id="fonds-container">
                        <?php foreach($catalogue_fonds as $theme => $images): ?>
                            <div class="option-group">
                                <h3 class="option-group-title" title="<?php echo htmlspecialchars($theme); ?>"><?php echo htmlspecialchars($theme); ?></h3>
                                <div class="option-grid grid-3">
                                    <?php foreach($images as $path): ?>
                                    <div class="option-box" data-img-src="<?php echo BASE_URL . $path; ?>"><img src="<?php echo BASE_URL . $path; ?>" alt="Fond <?php echo htmlspecialchars(basename($path, '.jpg')); ?>" style="width:100%; height: 50px; object-fit:cover;"></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div id="panel-step-3" class="panel-step">
                     <h2 class="panel-title">Personnalisation (Texte & Photos)</h2>
                     <div class="option-group">
                        <h3 class="option-group-title">Votre Texte <span class="info-icon" title="Ajoutez ou modifiez le texte sur votre plaque.">ⓘ</span></h3>
                        <textarea id="text-input-1" placeholder="Écrivez votre message..." rows="3"></textarea>
                        <div class="text-editor-toolbar">
                            <select id="font-family-select" class="tool-btn">
                                <option value="Montserrat">Montserrat</option>
                                <option value="Playfair Display">Playfair Display</option>
                                <option value="Arial">Arial</option>
                                <option value="Times New Roman">Times New Roman</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Verdana">Verdana</option>
                            </select>
                            <input type="number" id="font-size-input" class="tool-btn" value="40" min="10" max="100" title="Taille du texte">
                            <input type="color" id="font-color-input" class="tool-btn" value="#000000" title="Couleur du texte">
                            <button id="text-bold-btn" class="tool-btn" title="Gras">B</button>
                            <button id="text-italic-btn" class="tool-btn" title="Italique">I</button>
                            <button id="text-underline-btn" class="tool-btn" title="Souligné">U</button>
                            <button id="text-align-left-btn" class="tool-btn" title="Aligner à gauche">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 15H3V17H15V15ZM15 7H3V9H15V7ZM3 13H21V11H3V13ZM3 21H21V19H3V21ZM3 3V5H21V3H3Z"/>
                                </svg>
                            </button>
                            <button id="text-align-center-btn" class="tool-btn" title="Centrer">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 15H9V17H15V15ZM15 7H9V9H15V7ZM3 13H21V11H3V13ZM3 21H21V19H3V21ZM3 3V5H21V3H3Z"/>
                                </svg>
                            </button>
                            <button id="text-align-right-btn" class="tool-btn" title="Aligner à droite">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 15H9V17H21V15ZM21 7H9V9H21V7ZM3 13H21V11H3V13ZM3 21H21V19H3V21ZM3 3V5H21V3H3Z"/>
                                </svg>
                            </button>
                        </div>
                     </div>
                      <div class="option-group">
                        <h3 class="option-group-title">Ajout de photos <span class="info-icon" title="Importez une image depuis votre ordinateur.">ⓘ</span></h3>
                        <label for="photo-upload" class="upload-zone">
                            <p>🖼️ Importer</p>
                            <span>Cliquez ou glissez une image ici (JPG, PNG)</span>
                        </label>
                        <input type="file" id="photo-upload" accept="image/jpeg, image/png" multiple style="display:none;">
                        <div class="object-controls-toolbar" style="margin-top: 15px;">
                            <button id="delete-object-btn" class="tool-btn" title="Supprimer l'objet sélectionné">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V7H6V19ZM19 4H15.5L14.5 3H9.5L8.5 4H5V6H19V4Z"/>
                                </svg>
                            </button>
                            <button id="bring-forward-btn" class="tool-btn" title="Avancer l'objet">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 17H7V15H21V17ZM21 11H13V9H21V11ZM21 5H3V7H21V5ZM11 19H3V17H11V19ZM11 13H3V11H11V13ZM11 7H3V5H11V7Z"/>
                                </svg>
                            </button>
                            <button id="send-backward-btn" class="tool-btn" title="Reculer l'objet">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 17H17V15H3V17ZM3 11H11V9H3V11ZM3 5H21V7H3V5ZM13 19H21V17H13V19ZM13 13H21V11H13V13ZM13 7H21V5H13V7Z"/>
                                </svg>
                            </button>
                            <button id="duplicate-object-btn" class="tool-btn" title="Dupliquer l'objet">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 1H4C2.9 1 2 1.9 2 3V17H4V3H16V1ZM19 5H8C6.9 5 6 5.9 6 7V21C6 22.1 6.9 23 8 23H19C20.1 23 21 22.1 21 21V7C21 5.9 20.1 5 19 5ZM19 21H8V7H19V21Z"/>
                                </svg>
                            </button>
                        </div>
                     </div>
                </div>

                <div id="panel-step-4" class="panel-step">
                    <h2 class="panel-title">Options et Validation</h2>
                    <div class="option-group">
                        <h3 class="option-group-title">Services complémentaires <span class="info-icon" title="Choisissez des services additionnels pour votre commande.">ⓘ</span></h3>
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
                        <textarea placeholder="Laissez vos commentaires pour le designer..." id="designer-comment" style="display:none;"></textarea>
                    </div>
                </div>
            </div>

            <div class="config-footer">
                <div id="summary-section">
                    <div class="summary-line">
                        <span class="label">Total</span>
                        <span class="value" id="total-price">175,00€</span> </div>
                </div>
                <div class="navigation-buttons">
                    <button id="btn-back" class="btn-config btn-back" disabled>Retour</button>
                    <button id="btn-continue" class="btn-config btn-continue">Ajouter au panier</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>assets/js/configurateur.min.js"></script>

</body>
</html>