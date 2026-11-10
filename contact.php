
<?php

require_once __DIR__ . '/includes/auth.php';

// Only logged-in users can view contact details.
requireLogin();

$firstname = htmlspecialchars(
    $_SESSION['firstname'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

$lastname = htmlspecialchars(
    $_SESSION['lastname'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

// Validate the contact ID in the URL.
$contactId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$contactId || $contactId < 1) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Details | Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/contact.js" defer></script>
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

        <div class="users-heading">
            <h2>Contact Details</h2>

            <a class="action-link" href="index.php">
                Back to Contacts
            </a>
        </div>

        <div class="dashboard-card">

            <p id="contact-message" role="status">
                Loading contact details...
            </p>

            <div id="contact-details" hidden>

                <div class="contact-details-heading">
                    <h3 id="contact-name"></h3>
                    <span id="contact-type" class="contact-type"></span>
                </div>

                <div class="contact-information">

                    <p>
                        <strong>Email Address:</strong>
                        <span id="contact-email"></span>
                    </p>

                    <p>
                        <strong>Telephone:</strong>
                        <span id="contact-telephone"></span>
                    </p>

                    <p>
                        <strong>Company:</strong>
                        <span id="contact-company"></span>
                    </p>

                    <p>
                        <strong>Assigned To:</strong>
                        <span id="contact-assigned"></span>
                    </p>

                    <p>
                        <strong>Created By:</strong>
                        <span id="contact-created-by"></span>
                    </p>

                    <p>
                        <strong>Date Created:</strong>
                        <span id="contact-created-at"></span>
                    </p>

                    <p>
                        <strong>Last Updated:</strong>
                        <span id="contact-updated-at"></span>
                    </p>

                </div>

                <div class="contact-actions">

                    <button type="button" id="assign-contact-button">
                        Assign to Me
                    </button>

                    <button type="button" id="change-type-button">
                        Change Type
                    </button>

                </div>

                <p id="contact-action-message" role="status"></p>

            </div>

        </div>

    </main>

</body>
</html>