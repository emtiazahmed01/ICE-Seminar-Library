<?php
session_start();
include("./dashboard/db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if user exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        // Generate reset token
        $resetToken = bin2hex(random_bytes(16));
        $sqlUpdate = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ss", $resetToken, $email);
        $stmtUpdate->execute();

        // Send reset email via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '';
            $mail->Password   = ''; // App password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('noreply@yourdomain.com', 'ICE Seminar Library');
            $mail->addAddress($email, $user['student_id']);

            $resetLink = "http://localhost/Seminar_Library/reset_password.php?token=$resetToken";

            $mail->isHTML(true);
            $mail->Subject = "Reset Your Password - ICE Seminar Library";
            $mail->Body    = "Hello,<br><br>
                              Click the link below to reset your password:<br>
                              <a href='$resetLink'>$resetLink</a><br><br>
                              If you did not request this, ignore this email.";

            $mail->send();
            $_SESSION['popupMessage'] = "✅ Reset link sent! Please check your email.";
        } catch (Exception $e) {
            $_SESSION['popupMessage'] = "❌ Email sending failed: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['popupMessage'] = "❌ Email not found!";
    }

    header("Location: ./forgot_password.html");
    exit();
}
?>

