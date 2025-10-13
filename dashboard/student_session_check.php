<?php
include("./db.php");
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.html");
    exit();
}
$student_id = $_SESSION['student_id'];
?>
