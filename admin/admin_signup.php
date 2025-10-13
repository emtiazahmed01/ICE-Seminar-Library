<?php
session_start();
include("../dashboard/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password_raw = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $roles = $_POST['role'] ?? []; // Array of roles from checkboxes

    // Password confirmation check
    if ($password_raw !== $confirm_password) {
        echo "<script>alert('❌ Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    // Hash password
    $password = password_hash($password_raw, PASSWORD_BCRYPT);

    // Convert roles array to comma-separated string
    $role = implode(", ", $roles);

    // Upload folders
    $profileDir = "uploads/profile/";
    $signDir = "uploads/sign/";

    if (!is_dir($profileDir)) mkdir($profileDir, 0777, true);
    if (!is_dir($signDir)) mkdir($signDir, 0777, true);

    // File flags
    $profileOk = false;
    $signOk = false;
    $profilePath = "";
    $signPath = "";

    // ✅ Check if profile picture uploaded
    if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] == 0) {
        $profileTmp = $_FILES["profile"]["tmp_name"];
        $profileName = time() . "_profile_" . basename($_FILES["profile"]["name"]);
        $profilePath = $profileDir . $profileName;

        $profileSize = getimagesize($profileTmp);
        if ($profileSize && $profileSize[0] == 300 && $profileSize[1] == 300) {
            move_uploaded_file($profileTmp, $profilePath);
            $profileOk = true;
        } else {
            echo "<script>alert('❌ Profile picture must be 300x300 pixels');</script>";
        }
    }

    // ✅ Check if signature uploaded
    if (isset($_FILES["signature"]) && $_FILES["signature"]["error"] == 0) {
        $signTmp = $_FILES["signature"]["tmp_name"];
        $signName = time() . "_sign_" . basename($_FILES["signature"]["name"]);
        $signPath = $signDir . $signName;

        $signSize = getimagesize($signTmp);
        if ($signSize && $signSize[0] == 300 && $signSize[1] == 80) {
            move_uploaded_file($signTmp, $signPath);
            $signOk = true;
        } else {
            echo "<script>alert('❌ Signature must be 300x80 pixels');</script>";
        }
    }

    // ✅ Only insert if both files are valid
    if ($profileOk && $signOk) {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM admin_users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "<script>alert('❌ Email already registered!'); window.history.back();</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO admin_users 
                (full_name, email, password, role, profile_picture, signature_file) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $full_name, $email, $password, $role, $profilePath, $signPath);

            if ($stmt->execute()) {
                echo "<script>alert('✅ Admin account created successfully!'); window.location='admin_login.php';</script>";
            } else {
                echo "❌ Database error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }

    $conn->close();
}
?>