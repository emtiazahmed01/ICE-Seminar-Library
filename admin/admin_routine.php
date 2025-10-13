<?php
include("../dashboard/db.php");
include("./admin_session_check.php");
include("./check_overdue.php");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $semester = $_POST['semester'];

    // Days array
    $days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday"];

    foreach ($days as $day) {
        for ($slot = 1; $slot <= 6; $slot++) { // 6 slots example
            $subject = $_POST[$day . "_subject_" . $slot] ?? '';
            $teacher = $_POST[$day . "_teacher_" . $slot] ?? '';
            $time    = $_POST[$day . "_time_" . $slot] ?? '';

            if (!empty($subject) && !empty($time)) {
                $sql = "INSERT INTO routine (semester, day, slot, subject, teacher, time) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssisss", $semester, $day, $slot, $subject, $teacher, $time);
                $stmt->execute();
            }
        }
    }

    echo "<p style='color:green;'>Routine for $semester added successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
  <title>Add Week Routine</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f4f4f9; }
    h2 { text-align:center; color:#333; }
    form { width:95%; margin:auto; background:#fff; padding:20px; border-radius:8px; }
    table { width:100%; border-collapse: collapse; margin-bottom:20px; }
    th, td { border:1px solid #ccc; padding:8px; text-align:center; }
    th { background:#eee; }
    input[type=text] { width:95%; padding:5px; }
    select { padding:5px; }
    .submit-btn { background:#1a73e8; color:#fff; padding:10px 20px; border:none; cursor:pointer; border-radius:5px; }
    .submit-btn:hover { background:#0b59c7; }
  </style>
</head>
<body>

<h2>Admin: Add Full Week Routine</h2>
<form method="post">

  <label><b>Select Semester:</b></label>
  <select name="semester" required>
    <option value="">--Select--</option>
    <option>Year: 1, Term: 1</option>
    <option>Year: 1, Term: 2</option>
    <option>Year: 2, Term: 1</option>
    <option>Year: 2, Term: 2</option>
    <option>Year: 3, Term: 1</option>
    <option>Year: 3, Term: 2</option>
    <option>Year: 4, Term: 1</option>
    <option>Year: 4, Term: 2</option>
  </select>
  <br><br>

  <?php
  $days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday"];
  foreach ($days as $day) {
      echo "<h3>$day</h3>";
      echo "<table>
              <tr>
                <th>Slot</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Time</th>
              </tr>";
      for ($i = 1; $i <= 6; $i++) {
          echo "<tr>
                  <td>$i</td>
                  <td><input type='text' name='{$day}_subject_$i' placeholder='Subject'></td>
                  <td><input type='text' name='{$day}_teacher_$i' placeholder='Teacher'></td>
                  <td><input type='text' name='{$day}_time_$i' placeholder='e.g. 9:00-10:00'></td>
                </tr>";
      }
      echo "</table>";
  }
  ?>

  <center><input type="submit" value="Save Routine" class="submit-btn"></center>

</form>

</body>
</html>
