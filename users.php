<?php

require_once __DIR__ . '/includes/auth.php';

// Only administrators can view the users page.
requireAdmin();

$firstname = htmlspecialchars($_SESSION['firstname'] ?? '');
$lastname = htmlspecialchars($_SESSION['lastname'] ?? '');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Users | Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/users.js" defer></script>
</head>
<body>
    <header class="dashboard-header">
        <h1>Dolphin CRM</h1>

        <nav>
            <span>Welcome, <?php echo $firstname . ' ' . $lastname; ?></span>
            <a href="index.php">Dashboard</a>
            <a href="create-user.php">Create User</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="dashboard-container">
        <div class="users-heading">
            <h2>Users</h2>
            <a class="action-link" href="create-user.php">Create New User</a>
        </div>

        <div class="dashboard-card">
            <p id="users-message" role="status">Loading users...</p>

            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Role</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>

                    <tbody id="users-table-body">
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>