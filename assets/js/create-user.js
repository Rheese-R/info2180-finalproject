const createUserForm = document.getElementById('create-user-form');
const createUserMessage = document.getElementById('create-user-message');
const createUserButton = document.getElementById('create-user-button');

createUserForm.addEventListener('submit', async function (event) {
    event.preventDefault();

    createUserMessage.textContent = '';
    createUserMessage.style.color = '#c62828';

    createUserButton.disabled = true;
    createUserButton.textContent = 'Creating User...';

    const formData = new FormData(createUserForm);

    try {
        const response = await fetch('api/create-user.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            createUserMessage.style.color = 'green';
            createUserMessage.textContent = result.message;

            createUserForm.reset();
        } else {
            createUserMessage.textContent = result.message;
        }

    } catch (error) {
        createUserMessage.textContent =
            'Unable to create user. Please try again.';
    } finally {
        createUserButton.disabled = false;
        createUserButton.textContent = 'Create User';
    }
});