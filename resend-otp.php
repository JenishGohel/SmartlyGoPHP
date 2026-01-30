<?php
session_start();
require_once "db.php";
require_once "mail.php";

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit;
}

$email = $_SESSION['reset_email'];
$message = "";

// Reuse logic to fetch user and send email
$sql = "SELECT user_id, name FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // Generate NEW 6-digit OTP
    $otp = rand(100000, 999999);
    $otp_hash = hash("sha256", $otp);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 10); // 10 minutes

    $sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $otp_hash, $expiry, $user["user_id"]);
    
    if ($stmt->execute()) {
        $mail = getMailer();
        if ($mail) {
            try {
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Resend Password Reset OTP';
                $mail->Body    = <<<EOT
                    Hi {$user['name']},<br><br>
                    Your NEW OTP for password reset is: <b>$otp</b><br><br>
                    This code expires in 10 minutes.<br>
                EOT;

                $mail->send();
                $message = "New OTP sent!";
            } catch (Exception $e) {
                $message = "Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
} else {
    $message = "User not found.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Resend OTP</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { text-align: center; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="container">
        <p><?php echo $message; ?></p>
        <a href="verify-otp.php">Enter OTP</a>
    </div>
</body>
</html>
