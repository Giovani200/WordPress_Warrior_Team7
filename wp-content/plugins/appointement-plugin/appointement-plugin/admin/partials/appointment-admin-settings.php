<?php
// Empêcher l'accès direct à ce fichier
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <h1>Paramètres des rendez-vous</h1>
    
    <form method="post" action="options.php">
        <?php
            settings_fields('appointment_plugin_options');
            do_settings_sections('appointment_plugin_options');
        ?>
        
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Email de notification</th>
                <td>
                    <input type="email" name="appointment_notification_email" 
                           value="<?php echo esc_attr(get_option('appointment_notification_email', get_option('admin_email'))); ?>" 
                           class="regular-text" />
                    <p class="description">Email qui recevra les notifications de nouveaux rendez-vous</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Durée par défaut (minutes)</th>
                <td>
                    <input type="number" name="appointment_default_duration" 
                           value="<?php echo esc_attr(get_option('appointment_default_duration', '60')); ?>" 
                           min="15" step="15" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row">Heures d'ouverture</th>
                <td>
                    <input type="time" name="appointment_opening_time" 
                           value="<?php echo esc_attr(get_option('appointment_opening_time', '09:00')); ?>" />
                    à
                    <input type="time" name="appointment_closing_time" 
                           value="<?php echo esc_attr(get_option('appointment_closing_time', '18:00')); ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row">Jours de fermeture</th>
                <td>
                    <?php
                    $days = array(
                        'monday' => 'Lundi',
                        'tuesday' => 'Mardi',
                        'wednesday' => 'Mercredi',
                        'thursday' => 'Jeudi',
                        'friday' => 'Vendredi',
                        'saturday' => 'Samedi',
                        'sunday' => 'Dimanche'
                    );
                    
                    $closed_days = get_option('appointment_closed_days', array('saturday', 'sunday'));
                    
                    foreach ($days as $key => $day) {
                        echo '<label style="display: block; margin-bottom: 5px;">';
                        echo '<input type="checkbox" name="appointment_closed_days[]" value="' . esc_attr($key) . '" ' . 
                             (in_array($key, $closed_days) ? 'checked' : '') . ' /> ';
                        echo esc_html($day);
                        echo '</label>';
                    }
                    ?>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Notifications par email</th>
                <td>
                    <label>
                        <input type="checkbox" name="appointment_notify_admin" 
                               value="1" <?php checked(get_option('appointment_notify_admin', '1')); ?> />
                        Envoyer une notification à l'administrateur pour chaque nouveau rendez-vous
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="appointment_notify_client" 
                               value="1" <?php checked(get_option('appointment_notify_client', '1')); ?> />
                        Envoyer un email de confirmation au client
                    </label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" colspan="2">
                    <h3>Configuration des emails</h3>
                </th>
            </tr>

            <tr valign="top">
                <th scope="row">Clé API SendGrid</th>
                <td>
                    <input type="password" name="appointment_sendgrid_api_key" 
                           value="<?php echo esc_attr(get_option('appointment_sendgrid_api_key', '')); ?>" 
                           class="regular-text" />
                    <p class="description">
                        Votre clé API SendGrid. Pour l'obtenir :
                        <ol>
                            <li>Créez un compte sur <a href="https://signup.sendgrid.com/" target="_blank">SendGrid</a></li>
                            <li>Allez dans Settings > API Keys</li>
                            <li>Créez une nouvelle clé API avec les permissions "Mail Send"</li>
                        </ol>
                    </p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Test d'envoi</th>
                <td>
                    <button type="button" id="test-email" class="button button-secondary">
                        Tester l'envoi d'email
                    </button>
                    <span id="email-test-result" style="margin-left: 10px;"></span>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>
