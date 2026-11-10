<?php

/**
 * Dolphin CRM - Authentication and Access Control
 * INFO2180 Project 2
 */

/**
 * Start the session if one has not already been started.
 */
function startAppSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Ensure that a user is logged in.
 * Redirect unauthenticated visitors to the login page.
 */
function requireLogin(): void
{
    startAppSession();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Ensure that the logged-in user is an administrator.
 * Regular members are redirected to the dashboard.
 */
function requireAdmin(): void
{
    requireLogin();

    if (($_SESSION['role'] ?? '') !== 'Admin') {
        header('Location: index.php');
        exit;
    }
}

/**
 * Ensure that an API request comes from an administrator.
 * Return a JSON error instead of redirecting.
 */
function requireAdminApi(): void
{
    startAppSession();

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);

        echo json_encode([
            'success' => false,
            'message' => 'You must be logged in to perform this action.'
        ]);

        exit;
    }

    if (($_SESSION['role'] ?? '') !== 'Admin') {
        http_response_code(403);

        echo json_encode([
            'success' => false,
            'message' => 'You do not have permission to perform this action.'
        ]);

        exit;
    }
}