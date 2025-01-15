<?php

class Appointment_Database {
    private $table_name;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'appointments';
        
        error_log("=== Test of writing to the log ===");
        error_log("Table name: " . $this->table_name);
        error_log("WordPress prefix: " . $wpdb->prefix);
        
        // Check if the table exists
        $this->maybe_create_table();
    }

    private function maybe_create_table() {
        error_log("Checking if table exists: " . $this->table_name);
        
        $table_exists = $this->wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") === $this->table_name;
        error_log("Table exists? " . ($table_exists ? "Yes" : "No"));

        if (!$table_exists) {
            error_log("Creating appointments table...");
            
            $this->create_table();
        }
    }

    public function create_table() {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            client_name varchar(100) NOT NULL,
            client_email varchar(100) NOT NULL,
            appointment_date datetime NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        error_log("Table creation SQL: $sql");
    }

    public function insert_appointment($data) {
        try {
            if (empty($data['client_name']) || empty($data['client_email']) || empty($data['appointment_date'])) {
                error_log("Missing required fields in insert_appointment");
                return false;
            }

            $result = $this->wpdb->insert(
                $this->table_name,
                array(
                    'client_name' => $data['client_name'],
                    'client_email' => $data['client_email'],
                    'appointment_date' => $data['appointment_date']
                ),
                array('%s', '%s', '%s')
            );

            if ($result === false) {
                error_log("Database error in insert_appointment: " . $this->wpdb->last_error);
                return false;
            }

            return $this->wpdb->insert_id;
        } catch (Exception $e) {
            error_log("Error in insert_appointment: " . $e->getMessage());
            return false;
        }
    }

    public function get_appointments() {
        return $this->wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY appointment_date DESC");
    }

    public function get_appointments_by_date($date) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE DATE(appointment_date) = %s",
                $date
            )
        );
    }
}
