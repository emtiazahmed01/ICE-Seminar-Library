<?php
include("./student_session_check.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)$_SESSION['student_id'];
    $book_id = (int)$_POST['book_id'];

    // Basic checks
    if ($book_id <= 0) {
        die("Invalid book.");
    }

    // Check if book exists and has copies
    $bRes = mysqli_query($conn, "SELECT available_copies FROM book_list WHERE book_id = $book_id");
    if (!$b = mysqli_fetch_assoc($bRes)) {
        echo "<script>alert('Book not found.'); window.location.href='./dashboard_booklist.php';</script>";
        exit;
    }
    if ((int)$b['available_copies'] <= 0) {
        echo "<script>alert('Book not currently available.'); window.location.href='./dashboard_booklist.php';</script>";
        exit;
    }

    // Prevent duplicate pending request
    $q = "SELECT * FROM borrow_requests WHERE student_id=$student_id AND book_id=$book_id AND status='Pending'";
    $chk = mysqli_query($conn, $q);
    if (mysqli_num_rows($chk) > 0) {
        echo "<script>alert('You already have a pending request for this book.'); window.location.href='./dashboard_booklist.php';</script>";
        exit;
    }

    // Insert request
    $ins = "INSERT INTO borrow_requests (student_id, book_id, status) VALUES ($student_id, $book_id, 'Pending')";
    if (mysqli_query($conn, $ins)) {
        echo "<script>alert('Request submitted successfully.'); window.location.href='book_list.php';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to submit request.'); window.location.href='book_list.php';</script>";
        exit;
    }
}
?>
