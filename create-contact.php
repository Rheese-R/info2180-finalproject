
<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

// Only logged-in users can create contacts.
requireLogin();

$firstname = htmlspecialchars($_SESSION['firstname'] ?? '', ENT_QUOTES, 'UTF-8');
$lastname = htmlspecialchars($_SESSION['lastname'] ?? '', ENT_QUOTES, 'UTF-8');

$users = [];

try {
    $pdo = getDBConnection();

    $stmt = $pdo->query(
        'SELECT id, firstname, lastname
         FROM users
         ORDER BY firstname ASC, lastname ASC'
    );

    $users = $stmt->fetchAll();

} catch (Throwable $e) {
    error_log('Contact form users error: ' . $e->getMessage());
    $userLoadError = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Contact | Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/create-contact.js" defer></script>
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

            <a href="index.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="dashboard-container">

        <h2>Add New Contact</h2>

        <div class="dashboard-card">

            <form id="create-contact-form">

                <div class="form-group">
                    <label for="title">Title</label>
                    <select id="title" name="title" required>
                        <option value="">Select a title</option>
                        <option value="Mr">Mr</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Ms">Ms</option>
                        <option value="Miss">Miss</option>
                        <option value="Dr">Dr</option>
                        <option value="Prof">Prof</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input
                        type="text"
                        id="firstname"
                        name="firstname"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input
                        type="text"
                        id="lastname"
                        name="lastname"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="telephone">Telephone</label>
                    <input
                        type="tel"
                        id="telephone"
                        name="telephone"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="company">Company</label>
                    <input
                        type="text"
                        id="company"
                        name="company"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="type">Contact Type</label>
                    <select id="type" name="type" required>
                        <option value="">Select contact type</option>
                        <option value="Sales Lead">Sales Lead</option>
                        <option value="Support">Support</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="assigned_to">Assigned To</label>

                    <select id="assigned_to" name="assigned_to" required>
                        <option value="">Select a user</option>

                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo (int) $user['id']; ?>">
                                <?php
                                echo htmlspecialchars(
                                    $user['firstname'] . ' ' . $user['lastname'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                    <?php if (!empty($userLoadError)): ?>
                        <small>Unable to load users. Please try again later.</small>
                    <?php endif; ?>
                </div>

                <p id="create-contact-message" role="alert"></p>

                <button type="submit" id="create-contact-button">
                    Save Contact
                </button>

                <a class="action-link" href="index.php">
                    Cancel
                </a>

            </form>

        </div>

    </main>

</body>
</html>
