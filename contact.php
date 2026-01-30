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

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact'])) {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $subject = clean_input($_POST['subject']);
    $message = clean_input($_POST['message']);
    
    // Insert into contact_messages table
    $sql = "INSERT INTO contact_messages (user_id, name, email, subject, message, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        $success_message = "✅ Message sent successfully! We'll get back to you within 24-48 hours.";
    } else {
        $error_message = "❌ Sorry, something went wrong. Please try again later or email us directly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - PHP Learning Hub</title>
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
        
        .contact-card {
            transition: all 0.3s;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(168, 85, 247, 0.3);
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
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .gradient-text {
            background: linear-gradient(135deg, #a855f7, #ec4899, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
        
        .social-icon {
            transition: all 0.3s;
        }
        
        .social-icon:hover {
            transform: scale(1.2) rotate(10deg);
        }
    </style>
</head>
<body class="font-sans">
    
    <!-- Floating Objects -->
    <div class="float-object">📧</div>
    <div class="float-object">📱</div>
    <div class="float-object">🌐</div>
    
    <!-- Header Navigation -->
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
            <a href="feedback.php" class="text-gray-700 hover:text-purple-600 font-semibold">💬 Feedback</a>
            <a href="contact.php" class="text-purple-600 font-semibold">📧 Contact</a>

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
        <div class="max-w-6xl mx-auto">
            
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h2 class="text-5xl font-extrabold mb-4" style="background: linear-gradient(135deg, #a855f7, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Get In Touch! 📧
                </h2>
                <p class="text-gray-700 text-lg">Have questions? We'd love to hear from you!</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    
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
                    
                    <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Send Us a Message</h3>
                        
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
                            
                            <!-- Subject -->
                            <div>
                                <label class="block text-lg font-semibold text-gray-700 mb-2">
                                    📝 Subject
                                </label>
                                <input 
                                    type="text" 
                                    name="subject" 
                                    class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-500"
                                    placeholder="What is this regarding?"
                                    required
                                >
                            </div>
                            
                            <!-- Message -->
                            <div>
                                <label class="block text-lg font-semibold text-gray-700 mb-2">
                                    💬 Message
                                </label>
                                <textarea 
                                    name="message" 
                                    rows="6"
                                    class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-500"
                                    placeholder="Tell us how we can help you..."
                                    required
                                ></textarea>
                            </div>
                            
                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                name="submit_contact"
                                class="btn-submit w-full text-white font-bold py-4 px-6 rounded-lg text-lg"
                            >
                                📤 Send Message
                            </button>
                            
                        </form>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="space-y-6">
                    
                    <!-- Email -->
                    <div class="contact-card bg-white bg-opacity-90 backdrop-blur-lg rounded-xl shadow-lg p-6">
                        <div class="text-5xl mb-4">📧</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Email Us</h3>
                        <a href="mailto:support@phplearnhub.com" class="text-purple-600 hover:underline">
                            jenish.gohel121873@marwadiuniversity.ac.in
                            ravi.sav121873@marwadiuniversity.ac.in
                        </a>
                        <p class="text-sm text-gray-500 mt-2">We reply within 24 hours</p>
                    </div>
                    
                    <!-- Phone -->
                    <div class="contact-card bg-white bg-opacity-90 backdrop-blur-lg rounded-xl shadow-lg p-6">
                        <div class="text-5xl mb-4">📱</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Call Us</h3>
                        <a href="tel:+919876543210" class="text-purple-600 hover:underline">
                            +91 63516 27987
                        </a>
                        <p class="text-sm text-gray-500 mt-2">Mon-Fri : 9AM - 6PM IST</p>
                    </div>
                    
                    <!-- Location -->
                    <div class="contact-card bg-white bg-opacity-90 backdrop-blur-lg rounded-xl shadow-lg p-6">
                        <div class="text-5xl mb-4">📍</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Visit Us</h3>
                        <p class="text-gray-600">
                            Rajkot , Gujarat<br>
                            India
                        </p>
                    </div>
                    
                    
                </div>
                
            </div>
            
            <!-- FAQ Section -->
            <div class="mt-12 bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8">
                <h3 class="text-3xl font-bold text-gray-800 mb-6 text-center">Frequently Asked Questions</h3>
                
                <div class="space-y-4">
                    <!-- <details class="bg-purple-50 rounded-lg p-4">
                        <summary class="font-semibold text-gray-800 cursor-pointer">
                            ❓ How do I reset my password?
                        </summary>
                        <p class="mt-2 text-gray-600">
                            Click on "Forgot Password" on the login page and follow the instructions sent to your email.
                        </p>
                    </details> -->
                    
                    <details class="bg-purple-50 rounded-lg p-4">
                        <summary class="font-semibold text-gray-800 cursor-pointer">
                            ❓ Are the games free to play?
                        </summary>
                        <p class="mt-2 text-gray-600">
                            Yes! All our learning games and challenges are completely free for all users.
                        </p>
                    </details>
                    
                    <details class="bg-purple-50 rounded-lg p-4">
                        <summary class="font-semibold text-gray-800 cursor-pointer">
                            ❓ Can I suggest new features?
                        </summary>
                        <p class="mt-2 text-gray-600">
                            Absolutely! Please use the feedback form or contact us directly with your suggestions.
                        </p>
                    </details>
                    
                    <details class="bg-purple-50 rounded-lg p-4">
                        <summary class="font-semibold text-gray-800 cursor-pointer">
                            ❓ How can I track my progress?
                        </summary>
                        <p class="mt-2 text-gray-600">
                            Your dashboard shows your scores, completed challenges, and learning progress.
                        </p>
                    </details>
                </div>
            </div>
            
        </div>
    </main>
    
</body>
</html>

<?php
/* 
SQL to create contact_messages table:

CREATE TABLE IF NOT EXISTS contact_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT 0,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('New', 'Read', 'Responded') DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/
?>