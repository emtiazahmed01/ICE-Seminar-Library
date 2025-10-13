<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If not logged in, redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ./admin_login.php");
    exit();
}

// Common admin variables
$adminID   = $_SESSION['admin_id'] ?? null;
$adminName = $_SESSION['admin_name'] ?? "Admin";
$adminEmail = $_SESSION['admin_email'] ?? "";
$adminRole = $_SESSION['admin_role'] ?? "";
$adminPic  = !empty($_SESSION['admin_picture']) ? $_SESSION['admin_picture'] : "../images/default_profile.png";
?>
