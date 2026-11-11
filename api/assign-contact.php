<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

// Ensure the user is logged in.
startAppSession();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to assign contacts.'
    ]);

    exit;
}

// Only POST requests are allowed.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);

    exit;
}

// Validate the submitted contact ID.
$contactId = filter_var(
    $_POST['contact_id'] ?? '',
    FILTER_VALIDATE_INT
);

if ($contactId === false || $contactId === null || $contactId < 1) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid contact ID.'
    ]);

    exit;
}

try {
    $pdo = getDBConnection();

    // Check that the contact exists.
    $checkContact = $pdo->prepare(
        'SELECT id FROM contacts WHERE id = :id'
    );

    $checkContact->execute([
        'id' => $contactId
    ]);

    if (!$checkContact->fetch()) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Contact not found.'
        ]);

        exit;
    }

    // Assign the contact to the currently logged-in user.
    $stmt = $pdo->prepare(
        'UPDATE contacts
         SET assigned_to = :user_id
         WHERE id = :contact_id'
    );

    $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'contact_id' => $contactId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Contact assigned to you successfully.'
    ]);

} catch (Throwable $e) {
    error_log('Assign contact error: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to assign contact. Please try again.'
    ]);
}