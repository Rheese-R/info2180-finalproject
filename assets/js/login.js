const loginForm = document.getElementById('login-form');
const loginMessage = document.getElementById('login-message');
const loginButton = document.getElementById('login-button');

loginForm.addEventListener('submit', async function (event) {
    event.preventDefault();

    loginMessage.textContent = '';
    loginButton.disabled = true;
    loginButton.textContent = 'Signing in...';

    const formData = new FormData(loginForm);

    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = 'index.php';
        } else {
            loginMessage.textContent = result.message;
        }
    } catch (error) {
        loginMessage.textContent = 'Unable to sign in. Please try again.';
    } finally {
        loginButton.disabled = false;
        loginButton.textContent = 'Sign In';
    }
});