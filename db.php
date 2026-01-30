<?php
$host = "localhost";      // Usually localhost
$user = "root";           // Your MySQL username
$pass = "";               // Your MySQL password (empty if XAMPP)
$db   = "smartlygo_db";       // Your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
