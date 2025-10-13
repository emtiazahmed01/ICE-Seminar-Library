<?php
session_start();
include("./dashboard/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Fetch user from 'users' table
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if email is verified
        if ($user['is_verified'] == 0) {
            $_SESSION['popupMessage'] = "❌ Your email is not verified. Please check your inbox!";
            header("Location: login.html");
            exit();
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Fetch personal info (optional, for dashboard)
            $sqlPersonal = "SELECT * FROM personal_info WHERE student_id = ?";
            $stmtPersonal = $conn->prepare($sqlPersonal);
            $stmtPersonal->bind_param("s", $user['student_id']);
            $stmtPersonal->execute();
            $personalInfo = $stmtPersonal->get_result()->fetch_assoc();

            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['user_name'] = $personalInfo['name'] ?? '';
            
            header("Location: ./dashboard/dashboard.php");
            exit();
        } else {
            $_SESSION['popupMessage'] = "❌ Invalid email or password!";
            header("Location: login.html");
            exit();
        }
    } else {
        $_SESSION['popupMessage'] = "❌ Invalid email or password!";
        header("Location: login.html");
        exit();
    }
}
?>
