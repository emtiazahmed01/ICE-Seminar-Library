<?php
include("./student_session_check.php");

// Fetch notices
$sql = "SELECT id, title, file_path, created_at FROM notice ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
$notices = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
}

// Fetch upcoming events
$sql_events = "SELECT id, title, file_path, created_at FROM events ORDER BY created_at DESC LIMIT 3";
$result_events = $conn->query($sql_events);
$events = [];
if ($result_events && $result_events->num_rows > 0) {
    while ($row = $result_events->fetch_assoc()) {
        $events[] = $row;
    }
}

// Fetch student info
$sql = "SELECT * FROM personal_info WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$studentName = $student["name"] ?? "Student";
$studentImage = $student["student_image"] ?? "../images/default.png";

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Notices || Student Dashboard</title>
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
          <li><a href="./dashboard.php">🏠 Dashboard</a></li>
          <li><a href="./dashboard_booklist.php">📚 Browse Books</a></li>
          <li><a href="./my_requests.php">📝 My Borrowed Books</a></li>
          <li><a href="./dashboard_notice.php"  class="active">📢 Notices</a></li>
          <li><a href="./faculty.php">👥 Faculty</a></li>
          <li><a href="./notifications.php">📬 Notifications</a></li>
        </ul>
      </nav>
    </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content">
    <div class="notice-layout">

      <!-- Left Notice List -->
      <div class="sidebar-section">
        <h3>Notice List</h3>
        <div class="scrolling">
          <ul id="notice-list">
            <?php foreach ($notices as $i => $notice): ?>
              <li><a href="javascript:void(0)" onclick="currentSlide(<?php echo $i; ?>)">
                <?php echo htmlspecialchars($notice['title']); ?>
              </a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

      <!-- Center Slideshow -->
      <div class="slideshow-container" id="slideshow">
        <?php foreach ($notices as $i => $notice): ?>
          <div class="slide <?php echo $i === 0 ? 'active' : ''; ?>">
            <img src="../admin/<?php echo htmlspecialchars($notice['file_path']); ?>" alt="<?php echo htmlspecialchars($notice['title']); ?>">
            <div class="caption"><?php echo htmlspecialchars($notice['title']); ?></div>
          </div>
        <?php endforeach; ?>

        <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next" onclick="changeSlide(1)">&#10095;</a>

        <div class="dots-container" id="dots-container">
          <?php foreach ($notices as $i => $notice): ?>
            <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $i; ?>)"></span>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right Sidebar: Events -->
      <div class="sidebar-section upcoming-events">
        <h3>Upcoming Events</h3>
        <div class="event-list">
          <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
              <div class="event-poster">
                <img src=".<?php echo htmlspecialchars($event['file_path']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                <div class="event-caption"><?php echo htmlspecialchars($event['title']); ?></div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No upcoming events available.</p>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </main>
</div>

<!-- ===== FOOTER ===== -->
<footer class="library-footer">
  <div class="footer-bottom">
    <p>Copyright © ICE Seminar Library. All rights reserved. 2025</p>
  </div>
</footer>

<!-- ===== JS ===== -->
<script>
let currentSlideIndex = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

function showSlide(index) {
  slides.forEach(s => s.classList.remove("active"));
  dots.forEach(d => d.classList.remove("active"));
  currentSlideIndex = (index + slides.length) % slides.length;
  slides[currentSlideIndex].classList.add("active");
  dots[currentSlideIndex].classList.add("active");
}

function changeSlide(n) { showSlide(currentSlideIndex + n); }
function currentSlide(n) { showSlide(n); }
</script>
</body>
</html>
