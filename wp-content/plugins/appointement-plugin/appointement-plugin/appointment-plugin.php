<?php
/**
 * Plugin Name: Appointment Plugin
 * Description: Un plugin simple pour gérer les rendez-vous
 * Version: 1.0.0
 * Author: TEAM 7
 */

// Si ce fichier est appelé directement, on sort
if (!defined('WPINC')) {
    die;
}

// Définir les constantes
define('APPOINTMENT_PLUGIN_VERSION', '1.0.0');
define('APPOINTMENT_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Activer le débogage
if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', true);
}
if (!defined('WP_DEBUG_LOG')) {
    define('WP_DEBUG_LOG', true);
}
if (!defined('WP_DEBUG_DISPLAY')) {
    define('WP_DEBUG_DISPLAY', false);
}

// Charger les dépendances
require_once APPOINTMENT_PLUGIN_DIR . 'includes/class-appointment-activator.php';
require_once APPOINTMENT_PLUGIN_DIR . 'includes/class-appointment-deactivator.php';
require_once APPOINTMENT_PLUGIN_DIR . 'includes/class-appointment-database.php';
require_once APPOINTMENT_PLUGIN_DIR . 'includes/class-appointment-admin.php';
require_once APPOINTMENT_PLUGIN_DIR . 'includes/class-appointment-public.php';
require_once APPOINTMENT_PLUGIN_DIR . 'includes/class-appointment-mailer.php';

// Activation et désactivation du plugin
register_activation_hook(__FILE__, array('Appointment_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('Appointment_Deactivator', 'deactivate'));

// Enregistrer les paramètres
function register_appointment_settings() {
    register_setting('appointment_plugin_options', 'appointment_notification_email');
    register_setting('appointment_plugin_options', 'appointment_default_duration');
    register_setting('appointment_plugin_options', 'appointment_opening_time');
    register_setting('appointment_plugin_options', 'appointment_closing_time');
    register_setting('appointment_plugin_options', 'appointment_closed_days');
    register_setting('appointment_plugin_options', 'appointment_notify_admin');
    register_setting('appointment_plugin_options', 'appointment_notify_client');
    
    // Paramètres SMTP
    register_setting('appointment_plugin_options', 'appointment_smtp_host');
    register_setting('appointment_plugin_options', 'appointment_smtp_port');
    register_setting('appointment_plugin_options', 'appointment_smtp_encryption');
    register_setting('appointment_plugin_options', 'appointment_smtp_username');
    register_setting('appointment_plugin_options', 'appointment_smtp_password');
    register_setting('appointment_plugin_options', 'appointment_smtp_from_email');
    register_setting('appointment_plugin_options', 'appointment_smtp_from_name');
}
add_action('admin_init', 'register_appointment_settings');

/**
 * Démarrer le plugin
 */
function run_appointment_plugin() {
    $plugin_name = 'appointment-plugin';
    $version = APPOINTMENT_PLUGIN_VERSION;

    // Initialiser l'interface publique
    $plugin_public = new Appointment_Public($plugin_name, $version);
    add_action('wp_enqueue_scripts', array($plugin_public, 'enqueue_styles'));
    add_action('wp_enqueue_scripts', array($plugin_public, 'enqueue_scripts'));
    
    // Ajouter le shortcode pour le formulaire de réservation
    add_shortcode('appointment_form', array($plugin_public, 'appointment_form_shortcode'));
    
    // Ajouter les actions AJAX
    add_action('wp_ajax_book_appointment', array($plugin_public, 'handle_appointment_booking'));
    add_action('wp_ajax_nopriv_book_appointment', array($plugin_public, 'handle_appointment_booking'));

    // Initialiser l'interface d'administration
    if (is_admin()) {
        $plugin_admin = new Appointment_Admin($plugin_name, $version);
        $plugin_admin->init();
    }
}

// Démarrer le plugin
run_appointment_plugin();
