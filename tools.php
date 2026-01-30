<?php
// --- START: PHP LOGIC AND VARIABLE SETUP ---
session_start();

// DEBUG: Uncomment the next 3 lines to see what's in your session
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
 
// Define user name - Check ALL possible session variables
$user_fullname = "Guest Learner"; // Default value

// Check all possible session variable names
if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
    $user_fullname = $_SESSION['name'];
} elseif (isset($_SESSION['full_name']) && !empty($_SESSION['full_name'])) {
    $user_fullname = $_SESSION['full_name'];
} elseif (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
    $user_fullname = $_SESSION['user_name'];
} elseif (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    $user_fullname = $_SESSION['username'];
} elseif (isset($_SESSION['user_fullname']) && !empty($_SESSION['user_fullname'])) {
    $user_fullname = $_SESSION['user_fullname'];
}

// You can add more PHP logic here as needed

// --- END: PHP LOGIC ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Learning Tools - Interactive Games</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary: #a855f7;
            --primary-dark: #9333ea;
            --secondary: #ec4899;
            --accent: #f59e0b;
        }
        
        body {
            background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Floating animated objects */
        .float-object {
            position: fixed;
            font-size: 60px;
            opacity: 0.15;
            z-index: 0;
            animation: float 20s infinite ease-in-out;
            filter: blur(1px);
        }
        
        .float-object:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .float-object:nth-child(2) {
            top: 60%;
            right: 15%;
            animation-delay: 3s;
            animation-duration: 25s;
        }
        
        .float-object:nth-child(3) {
            bottom: 15%;
            left: 20%;
            animation-delay: 6s;
            animation-duration: 18s;
        }
        
        .float-object:nth-child(4) {
            top: 40%;
            right: 5%;
            animation-delay: 2s;
            animation-duration: 22s;
        }
        
        .float-object:nth-child(5) {
            top: 80%;
            left: 60%;
            animation-delay: 4s;
            animation-duration: 19s;
        }
        
        .float-object:nth-child(6) {
            top: 25%;
            left: 45%;
            animation-delay: 5s;
            animation-duration: 24s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            25% {
                transform: translate(30px, -30px) rotate(5deg) scale(1.1);
            }
            50% {
                transform: translate(-20px, 20px) rotate(-5deg) scale(0.9);
            }
            75% {
                transform: translate(40px, 10px) rotate(3deg) scale(1.05);
            }
        }
        
        /* Header styles */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Game card hover effects */
        .game-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .game-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(168, 85, 247, 0.1),
                transparent
            );
            transform: rotate(45deg);
            transition: all 0.6s;
        }
        
        .game-card:hover::before {
            left: 100%;
        }
        
        .game-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(168, 85, 247, 0.3);
            border-color: var(--primary);
        }
        
        /* Scored game card - special styling */
        .scored-game {
            border: 2px solid rgba(245, 158, 11, 0.5);
        }
        
        .scored-game:hover {
            border-color: var(--accent);
            box-shadow: 0 20px 40px rgba(245, 158, 11, 0.3);
        }
        
        /* Icon animation */
        .game-icon {
            transition: all 0.4s ease;
            display: inline-block;
        }
        
        .game-card:hover .game-icon {
            transform: scale(1.2) rotate(10deg);
        }
        
        /* Difficulty badges */
        .difficulty-easy {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .difficulty-medium {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .difficulty-hard {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        /* Pulse animation for badges */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--secondary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Navigation hover effect */
        .nav-link {
            position: relative;
            transition: all 0.3s;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: all 0.3s;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }
        
        /* Sparkle effect */
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }
        
        .sparkle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: sparkle 1.5s infinite;
        }
        
        .sparkle:nth-child(1) { top: 20%; right: 20%; animation-delay: 0s; }
        .sparkle:nth-child(2) { top: 60%; right: 10%; animation-delay: 0.5s; }
        .sparkle:nth-child(3) { top: 40%; right: 30%; animation-delay: 1s; }
        
        /* Category section styling */
        .category-header {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(236, 72, 153, 0.1));
            border-left: 4px solid var(--primary);
            backdrop-filter: blur(10px);
        }
        
        .scored-category {
            border-left-color: var(--accent);
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.1));
        }
    </style>
