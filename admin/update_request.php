<?php
include("../dashboard/db.php");
include("./admin_session_check.php");
include("./check_overdue.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ./manage_requests.php");
    exit();
}

$request_id = (int)$_POST['request_id'];
$action = $_POST['action'];

// fetch request data
$rQ = mysqli_query($conn, "SELECT * FROM borrow_requests WHERE request_id = $request_id");
if (!$rq = mysqli_fetch_assoc($rQ)) {
    echo "<script>alert('Request not found.'); window.location.href='-./manage_requests.php';</script>";
    exit;
}
$book_id = (int)$rq['book_id'];
$student_id = (int)$rq['student_id'];

// fetch student email/name
$stu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM personal_info WHERE id=$student_id"));
$student_name = $stu['name'] ?? '';

$stu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM users WHERE id=$student_id"));
$student_email = $stu['email'] ?? '';

if ($action === 'approve') {
    // reduce available copies safely
    mysqli_query($conn, "UPDATE book_list SET available_copies = available_copies - 1 WHERE book_id=$book_id AND available_copies > 0");

    // set due date (7 days from today) - change duration if needed
    $dueDate = date('Y-m-d', strtotime('+7 days'));
    mysqli_query($conn, "UPDATE borrow_requests SET status='Approved', approved_date=NOW(), due_date='$dueDate' WHERE request_id=$request_id");

    // create notification
    $msg = "Your request for book (ID:$book_id) has been approved. Due date: $dueDate";
    mysqli_query($conn, "INSERT INTO notifications (user_id, message) VALUES ($student_id, '".mysqli_real_escape_string($conn, $msg)."')");

    // optional email (ensure mail is configured)
    /*
    $subject = "Book Request Approved";
    $body = "Hello $student_name,\n\n$msg\n\nRegards";
    mail($student_email, $subject, $body);
    */

    echo "<script>alert('Request approved.'); window.location.href='manage_requests.php';</script>";
    exit;
}

if ($action === 'reject') {
    mysqli_query($conn, "UPDATE borrow_requests SET status='Rejected' WHERE request_id=$request_id");
    $msg = "Your request for book (ID:$book_id) was rejected by the library.";
    mysqli_query($conn, "INSERT INTO notifications (user_id, message) VALUES ($student_id, '".mysqli_real_escape_string($conn, $msg)."')");
    /*
    mail($student_email, "Book Request Rejected", "Hello $student_name,\n\n$msg\n\nRegards");
    */
    echo "<script>alert('Request rejected.'); window.location.href='manage_requests.php';</script>";
    exit;
}

if ($action === 'return') {
    // increase copies
    mysqli_query($conn, "UPDATE book_list SET available_copies = available_copies + 1 WHERE book_id=$book_id");

    // Calculate fine if overdue
    // Use due_date or approved_date to compute days borrowed
    $row = $rq;
    $due_date = $row['due_date'];
    $approved_date = $row['approved_date'];
    $return_date = date('Y-m-d H:i:s');
    $fine_amount = 0.00;

    // if due_date exists and return is after due_date, calculate fine
    if (!empty($due_date)) {
        $today = new DateTime(); // return time
        $due = new DateTime($due_date);
        if ($today > $due) {
            $diff = $due->diff($today);
            $daysOver = (int)$diff->days;
            $finePerDay = 10.00; // change per-day fine
            $fine_amount = $daysOver * $finePerDay;
        }
    }

    // update borrow_requests
    mysqli_query($conn, "UPDATE borrow_requests SET status='Returned', return_date='$return_date', fine=$fine_amount WHERE request_id=$request_id");

    // create notification
    $msg = "Book (ID:$book_id) marked returned. Fine: ৳ ".number_format($fine_amount,2);
    mysqli_query($conn, "INSERT INTO notifications (user_id, message) VALUES ($student_id, '".mysqli_real_escape_string($conn, $msg)."')");

    /*
    mail($student_email, "Book Returned", "Hello $student_name,\n\n$msg\n\nRegards");
    */

    echo "<script>alert('Book marked returned. Fine: ৳ ".number_format($fine_amount,2)."'); window.location.href='manage_requests.php';</script>";
    exit;
}

// unknown action
header("Location: ./manage_requests.php");
exit();
?>
