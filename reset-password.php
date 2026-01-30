<?php
// reset-password.php
session_start();

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    echo "Access Denied. Please verify OTP first.";
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Set New Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 400px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Set New Password</h2>
        <form action="process-reset-password.php" method="POST">
            
            <div class="input-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="input-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            
            <button type="submit">Update Password</button>
        </form>
    </div>
</body>
</html>
