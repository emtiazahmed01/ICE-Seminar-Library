<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['student_id'])) {
    // Clear all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();
}

// Redirect to login page
header("Location: ../login.html");
exit();
?>