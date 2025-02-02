<?php

class Appointment_Public {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Ajouter les actions AJAX
        add_action('wp_ajax_get_available_slots', array($this, 'get_available_slots'));
        add_action('wp_ajax_nopriv_get_available_slots', array($this, 'get_available_slots'));
        add_action('wp_ajax_book_appointment', array($this, 'handle_appointment_booking'));
        add_action('wp_ajax_nopriv_book_appointment', array($this, 'handle_appointment_booking'));
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . '../assets/css/public.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . '../assets/js/public.js', array('jquery'), $this->version, true);
        
        // Localiser le script avec les paramètres nécessaires
        $settings = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('appointment_nonce'),
            'settings' => array(
                'opening_time' => get_option('appointment_opening_time', '09:00'),
                'closing_time' => get_option('appointment_closing_time', '18:00'),
                'duration' => intval(get_option('appointment_default_duration', '60')),
                'closed_days' => get_option('appointment_closed_days', array('saturday', 'sunday')),
                'date_format' => get_option('date_format', 'Y-m-d'),
                'time_format' => get_option('time_format', 'H:i')
            ),
            'messages' => array(
                'error_fetch' => __('Erreur lors de la récupération des créneaux.', 'appointment-plugin'),
                'no_slots' => __('Aucun créneau disponible pour cette date.', 'appointment-plugin'),
                'processing' => __('Traitement en cours...', 'appointment-plugin')
            )
        );
        
        wp_localize_script($this->plugin_name, 'appointmentAjax', $settings);
    }

    public function appointment_form_shortcode() {
        ob_start();
        ?>
        <div id="appointment-form-container">
            <h1 class="reservation-title">Réservation</h1>
            <div class="calendar-container">
                <div class="calendar-header">
                    <button id="prevMonth">&lt;</button>
                    <h2 id="monthDisplay">Février</h2>
                    <button id="nextMonth">&gt;</button>
                </div>
                <div class="weekdays">
                    <div>Lun</div>
                    <div>Mar</div>
                    <div>Mer</div>
                    <div>Jeu</div>
                    <div>Ven</div>
                    <div>Sam</div>
                    <div>Dim</div>
                </div>
                <div id="calendar"></div>
            </div>

            <div id="time-slots" class="time-slots" style="display: none;">
                <h3>Créneaux disponibles</h3>
                <div id="slots-container"></div>
            </div>

            <div id="appointment-form-wrapper" style="display: none;">
                <form id="appointment-form" class="appointment-form">
                    <div class="form-group">
                        <label for="client_name">Nom complet *</label>
                        <input type="text" id="client_name" name="client_name" required>
                    </div>

                    <div class="form-group">
                        <label for="client_email">Email *</label>
                        <input type="email" id="client_email" name="client_email" required>
                    </div>

                    <div class="form-group">
                        <input type="hidden" id="appointment_date" name="appointment_date" required>
                        <div id="selected-slot"></div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-button">Confirmer le rendez-vous</button>
                        <button type="button" class="cancel-button" onclick="hideForm()">Annuler</button>
                    </div>

                    <div id="appointment-message" class="message" style="display: none;"></div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function get_available_slots() {
        check_ajax_referer('appointment_nonce', 'nonce');
        
        $date = sanitize_text_field($_POST['date']);
        if (empty($date)) {
            wp_send_json_error('Date requise');
            return;
        }

        try {
            $database = new Appointment_Database();
            $appointments = $database->get_appointments_by_date($date);
            
            // Récupérer les paramètres
            $opening_time = get_option('appointment_opening_time', '09:00');
            $closing_time = get_option('appointment_closing_time', '18:00');
            $duration = intval(get_option('appointment_default_duration', '60'));
            
            // Convertir les heures d'ouverture et de fermeture en minutes depuis minuit
            $opening_minutes = $this->time_to_minutes($opening_time);
            $closing_minutes = $this->time_to_minutes($closing_time);
            
            // Créer un tableau des créneaux occupés
            $occupied_slots = array();
            foreach ($appointments as $appointment) {
                $apt_time = date('H:i', strtotime($appointment->appointment_date));
                $apt_minutes = $this->time_to_minutes($apt_time);
                $apt_duration = intval($appointment->duration ?? $duration);
                
                // Marquer tous les créneaux occupés par ce rendez-vous
                for ($i = 0; $i < $apt_duration; $i += 15) {
                    $occupied_slots[] = $apt_minutes + $i;
                }
            }
            
            // Générer tous les créneaux disponibles
            $slots = array();
            for ($time = $opening_minutes; $time < $closing_minutes; $time += $duration) {
                $is_available = true;
                
                // Vérifier si le créneau et sa durée sont disponibles
                for ($i = 0; $i < $duration; $i += 15) {
                    if (in_array($time + $i, $occupied_slots)) {
                        $is_available = false;
                        break;
                    }
                }
                
                if ($is_available) {
                    $slot_time = $this->minutes_to_time($time);
                    $datetime = $date . ' ' . $slot_time;
                    
                    $slots[] = array(
                        'datetime' => $datetime,
                        'time' => $slot_time
                    );
                }
            }
            
            wp_send_json_success($slots);
        } catch (Exception $e) {
            error_log('Error in get_available_slots: ' . $e->getMessage());
            wp_send_json_error('Une erreur est survenue lors de la récupération des créneaux.');
        }
    }

    private function time_to_minutes($time) {
        list($hours, $minutes) = explode(':', $time);
        return ($hours * 60) + $minutes;
    }

    private function minutes_to_time($minutes) {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $mins);
    }

    public function handle_appointment_booking() {
        try {
            check_ajax_referer('appointment_nonce', 'nonce');

            // Validation des champs requis
            $required_fields = array(
                'client_name' => 'Le nom est requis',
                'client_email' => 'L\'email est requis',
                'appointment_date' => 'La date du rendez-vous est requise'
            );

            $errors = array();
            foreach ($required_fields as $field => $message) {
                if (empty($_POST[$field])) {
                    $errors[] = $message;
                }
            }

            // Validation spécifique de l'email
            if (!empty($_POST['client_email']) && !is_email($_POST['client_email'])) {
                $errors[] = "L'adresse email n'est pas valide";
            }

            // Validation de la date
            if (!empty($_POST['appointment_date'])) {
                try {
                    $appointment_datetime = new DateTime($_POST['appointment_date']);
                    $current_datetime = new DateTime();
                    
                    if ($appointment_datetime < $current_datetime) {
                        $errors[] = "La date du rendez-vous ne peut pas être dans le passé";
                    }
                } catch (Exception $e) {
                    $errors[] = "Le format de la date n'est pas valide";
                }
            }

            if (!empty($errors)) {
                wp_send_json_error(array(
                    'message' => implode('<br>', $errors)
                ));
                return;
            }

            $appointment_data = array(
                'client_name' => sanitize_text_field($_POST['client_name']),
                'client_email' => sanitize_email($_POST['client_email']),
                'appointment_date' => sanitize_text_field($_POST['appointment_date']),
                'duration' => intval(get_option('appointment_default_duration', '60'))
            );

            $database = new Appointment_Database();
            $result = $database->insert_appointment($appointment_data);

            if ($result['success']) {
                try {
                    require_once APPOINTMENT_PLUGIN_DIR . 'includes/class-appointment-mailer.php';
                    Appointment_Mailer::send_confirmation_email($appointment_data);
                    
                    wp_send_json_success(array(
                        'message' => "Votre rendez-vous a été confirmé avec succès ! Un email de confirmation vous a été envoyé.",
                        'type' => 'success',
                        'appointment_id' => $result['appointment_id']
                    ));
                } catch (Exception $e) {
                    error_log("Error sending confirmation email: " . $e->getMessage());
                    
                    $notify_client = get_option('appointment_notify_client', '1');
                    $message = "Votre rendez-vous a été enregistré avec succès ! ";
                    
                    if ($notify_client === '1') {
                        $message .= "Cependant, l'envoi de l'email de confirmation a rencontré un problème. ";
                        $message .= "Veuillez noter soigneusement les détails de votre rendez-vous.";
                    }
                    
                    wp_send_json_success(array(
                        'message' => $message,
                        'type' => 'warning',
                        'appointment_id' => $result['appointment_id']
                    ));
                }
            } else {
                $error_message = $result['message'];
                if (strpos($error_message, 'Ce créneau est déjà réservé') !== false) {
                    $error_message = "Désolé, ce créneau vient d'être réservé par un autre client. Veuillez choisir un autre horaire.";
                }
                
                wp_send_json_error(array(
                    'message' => $error_message
                ));
            }

        } catch (Exception $e) {
            error_log("Error in handle_appointment_booking: " . $e->getMessage());
            wp_send_json_error(array(
                'message' => "Une erreur inattendue est survenue. Veuillez réessayer ou nous contacter si le problème persiste."
            ));
        }
    }
}
