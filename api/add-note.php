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
        'message' => 'You must be logged in to add a note.'
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

// Retrieve and validate the submitted information.
$contactId = filter_input(INPUT_POST, 'contact_id', FILTER_VALIDATE_INT);
$comment = trim($_POST['comment'] ?? '');

if (!$contactId || $contactId < 1) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid contact ID.'
    ]);

    exit;
}

if ($comment === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Please enter a note before submitting.'
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

    // Save the note using the logged-in user's ID.
    $stmt = $pdo->prepare(
        'INSERT INTO notes (contact_id, comment, created_by)
         VALUES (:contact_id, :comment, :created_by)'
    );

    $stmt->execute([
        'contact_id' => $contactId,
        'comment' => $comment,
        'created_by' => $_SESSION['user_id']
    ]);

    // Update the contact's last-updated timestamp.
    $updateStatement = $pdo->prepare(
        'UPDATE contacts
         SET updated_at = CURRENT_TIMESTAMP
         WHERE id = :contact_id'
    );

    $updateStatement->execute([
        'contact_id' => $contactId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Note added successfully.',
        'note_id' => $pdo->lastInsertId()
    ]);

} catch (Throwable $e) {
    error_log('Add note error: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to add note. Please try again.'
    ]);
}