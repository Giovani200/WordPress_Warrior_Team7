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
        } else {
            error_log("Table exists, checking for updates...");
            $this->maybe_update_table();
        }
    }

    private function maybe_update_table() {
        $columns = $this->wpdb->get_results("SHOW COLUMNS FROM {$this->table_name}");
        $column_names = array_map(function($column) {
            return $column->Field;
        }, $columns);

        $missing_columns = array();
        
        // Vérifier les colonnes manquantes
        if (!in_array('duration', $column_names)) {
            $missing_columns[] = "ADD COLUMN `duration` int(11) DEFAULT 60";
        }
        if (!in_array('status', $column_names)) {
            $missing_columns[] = "ADD COLUMN `status` varchar(20) DEFAULT 'pending'";
        }
        if (!in_array('notes', $column_names)) {
            $missing_columns[] = "ADD COLUMN `notes` text";
        }
        if (!in_array('created_at', $column_names)) {
            $missing_columns[] = "ADD COLUMN `created_at` datetime DEFAULT CURRENT_TIMESTAMP";
        }
        if (!in_array('updated_at', $column_names)) {
            $missing_columns[] = "ADD COLUMN `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        }

        // Si des colonnes sont manquantes, les ajouter
        if (!empty($missing_columns)) {
            error_log("Adding missing columns to appointments table");
            $alter_query = "ALTER TABLE {$this->table_name} " . implode(", ", $missing_columns);
            error_log("Alter query: " . $alter_query);
            
            $result = $this->wpdb->query($alter_query);
            if ($result === false) {
                error_log("Error updating table structure: " . $this->wpdb->last_error);
            } else {
                error_log("Table structure updated successfully");
            }
        }

        // Vérifier et ajouter les index manquants
        $indexes = $this->wpdb->get_results("SHOW INDEX FROM {$this->table_name}");
        $index_names = array_map(function($index) {
            return $index->Key_name;
        }, $indexes);

        if (!in_array('idx_appointment_date', $index_names)) {
            $this->wpdb->query("ALTER TABLE {$this->table_name} ADD INDEX idx_appointment_date (appointment_date)");
        }
        if (!in_array('idx_status', $index_names)) {
            $this->wpdb->query("ALTER TABLE {$this->table_name} ADD INDEX idx_status (status)");
        }
        if (!in_array('idx_client_email', $index_names)) {
            $this->wpdb->query("ALTER TABLE {$this->table_name} ADD INDEX idx_client_email (client_email)");
        }
    }

    public function create_table() {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            client_name varchar(100) NOT NULL,
            client_email varchar(100) NOT NULL,
            appointment_date datetime NOT NULL,
            duration int(11) DEFAULT 60,
            status varchar(20) DEFAULT 'pending',
            notes text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY idx_appointment_date (appointment_date),
            KEY idx_status (status),
            KEY idx_client_email (client_email)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        error_log("Table creation SQL: $sql");
    }

    public function insert_appointment($data) {
        try {
            error_log("Starting insert_appointment with data: " . print_r($data, true));

            if (!$this->validate_appointment_data($data)) {
                error_log("Validation failed for appointment data");
                return array(
                    'success' => false,
                    'message' => 'Validation des données échouée'
                );
            }

            // Format the appointment date correctly
            $appointment_datetime = new DateTime($data['appointment_date']);
            $formatted_date = $appointment_datetime->format('Y-m-d H:i:s');

            if ($this->check_appointment_overlap($formatted_date, $data['duration'] ?? 60)) {
                error_log("Appointment overlap detected for date: " . $formatted_date);
                return array(
                    'success' => false,
                    'message' => 'Ce créneau est déjà réservé'
                );
            }

            $insert_data = array(
                'client_name' => sanitize_text_field($data['client_name']),
                'client_email' => sanitize_email($data['client_email']),
                'appointment_date' => $formatted_date,
                'duration' => absint($data['duration'] ?? 60),
                'status' => 'pending',
                'notes' => sanitize_textarea_field($data['notes'] ?? '')
            );

            error_log("Attempting to insert with data: " . print_r($insert_data, true));

            $result = $this->wpdb->insert(
                $this->table_name,
                $insert_data,
                array('%s', '%s', '%s', '%d', '%s', '%s')
            );

            if ($result === false) {
                error_log("Database error in insert_appointment: " . $this->wpdb->last_error);
                return array(
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement dans la base de données'
                );
            }

            $insert_id = $this->wpdb->insert_id;
            error_log("Successfully inserted appointment with ID: " . $insert_id);

            return array(
                'success' => true,
                'message' => 'Rendez-vous enregistré avec succès',
                'appointment_id' => $insert_id
            );

        } catch (Exception $e) {
            error_log("Error in insert_appointment: " . $e->getMessage());
            return array(
                'success' => false,
                'message' => 'Une erreur inattendue est survenue'
            );
        }
    }

    private function validate_appointment_data($data) {
        error_log("Validating appointment data: " . print_r($data, true));

        if (empty($data['client_name'])) {
            error_log("Missing client name");
            return false;
        }

        if (empty($data['client_email'])) {
            error_log("Missing client email");
            return false;
        }

        if (empty($data['appointment_date'])) {
            error_log("Missing appointment date");
            return false;
        }

        if (!is_email($data['client_email'])) {
            error_log("Invalid email format: " . $data['client_email']);
            return false;
        }

        try {
            $appointment_datetime = new DateTime($data['appointment_date']);
            $current_datetime = new DateTime();

            if ($appointment_datetime < $current_datetime) {
                error_log("Appointment date is in the past: " . $data['appointment_date']);
                return false;
            }
        } catch (Exception $e) {
            error_log("Invalid date format: " . $data['appointment_date']);
            return false;
        }

        return true;
    }

    private function check_appointment_overlap($appointment_date, $duration) {
        try {
            $start_time = new DateTime($appointment_date);
            $end_time = clone $start_time;
            $end_time->modify("+{$duration} minutes");

            $query = $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_name} 
                WHERE appointment_date < %s 
                AND DATE_ADD(appointment_date, INTERVAL duration MINUTE) > %s
                AND status != 'cancelled'",
                $end_time->format('Y-m-d H:i:s'),
                $start_time->format('Y-m-d H:i:s')
            );

            error_log("Overlap check query: " . $query);
            $count = (int) $this->wpdb->get_var($query);
            error_log("Overlap count: " . $count);

            return $count > 0;
        } catch (Exception $e) {
            error_log("Error in check_appointment_overlap: " . $e->getMessage());
            return true; // En cas d'erreur, on considère qu'il y a chevauchement par sécurité
        }
    }

    public function get_appointments() {
        return $this->wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY appointment_date DESC");
    }

    public function get_appointments_by_date($date) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} 
                WHERE DATE(appointment_date) = %s 
                AND status != 'cancelled'
                ORDER BY appointment_date ASC",
                $date
            )
        );
    }

    public function update_appointment_status($appointment_id, $status) {
        return $this->wpdb->update(
            $this->table_name,
            array('status' => $status),
            array('id' => $appointment_id),
            array('%s'),
            array('%d')
        );
    }

    public function get_upcoming_appointments($limit = 10) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} 
                WHERE appointment_date >= NOW() 
                AND status != 'cancelled'
                ORDER BY appointment_date ASC 
                LIMIT %d",
                $limit
            )
        );
    }

}
