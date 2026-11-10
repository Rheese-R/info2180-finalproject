<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

// Only administrators can retrieve the users list.
requireAdminApi();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);

    exit;
}

try {
    $pdo = getDBConnection();

    $stmt = $pdo->query(
        'SELECT id, firstname, lastname, email, role, created_at
         FROM users
         ORDER BY created_at DESC'
    );

    $users = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'users' => $users
    ]);

} catch (Throwable $e) {
    error_log('Users list error: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to retrieve users. Please try again.'
    ]);
}