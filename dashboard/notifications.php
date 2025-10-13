<?php
include("./student_session_check.php");

// ✅ Fetch student info securely
$sql = "SELECT name, student_image FROM personal_info WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$studentName = $student["name"] ?? "Student";
$studentImage = !empty($student["student_image"]) ? $student["student_image"] : "../images/default.png";

// ✅ Fetch notifications securely
$stmt = $conn->prepare("SELECT message, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$notis = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications || Student Dashboard</title>
  <link rel="stylesheet" href="./dashboard.css">
  <link rel="stylesheet" href="../top_button.css">
</head>
<body>

<!-- ===== HEADER ===== -->
  <header class="main-header">
    <div class="header-left">
      <a href="dashboard.php"><img src="../images/logo.png" alt="ICE Logo" class="logo"></a>
      <h1>ICE Seminar Library</h1>
    </div>
    <div class="header-right">
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </header>

  <!-- ===== DASHBOARD LAYOUT ===== -->
  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="student-profile">
        <img src="<?php echo htmlspecialchars($studentImage); ?>" alt="Student Photo" class="student-photo">
        <h3 class="student-name"><?php echo htmlspecialchars($studentName); ?></h3>
      </div>
      <nav>
        <ul>
          <li><a href="./dashboard.php" >🏠 Dashboard</a></li>
          <li><a href="./dashboard_booklist.php">📚 Browse Books</a></li>
          <li><a href="./my_requests.php">📝 My Borrowed Books</a></li>
          <li><a href="./dashboard_notice.php">📢 Notices</a></li>
          <li><a href="./faculty.php">👥 Faculty</a></li>
          <li><a href="./notifications.php"  class="active">🔔 Notifications</a></li>
        </ul>
      </nav>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">
      <h2>🔔 Your Notifications</h2>
      <div class="notifications-container">
        <ul class="notifications">
          <?php if ($notis->num_rows > 0): ?>
            <?php while ($n = $notis->fetch_assoc()): ?>
              <li>
                <div class="notification-message">
                  <?= htmlspecialchars($n['message']); ?>
                </div>
                <small class="notification-time">
                  <?= date('d M Y, h:i A', strtotime($n['created_at'])); ?>
                </small>
              </li>
            <?php endwhile; ?>
          <?php else: ?>
            <li class="no-notifications">No notifications available.</li>
          <?php endif; ?>
        </ul>
      </div>
    </main>

  </div>

  <!-- ===== FOOTER ===== -->
  <footer class="library-footer">
    <div class="footer-bottom">
      <p>Copyright © ICE Seminar Library. All rights reserved. 2025</p>
    </div>
  </footer>

</body>
</html>
