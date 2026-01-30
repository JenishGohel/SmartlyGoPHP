<?php
// PHP setup for consistency
session_start();
// Define user name as a placeholder
$user_fullname = "Guest Learner"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive PHP Learning Modules (Gold Focus)</title>
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
            --php-light: #a6aed4;
            /* --- UPDATED COLORS (Gold/Yellow) --- */
            --highlight-color: #f59e0b; /* Deep Gold/Amber */
            --shadow-color: rgba(245, 158, 11, 0.5); /* Semi-transparent Gold for shadow */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--background-start) 0%, var(--background-end) 100%);
            color: var(--text-dark);
            min-height: 100vh;
            margin: 0;
            padding-top: 80px;
        }

        /* --- Header/Navigation Bar (Consistent Styling) --- */
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
            max-width: 1200px;
            margin: 40px auto 60px auto; 
            padding: 20px;
            text-align: center;
        }
        .content-container h2 {
            color: var(--primary-hover);
            margin-bottom: 5px;
            font-size: 2.5rem;
        }
        .intro-text {
            margin-bottom: 40px; 
            font-size: 1.1rem;
            color: #6b7280;
        }

        /* --- FLIP CARD GRID STYLES --- */
        .flip-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px; 
            perspective: 1000px; 
            margin: 0; 
        }
        
        .flip-card {
            background-color: transparent;
            height: 250px; 
            cursor: pointer;
            position: relative; 
            transform: translateZ(0); 
            transition: transform 0.3s ease-out, box-shadow 0.3s; 
            border-radius: 15px;
            overflow: hidden; 
        }
        
        /* Border Animation Pseudo-Element */
        .flip-card::before {
            content: '';
            position: absolute;
            top: 50%; 
            left: 50%; 
            width: 0; 
            height: 0; 
            background-color: var(--shadow-color); 
            border: 3px solid var(--highlight-color);
            border-radius: 15px;
            z-index: 10;
            transform: translate(-50%, -50%); 
            transition: width 0.4s ease-out, height 0.4s ease-out, box-shadow 0.4s ease-out; 
            pointer-events: none; 
        }
        
        /* Effect on Click: Enlargement and Border Animation */
        .flip-card.flipped {
            transform: scale(1.05); 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); 
        }
        
        /* Expand the pseudo-element to cover the entire box on flip */
        .flip-card.flipped::before {
            width: 100%; 
            height: 100%; 
            box-shadow: 0 0 15px var(--highlight-color);
        }

        /* The container for the flipping effect */
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
            border-radius: 15px;
            z-index: 20; 
        }

        /* Flip only when the 'flipped' class is active */
        .flip-card.flipped .flip-card-inner {
            transform: rotateY(180deg);
        }

        /* Position the front and back side */
        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 1px solid var(--widget-border);
            box-sizing: border-box; 
        }

        /* Style the front side */
        .flip-card-front {
            background: linear-gradient(145deg, var(--widget-bg), var(--glass-bg));
            color: var(--php-color);
        }
        .flip-card-front h3 {
            font-size: 1.5rem;
            margin: 10px 0;
        }
        .flip-card-front .icon {
            font-size: 3.5rem;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        /* Style the back side */
        .flip-card-back {
            background: linear-gradient(145deg, var(--php-color), var(--php-light));
            color: white;
            transform: rotateY(180deg);
            text-align: left;
        }
        .flip-card-back h4 {
            margin-top: 0;
            font-size: 1.2rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.5);
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .flip-card-back p {
            font-size: 0.95rem;
            line-height: 1.4;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 600px) {
            .flip-card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <script>
        function toggleFlip(clickedCard) {
            const allCards = document.querySelectorAll('.flip-card');
            const isFlipped = clickedCard.classList.contains('flipped');
            
            // 1. Close all other cards
            allCards.forEach(card => {
                if (card !== clickedCard && card.classList.contains('flipped')) {
                    card.classList.remove('flipped');
                }
            });

            // 2. Toggle the class on the clicked card
            if (isFlipped) {
                // If it was already flipped, unflipping it
                clickedCard.classList.remove('flipped');
            } else {
                // If it was not flipped, flip it
                clickedCard.classList.add('flipped');
            }
        }
    </script>
</head>
<body>
    
    <div class="header">
        <div class="header-title"><a href="dashboard_enhanced_php.php" style="text-decoration: none; color: var(--php-color);">📚 PHP Learning Hub</a></div>
        <div class="nav-links">
            <a href="dashboard_enhanced_php.php">Dashboard</a>
            <a href="help.php">Help</a>
            <a href="contact.php">Contact</a>
            <a href="feedback.php">Feedback</a>
        </div>
        <a href="index_full_signup.php" class="login-btn">Log In</a>
    </div>

    <div class="content-container">
        <h2>Interactive PHP Concepts</h2>
        <p class="intro-text">Click on any card to reveal the definition and details. **Only one card can be viewed at a time** for better focus.</p>
        
        <div class="flip-card-grid">
            
            <div class="flip-card" onclick="toggleFlip(this)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <span class="icon">🐘</span>
                        <h3>What is PHP?</h3>
                        <p style="font-size: 0.85rem; color: var(--text-dark);">Click to reveal the definition.</p>
                    </div>
                    <div class="flip-card-back">
                        <h4>Hypertext Preprocessor</h4>
                        <p>PHP is a popular, open-source **server-side scripting language** designed for web development. It is embedded within HTML and is used to manage dynamic content, databases, and session tracking.</p>
                    </div>
                </div>
            </div>

            <div class="flip-card" onclick="toggleFlip(this)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <span class="icon">🏷️</span>
                        <h3>Variables</h3>
                        <p style="font-size: 0.85rem; color: var(--text-dark);">Click to reveal the syntax and usage.</p>
                    </div>
                    <div class="flip-card-back">
                        <h4>Defining Data</h4>
                        <p>In PHP, variables start with the **`$` sign**, followed by the variable name (e.g., `\$name`). PHP is **loosely typed**, meaning you don't declare the data type explicitly.</p>
                    </div>
                </div>
            </div>

            <div class="flip-card" onclick="toggleFlip(this)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <span class="icon">🚦</span>
                        <h3>Control Structures</h3>
                        <p style="font-size: 0.85rem; color: var(--text-dark);">Click to understand conditional logic.</p>
                    </div>
                    <div class="flip-card-back">
                        <h4>Conditional Logic</h4>
                        <p>These structures (like `if`, `else`, `elseif`, `switch`) allow you to execute different blocks of code based on conditions being true or false. They control the **flow of execution**.</p>
                    </div>
                </div>
            </div>

            <div class="flip-card" onclick="toggleFlip(this)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <span class="icon">🔁</span>
                        <h3>Loops</h3>
                        <p style="font-size: 0.85rem; color: var(--text-dark);">Click to see common loop types.</p>
                    </div>
                    <div class="flip-card-back">
                        <h4>Iteration</h4>
                        <p>Loops (`for`, `while`, `do-while`, `foreach`) are used to execute a block of code repeatedly. The `foreach` loop is most commonly used for iterating over **arrays and objects**.</p>
                    </div>
                </div>
            </div>
            
            <div class="flip-card" onclick="toggleFlip(this)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <span class="icon">📋</span>
                        <h3>Arrays</h3>
                        <p style="font-size: 0.85rem; color: var(--text-dark);">Click to learn about data collections.</p>
                    </div>
                    <div class="flip-card-back">
                        <h4>Indexed, Associative, Multidimensional</h4>
                        <p>An array is a special variable that can hold more than one value at a time. PHP supports three types: **Indexed** (numeric keys), **Associative** (named keys), and **Multidimensional**.</p>
                    </div>
                </div>
            </div>

            <div class="flip-card" onclick="toggleFlip(this)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <span class="icon">⚙️</span>
                        <h3>Functions</h3>
                        <p style="font-size: 0.85rem; color: var(--text-dark);">Click to understand reusable code blocks.</p>
                    </div>
                    <div class="flip-card-back">
                        <h4>Reusability and Scope</h4>
                        <p>A function is a block of statements that can be used repeatedly in a program. They help organize code, improve reusability, and can accept **arguments** and return **values**.</p>
                    </div>
                </div>
            </div>
            
            <div class="flip-card" onclick="toggleFlip(this)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <span class="icon">🌍</span>
                        <h3>Superglobals</h3>
                        <p style="font-size: 0.85rem; color: var(--text-dark);">Click to see global variables.</p>
                    </div>
                    <div class="flip-card-back">
                        <h4>Accessing Environment Data</h4>
                        <p>These are built-in variables that are always available in all scopes. Examples include `\$_GET`, `\$_POST` (for form data), and `\$_SESSION` (for state management).</p>
                    </div>
                </div>
            </div>
            
            <div class="flip-card" onclick="toggleFlip(this)">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <span class="icon">🧱</span>
                        <h3>OOP Basics</h3>
                        <p style="font-size: 0.85rem; color: var(--text-dark);">Click to learn about Object-Oriented Programming.</p>
                    </div>
                    <div class="flip-card-back">
                        <h4>Classes, Objects, Methods</h4>
                        <p>PHP supports OOP, allowing developers to create **classes** (blueprints) and **objects** (instances). Key concepts are encapsulation, inheritance, and polymorphism.</p>
                    </div>
                </div>
            </div>

        </div>
        
        <p style="margin-top: 50px; color: var(--php-color); font-weight: 600;">Ready to test your knowledge? Return to the Dashboard for practice quizzes!</p>
        
    </div>
    
</body>
</html>