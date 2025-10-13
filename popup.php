<?php
session_start();

$popupMessage = isset($_SESSION['popupMessage']) ? $_SESSION['popupMessage'] : '';
unset($_SESSION['popupMessage']); // clear after showing once

echo json_encode(["message" => $popupMessage]);
