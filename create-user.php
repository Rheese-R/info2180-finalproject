<?php

require_once __DIR__ . '/includes/auth.php';

requireAdmin();

$firstname = htmlspecialchars($_SESSION['firstname'] ?? '');
$lastname = htmlspecialchars($_SESSION['lastname'] ?? '');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create User | Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/create-user.js" defer></script>
</head>
<body>
    <header class="dashboard-header">
        <h1>Dolphin CRM</h1>

        <nav>
            <span>Welcome, <?php echo $firstname . ' ' . $lastname; ?></span>
            <a href="index.php">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="dashboard-container">
        <h2>Create New User</h2>

        <div class="dashboard-card">
            <form id="create-user-form">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" required />
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" required />
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                    <small>
                        At least 8 characters, including an uppercase letter,
                        a lowercase letter and a number.
                    </small>
                </div>

                <div class="form-group">
                    <label for="role">User Role</label>
                    <select id="role" name="role" required>
                        <option value="Member">Member</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <p id="create-user-message" role="alert"></p>

                <button type="submit" id="create-user-button">
                    Create User
                </button>
            </form>
        </div>
    </main>
</body>
</html>