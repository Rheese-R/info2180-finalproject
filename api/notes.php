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
        'message' => 'You must be logged in to view notes.'
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
$contactId = filter_input(INPUT_GET, 'contact_id', FILTER_VALIDATE_INT);

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

    // Check that the contact exists.
    $contactStatement = $pdo->prepare(
        'SELECT id FROM contacts WHERE id = :contact_id'
    );

    $contactStatement->execute([
        'contact_id' => $contactId
    ]);

    if (!$contactStatement->fetch()) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Contact not found.'
        ]);

        exit;
    }

    // Retrieve the notes and the names of their authors.
    $stmt = $pdo->prepare(
        'SELECT
            notes.id,
            notes.comment,
            notes.created_at,
            users.firstname AS author_firstname,
            users.lastname AS author_lastname
         FROM notes
         INNER JOIN users
            ON notes.created_by = users.id
         WHERE notes.contact_id = :contact_id
         ORDER BY notes.created_at DESC, notes.id DESC'
    );

    $stmt->execute([
        'contact_id' => $contactId
    ]);

    $notes = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'notes' => $notes
    ]);

} catch (Throwable $e) {
    error_log('Retrieve notes error: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to retrieve notes. Please try again.'
    ]);
}