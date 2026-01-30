<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "db.php";
require_once "mail.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    $sql = "SELECT user_id, name FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Generate 6-digit OTP
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
                    $mail->Subject = 'Password Reset OTP';
                    $mail->Body    = <<<EOT
                        Hi {$user['name']},<br><br>
                        Your OTP for password reset is: <b>$otp</b><br><br>
                        This code expires in 10 minutes.<br>
                        <br>
                        <small>If you didn't request this, ignore this email.</small>
                    EOT;

                    $mail->send();
                    
                    // Store email in session to verify next
                    $_SESSION['reset_email'] = $email;
                    header("Location: verify-otp.php");
                    exit;

                } catch (Exception $e) {
                    $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $message = "Mailer configuration error.";
            }
        } else {
            $message = "Error updating database."; 
        }

    } else {
        $message = "If an account with that email exists, we have sent an OTP.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Processing...</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { text-align: center; margin-top: 50px; }
        .message { font-size: 1.2em; color: red; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- If we are here, it means error or email not found/sent -->
        <p class="message"><?php echo $message; ?></p>
        <a href="forgot-password.php">Try Again</a>
    </div>
</body>
</html>
