<?php
session_start();
require_once 'db.php';

// Get user info from session or set as guest
$user_fullname = isset($_SESSION['name']) ? $_SESSION['name'] : "Guest Learner";
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Fetch user data if logged in
$user_score = 0;
$user_level = 1;
$games_played = 0;
$current_streak = 0;

if ($user_id > 0) {
    // Get user basic info
    $sql = "SELECT name, email FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $user_fullname = $user_data['name'];
    }
    
    // Calculate user score from game progress
    $score_sql = "SELECT COUNT(*) as games_played, SUM(points_earned) as total_score 
                  FROM user_game_progress WHERE user_id = ?";
    $score_stmt = $conn->prepare($score_sql);
    if ($score_stmt) {
        $score_stmt->bind_param("i", $user_id);
        $score_stmt->execute();
        $score_result = $score_stmt->get_result();
        
        if ($score_result && $score_result->num_rows > 0) {
            $score_data = $score_result->fetch_assoc();
            $games_played = $score_data['games_played'] ?? 0;
            $user_score = $score_data['total_score'] ?? 0;
            
            // Calculate level: Level 1 = 0-99 pts, Level 2 = 100-199 pts, etc.
            $user_level = floor($user_score / 100) + 1;
        }
        $score_stmt->close();
    }
    
    // Get current streak
    $streak_sql = "SELECT current_streak FROM user_streaks WHERE user_id = ?";
    $streak_stmt = $conn->prepare($streak_sql);
    if ($streak_stmt) {
        $streak_stmt->bind_param("i", $user_id);
        $streak_stmt->execute();
        $streak_result = $streak_stmt->get_result();
        
        if ($streak_result && $streak_result->num_rows > 0) {
            $streak_data = $streak_result->fetch_assoc();
            $current_streak = $streak_data['current_streak'] ?? 0;
        }
        $streak_stmt->close();
    }
}

// Educational quotes array
$quotes = [
    '"The beautiful thing about learning is that no one can take it away from you." – B.B. King',
    '"Education is the most powerful weapon which you can use to change the world." – Nelson Mandela',
    '"The mind is not a vessel to be filled, but a fire to be kindled." – Plutarch',
    '"Learning is not attained by chance, it must be sought for with ardor and attended to with diligence." – Abigail Adams',
    '"The more that you read, the more things you will know. The more that you learn, the more places you\'ll go." – Dr. Seuss',
    '"Live as if you were to die tomorrow. Learn as if you were to live forever." – Mahatma Gandhi'
];
$current_quote = $quotes[array_rand($quotes)];

// Calculate progress to next level
$current_level_min = ($user_level - 1) * 100;
$next_level_min = $user_level * 100;
$progress_in_level = $user_score - $current_level_min;
$progress_percentage = round(($progress_in_level / 100) * 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PHP LEARNING HUB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%);
            min-height: 100vh;
            overflow-x: hidden;
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
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #a855f7, #ec4899, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .widget-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .widget-card::before {
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
        
        .widget-card:hover::before {
            left: 100%;
        }
        
        .widget-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(168, 85, 247, 0.3);
            border-color: #a855f7;
        }
        
        .stat-card {
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(168, 85, 247, 0.2);
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slide {
            animation: slideIn 0.6s ease-out;
        }
        
        .quote-card {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(236, 72, 153, 0.1));
            border-left: 4px solid #a855f7;
        }
        
        .progress-bar {
            height: 12px;
            background: #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #a855f7, #ec4899);
            transition: width 1s ease;
            border-radius: 6px;
        }

        .login-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .8;
            }
        }
    </style>
</head>
<body class="font-sans">
    
    <!-- Floating Background Objects -->
    <div class="float-object">💻</div>
    <div class="float-object">🎮</div>
    <div class="float-object">🎯</div>
    <div class="float-object">📚</div>
    
    <!-- Header Navigation -->
