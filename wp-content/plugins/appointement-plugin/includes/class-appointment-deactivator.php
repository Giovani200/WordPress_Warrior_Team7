<?php

class Appointment_Deactivator {
    public static function deactivate() {
        error_log('Deactivating Appointment Plugin...');
        // Ne pas supprimer les données lors de la désactivation
    }

    private static function remove_plugin_roles() {
        // Retirer la capacité de gérer les rendez-vous des administrateurs
        $admin_role = get_role('administrator');
        if ($admin_role) {
            $admin_role->remove_cap('manage_appointments');
        }
    }
}
