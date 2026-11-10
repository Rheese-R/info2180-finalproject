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
        'message' => 'You must be logged in to view contact details.'
    ]);

    exit;
}

// Only GET requests are allowed.
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);

    exit;
}

// Validate the contact ID.
$contactId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$contactId || $contactId < 1) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid contact ID.'
    ]);

    exit;
}

try {
    $pdo = getDBConnection();

    // Retrieve the contact and the names of the related users.
    $stmt = $pdo->prepare(
        'SELECT
            contacts.id,
            contacts.title,
            contacts.firstname,
            contacts.lastname,
            contacts.email,
            contacts.telephone,
            contacts.company,
            contacts.type,
            contacts.assigned_to,
            contacts.created_at,
            contacts.updated_at,
            assigned_user.firstname AS assigned_firstname,
            assigned_user.lastname AS assigned_lastname,
            creator.firstname AS creator_firstname,
            creator.lastname AS creator_lastname
         FROM contacts
         INNER JOIN users AS assigned_user
            ON contacts.assigned_to = assigned_user.id
         INNER JOIN users AS creator
            ON contacts.created_by = creator.id
         WHERE contacts.id = :contact_id'
    );

    $stmt->execute([
        'contact_id' => $contactId
    ]);

    $contact = $stmt->fetch();

    if (!$contact) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Contact not found.'
        ]);

        exit;
    }

    echo json_encode([
        'success' => true,
        'contact' => $contact
    ]);

} catch (Throwable $e) {
    error_log('Contact details error: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to retrieve contact details. Please try again.'
    ]);
}