<header class="header fixed top-0 left-0 right-0 z-50 px-6 py-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <span class="text-3xl">🏠</span>
            <h1 class="text-2xl font-bold gradient-text">SmartlyGoPHP</h1>
        </div>
        
        <nav class="hidden md:flex space-x-6 items-center">
            <a href="dashboard.php" class="text-purple-600 font-semibold">🏠 Home</a>
            <a href="tools.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">🛠️ Tools</a>
            <a href="modules.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">📚 Modules</a>
            <a href="feedback.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">💬 Feedback</a>
            <a href="contact.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">📧 Contact</a>
            
            <?php if ($user_id > 0): ?>
                <a href="logout.php" 
                   class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all font-semibold" 
                   onclick="return confirm('Are you sure you want to logout?')">
                   🚪 Logout
                </a>
            <?php else: ?>
                <a href="index.php" 
                   class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all font-semibold login-pulse shadow-lg">
                   🔐 Login
                </a>
            <?php endif; ?>
        </nav>
        
        <!-- Mobile Menu Button -->
        <button class="md:hidden text-3xl text-purple-600" onclick="toggleMobileMenu()">☰</button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
        <a href="dashboard.php" class="block px-4 py-2 bg-purple-100 rounded-lg">🏠 Home</a>
        <a href="tools.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">🛠️ Tools</a>
        <a href="modules.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">📚 Modules</a>
        <a href="feedback.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">💬 Feedback</a>
        <a href="contact.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">📧 Contact</a>
        
        <?php if ($user_id > 0): ?>
            <a href="logout.php" 
               class="block px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition" 
               onclick="return confirm('Are you sure you want to logout?')">
               🚪 Logout
            </a>
        <?php else: ?>
            <a href="index.php" 
               class="block px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition">
               🔐 Login
            </a>
        <?php endif; ?>
    </div>
