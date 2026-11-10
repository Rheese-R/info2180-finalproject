<?php

require_once __DIR__ . '/includes/auth.php';

// Only logged-in users can access the dashboard.
requireLogin();

$firstname = htmlspecialchars($_SESSION['firstname'] ?? '');
$lastname = htmlspecialchars($_SESSION['lastname'] ?? '');
$role = htmlspecialchars($_SESSION['role'] ?? '');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <header class="dashboard-header">
        <h1>Dolphin CRM</h1>

        <nav>
            <span>Welcome, <?php echo $firstname . ' ' . $lastname; ?></span>

            <?php if (($_SESSION['role'] ?? '') === 'Admin'): ?>
                <a href="create-user.php">Create User</a>
                <a href="users.php">Users</a>
            <?php endif; ?>

            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="dashboard-container">
        <h2>Dashboard</h2>

        <p>You are successfully logged in to Dolphin CRM.</p>

        <div class="dashboard-card">
            <h3>Account Information</h3>

            <p>
                <strong>Name:</strong>
                <?php echo $firstname . ' ' . $lastname; ?>
            </p>

            <p>
                <strong>Role:</strong>
                <?php echo $role; ?>
            </p>
        </div>
    </main>
</body>
</html>