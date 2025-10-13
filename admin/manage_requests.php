<?php
include("../dashboard/db.php");
include("./admin_session_check.php");
include("./check_overdue.php");

// Fetch all borrow requests with correct joins
$sql = "
    SELECT 
        r.request_id,
        r.student_id,
        r.book_id,
        r.request_date,
        r.due_date,
        r.status,
        r.fine,
        b.title,
        b.author,
        p.name AS student_name,
        u.email AS student_email
    FROM borrow_requests r
    JOIN book_list b ON r.book_id = b.book_id
    JOIN personal_info p ON r.student_id = p.student_id
    JOIN users u ON p.student_id = u.student_id
    ORDER BY r.request_date DESC
";

$res = mysqli_query($conn, $sql);
if (!$res) {
    die("Database error: " . mysqli_error($conn));
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Manage Requests</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
<link rel="stylesheet" href="./admin_header.css">
<link rel="stylesheet" href="./admin_dashboard.css">
<link rel="stylesheet" href="./styles.css">
</head>
<body>
  <!-- ===== HEADER ===== -->
  <header class="navbar">
    <div class="logo">
      <img src="../images/logo.png" alt="ICE Logo">
      <h1>ICE Seminar Library</h1>
    </div>
    <div class="admin-info">
      <span><h2>Welcome, <?php echo htmlspecialchars($adminName ?? 'Admin'); ?></h2></span>
      <img src="<?php echo htmlspecialchars($adminPic ?? '../images/default_admin.png'); ?>" alt="Admin Profile">
    </div>
  </header>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <nav>
      <a href="./admin_dashboard.php">🏠 Dashboard</a>
      <a href="#">📚 Manage Seminars</a>
      <a href="./book_entry.php">📂 Upload Books</a>
      <a href="./up_notice.php">📢 Upload Notices</a>
      <a href="./manage_requests.php" class="active">👩‍🏫 Manage Requests</a>
      <a href="./report.php">📊 View Reports</a>
      <a href="#">⚙️ Edit Profile</a>
      <a href="./admin_logout.php" class="logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content">
    <div class="container">
      <h2>Manage Book Requests</h2>
      <a href="./report.php" class="btn">View Reports</a>
      <table>
        <thead>
          <tr>
            <th>Student</th>
            <th>Book</th>
            <th>Requested</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Fine</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($res) > 0): ?>
            <?php while ($r = mysqli_fetch_assoc($res)): ?>
              <tr>
                <td>
                  <?= htmlspecialchars($r['student_name']) ?><br>
                  <small><?= htmlspecialchars($r['student_email']) ?></small>
                </td>
                <td>
                  <?= htmlspecialchars($r['title']) ?><br>
                  <small><?= htmlspecialchars($r['author']) ?></small>
                </td>
                <td><?= date('d M Y', strtotime($r['request_date'])) ?></td>
                <td><?= !empty($r['due_date']) ? date('d M Y', strtotime($r['due_date'])) : '-' ?></td>
                <td class="status-<?= strtolower($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></td>
                <td><?= ($r['fine'] > 0) ? "৳ " . number_format($r['fine'], 2) : '-' ?></td>
                <td>
                  <?php if ($r['status'] == 'Pending'): ?>
                    <form method="POST" action="update_request.php" style="display:inline;">
                      <input type="hidden" name="request_id" value="<?= $r['request_id'] ?>">
                      <input type="hidden" name="action" value="approve">
                      <button type="submit" class="approve">Approve</button>
                    </form>
                    <form method="POST" action="update_request.php" style="display:inline;">
                      <input type="hidden" name="request_id" value="<?= $r['request_id'] ?>">
                      <input type="hidden" name="action" value="reject">
                      <button type="submit" class="reject">Reject</button>
                    </form>
                  <?php elseif ($r['status'] == 'Approved' || $r['status'] == 'Overdue'): ?>
                    <form method="POST" action="update_request.php" style="display:inline;">
                      <input type="hidden" name="request_id" value="<?= $r['request_id'] ?>">
                      <input type="hidden" name="action" value="return">
                      <button type="submit" class="return">Mark Returned</button>
                    </form>
                  <?php else: ?>
                    <button class="disabled" disabled><?= htmlspecialchars($r['status']) ?></button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="7">No requests found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- ===== FOOTER ===== -->
  <footer class="library-footer">
    <p>Copyright © ICE Seminar Library. All rights reserved. <?= date('Y') ?></p>
  </footer>
</body>
</html>