</header>

    
    <!-- Main Content -->
    <main class="pt-28 pb-12 px-4 relative z-10">
        <div class="max-w-7xl mx-auto">
            
            <!-- Guest Alert Banner (Only shown when not logged in) -->
            <?php if ($user_id == 0): ?>
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-2xl p-6 mb-8 shadow-2xl animate-slide">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="text-5xl mr-4">🔐</div>
                        <div>
                            <h3 class="text-2xl font-bold mb-1">Login to Track Your Progress!</h3>
                            <p class="text-purple-100">Sign in to save your scores, earn achievements, and track your learning journey.</p>
                        </div>
                    </div>
                    <a href="index.php" 
                       class="bg-white text-purple-600 px-8 py-3 rounded-lg font-bold hover:bg-purple-50 transition-all shadow-lg transform hover:scale-105">
                        Login Now
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Welcome Card -->
            <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-8 animate-slide">
                <h2 class="text-4xl font-extrabold mb-4 gradient-text">
                    Hello, <?php echo htmlspecialchars($user_fullname); ?>! 👋
                </h2>
                <p class="text-gray-700 text-lg mb-6">
                    Welcome back to your PHP learning journey! Master the essentials of backend web development.
                </p>
                
                <!-- Level Progress (Only for logged-in users) -->
                <?php if ($user_id > 0): ?>
                <div class="bg-purple-50 rounded-xl p-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-purple-800">Level <?php echo $user_level; ?></span>
                        <span class="text-sm text-purple-600"><?php echo $progress_in_level; ?>/100 XP</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $progress_percentage; ?>%"></div>
                    </div>
                    <p class="text-xs text-purple-600 mt-2">
                        <?php echo (100 - $progress_in_level); ?> XP needed to reach Level <?php echo ($user_level + 1); ?>
                    </p>
                </div>
                <?php else: ?>
                <!-- Guest Message -->
                <div class="bg-amber-50 border-l-4 border-amber-500 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <div class="text-3xl mr-3">⚠️</div>
                        <p class="text-amber-800 font-medium">
                            You're browsing as a guest. <a href="login.php" class="underline font-bold hover:text-amber-600">Login</a> to unlock all features and track your progress!
                        </p>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Quote Box -->
                <div class="quote-card p-6 rounded-xl">
                    <div class="text-3xl mb-3">💭</div>
                    <p class="text-gray-800 text-lg italic font-medium">
                        <?php echo $current_quote; ?>
                    </p>
                </div>
            </div>
            
            <!-- User Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-white bg-opacity-90 backdrop-blur-lg rounded-xl p-6 text-center shadow-lg">
                    <div class="text-5xl mb-3">🏆</div>
                    <div class="text-3xl font-bold text-purple-600"><?php echo $user_score; ?></div>
                    <div class="text-gray-600 font-semibold">Total Score</div>
                    <?php if ($user_id == 0): ?>
                        <div class="text-xs text-gray-500 mt-1">Login to track</div>
                    <?php endif; ?>
                </div>
                
                <div class="stat-card bg-white bg-opacity-90 backdrop-blur-lg rounded-xl p-6 text-center shadow-lg">
                    <div class="text-5xl mb-3">⭐</div>
                    <div class="text-3xl font-bold text-orange-600"><?php echo $user_level; ?></div>
                    <div class="text-gray-600 font-semibold">Current Level</div>
                    <?php if ($user_id == 0): ?>
                        <div class="text-xs text-gray-500 mt-1">Login to track</div>
                    <?php endif; ?>
                </div>
                
                <div class="stat-card bg-white bg-opacity-90 backdrop-blur-lg rounded-xl p-6 text-center shadow-lg">
                    <div class="text-5xl mb-3">🎮</div>
                    <div class="text-3xl font-bold text-pink-600"><?php echo $games_played; ?></div>
                    <div class="text-gray-600 font-semibold">Games Played</div>
                    <?php if ($user_id == 0): ?>
                        <div class="text-xs text-gray-500 mt-1">Login to track</div>
                    <?php endif; ?>
                </div>
                
                <div class="stat-card bg-white bg-opacity-90 backdrop-blur-lg rounded-xl p-6 text-center shadow-lg">
                    <div class="text-5xl mb-3">🔥</div>
                    <div class="text-3xl font-bold text-red-600"><?php echo $current_streak; ?></div>
                    <div class="text-gray-600 font-semibold">Day Streak</div>
                    <?php if ($user_id == 0): ?>
                        <div class="text-xs text-gray-500 mt-1">Login to track</div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Tools Card -->
                    <a href="tools.php" class="widget-card rounded-2xl p-8 cursor-pointer">
                        <div class="flex items-center">
                            <div class="text-6xl mr-6">🛠️</div>
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800 mb-2">Learning Tools</h4>
                                <p class="text-gray-600">Play interactive PHP games and challenges to level up your skills!</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Modules Card -->
                    <a href="modules.php" class="widget-card rounded-2xl p-8 cursor-pointer">
                        <div class="flex items-center">
                            <div class="text-6xl mr-6">📚</div>
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800 mb-2">PHP Modules</h4>
                                <p class="text-gray-600">Learn PHP basics, functions, arrays, and advanced concepts step by step.</p>
                            </div>
                        </div>
                    </a>
                    
                </div>
            </div>
            
            <!-- Motivational Quotes Section -->
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl p-8 text-white shadow-2xl mb-8">
                <h3 class="text-2xl font-bold mb-6 text-center">Learning Inspiration ✨</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                        <div class="text-3xl mb-3">🎯</div>
                        <p class="text-lg italic">"The expert in anything was once a beginner."</p>
                        <p class="text-sm mt-2 opacity-80">– Helen Hayes</p>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-xl p-6">
                        <div class="text-3xl mb-3">🚀</div>
                        <p class="text-lg italic">"Learning never exhausts the mind."</p>
                        <p class="text-sm mt-2 opacity-80">– Leonardo da Vinci</p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-xl p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Your Learning Journey</h3>
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-4xl mr-4">📝</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">Start with Fill in the Blanks</h4>
                            <p class="text-gray-600 text-sm">Perfect for beginners - complete PHP code snippets</p>
                        </div>
                        <a href="tools.php" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">Start</a>
                    </div>
                    
                    <div class="flex items-center p-4 bg-pink-50 rounded-lg">
                        <div class="text-4xl mr-4">🔍</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">Challenge: Find the Error</h4>
                            <p class="text-gray-600 text-sm">Sharpen your debugging skills</p>
                        </div>
                        <a href="tools.php" class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 transition">Play</a>
                    </div>
                    
                    <div class="flex items-center p-4 bg-orange-50 rounded-lg">
                        <div class="text-4xl mr-4">🏆</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">Take the Quiz Challenge</h4>
                            <p class="text-gray-600 text-sm">Test your PHP knowledge with multiple choice questions</p>
                        </div>
                        <a href="tools.php" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition">Quiz</a>
                    </div>
                    
                    <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-4xl mr-4">📚</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">Read PHP Modules</h4>
                            <p class="text-gray-600 text-sm">Learn concepts from 12 comprehensive chapters</p>
                        </div>
                        <a href="modules.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Learn</a>
                    </div>
                </div>
            </div>
            
        </div>
    </main>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const toggle = event.target.closest('button');
            
            if (!menu.contains(event.target) && !toggle) {
                menu.classList.add('hidden');
            }
        });
    </script>
    
</body>
</html>