<?php
include("./dashboard/db.php");

// Fetch last 5 notices
$sql = "SELECT id, title, file_path, created_at FROM notice ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);

$notices = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = [
            "id" => $row["id"],
            "title" => $row["title"],
            "file_path" => $row["file_path"],
            "created_at" => $row["created_at"]
        ];
    }
}

// Fetch upcoming events (last 3 for example)
$sql_events = "SELECT id, title, file_path, created_at FROM events ORDER BY created_at DESC LIMIT 3";
$result_events = $conn->query($sql_events);

$events = [];
if ($result_events && $result_events->num_rows > 0) {
    while ($row = $result_events->fetch_assoc()) {
        $events[] = [
            "id" => $row["id"],
            "title" => $row["title"],
            "file_path" => $row["file_path"],
            "created_at" => $row["created_at"]
        ];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notices || ICE Seminar Library</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="./images/Fav Icon.png">
    <link rel="stylesheet" href="./header.css">
    <link rel="stylesheet" href="./top_button.css">
    <link rel="stylesheet" href="./notices.css">
    <link rel="stylesheet" href="./footer.css">
</head>
<body>

  <div class="overlay" id="overlay"></div>
  <header>
    <nav>
      <div class="logo">
        <a href="./index.php"><img src="images/logo.png" alt="Logo"></a>
      </div>
      <ul id="nav-links">
        <li><a href="./index.php">Home📚</a></li>
        <li><a href="./about.html">About🗨️</a></li>
        <li><a href="./services.php">Services🌐</a></li>
        <li><a href="#" class="active">Notices🔔</a></li>
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
      <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
    </nav>
  </header>

<div class="layout">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>NoticeBoard</h3>
        <div class="scrolling">
            <ul id="notice-list">
    <?php foreach ($notices as $i => $notice): ?>
    <li>
        <a href="javascript:void(0)" onclick="currentSlide(<?php echo $i; ?>)">
            <?php echo htmlspecialchars($notice['title']); ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>

        </div>
    </div>
    <!-- Slideshow -->
    <div class="slideshow-container" id="slideshow">
        <?php foreach ($notices as $i => $notice): ?>
        <div class="slide <?php echo $i === 0 ? 'active' : ''; ?>">
            <img src="./admin/<?php echo htmlspecialchars($notice['file_path']); ?>" alt="<?php echo htmlspecialchars($notice['title']); ?>">
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

    <!-- Right Sidebar -->
<div class="sidebar upcoming-events">
    <h3>Upcoming Events</h3>
    <div class="event-list">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
            <div class="event-poster">
                <img src="<?php echo htmlspecialchars($event['file_path']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                <div class="event-caption"><?php echo htmlspecialchars($event['title']); ?></div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No upcoming events available.</p>
        <?php endif; ?>
    </div>
</div>

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

      <div class="footer-social">
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin"></i></a>
      </div>
    </div>

  </div>

  <!-- Bottom Line -->
  <div class="footer-bottom">
    <p>Copyright © ICE Seminar Library. All rights reserved. 2025</p>
  </div>
</footer>
<script src="./top-button.js"></script>
<script src="./notices.js"></script>
</body>
</html>

