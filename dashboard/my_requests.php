<?php
include("./student_session_check.php");
include("./db.php"); // ✅ Ensure DB connection

// Fetch student info for sidebar
$sqlStudent = "SELECT * FROM personal_info WHERE student_id = ?";
$stmtStudent = $conn->prepare($sqlStudent);
$stmtStudent->bind_param("s", $student_id);
$stmtStudent->execute();
$resultStudent = $stmtStudent->get_result();
$student = $resultStudent->fetch_assoc();

$studentName = $student["name"] ?? "Student";
$studentImage = !empty($student["student_image"]) ? $student["student_image"] : "../images/default.png";

// Fetch borrow requests
$sql = "SELECT r.*, b.title, b.author
        FROM borrow_requests r
        JOIN book_list b ON r.book_id = b.book_id
        WHERE r.student_id = ?
        ORDER BY r.request_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$res = $stmt->get_result();

// Fetch latest notifications (10)
$notiSql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10";
$stmt2 = $conn->prepare($notiSql);
$stmt2->bind_param("s", $student_id);
$stmt2->execute();
$notis = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Borrow Requests || Student Dashboard</title>
  <link rel="stylesheet" href="./dashboard.css">
  <style>
    .dashboard-main {
      margin-left: 250px;
      padding: 20px;
      margin-top: 20px;
      background: #f5f6fa;
      min-height: 100vh;
    }

    h2 {
      color: #2d3436;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    th, td {
      text-align: left;
      padding: 12px 15px;
      border-bottom: 1px solid #eee;
    }

    th {
      background: #1a237e;
      color: white;
      font-weight: normal;
    }

    tr:hover {
      background: #f9f9f9;
    }

    .status-pending { color: #f39c12; font-weight: bold; }
    .status-approved { color: #27ae60; font-weight: bold; }
    .status-rejected { color: #e74c3c; font-weight: bold; }
    .status-returned { color: #2980b9; font-weight: bold; }

    footer.library-footer {
      background: #1a237e;
      color: #fff;
      text-align: center;
      padding: 10px 0;
      margin-top: 40px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .dashboard-main {
        margin-left: 0;
        padding: 10px;
      }
      .sidebar {
        position: fixed;
        left: -100%;
        top: 0;
        height: 100%;
        width: 250px;
        background: #1a237e;
        transition: 0.3s;
        z-index: 100;
      }
      .sidebar.active {
        left: 0;
      }
      .hamburger {
        display: block;
        cursor: pointer;
        font-size: 22px;
        margin-right: 15px;
      }
      .header-left {
        display: flex;
        align-items: center;
        gap: 10px;
      }
    }

    .hamburger {
      display: none;
      flex-direction: column;
      gap: 5px;
    }
    .hamburger span {
      width: 25px;
      height: 3px;
      background: #fff;
      border-radius: 2px;
    }
  </style>
</head>
<body>
  <!-- ===== HEADER ===== -->
  <header class="main-header">
    <div class="header-left">
      <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
      </div>
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
    <aside class="sidebar" id="sidebar">
      <div class="student-profile">
        <img src="<?php echo htmlspecialchars($studentImage); ?>" alt="Student Photo" class="student-photo">
        <h3 class="student-name"><?php echo htmlspecialchars($studentName); ?></h3>
      </div>
      <nav>
        <ul>
          <li><a href="./dashboard.php">🏠 Dashboard</a></li>
          <li><a href="./dashboard_booklist.php">📚 Browse Books</a></li>
          <li><a href="./my_requests.php" class="active">📝 My Borrowed Books</a></li>
          <li><a href="./dashboard_notice.php">📢 Notices</a></li>
          <li><a href="./faculty.php">👥 Faculty</a></li>
          <li><a href="./notifications.php">🔔 Notifications</a></li>
        </ul>
      </nav>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="dashboard-main">
      <h2>📖 My Borrow Requests</h2>
      <div class="dashboard-flex" style="display: flex; gap: 20px; flex-wrap: wrap;">
        <!-- Requests Table -->
        <div style="flex: 2; min-width: 60%;">
          <table>
            <thead>
              <tr>
                <th>Book</th>
                <th>Author</th>
                <th>Requested</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Fine</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($res->num_rows > 0): ?>
                <?php while ($r = $res->fetch_assoc()): 
                  $statusClass = 'status-' . strtolower($r['status']);
                ?>
                <tr>
                  <td><?= htmlspecialchars($r['title']) ?></td>
                  <td><?= htmlspecialchars($r['author']) ?></td>
                  <td><?= date('d M Y', strtotime($r['request_date'])) ?></td>
                  <td><?= !empty($r['due_date']) ? date('d M Y', strtotime($r['due_date'])) : '-' ?></td>
                  <td class="<?= $statusClass ?>">
                    <?= $r['status'] ?>
                    <?php if ($r['status'] === 'Returned' && !empty($r['return_date'])): ?>
                      <br><small>on <?= date('d M Y', strtotime($r['return_date'])) ?></small>
                    <?php endif; ?>
                  </td>
                  <td><?= ($r['fine'] > 0) ? "৳ " . number_format($r['fine'], 2) : '-' ?></td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="6">No borrow requests found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== FOOTER ===== -->
  <footer class="library-footer">
    <div class="footer-bottom">
      <p>Copyright © ICE Seminar Library. All rights reserved. 2025</p>
    </div>
  </footer>

  <!-- ===== JS FOR HAMBURGER ===== -->
  <script>
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    hamburger.addEventListener('click', () => {
      sidebar.classList.toggle('active');
    });
  </script>
</body>
</html>
