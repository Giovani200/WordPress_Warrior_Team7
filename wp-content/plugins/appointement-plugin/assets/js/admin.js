document.addEventListener('DOMContentLoaded', function() {
    const testButton = document.getElementById('test-email');
    const resultSpan = document.getElementById('email-test-result');

    if (testButton) {
        testButton.addEventListener('click', function() {
            testButton.disabled = true;
            resultSpan.textContent = 'Test en cours...';

            const formData = new FormData();
            formData.append('action', 'test_email_sending');
            formData.append('nonce', appointmentAdmin.nonce);

            fetch(appointmentAdmin.ajaxurl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultSpan.innerHTML = '<span style="color: green;">✓ Email envoyé avec succès</span>';
                } else {
                    resultSpan.innerHTML = '<span style="color: red;">✗ Échec de l\'envoi : ' + data.data + '</span>';
                }
            })
            .catch(error => {
                resultSpan.innerHTML = '<span style="color: red;">✗ Erreur lors du test</span>';
                console.error('Error:', error);
            })
            .finally(() => {
                testButton.disabled = false;
            });
        });
    }
});
