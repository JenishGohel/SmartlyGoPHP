<?php
// Include the database logic and variables from the previous step
// This section handles the form submission, error variables (like $email_err, $password_err, etc.), and redirection.
// The UI below uses these variables.
require_once "signup_logic.php";
// Note: Assuming you moved the PHP logic from the previous signup.php into a new file named signup_logic.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            
            <div class="input-group">
                <label for="fullname">Full Name</label>
                <input 
                    type="text" 
                    id="fullname" 
                    name="fullname" 
                    required
                    value="<?php echo $fullname; // Keeps the entered name on error ?>"
                >
                <span class="error-msg"><?php echo $fullname_err; ?></span>
            </div>

            <div class="input-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    value="<?php echo $email; // Keeps the entered email on error ?>"
                >
                <span class="error-msg"><?php echo $email_err; ?></span>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                >
                <span class="error-msg"><?php echo $password_err; ?></span>
            </div>

            <div class="input-group">
                <label for="confirm-password">Confirm Password</label>
                <input 
                    type="password" 
                    id="confirm-password" 
                    name="confirm-password" 
                    required
                >
            </div>
            
            <button type="submit">Sign Up</button>
        </form>
        
        <div class="link-text">
            Already have an account? <a href="login.php">Log In</a>
        </div>
    </div>
</body>
</html>