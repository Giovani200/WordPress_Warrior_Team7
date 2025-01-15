<?php

class Appointment_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . '../assets/css/admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . '../assets/js/admin.js', array('jquery'), $this->version, true);
        
        wp_localize_script($this->plugin_name . '-admin', 'appointmentAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('appointment_admin_nonce')
        ));
    }

    public function add_menu_pages() {
        add_menu_page(
            'Gestion des rendez-vous',
            'Rendez-vous',
            'manage_options',
            'appointment-plugin',
            array($this, 'display_appointments_page'),
            'dashicons-calendar-alt',
            30
        );

        add_submenu_page(
            'appointment-plugin',
            'Liste des rendez-vous',
            'Liste',
            'manage_options',
            'appointment-plugin',
            array($this, 'display_appointments_page')
        );

        add_submenu_page(
            'appointment-plugin',
            'Paramètres',
            'Paramètres',
            'manage_options',
            'appointment-settings',
            array($this, 'display_settings_page')
        );
    }

    public function display_appointments_page() {
        $database = new Appointment_Database();
        $appointments = $database->get_appointments();
        require_once APPOINTMENT_PLUGIN_DIR . 'admin/partials/appointment-admin-display.php';
    }

    public function display_settings_page() {
        require_once APPOINTMENT_PLUGIN_DIR . 'admin/partials/appointment-admin-settings.php';
    }

    public function init() {
        add_action('admin_menu', array($this, 'add_menu_pages'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
}
