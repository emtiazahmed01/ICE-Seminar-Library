<?php
session_start();
include("../dashboard/db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, email, password, role, profile_picture FROM admin_users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['full_name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_role'] = $row['role'];
            $_SESSION['admin_picture'] = $row['profile_picture'];

            header("Location: ./admin_dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid password.";
        }
    } else {
        $error = "❌ No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>
