<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

// Start the session and ensure the user is logged in.
startAppSession();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to view contacts.'
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

// Get the selected dashboard filter.
$filter = $_GET['filter'] ?? 'all';

// Check that the filter is valid.
$allowedFilters = ['all', 'Sales Lead', 'Support', 'assigned'];

if (!in_array($filter, $allowedFilters, true)) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid contact filter.'
    ]);

    exit;
}

try {
    $pdo = getDBConnection();

    // Base query retrieves contacts and their assigned user's name.
    $sql = '
        SELECT
            contacts.id,
            contacts.title,
            contacts.firstname,
            contacts.lastname,
            contacts.email,
            contacts.company,
            contacts.type,
            contacts.assigned_to,
            users.firstname AS assigned_firstname,
            users.lastname AS assigned_lastname
        FROM contacts
        INNER JOIN users
            ON contacts.assigned_to = users.id
    ';

    $parameters = [];

    // Apply the selected filter.
    if ($filter === 'Sales Lead' || $filter === 'Support') {
        $sql .= ' WHERE contacts.type = :type';
        $parameters['type'] = $filter;

    } elseif ($filter === 'assigned') {
        $sql .= ' WHERE contacts.assigned_to = :user_id';
        $parameters['user_id'] = $_SESSION['user_id'];
    }

    $sql .= ' ORDER BY contacts.created_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($parameters);

    $contacts = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'contacts' => $contacts
    ]);

} catch (Throwable $e) {
    error_log('Contacts list error: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to retrieve contacts. Please try again.'
    ]);
}
