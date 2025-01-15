document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date();
    let selectedDate = null;
    let selectedSlot = null;
    let messageTimeout = null;

    const calendar = document.getElementById('calendar');
    const monthDisplay = document.getElementById('monthDisplay');
    const prevButton = document.getElementById('prevMonth');
    const nextButton = document.getElementById('nextMonth');
    const timeSlotsDiv = document.getElementById('time-slots');
    const slotsContainer = document.getElementById('slots-container');
    const appointmentForm = document.getElementById('appointment-form-wrapper');
    const form = document.getElementById('appointment-form');
    const messageDiv = document.getElementById('appointment-message');

    const weekdays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    // Initialisation
    displayCalendar();
    setupEventListeners();

    function setupEventListeners() {
        prevButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            displayCalendar();
        });

        nextButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            displayCalendar();
        });

        form.addEventListener('submit', handleFormSubmit);
    }

    function displayCalendar() {
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        
        monthDisplay.textContent = `${months[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        calendar.innerHTML = '';

        // Ajuster le premier jour pour commencer par lundi (1) au lieu de dimanche (0)
        let startingDay = firstDay.getDay() || 7;
        startingDay--; // Convertir en index 0-6 avec lundi comme 0

        // Ajouter les jours vides du début du mois
        for (let i = 0; i < startingDay; i++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar-day');
            calendar.appendChild(dayElement);
        }

        // Ajouter les jours du mois
        const today = new Date();
        for (let day = 1; day <= lastDay.getDate(); day++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar-day');
            dayElement.textContent = day;

            const currentDayDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            
            // Vérifier si le jour est dans le passé ou est un jour fermé
            const dayName = weekdays[currentDayDate.getDay()].toLowerCase();
            const isPast = currentDayDate < new Date(today.setHours(0, 0, 0, 0));
            const isClosed = appointmentAjax.settings.closed_days.includes(dayName);

            if (!isPast && !isClosed) {
                dayElement.classList.add('selectable');
                dayElement.addEventListener('click', () => selectDate(currentDayDate));
            } else {
                dayElement.classList.add('disabled');
            }

            // Marquer le jour actuel
            if (currentDayDate.toDateString() === today.toDateString()) {
                dayElement.classList.add('today');
            }

            // Marquer le jour sélectionné
            if (selectedDate && currentDayDate.toDateString() === selectedDate.toDateString()) {
                dayElement.classList.add('selected');
            }

            calendar.appendChild(dayElement);
        }
    }

    function selectDate(date) {
        // Retirer la classe selected de l'ancien jour sélectionné
        const previousSelected = calendar.querySelector('.selected');
        if (previousSelected) {
            previousSelected.classList.remove('selected');
        }

        // Ajouter la classe selected au nouveau jour
        selectedDate = date;
        displayCalendar();

        // Formater la date pour l'API
        const formattedDate = formatDate(date);
        fetchAvailableSlots(formattedDate);
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function fetchAvailableSlots(date) {
        const formData = new FormData();
        formData.append('action', 'get_available_slots');
        formData.append('date', date);
        formData.append('nonce', appointmentAjax.nonce);

        showMessage('info', appointmentAjax.messages.processing);

        fetch(appointmentAjax.ajaxurl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTimeSlots(data.data);
            } else {
                showMessage('error', data.data || appointmentAjax.messages.error_fetch);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', appointmentAjax.messages.error_fetch);
        });
    }

    function displayTimeSlots(slots) {
        slotsContainer.innerHTML = '';
        
        if (slots.length === 0) {
            showMessage('info', appointmentAjax.messages.no_slots);
            timeSlotsDiv.style.display = 'none';
            return;
        }

        slots.forEach(slot => {
            const button = document.createElement('button');
            button.type = 'button';
            button.classList.add('time-slot');
            button.textContent = slot.time;
            button.addEventListener('click', () => selectTimeSlot(slot));
            slotsContainer.appendChild(button);
        });

        timeSlotsDiv.style.display = 'block';
        hideMessage();
    }

    function selectTimeSlot(slot) {
        selectedSlot = slot;

        // Mettre à jour l'affichage du créneau sélectionné
        const selectedSlotDiv = document.getElementById('selected-slot');
        selectedSlotDiv.textContent = `Rendez-vous le ${formatDateForDisplay(selectedDate)} à ${slot.time}`;
        
        // Mettre à jour le champ caché du formulaire
        document.getElementById('appointment_date').value = slot.datetime;
        
        // Afficher le formulaire
        appointmentForm.style.display = 'block';
        
        // Retirer la sélection des autres créneaux
        const slots = slotsContainer.querySelectorAll('.time-slot');
        slots.forEach(s => s.classList.remove('selected'));
        
        // Ajouter la classe selected au créneau choisi
        event.target.classList.add('selected');
    }

    function formatDateForDisplay(date) {
        return date.toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function handleFormSubmit(event) {
        event.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'book_appointment');
        formData.append('nonce', appointmentAjax.nonce);

        showMessage('info', 'Enregistrement du rendez-vous...');

        fetch(appointmentAjax.ajaxurl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('success', data.data);
                form.reset();
                appointmentForm.style.display = 'none';
                timeSlotsDiv.style.display = 'none';
                selectedDate = null;
                selectedSlot = null;
                displayCalendar();
                
                // Ne pas cacher le message de succès automatiquement
                return;
            } else {
                showMessage('error', data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'Une erreur est survenue lors de l\'enregistrement du rendez-vous.');
        });
    }

    function showMessage(type, message, duration = null) {
        // Effacer le timeout précédent s'il existe
        if (messageTimeout) {
            clearTimeout(messageTimeout);
            messageTimeout = null;
        }

        messageDiv.textContent = message;
        messageDiv.className = `message ${type}`;
        messageDiv.style.display = 'block';
        messageDiv.style.opacity = '1';

        // Ne pas cacher automatiquement les messages de succès
        if (type !== 'success' && duration !== null) {
            messageTimeout = setTimeout(() => {
                hideMessage();
            }, duration);
        }
    }

    function hideMessage() {
        if (messageDiv.style.display === 'none') return;

        messageDiv.style.opacity = '0';
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 300);
    }
});

function hideForm() {
    document.getElementById('appointment-form-wrapper').style.display = 'none';
    document.getElementById('appointment-form').reset();
}
