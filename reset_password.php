<?php
session_start();
include("./dashboard/db.php");

$showForm = false;
$token = "";

// Handle POST request (password reset)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        $_SESSION['popupMessage'] = "❌ Passwords do not match!";
        header("Location: reset_password.php?token=$token");
        exit();
    }

    // Check token
    $sql = "SELECT * FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        // Update password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sqlUpdate = "UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ss", $hashedPassword, $token);
        $stmtUpdate->execute();

        $_SESSION['popupMessage'] = "✅ Password reset successful! You can now login.";
        header("Location: ./login.html");
        exit();
    } else {
        $_SESSION['popupMessage'] = "❌ Invalid or expired token!";
        header("Location: ./login.html");
        exit();
    }
}
// Show form if token is provided
elseif (isset($_GET['token'])) {
    $token = $_GET['token'];
    $showForm = true;
} else {
    $_SESSION['popupMessage'] = "❌ Invalid access!";
    header("Location: ./login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password || ICE Seminar Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" sizes="16x16" href="./images/Fav Icon.png">
  <link rel="stylesheet" href="./signup.css">
  <link rel="stylesheet" href="./top_button.css">
  <link rel="stylesheet" href="./header.css">
  <link rel="stylesheet" href="./footer.css">
</head>
<body class="signup-page">
  <div class="overlay" id="overlay"></div>

  <!-- ===== HEADER ===== -->
  <header>
    <nav>
      <div class="logo">
        <a href="./index.php"><img src="./images/logo.png" alt="Logo"></a>
      </div>
      <ul id="nav-links">
        <li><a href="./index.php">Home📚</a></li>
        <li><a href="./about.html">About🗨️</a></li>
        <li><a href="./services.php">Services🌐</a></li>
        <li><a href="./notice.php">Notices🔔</a></li>
        <li><a href="./download.html">Forms⬇️</a></li>
        <li><a href="./contact.html">Contact📞</a></li>
        <li><a href="./signup.html">SignUp🧑‍🎓</a></li>
        <li><a href="./login.html" class="active">Login</a></li>
      </ul>
      <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>
  </header>

  <!-- ===== CENTERED FORM CONTAINER ===== -->
  <div class="container">
    <h2>Reset Password</h2>

    <?php if($showForm): ?>
      <form method="POST" action="./reset_password.php">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
        <button type="submit" class="btn">Reset Password</button>
      </form>
    <?php else: ?>
      <p style="text-align:center; color:red;">❌ Invalid or expired token!</p>
    <?php endif; ?>
  </div>

  <!-- ===== BACK TO TOP ===== -->
  <div class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up">⬆️</i>
  </div>

  <!-- ===== FOOTER ===== -->
  <footer class="library-footer">
    <div class="footer-container">
      <div class="footer-logo">
        <img src="./images/ICE.png" alt="ICE Seminar Library Logo">
        <p class="brand">ICE Seminar Library</p>
        <p class="slogan">Knowledge at Your Fingertips....</p>
      </div>

      <div class="footer-column">
        <h3>📖Help</h3>
        <ul>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms & Conditions</a></li>
          <li><a href="#">FAQs</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h3>🌐Explore</h3>
        <ul>
          <li><a href="https://nstu.edu.bd/" target="_blank"><b>NSTU Website</b></a></li>
          <li><a href="https://sportal.nstu.ac.bd/" target="_blank"><b>NSTU Student Portal</b></a></li>
          <li><a href="https://e-noticeboard-nu.vercel.app/" target="_blank"><b>E-NoticeBoard-ICE</b></a></li>
          <li><a href="https://admission.nstu.edu.bd/" target="_blank"><b>NSTU Admission Portal</b></a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h3>📍Get in Touch</h3>
        <p>Academic Building-2 (8th Floor)<br>Sonapur, Noakhali-3814;<br>Noakhali</p>
        <ul>
          <li><p>A/C: 0200005277182</p></li>
          <li><p>Helpline: <a href="tel:02334496522">02334496522</a></p></li>
          <li><p>Email: <a href="mailto:office.ice.nstu@gmail.com">office.ice.nstu@gmail.com</a></p></li>
        </ul>
        <div class="footer-social">
          <a href="#"><i class="fab fa-youtube"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <p>Copyright © ICE Seminar Library. All rights reserved. 2025</p>
    </div>
  </footer>

  <!-- ===== POPUP ===== -->
  <div id="popup" style="display:none;">
    <div class="popup-content">
      <span id="closePopup">&times;</span>
      <p id="popupMessage"><?php
        if(isset($_SESSION['popupMessage'])) {
            echo $_SESSION['popupMessage'];
            unset($_SESSION['popupMessage']);
        }
      ?></p>
    </div>
  </div>

  <script src="./top-button.js"></script>  
  <script src="./signup.js"></script>
</body>
</html>
