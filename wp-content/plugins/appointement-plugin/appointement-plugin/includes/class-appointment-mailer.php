<?php

class Appointment_Mailer {
    public static function send_confirmation_email($appointment_data) {
        try {
            $api_key = get_option('appointment_sendgrid_api_key', '');
            if (empty($api_key)) {
                throw new Exception('Clé API SendGrid non configurée');
            }

            $notify_client = get_option('appointment_notify_client', '1');
            $notify_admin = get_option('appointment_notify_admin', '1');
            $success = true;
            $errors = array();

            require_once APPOINTMENT_PLUGIN_DIR . 'vendor/autoload.php';
            $sendgrid = new \SendGrid($api_key);

            // Email au client
            if ($notify_client === '1') {
                $email = new \SendGrid\Mail\Mail();
                $email->setFrom(
                    get_option('appointment_notification_email', get_option('admin_email')),
                    get_option('blogname')
                );
                $email->setSubject('Confirmation de votre rendez-vous');
                $email->addTo($appointment_data['client_email']);
                $email->addContent("text/html", self::get_client_email_content($appointment_data));

                try {
                    $response = $sendgrid->send($email);
                    if ($response->statusCode() >= 400) {
                        $success = false;
                        $errors[] = "Échec de l'envoi de l'email au client";
                        error_log("SendGrid Error (client): " . $response->body());
                    }
                } catch (Exception $e) {
                    $success = false;
                    $errors[] = $e->getMessage();
                    error_log("SendGrid Exception (client): " . $e->getMessage());
                }
            }

            // Email à l'administrateur
            if ($notify_admin === '1') {
                $admin_email = get_option('appointment_notification_email', get_option('admin_email'));
                $email = new \SendGrid\Mail\Mail();
                $email->setFrom(
                    get_option('appointment_notification_email', get_option('admin_email')),
                    get_option('blogname')
                );
                $email->setSubject('Nouveau rendez-vous');
                $email->addTo($admin_email);
                $email->addContent("text/html", self::get_admin_email_content($appointment_data));

                try {
                    $response = $sendgrid->send($email);
                    if ($response->statusCode() >= 400) {
                        $success = false;
                        $errors[] = "Échec de l'envoi de l'email à l'administrateur";
                        error_log("SendGrid Error (admin): " . $response->body());
                    }
                } catch (Exception $e) {
                    $success = false;
                    $errors[] = $e->getMessage();
                    error_log("SendGrid Exception (admin): " . $e->getMessage());
                }
            }

            if (!$success) {
                throw new Exception(implode(', ', $errors));
            }

            return true;

        } catch (Exception $e) {
            error_log("Error in send_confirmation_email: " . $e->getMessage());
            throw $e;
        }
    }
    
    private static function get_client_email_content($appointment_data) {
        $date = date_i18n('l j F Y à H:i', strtotime($appointment_data['appointment_date']));
        $site_name = get_bloginfo('name');
        
        $message = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #7C8B6F; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { padding: 30px; background-color: #f9f9f9; border: 1px solid #ddd; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
                .important { color: #7C8B6F; font-weight: bold; }
                .details { background-color: #fff; padding: 15px; border: 1px solid #eee; margin: 15px 0; border-radius: 5px; }
                .contact { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Confirmation de votre rendez-vous</h2>
                </div>
                <div class="content">
                    <p>Bonjour ' . esc_html($appointment_data['client_name']) . ',</p>
                    
                    <p>Nous vous confirmons votre rendez-vous chez <strong>' . esc_html($site_name) . '</strong>.</p>
                    
                    <div class="details">
                        <p class="important">Détails de votre rendez-vous :</p>
                        <ul>
                            <li><strong>Date :</strong> ' . esc_html($date) . '</li>
                            <li><strong>Nom :</strong> ' . esc_html($appointment_data['client_name']) . '</li>
                            <li><strong>Email :</strong> ' . esc_html($appointment_data['client_email']) . '</li>
                            <li><strong>Durée :</strong> ' . esc_html($appointment_data['duration']) . ' minutes</li>
                        </ul>
                    </div>

                    <div class="contact">
                        <p><strong>Important :</strong></p>
                        <ul>
                            <li>En cas d\'empêchement, merci de nous prévenir au moins 24h à l\'avance</li>
                            <li>Présentez-vous 5 minutes avant l\'heure de votre rendez-vous</li>
                        </ul>
                        
                        <p>Pour toute modification ou annulation, merci de nous contacter directement.</p>
                    </div>
                </div>
                <div class="footer">
                    <p>Cet email a été envoyé automatiquement par ' . esc_html($site_name) . '.</p>
                    <p>Merci de ne pas y répondre.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $message;
    }
    
    private static function get_admin_email_content($appointment_data) {
        $date = date_i18n('l j F Y à H:i', strtotime($appointment_data['appointment_date']));
        $site_name = get_bloginfo('name');
        
        $message = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #2196F3; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { padding: 30px; background-color: #f9f9f9; border: 1px solid #ddd; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
                .important { color: #2196F3; font-weight: bold; }
                .details { background-color: #fff; padding: 15px; border: 1px solid #eee; margin: 15px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Nouveau rendez-vous</h2>
                </div>
                <div class="content">
                    <p class="important">Un nouveau rendez-vous vient d\'être pris sur ' . esc_html($site_name) . '.</p>
                    
                    <div class="details">
                        <p><strong>Détails du rendez-vous :</strong></p>
                        <ul>
                            <li><strong>Date :</strong> ' . esc_html($date) . '</li>
                            <li><strong>Durée :</strong> ' . esc_html($appointment_data['duration']) . ' minutes</li>
                            <li><strong>Client :</strong> ' . esc_html($appointment_data['client_name']) . '</li>
                            <li><strong>Email :</strong> ' . esc_html($appointment_data['client_email']) . '</li>
                        </ul>
                    </div>

                    <p>Vous pouvez gérer ce rendez-vous depuis votre tableau de bord WordPress.</p>
                </div>
                <div class="footer">
                    <p>Notification automatique de ' . esc_html($site_name) . '</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $message;
    }
}
