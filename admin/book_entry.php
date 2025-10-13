<?php
include("../dashboard/db.php");
include("./admin_session_check.php");
include("./check_overdue.php");

// Function to generate acronym from book title
function generateAcronym($title) {
    $words = explode(" ", $title);
    $acronym = "";
    foreach ($words as $w) {
        $acronym .= strtoupper(substr($w, 0, 1));
    }
    return $acronym;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title     = $_POST['title'];
    $author    = $_POST['author'];
    $category  = $_POST['category'];
    $edition   = $_POST['edition'];
    $publisher = $_POST['publisher'];
    $amount    = intval($_POST['amount']);

    // ✅ Handle cover image upload
    $targetDir = "uploads/book_covers/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $coverFile = "";
    if (!empty($_FILES["file"]["name"])) {
        $fileName = basename($_FILES["file"]["name"]);
        $targetFile = $targetDir . time() . "_" . $fileName;

        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png"];
        if (in_array($fileType, $allowed)) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                $coverFile = "admin/". $targetFile;
            }
        }
    }

    // ✅ Insert book entry
    $insert_sql = "INSERT INTO book_list (title, author, category, edition, publisher_company, available_copies, cover_page)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssssis", $title, $author, $category, $edition, $publisher, $amount, $coverFile);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<script>alert('Book(s) successfully added!'); window.location.href='book_entry.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
  <title>ICE Seminar Library | Admin Dashboard</title>
  <link rel="stylesheet" href="./admin_header.css">
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
      <span><h2>Welcome, <?php echo htmlspecialchars($adminName); ?></h2></span>
      <img src="<?php echo htmlspecialchars($adminPic); ?>" alt="Admin Profile">
    </div>
  </header>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <nav>
      <a href="./admin_dashboard.php">🏠 Dashboard</a>
      <a href="#">📚 Manage Seminars</a>
      <a href="./book_entry.php" class="active">📂 Upload Books</a>
      <a href="./up_notice.php">📢 Upload Notices</a>
      <a href="./manage_requests.php">👩‍🏫 Manage Requests</a>
      <a href="./report.php">👥 View Reports</a>
      <a href="#">⚙️ Edit Profile</a>
      <a href="./admin_logout.php" class="logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content">
    <div class="form-container">
      <center><img src="./uploads/book_entry.jpg" height="80" width="100" alt="Book Logo"></center>
      <h2>Book Entry Form</h2>
      <form method="POST" action="" enctype="multipart/form-data">
        <label>Book Title:</label>
        <input type="text" name="title" required>

        <label>Author:</label>
        <input type="text" name="author" required>

        <label>Category:</label>
        <input type="text" name="category" required>

        <label>Edition:</label>
        <input type="text" name="edition" required>

        <label>Publisher Company:</label>
        <input type="text" name="publisher" required>

        <label>Number of Copies:</label>
        <input type="number" name="amount" min="1" value="1" required>

        <label for="file">Upload Cover Page:</label>
        <div class="file-upload">
          <input type="file" id="file" name="file" accept=".jpg,.jpeg,.png" required>
          <div id="filePreview" style="margin-top: 10px;"></div>
        </div>

        <button type="submit">Add Book</button>
      </form>
    </div>
  </main>

  <!-- ===== FOOTER ===== -->
  <footer class="library-footer">
    <p>Copyright © ICE Seminar Library. All rights reserved. <?php echo date('Y'); ?></p>
  </footer>

  <!-- ===== JS PREVIEW ===== -->
  <script>
    const fileInput = document.getElementById('file');
    const preview = document.getElementById('filePreview');

    fileInput.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        preview.textContent = `Selected: ${file.name}`;
        if (file.type.startsWith("image/")) {
          const reader = new FileReader();
          reader.onload = function(e) {
            preview.innerHTML = `Selected: ${file.name}<br><img src="${e.target.result}" alt="Preview" style="width:120px;margin-top:8px;">`;
          };
          reader.readAsDataURL(file);
        }
      } else {
        preview.textContent = "No file chosen";
      }
    });
  </script>

</body>
</html>
