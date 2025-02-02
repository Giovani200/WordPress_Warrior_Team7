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
    <th scope="row">Hôte SMTP</th>
    <td>
        <input type="text" name="appointment_smtp_host" 
               value="<?php echo esc_attr(get_option('appointment_smtp_host', 'smtp.gmail.com')); ?>" 
               class="regular-text" />
        <p class="description">Pour Gmail, utilisez <strong>smtp.gmail.com</strong>. Pour Outlook, utilisez <strong>smtp.office365.com</strong>.</p>
    </td>
</tr>

<tr valign="top">
    <th scope="row">Port SMTP</th>
    <td>
        <input type="number" name="appointment_smtp_port" 
               value="<?php echo esc_attr(get_option('appointment_smtp_port', '587')); ?>" 
               min="1" max="65535" />
        <p class="description">Utilisez <strong>587</strong> pour TLS ou <strong>465</strong> pour SSL.</p>
    </td>
</tr>

<tr valign="top">
    <th scope="row">Nom d'utilisateur SMTP</th>
    <td>
        <input type="text" name="appointment_smtp_username" 
               value="<?php echo esc_attr(get_option('appointment_smtp_username', '')); ?>" 
               class="regular-text" />
        <p class="description">Votre adresse e-mail Gmail ou Outlook.</p>
    </td>
</tr>

<tr valign="top">
    <th scope="row">Mot de passe SMTP</th>
    <td>
        <input type="password" name="appointment_smtp_password" 
               value="<?php echo esc_attr(get_option('appointment_smtp_password', '')); ?>" 
               class="regular-text" />
        <p class="description">Votre mot de passe Gmail ou Outlook. Utilisez un mot de passe d'application si l'authentification à deux facteurs est activée.</p>
    </td>
</tr>

<tr valign="top">
    <th scope="row">Sécurité SMTP</th>
    <td>
        <select name="appointment_smtp_encryption">
            <option value="tls" <?php selected(get_option('appointment_smtp_encryption', 'tls'), 'tls'); ?>>TLS</option>
            <option value="ssl" <?php selected(get_option('appointment_smtp_encryption', 'ssl'), 'ssl'); ?>>SSL</option>
        </select>
        <p class="description">Choisissez TLS pour le port 587 ou SSL pour le port 465.</p>
    </td>
</tr>

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
    <th scope="row">Notification au client</th>
    <td>
        <label><input type="radio" name="appointment_notify_client" value="1" <?php checked(get_option('appointment_notify_client', '1'), '1'); ?>>Oui</label>
        <label><input type="radio" name="appointment_notify_client" value="0" <?php checked(get_option('appointment_notify_client', '1'), '0'); ?>>Non</label>
        <p class="description">Choisissez Oui pour notifier le client lorsqu'il a un rendez-vous</p>
    </td>
</tr>

<tr valign="top">
    <th scope="row">Notification à l'administrateur</th>
    <td>
        <label><input type="radio" name="appointment_notify_admin" value="1" <?php checked(get_option('appointment_notify_admin', '1'), '1'); ?>>Oui</label>
        <label><input type="radio" name="appointment_notify_admin" value="0" <?php checked(get_option('appointment_notify_admin', '1'), '0'); ?>>Non</label>
        <p class="description">Choisissez Oui pour notifier l'administrateur lorsqu'il a un nouveau rendez-vous</p>
    </td>
</tr>

</table>


        <?php submit_button(); ?>
    </form>
</div>
