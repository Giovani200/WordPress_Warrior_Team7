<?php

class Appointment_Mailer {
    public static function send_confirmation_email($appointment_data) {
        $to = $appointment_data['client_email'];
        $admin_email = get_option('appointment_notification_email', get_option('admin_email'));
        
        // Email au client
        $subject = 'Confirmation de votre rendez-vous';
        $message = self::get_client_email_content($appointment_data);
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail($to, $subject, $message, $headers);
        
        // Email à l'administrateur
        $admin_subject = 'Nouveau rendez-vous';
        $admin_message = self::get_admin_email_content($appointment_data);
        
        wp_mail($admin_email, $admin_subject, $admin_message, $headers);
    }
    
    private static function get_client_email_content($appointment_data) {
        $date = date_i18n('l j F Y à H:i', strtotime($appointment_data['appointment_date']));
        
        $message = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Confirmation de votre rendez-vous</h2>
                </div>
                <div class="content">
                    <p>Bonjour ' . esc_html($appointment_data['client_name']) . ',</p>
                    <p>Votre rendez-vous a été confirmé pour le ' . esc_html($date) . '.</p>
                    <p>Détails du rendez-vous :</p>
                    <ul>
                        <li>Nom : ' . esc_html($appointment_data['client_name']) . '</li>
                        <li>Email : ' . esc_html($appointment_data['client_email']) . '</li>
                        <li>Date : ' . esc_html($date) . '</li>
                    </ul>
                    <p>Si vous souhaitez modifier ou annuler votre rendez-vous, veuillez nous contacter.</p>
                </div>
                <div class="footer">
                    <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $message;
    }
    
    private static function get_admin_email_content($appointment_data) {
        $date = date_i18n('l j F Y à H:i', strtotime($appointment_data['appointment_date']));
        
        $message = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #2196F3; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Nouveau rendez-vous</h2>
                </div>
                <div class="content">
                    <p>Un nouveau rendez-vous a été pris.</p>
                    <p>Détails du rendez-vous :</p>
                    <ul>
                        <li>Nom : ' . esc_html($appointment_data['client_name']) . '</li>
                        <li>Email : ' . esc_html($appointment_data['client_email']) . '</li>
                        <li>Date : ' . esc_html($date) . '</li>
                    </ul>
                    <p>Vous pouvez gérer ce rendez-vous depuis le tableau de bord WordPress.</p>
                </div>
                <div class="footer">
                    <p>Cet email a été envoyé automatiquement par votre site WordPress.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $message;
    }
}