</head>
<body class="font-sans">
    
    <!-- Floating Background Objects -->
    <div class="float-object">🎮</div>
    <div class="float-object">💻</div>
    <div class="float-object">🎯</div>
    <div class="float-object">⚡</div>
    <div class="float-object">🏆</div>
    <div class="float-object">🎲</div>
    
    <!-- Header Navigation -->
    <header class="header fixed top-0 left-0 right-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="text-3xl">🏠</span>
            <h1 class="text-2xl font-bold gradient-text">SmartlyGoPHP</h1>
            </div>
            
            <nav class="hidden md:flex space-x-6 items-center">
                <a href="dashboard.php" class="nav-link text-gray-700 hover:text-purple-600 font-semibold">🏠 Home</a>
                <a href="tools.php" class="nav-link active text-purple-600 font-semibold">🛠️ Tools</a>
                <a href="modules.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">📚 Modules</a>
                <a href="feedback.php" class="nav-link text-gray-700 hover:text-purple-600 font-semibold">💬 Feedback</a>
                <a href="contact.php" class="nav-link text-gray-700 hover:text-purple-600 font-semibold">📧 Contact</a>

                <?php if (!empty($_SESSION['user_id']) || !empty($_SESSION['name']) || !empty($_SESSION['full_name'])): ?>
                    <a href="logout.php" 
                       class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all font-semibold" 
                       onclick="return confirm('Are you sure you want to logout?')">🚪 Logout</a>
                <?php endif; ?>
            </nav>
            
            <!-- Mobile Menu Button -->
            <button class="md:hidden text-3xl text-purple-600" onclick="toggleMobileMenu()">☰</button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
            <a href="dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">🏠 Home</a>
            <a href="tools.php" class="block px-4 py-2 bg-purple-100 rounded-lg">🛠️ Tools</a>
            <a href="feedback.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">💬 Feedback</a>
            <a href="contact.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">📧 Contact</a>
            
            <?php if (!empty($_SESSION['user_id']) || !empty($_SESSION['name']) || !empty($_SESSION['full_name'])): ?>
                <a href="logout.php" 
                   class="block px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition" 
                   onclick="return confirm('Are you sure you want to logout?')">🚪 Logout</a>
            <?php endif; ?>
        </div>
    </header>

    
    <!-- Main Content -->
    <main class="pt-28 pb-12 px-4 relative z-10">
        <div class="max-w-7xl mx-auto">
            
            <!-- Page Title -->
            <div class="text-center mb-12">
                <h2 class="text-5xl font-extrabold mb-4 gradient-text">Choose Your Challenge! 🎯</h2>
                <p class="text-gray-700 text-lg">Level up your PHP skills with interactive games and challenges</p>
                <p class="text-gray-600 mt-2">Welcome back, <strong><?php echo htmlspecialchars($user_fullname); ?></strong>! 👋</p>
            </div>
            
            <!-- SCORED GAMES SECTION -->
            <div class="mb-16">
                <div class="category-header scored-category rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-800 mb-2">🏆 Scored Challenges</h3>
                            <p class="text-gray-600">Complete these games to earn scores and track your progress on the dashboard!</p>
                        </div>
                        <span class="text-6xl">📊</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <!-- Fill in the Blanks -->
                    <div class="game-card scored-game rounded-2xl p-6 cursor-pointer group" onclick="window.location.href='fill-blanks.php'">
                        <div class="relative">
                            <span class="absolute top-0 right-0 bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-full">📊 Scored</span>
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                        </div>
                        <div class="flex items-center justify-between mb-4 mt-6">
                            <span class="game-icon text-6xl">📝</span>
                            <span class="difficulty-easy text-white text-xs font-bold px-3 py-1 rounded-full">Easy</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Fill in the Blanks</h3>
                        <p class="text-gray-600 mb-4">Complete PHP code snippets by filling in the missing parts. Your score will be saved!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-amber-600 font-semibold">✨ Saves to Dashboard</span>
                            <span class="text-purple-600 font-semibold group-hover:translate-x-2 transition-transform inline-block">Play Now →</span>
                        </div>
                    </div>

                    <!-- Quiz Challenge -->
                    <div class="game-card scored-game rounded-2xl p-6 cursor-pointer group" onclick="window.location.href='quiz.php'">
                        <div class="relative">
                            <span class="absolute top-0 right-0 bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-full">📊 Scored</span>
                        </div>
                        <div class="flex items-center justify-between mb-4 mt-6">
                            <span class="game-icon text-6xl">📚</span>
                            <span class="difficulty-medium text-white text-xs font-bold px-3 py-1 rounded-full">Medium</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Quiz Challenge</h3>
                        <p class="text-gray-600 mb-4">Multiple-choice questions covering all PHP topics. Your score will be tracked!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-amber-600 font-semibold">✨ Saves to Dashboard</span>
                            <span class="text-purple-600 font-semibold group-hover:translate-x-2 transition-transform inline-block">Play Now →</span>
                        </div>
                    </div>

                    <!-- True or False -->
                    <div class="game-card scored-game rounded-2xl p-6 cursor-pointer group" onclick="window.location.href='true-false.php'">
                        <div class="relative">
                            <span class="absolute top-0 right-0 bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-full">📊 Scored</span>
                        </div>
                        <div class="flex items-center justify-between mb-4 mt-6">
                            <span class="game-icon text-6xl">✅</span>
                            <span class="difficulty-easy text-white text-xs font-bold px-3 py-1 rounded-full">Easy</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">True or False</h3>
                        <p class="text-gray-600 mb-4">Quick true/false statements about PHP. Your results will be recorded!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-amber-600 font-semibold">✨ Saves to Dashboard</span>
                            <span class="text-purple-600 font-semibold group-hover:translate-x-2 transition-transform inline-block">Play Now →</span>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- PRACTICE GAMES SECTION -->
            <div class="mb-12">
                <div class="category-header rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-800 mb-2">🎮 Practice Games</h3>
                            <p class="text-gray-600">Sharpen your skills with these fun practice challenges - play anytime, no pressure!</p>
                        </div>
                        <span class="text-6xl">🎯</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                    <!-- Find the Error -->
                    <div class="game-card rounded-2xl p-6 cursor-pointer group" onclick="window.location.href='find-error.php'">
                        <div class="relative">
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full pulse">🔥 Popular</span>
                        </div>
                        <div class="flex items-center justify-between mb-4 mt-6">
                            <span class="game-icon text-6xl">🔍</span>
                            <span class="difficulty-medium text-white text-xs font-bold px-3 py-1 rounded-full">Medium</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Find the Error</h3>
                        <p class="text-gray-600 mb-4">Spot syntax and logical errors in PHP code. Sharpen your debugging skills!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Practice Mode</span>
                            <span class="text-purple-600 font-semibold group-hover:translate-x-2 transition-transform inline-block">Play Now →</span>
                        </div>
                    </div>
                    
                    <!-- Fix the Code -->
                    <div class="game-card rounded-2xl p-6 cursor-pointer group" onclick="window.location.href='fix-code.php'">
                        <div class="flex items-center justify-between mb-4">
                            <span class="game-icon text-6xl">🔧</span>
                            <span class="difficulty-hard text-white text-xs font-bold px-3 py-1 rounded-full">Hard</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Fix the Code</h3>
                        <p class="text-gray-600 mb-4">Repair broken PHP scripts and make them work. Advanced debugging challenge!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Practice Mode</span>
                            <span class="text-purple-600 font-semibold group-hover:translate-x-2 transition-transform inline-block">Play Now →</span>
                        </div>
                    </div>
                    
                    <!-- Flashcards -->
                    <div class="game-card rounded-2xl p-6 cursor-pointer group" onclick="window.location.href='flashcards.php'">
                        <div class="relative">
                            <span class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full pulse">✨ New</span>
                        </div>
                        <div class="flex items-center justify-between mb-4 mt-6">
                            <span class="game-icon text-6xl">🎴</span>
                            <span class="difficulty-easy text-white text-xs font-bold px-3 py-1 rounded-full">Easy</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Flashcards</h3>
                        <p class="text-gray-600 mb-4">Quick-fire PHP concept review. Flip cards to test your knowledge!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Practice Mode</span>
                            <span class="text-purple-600 font-semibold group-hover:translate-x-2 transition-transform inline-block">Play Now →</span>
                        </div>
                    </div>
                    
                    <!-- Predict Output -->
                    <div class="game-card rounded-2xl p-6 cursor-pointer group" onclick="window.location.href='predict-output.php'">
                        <div class="flex items-center justify-between mb-4">
                            <span class="game-icon text-6xl">🎯</span>
                            <span class="difficulty-medium text-white text-xs font-bold px-3 py-1 rounded-full">Medium</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Predict Output</h3>
                        <p class="text-gray-600 mb-4">Guess what the code will output. Master PHP logic and behavior!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Practice Mode</span>
                            <span class="text-purple-600 font-semibold group-hover:translate-x-2 transition-transform inline-block">Play Now →</span>
                        </div>
                    </div>
                    
                    <!-- Treasure Hunt -->
                    <div class="game-card rounded-2xl p-6 cursor-pointer group" onclick="window.location.href='treasure-hunt.php'">
                        <div class="relative">
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="game-icon text-6xl">🗺️</span>
                            <span class="difficulty-hard text-white text-xs font-bold px-3 py-1 rounded-full">Hard</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Treasure Hunt</h3>
                        <p class="text-gray-600 mb-4">Follow clues and solve PHP puzzles to find the treasure. Epic adventure!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Practice Mode</span>
                            <span class="text-purple-600 font-semibold group-hover:translate-x-2 transition-transform inline-block">Play Now →</span>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- Stats Section -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white bg-opacity-80 backdrop-blur-lg rounded-2xl p-6 text-center border-2 border-purple-200">
                    <div class="text-5xl mb-2">🎮</div>
                    <div class="text-3xl font-bold text-purple-600">8</div>
                    <div class="text-gray-600 font-semibold">Game Modes</div>
                </div>
                <div class="bg-white bg-opacity-80 backdrop-blur-lg rounded-2xl p-6 text-center border-2 border-amber-200">
                    <div class="text-5xl mb-2">🏆</div>
                    <div class="text-3xl font-bold text-amber-600">3</div>
                    <div class="text-gray-600 font-semibold">Scored Challenges</div>
                </div>
                <div class="bg-white bg-opacity-80 backdrop-blur-lg rounded-2xl p-6 text-center border-2 border-pink-200">
                    <div class="text-5xl mb-2">⭐</div>
                    <div class="text-3xl font-bold text-pink-600">1000+</div>
                    <div class="text-gray-600 font-semibold">Active Learners</div>
                </div>
            </div>
            
        </div>
    </main>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
        
        // Add ripple effect on card click
        document.querySelectorAll('.game-card').forEach(card => {
            card.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(168, 85, 247, 0.5)';
                ripple.style.width = ripple.style.height = '100px';
                ripple.style.left = e.clientX - this.offsetLeft - 50 + 'px';
                ripple.style.top = e.clientY - this.offsetTop - 50 + 'px';
                ripple.style.animation = 'ripple 0.6s ease-out';
                ripple.style.pointerEvents = 'none';
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
        
        // Ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                from {
                    transform: scale(0);
                    opacity: 1;
                }
                to {
                    transform: scale(3);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    
</body>
</html>