const createContactForm = document.getElementById('create-contact-form');
const createContactMessage = document.getElementById('create-contact-message');
const createContactButton = document.getElementById('create-contact-button');

createContactForm.addEventListener('submit', async function (event) {
    event.preventDefault();

    createContactMessage.textContent = '';
    createContactMessage.style.color = '#c62828';

    createContactButton.disabled = true;
    createContactButton.textContent = 'Saving Contact...';

    const formData = new FormData(createContactForm);

    try {
        const response = await fetch('api/create-contact.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            createContactMessage.textContent =
                result.message || 'Unable to create contact.';
            return;
        }

        createContactMessage.style.color = 'green';
        createContactMessage.textContent = result.message;

        createContactForm.reset();

        // Return to the dashboard after a short delay.
        setTimeout(function () {
            window.location.href = 'index.php';
        }, 1500);

    } catch (error) {
        createContactMessage.textContent =
            'Unable to connect to the server. Please try again.';
    } finally {
        createContactButton.disabled = false;
        createContactButton.textContent = 'Save Contact';
    }
});