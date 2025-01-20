<?php
add_filter('use_block_editor_for_post', '__return_true');
add_theme_support('wp-block-editor');

/**
 * Fichier principal des fonctionnalités du thème
 * Ce fichier contient toutes les fonctions essentielles pour le bon fonctionnement du thème
 */

// ========================================
// DÉFINITION DES CONSTANTES DU THÈME
// ========================================

// Version actuelle du thème - utilisée pour le cache des fichiers CSS et JS
define('Gite_THEME_VERSION', '1.1.0');

// Version minimale de WordPress requise pour ce thème
define('Gite_THEME_MIN_WP_VERSION', '6.4');

// ========================================
// CHARGEMENT DES DÉPENDANCES EXTERNES
// ========================================

// Chargement du Bootstrap Navwalker - permet d'intégrer Bootstrap avec les menus WordPress
require_once get_template_directory() . '/includes/class-wp-bootstrap-navwalker.php';

/**
 * Fonction pour charger les styles et scripts du thème
 * Cette fonction est appelée automatiquement par WordPress lors du chargement des pages
 */
function gite_scripts() {
    // ========================================
    // CHARGEMENT DES STYLES CSS
    // ========================================
    
    // Chargement de Bootstrap CSS depuis le CDN (Content Delivery Network)
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2', 'all');
    
    // Gestion intelligente des versions CSS pour le cache
    // En mode développement (WP_DEBUG), on force le rechargement avec timestamp
    // En production, on utilise la version du thème
    $css_version = WP_DEBUG ? time() : Gite_THEME_VERSION;
    
    // Chargement des styles personnalisés du thème
    wp_enqueue_style('gite-custom-styles', get_template_directory_uri() . '/css/custom.css', array('bootstrap'), $css_version, 'all');
    wp_enqueue_style('gite_custom', get_template_directory_uri() . '/style.css', array('bootstrap', 'gite-custom-styles'), $css_version, 'all');

    // ========================================
    // CHARGEMENT DES SCRIPTS JAVASCRIPT
    // ========================================
    
    // jQuery est inclus par défaut avec WordPress
    wp_enqueue_script('jquery');
    
    // Chargement de Popper.js - requis pour certaines fonctionnalités Bootstrap
    wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js', array(), '2.11.8', true);
    
    // Chargement de Bootstrap JS
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js', array('jquery', 'popper'), '5.3.2', true);
    
    // Version des scripts JS (même logique que pour le CSS)
    $js_version = WP_DEBUG ? time() : Gite_THEME_VERSION;
    
    // Chargement du script personnalisé du thème
    wp_enqueue_script('gite_script', get_template_directory_uri() . '/js/gite.js', array('jquery', 'bootstrap'), $js_version, true);

    // Configuration pour JavaScript
    // Permet d'accéder à des variables PHP depuis JavaScript
    wp_localize_script('gite_script', 'giteData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'), // URL pour les requêtes AJAX
        'nonce' => wp_create_nonce('gite_nonce')  // Jeton de sécurité
    ));
}
// Déclenche le chargement des scripts quand WordPress charge la page
add_action('wp_enqueue_scripts', 'gite_scripts');

/**
 * Configuration des styles pour l'administration WordPress
 * Permet d'avoir un style cohérent dans l'interface admin
 */
function gite_admin_scripts() {
    wp_enqueue_style('bootstrap-adm-core', get_template_directory_uri() . '/css/bootstrap.min.css', array(), Gite_THEME_VERSION);
}
add_action('admin_init', 'gite_admin_scripts');

/**
 * Configuration principale du thème
 * Cette fonction définit toutes les fonctionnalités supportées par le thème
 */
