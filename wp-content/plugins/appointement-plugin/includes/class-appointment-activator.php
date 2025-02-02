<?php

class Appointment_Activator {
    public static function activate() {
        error_log('Activating Appointment Plugin...');
        
        $database = new Appointment_Database();
        $database->create_table();
        
        error_log('Appointment Plugin activated successfully.');
    }

    private static function add_plugin_roles() {
        // Ajouter la capacité de gérer les rendez-vous aux administrateurs
        $admin_role = get_role('administrator');
        if ($admin_role) {
            $admin_role->add_cap('manage_appointments');
        }
    }
}
