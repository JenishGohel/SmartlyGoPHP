<?php
session_start();
require_once 'db.php';

$user_fullname = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Guest Learner";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['show_answer'])) {
    $error_id = (int)$_POST['error_id'];
    
    // Get correct answer
    $sql = "SELECT answer_description, correct_code FROM findtheerror WHERE error_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $error_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $answer_data = $result->fetch_assoc();
    
    $_SESSION['show_solution'] = true;
    $_SESSION['answer_description'] = $answer_data['answer_description'];
    $_SESSION['correct_code'] = $answer_data['correct_code'];
}

// Fetch a random question
$sql = "SELECT * FROM findtheerror ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $question = $result->fetch_assoc();
} else {
    die("No questions available in database!");
}

// Check if solution should be shown
$show_solution = isset($_SESSION['show_solution']) ? $_SESSION['show_solution'] : false;
$answer_description = isset($_SESSION['answer_description']) ? $_SESSION['answer_description'] : '';
$correct_code = isset($_SESSION['correct_code']) ? $_SESSION['correct_code'] : '';

// Clear session after displaying
if ($show_solution) {
    unset($_SESSION['show_solution']);
    unset($_SESSION['answer_description']);
    unset($_SESSION['correct_code']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find the Error - PHP Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%);
            min-height: 100vh;
        }
        
        .code-box {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            font-size: 16px;
            line-height: 1.8;
            white-space: pre-wrap;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        
        .line-numbers {
            position: absolute;
            left: 0;
            top: 20px;
            bottom: 20px;
            width: 40px;
            background: #1a202c;
            color: #718096;
            padding: 0 10px;
            text-align: right;
            border-right: 2px solid #4a5568;
            user-select: none;
        }
        
        .code-content {
            margin-left: 50px;
        }
        
        .error-highlight {
            background: rgba(239, 68, 68, 0.3);
            border-left: 3px solid #ef4444;
            padding-left: 10px;
        }
        
        .solution-box {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.4);
        }
    </style>
</head>
<body class="font-sans">
    
    <div class="container max-w-5xl mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-5xl font-bold mb-2" style="background: linear-gradient(135deg, #f59e0b, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                🔍 Find the Error
            </h1>
            <p class="text-gray-700 text-lg">Spot the bug in the PHP code below!</p>
            <p class="text-gray-600 mt-2">Welcome, <strong><?php echo htmlspecialchars($user_fullname); ?></strong>! 👋</p>
        </div>
        
        <!-- Question Card -->
        <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-6">
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">🐛 Buggy Code:</h2>
                <p class="text-lg text-gray-600 mb-4">Find and identify the error in this code:</p>
            </div>
            
            <div class="code-box mb-6">
                <div class="line-numbers">
                    <?php 
                    $lines = substr_count($question['buggy_code'], "\n") + 1;
                    for ($i = 1; $i <= $lines; $i++) {
                        echo $i . "\n";
                    }
                    ?>
                </div>
                <div class="code-content">
                    <?php echo htmlspecialchars($question['buggy_code']); ?>
                </div>
            </div>
            
            <?php if ($show_solution): ?>
                <!-- Solution Display -->
                <div class="solution-box">
                    <h3 class="text-2xl font-bold mb-3">✅ Solution:</h3>
                    <p class="text-lg mb-4"><strong>Error Description:</strong> <?php echo htmlspecialchars($answer_description); ?></p>
                    
                    <h4 class="text-xl font-bold mb-2">Correct Code:</h4>
                    <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                        <pre class="text-white"><code><?php echo htmlspecialchars($correct_code); ?></code></pre>
                    </div>
                </div>
                
                <div class="text-center mt-6">
                    <a 
                        href="find-error.php" 
                        class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition shadow-lg"
                    >
                        ↻ Next Challenge
                    </a>
                </div>
            <?php else: ?>
                <!-- Show Answer Form -->
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="error_id" value="<?php echo $question['error_id']; ?>">
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <p class="text-yellow-800">
                            💡 <strong>Tip:</strong> Look carefully at the syntax, variable names, and punctuation!
                        </p>
                    </div>
                    
                    <div class="flex gap-4">
                        <button 
                            type="submit" 
                            name="show_answer"
                            class="btn-primary flex-1 text-white font-bold py-3 px-6 rounded-lg text-lg"
                        >
                            🔓 Show Solution
                        </button>
                        
                        <a 
                            href="find-error.php" 
                            class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition"
                        >
                            ⏭️ Skip Question
                        </a>
                    </div>
                </form>
            <?php endif; ?>
            
        </div>
        
        <!-- Back Button -->
        <div class="text-center">
            <a 
                href="tools.php" 
                class="inline-block bg-white bg-opacity-80 hover:bg-opacity-100 text-purple-600 font-semibold py-3 px-8 rounded-lg transition shadow-lg"
            >
                ← Back to Tools
            </a>
        </div>
        
    </div>
    
</body>
</html>