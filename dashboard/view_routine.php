<?php
include("./student_session_check.php");

// Get semester (year_term) from academic_info
$sql = "SELECT year_term FROM academic_info WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$semester = $row['year_term'];  // auto-detected semester

// Fetch routine
$sql = "SELECT * FROM routine 
        WHERE semester = ? 
        ORDER BY FIELD(day,'Sunday','Monday','Tuesday','Wednesday','Thursday'), slot ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $semester);
$stmt->execute();
$routine_result = $stmt->get_result();
$data = $routine_result->fetch_all(MYSQLI_ASSOC);

// Organize routine data by day and slot
$routines = [];
foreach ($data as $row) {
    $day = $row['day'];
    $slot = (int)$row['slot'];
    $routines[$day][$slot] = [
        'subject' => $row['subject'],
        'teacher' => $row['teacher'],
        'time' => $row['time']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" sizes="16x16" href="../images/Fav Icon.png">
  <title>View Class Routine</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f4f4f9; margin:0; padding:0; }
    h2 { text-align:center; color:#333; margin-top:20px; }
    table { width:90%; margin:20px auto; border-collapse:collapse; }
    th, td { border:1px solid #ccc; padding:10px; text-align:center; }
    th { background:#eee; }
    td { background:#fff; }
    .day-heading { background:#ddd; font-weight:bold; text-align:left; }
  </style>
</head>
<body>

<h2>Student: View Class Routine</h2>

<?php if (!empty($semester)): ?>
  <h3 style="text-align:center;">Routine for <?= htmlspecialchars($semester) ?></h3>
  <table>
    <tr>
      <th>Day</th>
      <th>Slot 1</th>
      <th>Slot 2</th>
      <th>Slot 3</th>
      <th>Slot 4</th>
      <th>Slot 5</th>
      <th>Slot 6</th>
    </tr>
    <?php
    $days = ["Sunday","Monday","Tuesday","Wednesday","Thursday"];
    foreach ($days as $day) {
        echo "<tr>";
        echo "<td class='day-heading'>$day</td>";
        for ($slot=1; $slot<=6; $slot++) {
            if (isset($routines[$day][$slot])) {
                $subj = htmlspecialchars($routines[$day][$slot]['subject']);
                $teacher = htmlspecialchars($routines[$day][$slot]['teacher']);
                $time = htmlspecialchars($routines[$day][$slot]['time']);
                echo "<td><b>$subj</b><br>$teacher<br><small>$time</small></td>";
            } else {
                echo "<td>-</td>";
            }
        }
        echo "</tr>";
    }
    ?>
  </table>
<?php else: ?>
  <p style="text-align:center; color:red;">No semester info found for your account.</p>
<?php endif; ?>

</body>
</html>
