<?php
include("./student_session_check.php");

// Fetch personal info using prepared statement
$sql = "SELECT * FROM personal_info WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$studentName = $student["name"] ?? "Student";
$studentImage = !empty($student["student_image"]) ? $student["student_image"] : "../images/default.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard || Student Dashboard</title>
  <link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
          <li><a href="./dashboard.php" class="active">🏠 Dashboard</a></li>
          <li><a href="./dashboard_booklist.php">📚 Browse Books</a></li>
          <li><a href="./my_requests.php">📝 My Borrowed Books</a></li>
          <li><a href="./dashboard_notice.php">📢 Notices</a></li>
          <li><a href="./faculty.php">👥 Faculty</a></li>
          <li><a href="./notifications.php">🔔 Notifications</a></li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h2>Welcome, <?php echo htmlspecialchars($studentName); ?></h2>
      <p>Select a service from the sidebar to continue.</p>

      <!-- Cards Section -->
      <div class="dashboard-cards">
        <div class="card">
          <h3>📚 Borrowed Books</h3>
          <p>View your current borrowed books and due dates.</p>
          <a href="./my_requests.php" class="card-btn">View Books</a>
        </div>

        <div class="card">
          <h3>📢 Latest Notices</h3>
          <p>Stay updated with library announcements.</p>
          <a href="./dashboard_notice.php" class="card-btn">View Notices</a>
        </div>

        <div class="card">
          <h3>🔔 Notifications</h3>
          <p>Check your latest notifications and updates.</p>
          <a href="./notifications.php" class="card-btn">Open</a>
        </div>

        <div class="card">
          <h3>📅 Book List</h3>
          <p>Explore available books and make requests.</p>
          <a href="./dashboard_booklist.php" class="card-btn">Browse Books</a>
        </div>
      </div>
    </main>
  </div>

  <!-- Footer -->
  <footer class="library-footer">
    <div class="footer-bottom">
      <p>Copyright © ICE Seminar Library. All rights reserved. 2025</p>
    </div>
  </footer>
  <script src="../top-button.js"></script>
</body>
</html>
