<?php
// check_overdue.php
if (!isset($conn)) include_once("../dashboard/db.php"); // adjust path if needed

// Update any approved requests past due date -> Overdue
$updateSql = "
    UPDATE borrow_requests
    SET status = 'Overdue'
    WHERE status = 'Approved' AND due_date IS NOT NULL AND due_date < CURDATE()
";
mysqli_query($conn, $updateSql);
?>
