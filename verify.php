<?php
include("./dashboard/db.php");

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $sql = "UPDATE users SET is_verified=1 WHERE verification_code=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $code);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "✅ Email verified! You can now <a href='./login.html'>login</a>.";
    } else {
        echo "❌ Invalid or already used verification code.";
    }
}
?>
