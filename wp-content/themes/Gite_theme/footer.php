<footer class="site-footer  text-dark pt-5 pb-3">
    <div class="container">
        <div class="row text-center text-md-start">
            <!-- Colonne 1 : Logo et Réseaux sociaux -->
            <div class="col-md-3 mb-4">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/logo.png" alt="Logo du Gîte Montplaisir" class="img-fluid mb-3">
                <div class="d-flex justify-content-center justify-content-md-start gap-3">
                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/instagram-icon.png" alt="Instagram" class="social-icon"></a>
                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/youtube-icon.png" alt="YouTube" class="social-icon"></a>
                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/linkedin-icon.png" alt="LinkedIn" class="social-icon"></a>
                </div>
            </div>

            <!-- Colonne 2 : Navigation -->
            <div class="col-md-3 mb-4">
                <h4 class="h6">Navigation</h4>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-dark text-decoration-none">Accueil</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Chambres</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Activités</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Tarifs et Promotions</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Colonne 3 : Contactez-nous -->
            <div class="col-md-3 mb-4">
                <h4 class="h6">Contactez-nous</h4>
                <p>Adresse : Gîte Montplaisir,<br>[Adresse]</p>
                <p>Tél : +33 (0)6 22 60 37 95</p>
                <p>Email : <a href="mailto:legitermontplaisir@gmail.com" class="text-dark text-decoration-none">legitermontplaisir@gmail.com</a></p>
            </div>

            <!-- Colonne 4 : Informations légales -->
            <div class="col-md-3 mb-4">
                <h4 class="h6">Informations légales</h4>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-dark text-decoration-none">Mentions légales</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Politique de confidentialité</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Conditions générales de location</a></li>
                </ul>
            </div>
        </div>

        <!-- Ligne du bas -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="mb-0 small">&copy; <?php echo date('Y'); ?> Gîte Montplaisir. Tous droits réservés.</p>
            </div>
        </div>
    </div>
    <?php wp_footer(); ?>
</footer>