function gite_setup() {
    // ========================================
    // FONCTIONNALITÉS WORDPRESS DE BASE
    // ========================================
    
    // Permet d'ajouter une image mise en avant aux articles
    add_theme_support('post-thumbnails');
    
    // Permet à WordPress de gérer le titre des pages
    add_theme_support('title-tag');
    
    // Permet d'ajouter un logo personnalisé
    add_theme_support('custom-logo');
    
    // Active les flux RSS automatiques
    add_theme_support('automatic-feed-links');
    
    // Active les fonctionnalités HTML5 modernes
    add_theme_support('html5', array(
        'search-form',  // Formulaire de recherche
        'comment-form', // Formulaire de commentaires
        'comment-list', // Liste des commentaires
        'gallery',      // Galeries d'images
        'caption',      // Légendes d'images
        'style',        // Styles intégrés
        'script'        // Scripts intégrés
    ));

    // ========================================
    // SUPPORT DE L'ÉDITEUR GUTENBERG
    // ========================================
    
    // Active les styles de blocs par défaut
    add_theme_support('wp-block-styles');
    
    // Permet les alignements larges et pleine largeur
    add_theme_support('align-wide');
    
    // Permet les contenus embarqués responsives
    add_theme_support('responsive-embeds');
    
    // Active les styles personnalisés dans l'éditeur
    add_theme_support('editor-styles');
    add_editor_style('css/editor-style.css');

    // ========================================
    // CONFIGURATION DES MENUS
    // ========================================
    
    // Enregistre les emplacements de menu disponibles
    register_nav_menus(array(
        'primary' => __('Menu Principal', 'gite'),   // Menu principal en haut de page
        'footer' => __('Menu Pied de page', 'gite')  // Menu dans le pied de page
    ));

    // ========================================
    // SÉCURITÉ ET CONFIGURATION GÉNÉRALE
    // ========================================
    
    // Masque la version de WordPress dans le code source
    remove_action('wp_head', 'wp_generator');
    
    // Définit la largeur maximale du contenu
    if (!isset($content_width)) {
        $content_width = 1200; // En pixels
    }
}
// Active la configuration du thème
add_action('after_setup_theme', 'gite_setup');

/**
 * Affiche les métadonnées d'un article (date et catégorie)
 * @param string $date1 Date au format machine
 * @param string $date2 Date formatée pour l'affichage
 * @param string $cat Catégorie de l'article
 */
function gite_post_meta($date1, $date2, $cat) {
    $chaine = 'publié le <time class="entry-date" datetime="';
    // $chaine .= $date1;
    // $chaine .= '">';
    $chaine .= $date2;
    // $chaine .= '</time> dans la catégorie ';
    $chaine .= $cat;
    return $chaine;
}

/**
 * Personnalise le texte "Lire la suite" des extraits d'articles
 * @param string $more Texte par défaut
 * @return string Texte personnalisé avec lien
 */
function new_excerpt_more($more) {
    return '<a class="more-link" href="' . get_permalink() . '">' . ' [lire la suite]' . '</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * Vérifie que la version de WordPress est compatible
 * Affiche un message d'erreur dans l'administration si la version est trop ancienne
 */
function gite_check_wp_version() {
    if (version_compare(get_bloginfo('version'), Gite_THEME_MIN_WP_VERSION, '<')) {
        add_action('admin_notices', function() {
            echo '<div class="error"><p>';
            printf(
                __('Ce thème nécessite WordPress version %s ou supérieure. Vous utilisez actuellement la version %s.', 'gite'),
                Gite_THEME_MIN_WP_VERSION,
                get_bloginfo('version')
            );
            echo '</p></div>';
        });
    }
}

// paramètre gutenberg
function gite_gutenberg_support() {
    // Active l'éditeur de blocs Gutenberg
    add_theme_support('wp-block-editor');

    // Active les styles spécifiques de l'éditeur
    add_theme_support('editor-styles');
    add_editor_style('css/editor-style.css');

    // Active les alignements larges et pleine largeur
    add_theme_support('align-wide');

    // Active la prise en charge des blocs responsives
    add_theme_support('responsive-embeds');

    // Active les styles des blocs par défaut
    add_theme_support('wp-block-styles');
}
add_action('after_setup_theme', 'gite_gutenberg_support');

add_action('admin_init', 'gite_check_wp_version');

function gite_enqueue_gutenberg_styles() {
    wp_enqueue_style('gutenberg-blocks', get_template_directory_uri() . '/css/gutenberg.css', array(), '1.0', 'all');
}
add_action('enqueue_block_editor_assets', 'gite_enqueue_gutenberg_styles');


// footer 

// Enregistrer le menu du footer
function Gite_register_menus() {
    register_nav_menus(array(
        'footer-menu' => __('Footer Menu', 'Gite'),
    ));
}
add_action('after_setup_theme', 'Gite_register_menus');


// Enregistrer une zone de widgets pour le footer
function Gite_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'mon-theme'),
        'id'            => 'footer-widgets',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'Gite_init');

