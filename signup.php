<?php
session_start();
include("./dashboard/db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

$error = "";
$insert_successful = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form values
    $studentID   = trim($_POST["studentID"]);
    $name        = trim($_POST["name"]);
    $father      = trim($_POST["father"]);
    $mother      = trim($_POST["mother"]);
    $dob         = $_POST["dob"];
    $mobile      = trim($_POST["mobile"]);
    $hall        = trim($_POST["hall"]);
    $blood       = trim($_POST["blood"]);
    $gender      = trim($_POST["gender"]);
    $dept        = trim($_POST["dept"]);
    $level       = trim($_POST["level"]);
    $sessionVal  = trim($_POST["session"]);
    $yearTerm    = trim($_POST["yearTerm"]);
    $email       = trim($_POST["email"]);
    $password    = $_POST["password"];
    $confirmPass = $_POST["confirmPassword"];

    // Validate password
    if ($password !== $confirmPass) {
        $error = "Passwords do not match!";
    } else {

        // Image upload
        $targetDir = "./dashboard/uploads/simages/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $imageFileType = strtolower(pathinfo($_FILES["studentImg"]["name"], PATHINFO_EXTENSION));
        $fileName = $studentID . "." . $imageFileType;
        $targetFilePath = $targetDir . $fileName;
        $relativePath = "./uploads/simages/" . $fileName;

        if ($_FILES["studentImg"]["size"] > 1000000) {
            $error = "Image must be less than 1MB!";
        } elseif (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
            $error = "Only JPG, JPEG, PNG allowed!";
        } else {
            // Check for duplicate email or student_id
            $sqlCheck = "SELECT * FROM users WHERE email=? OR student_id=?";
            $stmt = $conn->prepare($sqlCheck);
            $stmt->bind_param("ss", $email, $studentID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Email or Student ID already registered!";
            } else {
                // Transaction start
                $conn->begin_transaction();

                try {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $verificationCode = bin2hex(random_bytes(16));

                    // Insert into users table
                    $sql1 = "INSERT INTO users (student_id, email, password, verification_code) VALUES (?, ?, ?, ?)";
                    $stmt1 = $conn->prepare($sql1);
                    $stmt1->bind_param("ssss", $studentID, $email, $hashedPassword, $verificationCode);
                    $stmt1->execute();

                    // Move image
                    move_uploaded_file($_FILES["studentImg"]["tmp_name"], $targetFilePath);

                    // Insert into personal_info
                    $sql2 = "INSERT INTO personal_info 
                             (student_id, name, father_name, mother_name, dob, mobile, hall, blood_group, gender, student_image)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bind_param("ssssssssss", $studentID, $name, $father, $mother, $dob, $mobile, $hall, $blood, $gender, $relativePath);
                    $stmt2->execute();

                    // Insert into academic_info
                    $sql3 = "INSERT INTO academic_info (student_id, department, level, session, year_term)
                             VALUES (?, ?, ?, ?, ?)";
                    $stmt3 = $conn->prepare($sql3);
                    $stmt3->bind_param("sssss", $studentID, $dept, $level, $sessionVal, $yearTerm);
                    $stmt3->execute();

                    // Commit transaction
                    $conn->commit();

                    // PHPMailer - send verification email
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';           // SMTP server
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'libraryiceseminar@gmail.com';      // SMTP username
                    $mail->Password   = 'rqurrtowzqlcttfq';        // SMTP password (app password)
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('noreply@yourdomain.com', 'ICE Seminar Library');
                    $mail->addAddress($email, $name);

                    $verifyLink = "http://localhost/Seminar_Library/verify.php?code=$verificationCode&student_id=$studentID";

                    $mail->isHTML(true);
                    $mail->Subject = "Verify Your Email - ICE Seminar Library";
                    $mail->Body    = "Hello $name,<br><br>Click the link to verify your email:<br>
                                      <a href='$verifyLink'>$verifyLink</a><br><br>Thank you.";

                    $mail->send();

                    $insert_successful = true;

                } catch (Exception $e) {
                    $conn->rollback();
                    $error = "Error during registration: " . $e->getMessage();
                }
            }
        }
    }
}

// Final Response
if ($insert_successful) {
    $_SESSION['popupMessage'] = "✅ Registration Successful! Please check your email to verify your account.";
} elseif ($error) {
    $_SESSION['popupMessage'] = "❌ Registration Error: " . $error;
}

header("Location: ./signup.html");
exit();
?>
