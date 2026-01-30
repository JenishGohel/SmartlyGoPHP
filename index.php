<?php
require "db.php";
session_start();

// --- Initialize Variables ---
$current_action = isset($_GET['action']) ? $_GET['action'] : 'login';
$login_err = $signup_message = "";
$email = $fullname = $signup_email = $stream = $institute = $state = $country = "";

// --- LOGIN FORM ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_submit'])) {
    $email = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $login_err = "";

    if ($email === "" || $password === "") {
        $login_err = "Please provide both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, name, email, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['loggedin'] = true;
                header("Location: dashboard.php");
                exit;
            } else {
                $login_err = "Incorrect password.";
            }
        } else {
            $login_err = "No account found with that email.";
        }
        $stmt->close();
    }
}

// --- SIGNUP FORM ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_submit'])) {
    $fullname = trim($_POST["fullname"]);
    $signup_email = trim($_POST["email"]);
    $stream = trim($_POST["stream"]);
    $institute = trim($_POST["institute"]);
    $state = trim($_POST["state"]);
    $country = trim($_POST["country"]);
    $password_1 = $_POST["password_1"];
    $password_2 = $_POST["password_2"];

    $errors = [];
    if (strlen($fullname) < 3) $errors[] = "Full name must be at least 3 characters.";
    if (!filter_var($signup_email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
    if (strlen($password_1) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password_1 !== $password_2) $errors[] = "Passwords do not match.";
    if ($stream === "") $errors[] = "Please select a stream.";
    if (strlen($institute) < 2) $errors[] = "Institute name is required.";
    if (strlen($state) < 2) $errors[] = "State is required.";
    if (strlen($country) < 2) $errors[] = "Country is required.";

    if (count($errors) > 0) {
        $signup_message = "ERROR: " . implode(" ", $errors);
        $current_action = 'signup';
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $signup_email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $signup_message = "ERROR: An account with that email already exists.";
            $current_action = 'signup';
            $stmt->close();
        } else {
            $stmt->close();
            $password_hash = password_hash($password_1, PASSWORD_DEFAULT);
            $insert = $conn->prepare("
                INSERT INTO users (name, email, password_hash, stream, institute_name, state, country)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $insert->bind_param("sssssss", $fullname, $signup_email, $password_hash, $stream, $institute, $state, $country);

            if ($insert->execute()) {
                $signup_message = "Successfully Enrolled : Enrollment submitted! Please log in.";
                $current_action = 'login';
                $fullname = $signup_email = $stream = $institute = $state = $country = "";
            } else {
                $signup_message = "ERROR: Could not register. Try again later.";
                $current_action = 'signup';
            }
            $insert->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($current_action == 'login' ? 'Student Login' : 'Enroll Now'); ?></title>
    
    <style>
        :root {
            --primary-color: #a855f7; 
            --primary-hover: #9333ea; 
            --background-start: #fef3f7; 
            --background-end: #e9d5ff; 
            --text-dark: #374151; 
            --glass-bg: rgba(255, 255, 255, 0.6); 
            --error-color: #ef4444; 
            --success-color: #10b981;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--background-start) 0%, var(--background-end) 100%);
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            position: relative;
        }

        /* --- MOVING OBJECTS --- */
        .edu-icon {
            position: absolute; font-size: 50px; opacity: 0.6; filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1)); z-index: 0;
        }
        .icon-book { top: 10%; left: 15%; animation: floatMove 15s infinite alternate ease-in-out; }
        .icon-pen { bottom: 20%; right: 10%; animation: floatMove 18s infinite alternate-reverse linear; }
        .icon-cap { top: 80%; left: 5%; animation: floatMove 12s infinite alternate ease-in-out; }
        @keyframes floatMove {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(50px, -50px) rotate(5deg); }
            100% { transform: translate(0, 0) rotate(-5deg); }
        }

        /* --- GLASS MORPHISM CARD --- */
        .container {
            background: var(--glass-bg);
            backdrop-filter: blur(15px); 
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.8); 
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 480px;
            text-align: center;
            z-index: 10; 
            transition: transform 0.3s;
        }

        /* --- RESPONSIVE LAYOUT FOR SIGNUP --- */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .container {
                width: 95%;
                padding: 20px;
            }
        }

        h2 {
            background-image: linear-gradient(45deg, var(--text-dark), var(--primary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 25px;
        }

        .input-group {
            text-align: left;
            margin-bottom: 5px;
        }
        .input-group label {
            display: block; font-weight: 600; color: var(--text-dark); margin-bottom: 5px;
        }
        .input-group input, .input-group select {
            width: 100%; padding: 10px; border: 1px solid rgba(0, 0, 0, 0.2); border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.8); color: var(--text-dark); box-sizing: border-box;
        }
        .input-group input:focus, .input-group select:focus {
            border-color: var(--primary-color); outline: none; background-color: white;
        }

        button {
            width: 100%; padding: 14px; margin-top: 20px;
            background: linear-gradient(90deg, var(--background-end) 0%, var(--primary-color) 100%);
            color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem;
            font-weight: 700; box-shadow: 0 5px 15px rgba(168, 85, 247, 0.4); transition: all 0.3s;
        }
        button:hover { 
            background: linear-gradient(90deg, var(--primary-hover) 0%, var(--background-end) 100%); 
            transform: translateY(-2px); 
        }
        
        .error-msg { 
            color: var(--error-color); font-size: 0.8rem; margin-top: 2px; display: block; font-weight: 600; 
        }
        .alert-message { 
            padding: 12px; margin-bottom: 15px; border-radius: 8px; text-align: left; font-size: 0.95rem; 
            border: 1px solid; background-color: rgba(255, 255, 255, 0.8); 
        }
        .alert-danger { 
            color: var(--error-color); border-color: var(--error-color); 
            box-shadow: 0 1px 3px rgba(239, 68, 68, 0.3); 
        }
        .alert-success { 
            color: var(--success-color); border-color: var(--success-color); 
            box-shadow: 0 1px 3px rgba(16, 185, 129, 0.3); 
        }
        
        .link-text {
            margin-top: 20px;
            font-size: 0.95rem;
            color: var(--text-dark);
        }
        .link-text a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        .link-text a:hover {
            text-decoration: underline;
        }
        
        /* --- POPUP MESSAGE (Modal) STYLING --- */
        .modal {
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            width: 80%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.3s ease-out;
        }
        .modal-content p {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
        }
        .modal-content .close-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: auto;
            margin-top: 0;
        }
        .modal-content .close-btn:hover {
            background-color: var(--primary-hover);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    
    <div class="edu-icon icon-book">📚</div>
    <div class="edu-icon icon-pen">🖋️</div>
    <div class="edu-icon icon-cap">🎓</div>

    <div class="container">
        
        <?php if ($current_action == 'login'): ?>
            
            <h2>Student Login</h2>

            <?php if (!empty($login_err)) {
                echo '<div class="alert-message alert-danger">' . htmlspecialchars($login_err) . '</div>';
            } ?>

            <form action="index.php" method="POST">
                <div class="input-group full-width">
                    <label for="username">Student ID / Email</label>
                    <input type="text" id="username" name="username" required placeholder="e.g., student@edu.com" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="input-group full-width">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
                
                <div style="text-align: right; margin-bottom: 15px;">
                    <a href="forgot-password.php" style="font-size: 0.9rem; color: var(--primary-color); text-decoration: none;">       </a>
                </div>
                
                <button type="submit" name="login_submit">Start Learning</button>
            </form>
            
            <div class="link-text">
                New Student? <a href="?action=signup">Enroll Now</a>
            </div>

        <?php else: ?>

            <h2>Enroll Now</h2>
            
            <?php if (!empty($signup_message)) {
                $class = strpos($signup_message, 'SUCCESS') !== false ? 'alert-success' : 'alert-danger';
                echo "<div class='alert-message $class'>" . htmlspecialchars($signup_message) . "</div>";
            } ?>
            
            <form id="signupForm" action="index.php?action=signup" method="POST" onsubmit="return validateSignupForm(event)">
                
                <div class="form-grid">
                    <div class="input-group full-width">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" required placeholder="Your Full Name" value="<?php echo htmlspecialchars($fullname); ?>">
                        <span class="error-msg" id="fullname-error"></span>
                    </div>

                    <div class="input-group full-width">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="Used for login and communication" value="<?php echo htmlspecialchars($signup_email); ?>">
                        <span class="error-msg" id="email-error"></span>
                    </div>

                    <div class="input-group full-width">
                        <label for="password_1">Choose Password</label>
                        <input type="password" id="password_1" name="password_1" required placeholder="Min 6 characters">
                        <span class="error-msg" id="password-1-error"></span>
                    </div>
                    <div class="input-group full-width">
                        <label for="password_2">Confirm Password</label>
                        <input type="password" id="password_2" name="password_2" required placeholder="Repeat your password">
                        <span class="error-msg" id="password-2-error"></span>
                    </div>
                    
                    <div class="input-group">
                        <label for="stream">Stream</label>
                        <select id="stream" name="stream" required>
                            <option value="">Select Stream</option>
                            <option value="Science" <?php echo ($stream == 'Science' ? 'selected' : ''); ?>>Science</option>
                            <option value="Commerce" <?php echo ($stream == 'Commerce' ? 'selected' : ''); ?>>Commerce</option>
                            <option value="Arts" <?php echo ($stream == 'Arts' ? 'selected' : ''); ?>>Arts</option>
                            <option value="Engineering" <?php echo ($stream == 'Engineering' ? 'selected' : ''); ?>>Engineering</option>
                        </select>
                        <span class="error-msg" id="stream-error"></span>
                    </div>
                    <div class="input-group">
                        <label for="institute">Institute</label>
                        <input type="text" id="institute" name="institute" required placeholder="College/School Name" value="<?php echo htmlspecialchars($institute); ?>">
                        <span class="error-msg" id="institute-error"></span>
                    </div>

                    <div class="input-group">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" required placeholder="Your State" value="<?php echo htmlspecialchars($state); ?>">
                        <span class="error-msg" id="state-error"></span>
                    </div>
                    <div class="input-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" required placeholder="Your Country" value="<?php echo htmlspecialchars($country); ?>">
                        <span class="error-msg" id="country-error"></span>
                    </div>
                </div>
                
                <button type="submit" name="signup_submit">Submit Enrollment</button>
            </form>
            
            <div class="link-text">
                Already registered? <a href="index.php">Go to Login</a>
            </div>

        <?php endif; ?>

    </div>

    <div id="statusModal" class="modal">
        <div class="modal-content">
            <p id="modalMessage"></p>
            <button class="close-btn" onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        // Function to display the modal
        function showModal(message, type) {
            const modal = document.getElementById('statusModal');
            const modalMessage = document.getElementById('modalMessage');
            
            modalMessage.textContent = message;
            
            // Apply success/error styling
            modalMessage.className = type === 'success' ? 'alert-success' : 'alert-danger';
            
            // Display the modal
            modal.style.display = 'flex'; 
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('statusModal').style.display = 'none';
        }

        // Check if a PHP signup message exists and show the modal on page load
        <?php if (!empty($signup_message)): ?>
            document.addEventListener('DOMContentLoaded', () => {
                showModal("<?php echo htmlspecialchars($signup_message); ?>", '<?php echo strpos($signup_message, "SUCCESS") !== false ? "success" : "error"; ?>');
            });
        <?php endif; ?>

        // --- CLIENT-SIDE VALIDATION FUNCTION ---
        function validateSignupForm(event) {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');

            // 1. Full Name check
            const fullname = document.getElementById('fullname').value.trim();
            if (fullname.length < 3) {
                document.getElementById('fullname-error').textContent = 'Full Name is required (min 3 characters).';
                isValid = false;
            }

            // 2. Email check
            const email = document.getElementById('email').value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                document.getElementById('email-error').textContent = 'Please enter a valid email address.';
                isValid = false;
            }

            // 3. Password check (Password 1)
            const password_1 = document.getElementById('password_1').value;
            if (password_1.length < 6) {
                document.getElementById('password-1-error').textContent = 'Password must be at least 6 characters.';
                isValid = false;
            }

            // 4. Password confirmation check (Password 2)
            const password_2 = document.getElementById('password_2').value;
            if (password_1 !== password_2) {
                document.getElementById('password-2-error').textContent = 'Passwords do not match.';
                isValid = false;
            }
            
            // 5. Stream, Institute, State, Country check
            const stream = document.getElementById('stream').value;
            const institute = document.getElementById('institute').value.trim();
            const state = document.getElementById('state').value.trim();
            const country = document.getElementById('country').value.trim();
            
            if (stream === "") {
                document.getElementById('stream-error').textContent = 'Please select a stream.';
                isValid = false;
            }
            if (institute.length < 3) {
                document.getElementById('institute-error').textContent = 'Institute name is required.';
                isValid = false;
            }
            if (state.length < 2) {
                document.getElementById('state-error').textContent = 'State is required.';
                isValid = false;
            }
            if (country.length < 2) {
                document.getElementById('country-error').textContent = 'Country is required.';
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault(); 
                showModal("Validation failed. Please correct the errors below.", 'error');
            }

            return isValid;
        }
    </script>
</body>
</html>