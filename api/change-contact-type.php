<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

startAppSession();

// Make sure the user is logged in.
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to change a contact type.'
    ]);
    exit;
}

// Only accept POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);
    exit;
}

// Retrieve and validate the submitted information.
$contactId = filter_input(INPUT_POST, 'contact_id', FILTER_VALIDATE_INT);
$type = trim($_POST['type'] ?? '');

if (!$contactId || $contactId < 1) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'A valid contact ID is required.'
    ]);
    exit;
}

$allowedTypes = ['Sales Lead', 'Support'];

if (!in_array($type, $allowedTypes, true)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid contact type.'
    ]);
    exit;
}

try {
    $pdo = getDBConnection();

    // Check that the contact exists.
    $checkStatement = $pdo->prepare(
        'SELECT id FROM contacts WHERE id = :id'
    );

    $checkStatement->execute([
        ':id' => $contactId
    ]);

    if (!$checkStatement->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Contact not found.'
        ]);
        exit;
    }

    // Update the contact type and last-updated timestamp.
    $updateStatement = $pdo->prepare(
        'UPDATE contacts
         SET type = :type, updated_at = CURRENT_TIMESTAMP
         WHERE id = :id'
    );

    $updateStatement->execute([
        ':type' => $type,
        ':id' => $contactId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Contact type updated successfully.'
    ]);

} catch (Throwable $e) {
    error_log('Change contact type failed: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Unable to update the contact type. Please try again.'
    ]);
}