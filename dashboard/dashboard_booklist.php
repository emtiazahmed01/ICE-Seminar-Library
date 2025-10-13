<?php
include("./student_session_check.php");

// Fetch personal info
$sql = "SELECT * FROM personal_info WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$studentName = $student["name"] ?? "Student";
$studentImage = !empty($student["student_image"]) ? $student["student_image"] : "../images/default.png";

// --- Book list logic ---
$limit = 5;
$page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$where = "WHERE 1";
$params = [];
$types = "";

if (!empty($search)) {
    $where .= " AND (title LIKE ? OR author LIKE ? OR publisher_company LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "sss";
}

if (!empty($category)) {
    $where .= " AND category LIKE ?";
    $params[] = "%$category%";
    $types .= "s";
}

$count_sql = "SELECT COUNT(*) FROM book_list $where";
$stmt_count = $conn->prepare($count_sql);
if (!empty($params)) $stmt_count->bind_param($types, ...$params);
$stmt_count->execute();
$stmt_count->bind_result($total_rows);
$stmt_count->fetch();
$stmt_count->close();

$total_pages = max(1, ceil($total_rows / $limit));

$sql = "SELECT * FROM book_list $where ORDER BY created_at DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $limit;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book List || Student Dashboard</title>
<link rel="stylesheet" href="./dashboard.css">
<link rel="stylesheet" href="../top_button.css">
<style>
/* Add your book-card styles here or in dashboard.css */
.cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
.book-card { background: #fff; border-radius:10px; padding:20px; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
.book-cover img { width:100%; max-height:200px; object-fit:contain; border-radius:6px;}
.borrow-btn { background:#1e88e5; color:white; padding:8px 12px; border:none; border-radius:5px; cursor:pointer; }
.borrow-btn:disabled { background:gray; cursor:not-allowed;}
.pagination a { margin:0 5px; text-decoration:none; padding:8px 12px; border:1px solid #ccc; border-radius:5px;}
.pagination a.active { background:#4CAF50; color:white; }
</style>
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
          <li><a href="./dashboard_booklist.php" class="active">📚 Browse Books</a></li>
          <li><a href="./my_requests.php">📝 My Borrowed Books</a></li>
          <li><a href="./dashboard_notice.php">📢 Notices</a></li>
          <li><a href="./faculty.php">👥 Faculty</a></li>
          <li><a href="./notifications.php">🔔 Notifications</a></li>
        </ul>
      </nav>
    </aside>

  <!-- Main Content -->
  <main class="main-content">
    <h2>Available Books</h2>

    <form method="get" class="form-inline">
        <input type="text" name="search" placeholder="Search by title/author/publisher" value="<?php echo htmlspecialchars($search); ?>">
        <input type="text" name="category" placeholder="Category" value="<?php echo htmlspecialchars($category); ?>">
        <button type="submit">Search</button>
        <a href="./dashboard_booklist.php">Reset</a>
    </form>

    <div class="cards-container">
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <div class="book-card">
            <?php if (!empty($row['cover_page']) && file_exists($row['cover_page'])): ?>
              <div class="book-cover"><img src="../admin/?php echo htmlspecialchars($row['cover_page']); ?>" alt="Book Cover"></div>
            <?php endif; ?>
            <div><strong><?php echo htmlspecialchars($row['title']); ?></strong></div>
            <div>Author: <?php echo htmlspecialchars($row['author']); ?></div>
            <div>Category: <?php echo htmlspecialchars($row['category']); ?></div>
            <div>Edition: <?php echo htmlspecialchars($row['edition']); ?></div>
            <div>Publisher: <?php echo htmlspecialchars($row['publisher_company']); ?></div>
            <div class="<?php echo ($row['available_copies']>0)?'':'unavailable'; ?>">
              <?php echo ($row['available_copies']>0)?"Available ({$row['available_copies']} copies)":"Not Available"; ?>
            </div>
            <?php if ($row['available_copies']>0): ?>
              <form action="./request_book.php" method="POST" style="margin-top:10px;">
                <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                <button class="borrow-btn">Borrow</button>
              </form>
            <?php else: ?>
              <button class="borrow-btn" disabled>Unavailable</button>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No books found.</p>
      <?php endif; ?>
    </div>

    <div class="pagination">
      <?php for ($i=1;$i<=$total_pages;$i++): ?>
        <a href="?page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>" class="<?php if($i==$page) echo 'active'; ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>

  </main>
</div>

<footer class="library-footer">
  <div class="footer-bottom">
    <p>Copyright © ICE Seminar Library. All rights reserved. 2025</p>
  </div>
</footer>

<script src="../top-button.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
