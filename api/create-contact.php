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
        'message' => 'You must be logged in to create a contact.'
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

// Retrieve and clean the submitted information.
$title = trim($_POST['title'] ?? '');
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$company = trim($_POST['company'] ?? '');
$type = trim($_POST['type'] ?? '');
$assignedTo = filter_var(
    $_POST['assigned_to'] ?? '',
    FILTER_VALIDATE_INT
);

// Check that all fields are completed.
if (
    $title === '' ||
    $firstname === '' ||
    $lastname === '' ||
    $email === '' ||
    $telephone === '' ||
    $company === '' ||
    $type === '' ||
    $assignedTo === false ||
    $assignedTo === null ||
    $assignedTo < 1
) {
    echo json_encode([
        'success' => false,
        'message' => 'Please complete all fields.'
    ]);

    exit;
}

// Validate the title.
$allowedTitles = ['Mr', 'Mrs', 'Ms', 'Miss', 'Dr', 'Prof'];

if (!in_array($title, $allowedTitles, true)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please select a valid title.'
    ]);

    exit;
}

// Validate the email address.
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);

    exit;
}

// Validate the contact type.
if (!in_array($type, ['Sales Lead', 'Support'], true)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please select a valid contact type.'
    ]);

    exit;
}

// Check field lengths against the database columns.
if (
    mb_strlen($title) > 20 ||
    mb_strlen($firstname) > 100 ||
    mb_strlen($lastname) > 100 ||
    mb_strlen($email) > 255 ||
    mb_strlen($telephone) > 30 ||
    mb_strlen($company) > 255
) {
    echo json_encode([
        'success' => false,
        'message' => 'One or more fields exceed the allowed length.'
    ]);

    exit;
}

try {
    $pdo = getDBConnection();

    // Confirm that the selected user exists.
    $checkUser = $pdo->prepare(
        'SELECT id FROM users WHERE id = :id'
    );

    $checkUser->execute([
        'id' => $assignedTo
    ]);

    if (!$checkUser->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'The selected user does not exist.'
        ]);

        exit;
    }

    // Save the new contact.
    $stmt = $pdo->prepare(
        'INSERT INTO contacts (
            title,
            firstname,
            lastname,
            email,
            telephone,
            company,
            type,
            assigned_to,
            created_by
        ) VALUES (
            :title,
            :firstname,
            :lastname,
            :email,
            :telephone,
            :company,
            :type,
            :assigned_to,
            :created_by
        )'
    );

    $stmt->execute([
        'title' => $title,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'telephone' => $telephone,
        'company' => $company,
        'type' => $type,
        'assigned_to' => $assignedTo,
        'created_by' => $_SESSION['user_id']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Contact created successfully.',
        'contact_id' => $pdo->lastInsertId()
    ]);

} catch (Throwable $e) {
    error_log('Create contact error: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'A server error occurred. Please try again.'
    ]);
}