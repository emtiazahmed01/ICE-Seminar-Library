<?php
include("./dashboard/db.php");

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$where = "WHERE 1";
$params = [];
$types = "";

// Search filter
if (!empty($search)) {
    $where .= " AND (title LIKE ? OR author LIKE ? OR publisher_company LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "sss";
}

// Category filter
if (!empty($category)) {
    $where .= " AND category LIKE ?";
    $params[] = "%$category%";
    $types .= "s";
}

// Count total rows
$count_sql = "SELECT COUNT(*) FROM book_list $where";
$stmt = $conn->prepare($count_sql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$stmt->bind_result($total_rows);
$stmt->fetch();
$stmt->close();

$total_pages = max(1, ceil($total_rows / $limit));

// Fetch books
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
<link rel="icon" type="image/png" sizes="16x16" href="./images/Fav Icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Services || ICE Seminar Library</title>
  <link rel="stylesheet" href="./styles.css">
  <link rel="stylesheet" href="./footer.css">
  <link rel="stylesheet" href="./top_button.css">
  <link rel="stylesheet" href="./header.css">
  <link rel="stylesheet" href="./books.css">
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <header>
    <nav>
      <div class="logo">
        <a href="./index.php"><img src="./images/logo.png" alt="Logo"></a>
      </div>
      <ul id="nav-links">
      <li><a href="./index.php">Home📚</a></li>
      <li><a href="./about.html">About🗨️</a></li>
      <li><a href="./services.php" class="active">Services🌐</a></li>
      <li><a href="./notice.php">Notices🔔</a></li>
      <li><a href="./download.html">Forms⬇️</a></li>
      <li><a href="./contact.html">Contact📞</a></li>
      <li class="dropdown">
        <a class="dropdown-btn">Account🧑‍🎓 ▾</a>
        <div class="dropdown-menu">
          <a href="./login.html">Login</a>
          <a href="./signup.html">Signup</a>
        </div>
      </li>
    </ul>
      <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>
  </header>

<h2>Book List</h2>

<form method="get" class="form-inline">
    <input type="text" name="search" placeholder="Search by title/author/publisher" value="<?php echo htmlspecialchars($search); ?>">
    <input type="text" name="category" placeholder="Category" value="<?php echo htmlspecialchars($category); ?>">
    <button type="submit">Search</button>
    <a href="./services.php">Reset</a>
</form>

<div class="cards-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="book-card">
                <?php if (!empty($row['cover_page']) && file_exists($row['cover_page'])): ?>
                    <div class="book-cover">
                        <img src="<?php echo htmlspecialchars($row['cover_page']); ?>" alt="Book Cover">
                    </div>
                <?php endif; ?>
                <div class="book-title"><?php echo htmlspecialchars($row['title']); ?></div>
                <div class="book-info"><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></div>
                <div class="book-info"><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></div>
                <div class="book-info"><strong>Edition:</strong> <?php echo htmlspecialchars($row['edition']); ?></div>
                <div class="book-info"><strong>Publisher:</strong> <?php echo htmlspecialchars($row['publisher_company']); ?></div>
                <div class="book-status <?php echo ($row['available_copies'] > 0) ? '' : 'unavailable'; ?>">
                    <?php echo ($row['available_copies'] > 0) ? "Available ({$row['available_copies']} copies)" : "Not Available"; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">No books found.</p>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>"
           class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>

<div class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up">⬆️</i>
  </div>

<footer class="library-footer">
  <div class="footer-container">

    <!-- Left Logo & Text -->
    <div class="footer-logo">
      <img src="./images/ICE.png" alt="ICE Seminar Library Logo">
      <p class="brand">ICE Seminar Library</p>
      <p class="slogan">Knowledge at Your Fingertips....</p>
    </div>

    <!-- Help Links -->
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

    <!-- Explore Links -->
    <div class="footer-column">
      <h3>🌐Explore</h3>
      <ul>
        <li><a href="https://nstu.edu.bd/" target="_blank"><b>NSTU Website</b></a></li>
        <li><a href="https://sportal.nstu.ac.bd/" target="_blank"><b>NSTU Student Portal</b></a></li>
        <li><a href="https://e-noticeboard-nu.vercel.app/" target="_blank"><b>E-NoticeBoard-ICE</b></a></li>
        <li><a href="https://admission.nstu.edu.bd/" target="_blank"><b>NSTU Admission Portal</b></a></li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div class="footer-column">
      <h3>📍Get in Touch</h3>
      <p>Academic Building-2 (8th Floor)<br>Sonapur, Noakhali-3814;<br>Noakhali</p>
      <ul>
      <li><p>A/C: 0200005277182</p></li>
      <li><p>Helpline: <a href="tel:02334496522">02334496522</a></p></li>
      <li><p>Email: <a href="mailto:office.ice.nstu@gmail.com">office.ice.nstu@gmail.com</a></p></li>
      </ul>

    </div>

  </div>

  <div class="footer-bottom">
    <p>Copyright © ICE Seminar Library. All rights reserved. 2025</p>
  </div>
</footer>

<script src="./top-button.js"></script> 

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
