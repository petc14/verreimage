<?php
/**
 * Fichier du Pied de Page (Footer)
 *
 * Ce fichier est inclus à la fin de chaque page du site principal.
 * Il ferme les balises HTML principales, contient les liens de navigation du pied de page,
 * les informations de contact, et inclut les scripts JavaScript nécessaires.
 * Il doit être placé dans le dossier /templates/
 */
?>
    </main>

    <footer class="main-footer">
        <div class="footer-container container">
            <div class="footer-col">
                <h4 class="footer-title">Verre & Image</h4>
                <p>L'art de l'hommage, gravé pour l'éternité, conçu avec soin dans notre atelier de Honfleur.</p>
                <p><strong>Adresse :</strong><br>1296 Av. du Président Duchesne<br>14600 HONFLEUR</p>
            </div>
            <div class="footer-col">
                <h4 class="footer-title">Navigation</h4>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>boutique.php">Boutique & Modèles</a></li>
                    <li><a href="<?php echo BASE_URL; ?>configurateur.php">Créer une plaque</a></li>
                    <li><a href="<?php echo BASE_URL; ?>presentation.php">Notre Savoir-Faire</a></li>
                    <li><a href="<?php echo BASE_URL; ?>realisations.php">Nos Réalisations</a></li>
                    <li><a href="<?php echo BASE_URL; ?>contact.php">Contactez-nous</a></li>
                </ul>
            </div>
             <div class="footer-col">
                <h4 class="footer-title">Informations</h4>
                <ul>
                    <li>Lundi - Vendredi : 9h30 - 18h30</li>
                    <li>Tél : <strong>01 42 09 52 09</strong></li>
                    <li>Email : contact@verreimage.com</li>
                    <li><a href="<?php echo BASE_URL; ?>mentions-legales.php">Mentions Légales</a></li>
                    <li><a href="#">Conditions Générales de Vente</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date("Y"); ?> <?php echo SITE_NAME; ?>. Tous droits réservés.</p>
        </div>
    </footer>
    
    <!-- Inclusion du fichier JavaScript principal à la fin du body pour de meilleures performances -->
    <script src="<?php echo BASE_URL; ?>js/script.js"></script>

</body>
</html>
