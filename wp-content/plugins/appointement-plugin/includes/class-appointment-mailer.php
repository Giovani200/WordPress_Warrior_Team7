<?php


class Appointment_Mailer {
        public static function send_confirmation_email($appointment_data) {
            try {
                // Récupérer les paramètres SMTP et les préférences de notification
                $smtp_host = get_option('appointment_smtp_host');
                $smtp_port = get_option('appointment_smtp_port');
                $smtp_username = get_option('appointment_smtp_username');
                $smtp_password = get_option('appointment_smtp_password');
                $notify_client = get_option('appointment_notify_client', '1');
                $notify_admin = get_option('appointment_notify_admin', '1');
    
                // Configurer PHPMailer
                $mail = new \PHPMailer\PHPMailer\PHPMailer();
                $mail->isSMTP();
                $mail->Host = $smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $smtp_username;
                $mail->Password = $smtp_password;
                $mail->SMTPSecure = 'tls'; // ou 'ssl' selon votre configuration
                $mail->Port = $smtp_port;
    
                // Email au client
                if ($notify_client === '1') {
                    $mail->setFrom(
                        get_option('appointment_notification_email', get_option('admin_email')), 
                        get_option('blogname')
                    );
                    $mail->addAddress($appointment_data['client_email']);
                    $mail->Subject = 'Confirmation de votre rendez-vous';
                    $mail->Body = self::get_client_email_content($appointment_data);
    
                    if (!$mail->send()) {
                        error_log('Mailer Error: ' . $mail->ErrorInfo);
                        throw new Exception('Échec de l\'envoi de l\'email au client');
                    }
                }
    
                // Email à l'administrateur
                if ($notify_admin === '1') {
                    $admin_email = get_option('appointment_notification_email', get_option('admin_email'));
                    $mail->clearAddresses(); // Effacer les adresses précédentes
                    $mail->addAddress($admin_email);
                    $mail->Subject = 'Nouveau rendez-vous';
                    $mail->Body = self::get_admin_email_content($appointment_data);
    
                    if (!$mail->send()) {
                        error_log('Mailer Error: ' . $mail->ErrorInfo);
                        throw new Exception('Échec de l\'envoi de l\'email à l\'administrateur');
                    }
                }
                if ($notify_admin === '1') {
                    $admin_email = get_option('appointment_notification_email', get_option('admin_email'));
                    $mail->clearAddresses(); // Effacer les adresses précédentes
                    $mail->addAddress($admin_email);
                    $mail->Subject = 'Nouveau rendez-vous';
                    $mail->Body = self::get_admin_email_content($appointment_data);
    
                    if (!$mail->send()) {
                        error_log('Mailer Error: ' . $mail->ErrorInfo);
                        throw new Exception('Échec de l\'envoi de l\'email à l\'administrateur');
                    }
                }
               
    
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
