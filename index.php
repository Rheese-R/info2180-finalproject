<?php

require_once __DIR__ . '/includes/auth.php';

// Only logged-in users can access the dashboard.
requireLogin();

$firstname = htmlspecialchars($_SESSION['firstname'] ?? '', ENT_QUOTES, 'UTF-8');
$lastname = htmlspecialchars($_SESSION['lastname'] ?? '', ENT_QUOTES, 'UTF-8');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/dashboard.js" defer></script>
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

        <div class="users-heading">
            <h2>Contacts</h2>
            <a class="action-link" href="create-contact.php">
                Add New Contact
            </a>
        </div>

        <div class="contact-filters">
            <button type="button" class="filter-button active" data-filter="all">
                All Contacts
            </button>

            <button type="button" class="filter-button" data-filter="Sales Lead">
                Sales Leads
            </button>

            <button type="button" class="filter-button" data-filter="Support">
                Support
            </button>

            <button type="button" class="filter-button" data-filter="assigned">
                Assigned to Me
            </button>
        </div>

        <div class="dashboard-card">

            <p id="contacts-message" role="status">
                Loading contacts...
            </p>

            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Company</th>
                            <th>Type</th>
                        </tr>
                    </thead>

                    <tbody id="contacts-table-body">
                    </tbody>
                </table>
            </div>

        </div>

    </main>

</body>
</html>