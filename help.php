<?php
// PHP setup for consistency (no auth check needed)
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - Education Hub</title>
    <style>
        :root {
            --primary-color: #a855f7; 
            --primary-hover: #9333ea; 
            --background-start: #fef3f7; 
            --background-end: #e9d5ff; 
            --text-dark: #374151; 
            --glass-bg: rgba(255, 255, 255, 0.7); 
            --widget-bg: rgba(255, 255, 255, 0.9);
            --widget-border: rgba(255, 255, 255, 0.95);
            --php-color: #777BB4;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--background-start) 0%, var(--background-end) 100%);
            color: var(--text-dark);
            min-height: 100vh;
            margin: 0;
            padding-top: 80px;
        }

        /* --- Header/Navigation Bar (Copied from Dashboard) --- */
        .header {
            position: fixed; top: 0; left: 0; width: 100%; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); z-index: 20; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center;
        }
        .header-title { color: var(--php-color); font-size: 1.5rem; font-weight: 700; }
        .nav-links { display: flex; gap: 15px; }
        .nav-links a { text-decoration: none; color: var(--text-dark); padding: 5px 10px; border-radius: 5px; transition: background-color 0.3s; }
        .nav-links a:hover { background-color: rgba(168, 85, 247, 0.1); }
        .login-btn { background-color: var(--primary-color); color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s; }
        .login-btn:hover { background-color: var(--primary-hover); }

        /* --- Page Content Layout --- */
        .content-container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid var(--widget-border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .content-container h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px;
            font-size: 2rem;
        }
        
        /* --- Static Accordion Styling --- */
        .accordion-item {
            margin-bottom: 10px;
            border: 1px solid rgba(168, 85, 247, 0.3);
            border-radius: 8px;
            overflow: hidden;
            background-color: var(--widget-bg);
        }
        .accordion-header {
            padding: 15px 20px;
            background-color: rgba(168, 85, 247, 0.1);
            cursor: pointer;
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
        }
        .accordion-header:hover {
            background-color: rgba(168, 85, 247, 0.2);
        }
        .accordion-icon {
            font-size: 1.2rem;
            color: var(--primary-hover);
        }
        .accordion-content {
            padding: 15px 20px;
            border-top: 1px solid rgba(168, 85, 247, 0.3);
            font-size: 0.95rem;
            color: #4b5563;
        }
    </style>
</head>
<body>
    
    <div class="header">
        <div class="header-title"><a href="dashboard_enhanced_php.php" style="text-decoration: none; color: var(--php-color);">📚 PHP Learning Hub</a></div>
        <div class="nav-links">
            <a href="dashboard_enhanced_php.php">Dashboard</a>
            <a href="contact.php">Contact</a>
            <a href="feedback.php">Feedback</a>
        </div>
        <a href="index_full_signup.php" class="login-btn">Log In</a>
    </div>

    <div class="content-container">
        <h2>Help Center & FAQs</h2>
        
        <div class="accordion-item">
            <div class="accordion-header">
                <span>How do I access the PHP modules?</span>
                <span class="accordion-icon">▶</span>
            </div>
            <div class="accordion-content">
                To access the full **PHP Modules** and track your progress, you must first **Sign Up** and **Log In** to your account. Click the 'Log In / Sign Up' button in the header to get started.
            </div>
        </div>

        <div class="accordion-item">
            <div class="accordion-header">
                <span>What are Superglobals and how do they work?</span>
                <span class="accordion-icon">▶</span>
            </div>
            <div class="accordion-content">
                **Superglobals** are built-in variables in PHP that are always available in all scopes. Examples include `\$_GET`, `\$_POST`, `\$_SESSION`, and `\$_SERVER`. They are essential for handling form data and session management.
            </div>
        </div>
        
        <div class="accordion-item">
            <div class="accordion-header">
                <span>I forgot my password. How can I reset it?</span>
                <span class="accordion-icon">▶</span>
            </div>
            <div class="accordion-content">
                Since we haven't set up the database yet, this feature is currently non-functional. In the final version, you would click the 'Forgot Password' link on the login page.
            </div>
        </div>
        
        <div class="accordion-item">
            <div class="accordion-header">
                <span>What development tools are recommended for PHP?</span>
                <span class="accordion-icon">▶</span>
            </div>
            <div class="accordion-content">
                We recommend using a local server environment like **XAMPP** or **WAMP**, and an IDE like **VS Code** with the official PHP extensions.
            </div>
        </div>

        <p style="text-align: center; margin-top: 30px; font-size: 0.9rem;">Still need assistance? Please visit the <a href="contact.php" style="color: var(--primary-color); font-weight: 600;">Contact Page</a>.</p>
        
    </div>
    
</body>
</html>