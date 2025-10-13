<?php 
include("./dashboard/db.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" sizes="16x16" href="./images/Fav Icon.png">
  <link rel="stylesheet" href="./styles.css">
  <link rel="stylesheet" href="./footer.css">
  <link rel="stylesheet" href="./top_button.css">
  <link rel="stylesheet" href="./header.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home || ICE Seminar Library</title>
</head>
<body>

<!-- HEADER -->
<header>
  <nav>
    <div class="logo">
      <a href="./index.php"><img src="./images/logo.png" alt="Library Logo"></a>
    </div>
    <ul id="nav-links">
      <li><a href="./index.php" class="active">Home📚</a></li>
      <li><a href="./about.html">About🗨️</a></li>
      <li><a href="./services.php">Services🌐</a></li>
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
    <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  </nav>
</header>

<!-- Headline Notice -->
<section id="headline">
  <marquee behavior="scroll" direction="left">
    <?php
      $sql = "SELECT title FROM notice ORDER BY created_at DESC LIMIT 1";
      $result = $conn->query($sql);
      if ($row = $result->fetch_assoc()) {
          echo "📢 Latest Notice: " . htmlspecialchars($row['title']);
      } else {
          echo "📢 No notices available.";
      }
    ?>
  </marquee>
</section>

<!-- Hero Slider -->
<section id="hero-slider" class="reveal">
  <div class="slider">
    <img id="slider-image" src="./images/slide1.jpg" alt="Library Event">
    <div class="caption" id="slider-caption">Welcome to ICE Seminar Library</div>
    <div id="slide-number">1 / 3</div>
    <div class="slider-buttons">
      <button onclick="prevSlide()">⬅️</button>
      <button onclick="nextSlide()">➡️</button>
    </div>
  </div>
</section>

<!-- Library Timetable -->
<section id="timetable" class="reveal">
  <h2>Library Opening Hours</h2>
  <table>
    <thead>
      <tr><th>Day</th><th>Opening</th><th>Closing</th></tr>
    </thead>
    <tbody>
      <tr><td>Sunday</td><td>Closed</td><td>-</td></tr>
      <tr><td>Monday</td><td>9:00 AM</td><td>8:00 PM</td></tr>
      <tr><td>Tuesday</td><td>9:00 AM</td><td>8:00 PM</td></tr>
      <tr><td>Wednesday</td><td>9:00 AM</td><td>8:00 PM</td></tr>
      <tr><td>Thursday</td><td>9:00 AM</td><td>8:00 PM</td></tr>
      <tr><td>Friday</td><td>9:00 AM</td><td>5:00 PM</td></tr>
      <tr><td>Saturday</td><td>10:00 AM</td><td>4:00 PM</td></tr>
    </tbody>
  </table>
</section>

<!-- Services -->
<section id="services" class="reveal">
  <h2>Using the Library</h2>
  <p>Use our tools and services to improve your library experience and save time</p>
  <div class="services-container">
    <div class="service">
      <h3>📘 Borrow, Renew & Return</h3>
      <p>Learn how to use your library materials.</p>
      <a href="./about.html">More details</a> | <a href="#">Tutorial</a>
    </div>
    <div class="service">
      <h3>👤 My Account</h3>
      <p>Log in to check & renew loans.</p>
      <a href="./login.html">Login</a> | <a href="./signup.html">Register</a>
    </div>
  </div>
</section>

<!-- Updates & Events -->
<section id="updates-events" class="reveal">
  <div class="updates">
    <h2>Upcoming Events</h2>
    <ul>
      <?php
      $sql = "SELECT title, event_date FROM events ORDER BY event_date ASC LIMIT 5";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<li>📅 " . htmlspecialchars($row['title']) . " – " . htmlspecialchars($row['event_date']) . "</li>";
          }
      } else { echo "<li>No upcoming events.</li>"; }
      ?>
    </ul>
  </div>
  <div class="news">
    <h2>Latest Notices</h2>
    <ul>
      <?php
      $sql = "SELECT title, created_at FROM notice ORDER BY created_at DESC LIMIT 5";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<li>📢 " . htmlspecialchars($row['title']) . " – " . htmlspecialchars($row['created_at']) . "</li>";
          }
      } else { echo "<li>No news available.</li>"; }
      ?>
    </ul>
  </div>
</section>

<!-- New Arrivals -->
<section id="new-arrivals" class="reveal">
  <h2>New Arrivals</h2>
  <div class="book-slider">
    <?php
    $sql = "SELECT title, author, cover_page FROM book_list ORDER BY created_at DESC LIMIT 6";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="book">';
            echo '<img src="'. htmlspecialchars($row['cover_page']) . '" alt="Book">';
            echo '<p><b>' . htmlspecialchars($row['title']) . '</b><br>Author: ' . htmlspecialchars($row['author']) . '</p>';
            echo '</div>';
        }
    } else { echo "<p>No books available.</p>"; }
    ?>
  </div>
</section>

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

  <div class="footer-bottom">
    <p>Copyright © ICE Seminar Library. All rights reserved. 2025</p>
  </div>
</footer>

<script src="./script.js"></script>
<script src="./top-button.js"></script>
</body>
</html>
