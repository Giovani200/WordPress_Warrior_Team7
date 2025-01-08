<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php bloginfo('description'); ?>">

    <?php if(is_home()): ?>
        <meta name="description" content="Le site présente la page des articles du blog"/>
    <?php endif ?>

    <?php if(is_front_page()): ?>
        <meta name="description" content="Le site présente la page d'accueil"/>
    <?php endif ?>

    <?php if(is_page() && !is_front_page()): ?>
        <meta name="description" content="Le site présente le contenu de type page"/>
    <?php endif ?>

    <?php if(is_category()): ?>
        <meta name="description" content="Liste des articles pour la catégorie <?php echo single_cat_title('',false) ?> "/>
    <?php endif ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="site">
        <header class="site-header">
            <nav class="navbar navbar-expand-lg navbar-light bg-white">
                <div class="container">
                    <!-- Logo -->
                    <a class="navbar-brand" href="<?php echo home_url(); ?>">
                        <?php
                        if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                        ?>
                            <img src="https://www.communication-pictomatic.com/uploadfiles/vos_images/Blog/Maison%20Escaouraou/MaisonEscaouraou_Com_logo1.jpg" alt="Logo Gîte" height="50">
                        <?php
                        }
                        ?>
                    </a>

                    <!-- Informations de contact -->
                    <div class="contact-info d-none d-lg-flex align-items-center me-auto ms-4">
                        <div class="location me-4">
                            <span class="text-muted">France, Le Perche</span>
                        </div>
                        <div class="phone">
                            <span class="text-muted">06 22 60 37 95</span>
                        </div>
                    </div>

                    <!-- Bouton toggle pour mobile -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Menu principal -->
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <?php 
                        wp_nav_menu(array(
                            'menu' => 'top-menu',
                            'theme_location' => 'primary',
                            'depth' => 2,
                            'container' => 'div',
                            'container_class' => 'collapse navbar-collapse',
                            'container_id' => 'navbarNav',
                            'menu_class' => 'navbar-nav ms-auto align-items-center',
                            'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
                            'walker' => new WP_Bootstrap_Navwalker(),
                        ));
                        ?>
                        
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item ms-3">
                                <a class="btn btn-success" href="#">Nous contacter</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Contenu principal -->
        <main class="site-main">
            <div class="content-grid">
                <div class="content-main">
                    <!-- <div class="container">
                        <div class="jumbotron">
                            <h1>Coucou 'est nous</h1>
                        </div>
                    </div> -->
                </div>
            </div>
        </main>
    </div>
</body>
</html>
