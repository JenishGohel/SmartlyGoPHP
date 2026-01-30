<?php
session_start();

// Store user name before destroying session
$user_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'User';

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - PHP Learning Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logout-card {
            animation: slideDown 0.5s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .wave {
            animation: wave 0.5s ease;
        }
        
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(20deg); }
            75% { transform: rotate(-20deg); }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #a855f7, #ec4899);
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(168, 85, 247, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #10b981, #059669);
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.4);
        }
    </style>
</head>
<body class="font-sans">
    
    <div class="logout-card max-w-md w-full mx-4">
        <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-12 text-center">
            
            <!-- Icon -->
            <div class="text-8xl mb-6 wave">👋</div>
            
            <!-- Message -->
            <h1 class="text-4xl font-bold mb-4" style="background: linear-gradient(135deg, #a855f7, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                See You Soon!
            </h1>
            
            <p class="text-xl text-gray-700 mb-2">
                Goodbye, <strong><?php echo htmlspecialchars($user_name); ?></strong>!
            </p>
            
            <p class="text-gray-600 mb-8">
                You have been successfully logged out.
            </p>
            
            <!-- Success Message -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8">
                <p class="text-green-800">
                    ✅ Your session has been ended securely.
                </p>
            </div>
            
            <!-- Buttons -->
            <div class="space-y-4">
                <!-- <a 
                    href="index.php" 
                    class="btn-primary block w-full text-white font-bold py-4 px-6 rounded-lg text-lg"
                >
                    🔐 Login Again
                </a> -->
                
                <a 
                    href="dashboard.php" 
                    class="btn-secondary block w-full text-white font-bold py-4 px-6 rounded-lg text-lg"
                >
                    🏠 Go to Dashboard
                </a>
            </div>
            
            <!-- Footer Message -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Keep learning, keep growing! 🚀
                </p>
            </div>
            
        </div>
        
        <!-- Quick Stats (Optional) -->
        <div class="mt-6 grid grid-cols-3 gap-4">
            <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                <div class="text-2xl mb-1">🎮</div>
                <div class="text-xs text-gray-600">8 Games</div>
            </div>
            <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                <div class="text-2xl mb-1">🏆</div>
                <div class="text-xs text-gray-600">Challenges</div>
            </div>
            <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                <div class="text-2xl mb-1">⭐</div>
                <div class="text-xs text-gray-600">Learn PHP</div>
            </div>
        </div>
    </div>
    
    <!-- Auto redirect after 5 seconds (optional) -->
    <script>
        // Uncomment to enable auto redirect
        // setTimeout(function() {
        //     window.location.href = 'login.php';
        // }, 5000);
    </script>
    
</body>
</html>