<?php
// Si accès direct, sortir
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="appointment-booking-form-wrapper">
    <div id="appointment-message"></div>
    
    <form id="appointment-form" class="appointment-form">
        <?php wp_nonce_field('appointment_nonce', 'nonce'); ?>
        
        <div class="form-group">
            <label for="full_name">Nom complet *</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="phone">Téléphone *</label>
            <input type="tel" id="phone" name="phone" required>
        </div>
        
        <div class="form-group">
            <label for="appointment_date">Date du rendez-vous *</label>
            <input type="text" id="appointment_date" name="appointment_date" required>
        </div>
        
        <div class="form-group">
            <label for="appointment_time">Heure du rendez-vous *</label>
            <input type="text" id="appointment_time" name="appointment_time" required>
        </div>
        
        <input type="hidden" id="selected_datetime" name="selected_datetime">
        
        <div class="form-group full-width">
            <label for="notes">Notes (optionnel)</label>
            <textarea id="notes" name="notes" rows="4"></textarea>
        </div>
        
        <button type="submit">Réserver le rendez-vous</button>
    </form>
</div>
