<?php
// process-reset-password.php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    die("Access Denied");
}

$password = $_POST["password"];
$password_confirmation = $_POST["password_confirmation"];
$email = $_SESSION['reset_email'];

if ($password !== $password_confirmation) {
    die("Passwords must match");
}

if (strlen($password) < 6) { 
    die("Password must be at least 6 characters");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password_hash = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $password_hash, $email);

if ($stmt->execute()) {
    // Clear session
    unset($_SESSION['reset_email']);
    unset($_SESSION['otp_verified']);
    
    echo <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>Password Reset Successful</title>
        <link rel="stylesheet" href="style.css">
        <style>
             .container { text-align: center; margin-top: 50px; }
             h2 { color: green; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Password Updated Successfully</h2>
            <p>You can now login with your new password.</p>
            <a href="index.php">Login</a>
        </div>
    </body>
    </html>
    HTML;
} else {
    die("Error updating password: " . $conn->error);
}
?>
