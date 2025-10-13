<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("./student_session_check.php");
include("./db.php");


// ✅ Fetch student info
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
  <link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faculty || Student Dashboard</title>
  <link rel="stylesheet" href="./dashboard.css">
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
          <li><a href="./dashboard_booklist.php">📚 Browse Books</a></li>
          <li><a href="./my_requests.php">📝 My Borrowed Books</a></li>
          <li><a href="./dashboard_notice.php">📢 Notices</a></li>
          <li><a href="./faculty.php"  class="active">👥 Faculty</a></li>
          <li><a href="./notifications.php">🔔 Notifications</a></li>
        </ul>
      </nav>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">
      <h2>Our Faculty Members</h2>
      <p>Meet the respected teachers of the Department of Information & Communication Engineering (ICE).</p>

      <div class="faculty">
        <div class="faculty-card">
          <img src="../images/faculty/Chairman_sir.png" alt="Dr. Md. Ashikur Rahman Khan">
          <h4>Dr. Md. Ashikur Rahman Khan</h4>
          <p>Chairman & Professor<br><b>+8801836350710</b><br>ashik@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Abid_sir.jpg" alt="Dr. Abidur Rahaman">
          <h4>Dr. Abidur Rahaman</h4>
          <p>Professor<br><b>+8801914317509</b><br>abidur@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Amzad_Sir.jpg" alt="Dr. Mohammad Amzad Hossain">
          <h4>Dr. Mohammad Amzad Hossain</h4>
          <p>Associate Professor<br><b>+8801722941909</b><br>amzad@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Masud_sir.jpg" alt="Dr. Md. Masudur Rahman">
          <h4>Dr. Md. Masudur Rahman</h4>
          <p>Associate Professor<br><b>+8801712442399</b><br>masudur@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Apurbo_Sir.jpg" alt="Dr. Apurba Adhikary">
          <h4>Dr. Apurba Adhikary</h4>
          <p>Associate Professor<br><b>+8801743947031</b><br>apurba@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/MainUddin_sir.jpeg" alt="Dr. Main Uddin">
          <h4>Dr. Main Uddin</h4>
          <p>Associate Professor<br><b>+8801721155620</b><br>mainuddin.ice@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Soheli_mam.jpeg" alt="Sultana Jahan Soheli">
          <h4>Sultana Jahan Soheli</h4>
          <p>Assistant Professor<br><b>+8801754429533</b><br>sjsoheli.ice@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Tanvir_sir.jpg" alt="Tanvir Zaman Khan">
          <h4>Tanvir Zaman Khan</h4>
          <p>Assistant Professor<br><b>+8801731256704</b><br>tzkhan19@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Naeem_sir.jpeg" alt="Md. Mahbubul Alam">
          <h4>Md. Mahbubul Alam</h4>
          <p>Assistant Professor<br><b>+8801755281840</b><br>mahbubulalam@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Sabbir_sir.png" alt="Md. Sabbir Ejaz">
          <h4>Md. Sabbir Ejaz</h4>
          <p>Assistant Professor<br><b>+8801725990631</b><br>sabbirejaz.ice@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Kamrul_sir.jpg" alt="Mohammad Kamrul Hasan">
          <h4>Mohammad Kamrul Hasan</h4>
          <p>Assistant Professor<br><b>+8801671082313</b><br>kamrul.ice@nstu.edu.bd</p>
        </div>

        <div class="faculty-card">
          <img src="../images/faculty/Istiaq_sir.jpeg" alt="Ishtiaq Ahammad">
          <h4>Ishtiaq Ahammad</h4>
          <p>Lecturer<br><b>+8801888369757</b><br>ishtiaq@nstu.edu.bd</p>
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

</body>
</html>
