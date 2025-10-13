<?php
include("../dashboard/db.php");
include("./admin_session_check.php");
include("./check_overdue.php");

// total books
$total_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM book_list"))['c'];
// total requests
$total_requests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM borrow_requests"))['c'];
// total fines collected (sum of fines in DB)
$total_fines = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(fine),0) AS s FROM borrow_requests"))['s'];
// most borrowed books
$most_borrowed_q = "
SELECT b.title, COUNT(r.request_id) AS cnt
FROM borrow_requests r
JOIN book_list b ON r.book_id = b.book_id
GROUP BY b.book_id
ORDER BY cnt DESC
LIMIT 5";
$top = mysqli_query($conn, $most_borrowed_q);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
<title>Library Report</title>
<link rel="stylesheet" href="./admin_header.css">
<link rel="stylesheet" href="./styles.css">
</head>
<body>
  <body>
  <!-- ===== HEADER ===== -->
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

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <nav>
      <a href="./admin_dashboard.php">🏠 Dashboard</a>
      <a href="#">📚 Manage Seminars</a>
      <a href="./book_entry.php">📂 Upload Books</a>
      <a href="./up_notice.php">📢 Upload Notices</a>
      <a href="./manage_requests.php">👩‍🏫 Manage Requests</a>
      <a href="./report.php" class="active">👥 View Reports</a>
      <a href="#">⚙️ Edit Profile</a>
      <a href="./admin_logout.php" class="logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content">
  <div class="container">
  <h2>Library Reports & Statistics</h2>
  <ul>
    <li>Total Books: <strong><?= $total_books ?></strong></li>
    <li>Total Requests: <strong><?= $total_requests ?></strong></li>
    <li>Total Fines Recorded: <strong>৳ <?= number_format($total_fines,2) ?></strong></li>
  </ul>

  <h3>Top Borrowed Books</h3>
  <table>
    <thead><tr><th>Title</th><th>Times Borrowed</th></tr></thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($top)): ?>
        <tr><td><?= htmlspecialchars($row['title']) ?></td><td><?= $row['cnt'] ?></td></tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</main>
  <!-- ===== FOOTER ===== -->
  <footer class="library-footer">
    <p>Copyright © ICE Seminar Library. All rights reserved. <?php echo date('Y'); ?></p>
  </footer>
</body>
</html>
