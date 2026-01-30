<?php
session_start();
require_once 'db.php';

// Clean input function (if not in db.php)
if (!function_exists('clean_input')) {
    function clean_input($data) {
        global $conn;
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        if (isset($conn)) {
            return $conn->real_escape_string($data);
        }
        return $data;
    }
}

$user_fullname = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "Guest Learner";
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $rating = (int)$_POST['rating'];
    $category = clean_input($_POST['category']);
    $message = clean_input($_POST['message']);
    
    // Insert into feedback table
    $sql = "INSERT INTO feedback (user_id, name, email, rating, category, message, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississ", $user_id, $name, $email, $rating, $category, $message);
    
    if ($stmt->execute()) {
        $success_message = "🎉 Thank you for your feedback! We appreciate your input and will review it soon.";
    } else {
        $error_message = "❌ Sorry, something went wrong. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - PHP Learning Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%);
            min-height: 100vh;
        }
        
        .float-object {
            position: fixed;
            font-size: 60px;
            opacity: 0.1;
            z-index: 0;
            animation: float 20s infinite ease-in-out;
        }
        
        .float-object:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .float-object:nth-child(2) { top: 60%; right: 15%; animation-delay: 3s; }
        .float-object:nth-child(3) { bottom: 15%; left: 20%; animation-delay: 6s; }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(30px, -30px) rotate(5deg); }
        }
        
        .star-rating {
            display: flex;
            gap: 10px;
            font-size: 2.5rem;
        }
        
        .star {
            cursor: pointer;
            transition: all 0.3s;
            filter: grayscale(100%);
        }
        
        .star:hover, .star.active {
            filter: grayscale(0%);
            transform: scale(1.2);
        }
        
        .success-message {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 20px;
            border-radius: 12px;
            animation: slideIn 0.5s ease;
        }
        
        .error-message {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 20px;
            border-radius: 12px;
            animation: slideIn 0.5s ease;
        }
        .gradient-text {
            background: linear-gradient(135deg, #a855f7, #ec4899, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #a855f7, #ec4899);
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(168, 85, 247, 0.4);
        }
    </style>
</head>
<body class="font-sans">
    
    <!-- Floating Objects -->
    <div class="float-object">💬</div>
    <div class="float-object">⭐</div>
    <div class="float-object">📝</div>
    
   <!-- Header Navigation -->
<header class="header fixed top-0 left-0 right-0 z-50 px-6 py-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <span class="text-3xl">🏠</span>
            <h1 class="text-2xl font-bold gradient-text">SmartlyGoPHP</h1>
        </div>
        
        <nav class="hidden md:flex space-x-6 items-center">
            <a href="dashboard.php" class="text-gray-700 hover:text-purple-600 font-semibold">🏠 Home</a>
            <a href="tools.php" class="text-gray-700 hover:text-purple-600 font-semibold">🛠️ Tools</a>
             <a href="modules.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">📚 Modules</a>
            <a href="feedback.php" class="text-purple-600 font-semibold">💬 Feedback</a>
            <a href="contact.php" class="text-gray-700 hover:text-purple-600 font-semibold">📧 Contact</a>

            <?php if (!empty($_SESSION['user_id']) || !empty($_SESSION['name']) || !empty($_SESSION['full_name'])): ?>
                <a href="logout.php" 
                   class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all font-semibold" 
                   onclick="return confirm('Are you sure you want to logout?')">🚪 Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

    
    <!-- Main Content -->
    <main class="pt-28 pb-12 px-4 relative z-10">
        <div class="max-w-4xl mx-auto">
            
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h2 class="text-5xl font-extrabold mb-4" style="background: linear-gradient(135deg, #a855f7, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    We Value Your Feedback! 💬
                </h2>
                <p class="text-gray-700 text-lg">Help us improve by sharing your thoughts and suggestions</p>
            </div>
            
            <!-- Success/Error Messages -->
            <?php if (isset($success_message)): ?>
                <div class="success-message mb-6">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message mb-6">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Feedback Form -->
            <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8">
                
                <form method="POST" class="space-y-6">
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">
                            👤 Your Name
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="<?php echo htmlspecialchars($user_fullname); ?>"
                            class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-500"
                            required
                        >
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">
                            📧 Your Email
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            value="<?php echo htmlspecialchars($user_email); ?>"
                            class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-500"
                            required
                        >
                    </div>
                    
                    <!-- Rating -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-3">
                            ⭐ Rate Your Experience
                        </label>
                        <div class="star-rating" id="starRating">
                            <span class="star" data-rating="1">⭐</span>
                            <span class="star" data-rating="2">⭐</span>
                            <span class="star" data-rating="3">⭐</span>
                            <span class="star" data-rating="4">⭐</span>
                            <span class="star" data-rating="5">⭐</span>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="5" required>
                        <p class="text-sm text-gray-500 mt-2">Click on stars to rate (1-5)</p>
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">
                            📁 Feedback Category
                        </label>
                        <select 
                            name="category" 
                            class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-500"
                            required
                        >
                            <option value="">Select a category...</option>
                            <option value="General">General Feedback</option>
                            <option value="Games">Games & Challenges</option>
                            <option value="UI/UX">User Interface</option>
                            <option value="Content">Learning Content</option>
                            <option value="Bug Report">Bug Report</option>
                            <option value="Feature Request">Feature Request</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <!-- Message -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">
                            💭 Your Feedback
                        </label>
                        <textarea 
                            name="message" 
                            rows="6"
                            class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-500"
                            placeholder="Share your thoughts, suggestions, or report issues..."
                            required
                        ></textarea>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        name="submit_feedback"
                        class="btn-submit w-full text-white font-bold py-4 px-6 rounded-lg text-lg"
                    >
                        📤 Submit Feedback
                    </button>
                    
                </form>
                
            </div>
            
            <!-- Why Feedback Matters -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white bg-opacity-80 rounded-xl p-6 text-center">
                    <div class="text-5xl mb-3">🎯</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Improve Quality</h3>
                    <p class="text-gray-600">Your feedback helps us enhance the learning experience</p>
                </div>
                <div class="bg-white bg-opacity-80 rounded-xl p-6 text-center">
                    <div class="text-5xl mb-3">💡</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">New Features</h3>
                    <p class="text-gray-600">Suggest features you'd like to see implemented</p>
                </div>
                <div class="bg-white bg-opacity-80 rounded-xl p-6 text-center">
                    <div class="text-5xl mb-3">🤝</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Community Input</h3>
                    <p class="text-gray-600">Be part of shaping the future of PHP Learning Hub</p>
                </div>
            </div>
            
        </div>
    </main>
    
    <script>
        // Star Rating System
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                
                // Update star display
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
        
        // Set all stars active by default (5 stars)
        stars.forEach(s => s.classList.add('active'));
    </script>
    
</body>
</html>

<?php
/* 
SQL to create feedback table:

CREATE TABLE IF NOT EXISTS feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT 0,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rating INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('New', 'Reviewed', 'Resolved') DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/
?>