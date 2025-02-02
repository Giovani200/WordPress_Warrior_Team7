<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <?php
    // Meta description dynamique
    $meta_description = get_bloginfo('description');
    
    if (is_home()) {
        $meta_description = "Découvrez notre gîte .";
    } elseif (is_front_page()) {
        $meta_description = "Bienvenue sur Gîte Montplaisir ! Un hébergement confortable en pleine nature dans Le Perche, France.";
    } elseif (is_single()) {
        $meta_description = get_the_excerpt();
    } elseif (is_page() && !is_front_page()) {
        $meta_description = get_the_title() . " - Découvrez tout ce qu'il faut savoir.";
    } elseif (is_category()) {
        $meta_description = "Parcourez nos articles dédiés à la catégorie " . single_cat_title('', false) . ".";
    }
    ?>

    <meta name="description" content="<?php echo esc_attr($meta_description); ?>">
    
    <!-- SEO et indexation -->
    <?php
    $robots_content = "index, follow"; 
    if (is_search() || is_404()) {
        $robots_content = "noindex, nofollow"; 
    }
    ?>
    <meta name="robots" content="<?php echo esc_attr($robots_content); ?>">
    
    <!-- Canonical URL pour éviter le contenu dupliqué -->
    <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">
    
   
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="site">
        <header class="site-header">
            <nav class="navbar navbar-expand-lg navbar-light bg-white">
                <div class="container" style="left: 1px; position: relative;">
                    <!-- Logo -->
                    <a class="navbar-brand" href="<?php echo home_url(); ?>">
                        <?php
                        if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                        ?>
                            <img src="https://www.gitemontplaisir.com/wp-content/uploads/2025/01/vvvvvvvvvvvvvvvvvvvvvvvvvvvv.png" alt="Logo du site" height="50">
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
                    <div class="collapse navbar-collapse" id="navbarNav" style="
    position: relative;
    
">
                        <?php 
                        wp_nav_menu(array(
                            'menu' => 'top-menu',
                            'theme_location' => 'primary',
                            'depth' => 2,
                            'container' => 'div',
                            'container_class' => 'collapse navbar-collapse',
                            'container_id' => 'navbarNav',
                            'menu_class' => 'navbar-nav ms-auto align-items-center justify-content-end',
                            'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
                            'walker' => new WP_Bootstrap_Navwalker(),
                        ));
                        ?>
                        
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item ms-3">
                                <a class="btn btn-success" href="https://www.gitemontplaisir.com/contact/" aria-label= "Accéder à la page de contact">Nous contacter</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Contenu principal -->
        
    </div>
</body>
</html>