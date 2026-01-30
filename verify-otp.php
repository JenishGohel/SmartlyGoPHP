<?php
session_start();
require_once "db.php";

$error = "";

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST["otp"];
    $otp_hash = hash("sha256", $otp);
    $email = $_SESSION['reset_email'];

    $sql = "SELECT * FROM users WHERE email = ? AND reset_token_hash = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $otp_hash);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (strtotime($user["reset_token_expires_at"]) <= time()) {
            $error = "OTP has expired";
        } else {
            // OTP Valid
            $_SESSION['otp_verified'] = true;
            header("Location: reset-password.php");
            exit;
        }
    } else {
        $error = "Invalid OTP";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="style.css">
    <style>
       .container { max-width: 400px; margin: 50px auto; padding: 20px; text-align: center; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
       input { padding: 10px; margin: 10px 0; width: 80%; text-align: center; font-size: 1.2em; letter-spacing: 5px; }
       .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter OTP</h2>
        <p>We sent a 6-digit code to <b><?php echo htmlspecialchars($_SESSION['reset_email']); ?></b></p>
        
        <?php if($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="otp" placeholder="123456" maxlength="6" required>
            <br>
            <button type="submit">Verify</button>
        </form>
        
        <div class="link-text" style="margin-top: 15px;">
            <p>Didn't receive code? <a href="resend-otp.php">Resend OTP</a></p>
        </div>
    </div>
</body>
</html>
