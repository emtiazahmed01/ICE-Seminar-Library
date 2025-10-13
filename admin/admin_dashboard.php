<?php
include("../dashboard/db.php");
include("./admin_session_check.php");
include("./check_overdue.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
  <title>Dashboard || Admin Dashboard</title>
  <link rel="stylesheet" href="./admin_header.css">
  <link rel="stylesheet" href="./admin_dashboard.css">
  <link rel="stylesheet" href="./styles.css">
</head>
<body>

  <!-- Top Navigation Bar -->
  <header class="navbar">
    <div class="logo">
      <img src="../images/logo.png" alt="ICE Logo">
      <h1>ICE Seminar Library</h1>
    </div>
    <div class="admin-info">
      <span><h2>Welcome, <?php echo htmlspecialchars($adminName); ?></h2></span>
      <img src="<?php echo htmlspecialchars($adminPic); ?>" alt="Admin Profile">
    </div>
  </header>

  <!-- Main Dashboard Container -->
  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <nav>
      <a href="./admin_dashboard.php"  class="active">🏠 Dashboard</a>
      <a href="#">📚 Manage Seminars</a>
      <a href="./book_entry.php">📂 Upload Books</a>
      <a href="./up_notice.php">📢 Upload Notices</a>
      <a href="./manage_requests.php">👩‍🏫 Manage Requests</a>
      <a href="./report.php">👥 View Reports</a>
      <a href="#">⚙️ Edit Profile</a>
      <a href="./admin_logout.php" class="logout">🚪 Logout</a>
    </nav>
    </aside>
  <main class="main-content">

  <div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($adminName); ?> 👋</h2>
    <div class="cards">
      <div class="card" onclick="location.href='./up_notice.php'">
        Notices
      </div>
      <div class="card" onclick="location.href='./manage_requests.php'">
        Book Approval
      </div>
      <div class="card" onclick="location.href='./report.php'">
        Borrowed Books
      </div>
      <div class="card" onclick="location.href='./book_entry.php'">
        Book Entry
      </div>
    </div>
  </div>
    </main> <!-- End of main.content -->
  </div> <!-- End of dashboard-container -->

  <footer class="library-footer">
    <div class="footer-bottom">
      <p>Copyright © ICE Seminar Library. All rights reserved. <?php echo date('Y'); ?></p>
    </div>
  </footer>

</body>
</html>
