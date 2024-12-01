<?php
session_start(); // Start the session

// Check if the PHPSESSID cookie exists
if (isset($_COOKIE['PHPSESSID'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Clear the PHPSESSID cookie
    setcookie('PHPSESSID', '', time() - 3600, '/'); // Expires the cookie

    // Optional: Redirect to the login page or send a success response
    header("Location: login.php"); // Redirect to login page
    exit();
} else {
    // If PHPSESSID cookie is not set, deny the logout attempt
    http_response_code(400);
    echo "No active session found.";
}
