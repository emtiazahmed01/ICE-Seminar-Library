<?php
include("../dashboard/db.php");
include("./admin_session_check.php");
include("./check_overdue.php");

// Initialize message
$message = '';

if (isset($_POST['submit'])) {
    $title = $_POST['title'];

    $targetDir = "uploads/notices/"; // folder to store files
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName   = basename($_FILES["file"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName; // unique name
    $fileType   = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedTypes = array("jpg", "jpeg", "png");

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // ✅ Correct Insert with prepared statement
            $stmt = $conn->prepare("INSERT INTO notice (title, file_path, created_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $title, $targetFile);

            if ($stmt->execute()) {
                $message = '<p style="color:green;">✅ Notice uploaded successfully</p>';
            } else {
                $message = '<p style="color:red;">❌ Database error: ' . $stmt->error . '</p>';
            }

            $stmt->close();
        } else {
            $message = '<p style="color:red;">❌ Error uploading file.</p>';
        }
    } else {
        $message = '<p style="color:red;">❌ Invalid file type.</p>';
    }
}

$conn->close();
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
      <a href="./book_entry.php">📚 Upload Books</a>
      <a href="./up_notice.php" class="active">📢 Upload Notices</a>
      <a href="./manage_requests.php">👩‍🏫 Manage Requests</a>
      <a href="./report.php">👥 View Reports</a>
      <a href="#">⚙️ Edit Profile</a>
      <a href="./admin_logout.php" class="logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content">

    <div class="form-container">
      <h2>Upload New Notice</h2>
      <p style="text-align:center;color:#555;margin-bottom:15px;">
        Upload new notice files (JPG, JPEG, PNG)
      </p>

      <?php if (!empty($message)) echo $message; ?>

      <form action="" method="post" enctype="multipart/form-data">

        <label for="topic">Notice Topic:</label>
        <input type="text" id="topic" name="title" placeholder="Enter notice title" required>

        <label for="file">Upload File:</label>
        <div class="file-upload">
          <input type="file" id="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" required>
          <div id="filePreview" style="margin-top: 10px;"></div>
        </div>

        <button type="submit" name="submit">Submit Notice</button>
      </form>
    </div>

  </main>

  <!-- ===== FOOTER ===== -->
  <footer class="library-footer">
    <p>Copyright © ICE Seminar Library. All rights reserved. <?php echo date('Y'); ?></p>
  </footer>

  <!-- ===== JS: FILE PREVIEW ===== -->
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