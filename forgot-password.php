<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add specific styles for the forgot password page if needed, 
           inheriting from style.css for consistency */
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        
        <p>Enter your email address to receive a password reset link.</p>

        <form action="send-password-reset.php" method="POST">
            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required>
            </div>
            
            <button type="submit">Send Reset Link</button>
        </form>
        
        <div class="link-text" style="margin-top: 15px; text-align: center;">
            <a href="index.php">Back to Login</a>
        </div>
    </div>
</body>
</html>
