<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

// Only administrators can create users.
requireAdminApi();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);

    exit;
}

$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

if (
    $firstname === '' ||
    $lastname === '' ||
    $email === '' ||
    $password === '' ||
    $role === ''
) {
    echo json_encode([
        'success' => false,
        'message' => 'Please complete all fields.'
    ]);

    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);

    exit;
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Password must contain at least 8 characters, including an uppercase letter, a lowercase letter and a number.'
    ]);

    exit;
}

if (!in_array($role, ['Admin', 'Member'], true)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please select a valid user role.'
    ]);

    exit;
}

try {
    $pdo = getDBConnection();

    // Check whether the email is already registered.
    $checkEmail = $pdo->prepare(
        'SELECT id FROM users WHERE email = :email'
    );

    $checkEmail->execute(['email' => $email]);

    if ($checkEmail->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'A user with this email address already exists.'
        ]);

        exit;
    }

    // Hash the password before storing it.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'INSERT INTO users (firstname, lastname, email, password, role)
         VALUES (:firstname, :lastname, :email, :password, :role)'
    );

    $stmt->execute([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => $role
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'User created successfully.'
    ]);

} catch (Throwable $e) {
    error_log('Create user error: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'A server error occurred. Please try again.'
    ]);
}