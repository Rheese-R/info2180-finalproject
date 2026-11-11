<?php
session_start();

// If the user is already logged in, take them to the dashboard.
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/login.js" defer></script>
</head>
<body>
    <main class="login-container">
        <section class="login-card">
            <h1>Dolphin CRM</h1>
            <p class="subtitle">Sign in to your account</p>

            <form id="login-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    />
                </div>

                <p id="login-message" role="alert"></p>

                <button type="submit" id="login-button">Sign In</button>
            </form>
        </section>
    </main>
</body>
</html>