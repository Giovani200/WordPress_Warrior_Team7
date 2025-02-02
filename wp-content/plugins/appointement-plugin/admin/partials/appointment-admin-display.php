<?php
// Empêcher l'accès direct à ce fichier
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <h1>Gestion des rendez-vous</h1>
    
    <table class="wp-list-table widefat fixed striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Date du rendez-vous</th><!-- Nouvelle colonne pour les actions -->
    </tr>
</thead>
<tbody>
    <?php if (!empty($appointments)) : ?>
        <?php foreach ($appointments as $appointment) : ?>
            <tr>
                <td><?php echo esc_html($appointment->id); ?></td>
                <td><?php echo esc_html($appointment->client_name); ?></td>
                <td><?php echo esc_html($appointment->client_email); ?></td>
                <td><?php echo esc_html($appointment->appointment_date); ?></td>
                <td>
    
</td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="5">Aucun rendez-vous trouvé.</td>
        </tr>
    <?php endif; ?>
</tbody>
    </table>
</div>
