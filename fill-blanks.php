<?php
session_start();
require_once 'db.php';
require_once 'save_game_progress.php';

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$user_fullname = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Guest Learner";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Initialize quiz session
if (!isset($_SESSION['quiz_mode'])) {
    $_SESSION['quiz_mode'] = false;
    $_SESSION['quiz_total'] = 0;
    $_SESSION['quiz_current'] = 0;
    $_SESSION['quiz_correct'] = 0;
    $_SESSION['quiz_score'] = 0;
    $_SESSION['quiz_questions'] = [];
}

// Start Quiz Mode
if (isset($_POST['start_quiz'])) {
    $total_questions = (int)$_POST['quiz_count'];
    $_SESSION['quiz_mode'] = true;
    $_SESSION['quiz_total'] = $total_questions;
    $_SESSION['quiz_current'] = 0;
    $_SESSION['quiz_correct'] = 0;
    $_SESSION['quiz_score'] = 0;
    unset($_SESSION['fib_progress_saved']); // Reset saved flag
    
    // Fetch random questions for the quiz
    $sql = "SELECT fib_id FROM fillintheblanks ORDER BY RAND() LIMIT $total_questions";
    $result = $conn->query($sql);
    $_SESSION['quiz_questions'] = [];
    while ($row = $result->fetch_assoc()) {
        $_SESSION['quiz_questions'][] = $row['fib_id'];
    }
}

// Reset Quiz
if (isset($_GET['reset'])) {
    $_SESSION['quiz_mode'] = false;
    $_SESSION['quiz_total'] = 0;
    $_SESSION['quiz_current'] = 0;
    $_SESSION['quiz_correct'] = 0;
    $_SESSION['quiz_score'] = 0;
    $_SESSION['quiz_questions'] = [];
    unset($_SESSION['fib_progress_saved']);
    header("Location: fill-blanks.php");
    exit();
}

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_answer'])) {
    $fib_id = (int)$_POST['fib_id'];
    $user_answer = clean_input($_POST['user_answer']);
    
    // Get correct answer
    $sql = "SELECT correct_answer FROM fillintheblanks WHERE fib_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fib_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $correct = $result->fetch_assoc();
    
    // Check answer
    $is_correct = (strtolower(trim($user_answer)) == strtolower(trim($correct['correct_answer'])));
    
    if ($_SESSION['quiz_mode']) {
        $_SESSION['quiz_current']++;
        if ($is_correct) {
            $_SESSION['quiz_correct']++;
            $_SESSION['quiz_score'] += 10;
        }
    }
    
    if ($is_correct) {
        $message = "🎉 Correct! Great job!";
        $message_class = "success";
    } else {
        $message = "❌ Incorrect! The correct answer is: <strong>" . htmlspecialchars($correct['correct_answer']) . "</strong>";
        $message_class = "error";
    }
    
    $_SESSION['game_message'] = $message;
    $_SESSION['game_message_class'] = $message_class;
}

// Check if quiz is complete
$quiz_complete = false;
if ($_SESSION['quiz_mode'] && $_SESSION['quiz_current'] >= $_SESSION['quiz_total']) {
    $quiz_complete = true;
    
    // Save progress when quiz is complete (only once)
    if ($user_id > 0 && !isset($_SESSION['fib_progress_saved'])) {
        saveGameProgress(
            $conn, 
            $user_id, 
            'fill-blanks',
            $_SESSION['quiz_score'], 
            $_SESSION['quiz_total'], 
            $_SESSION['quiz_correct'], 
            $_SESSION['quiz_total'] - $_SESSION['quiz_correct']
        );
        $_SESSION['fib_progress_saved'] = true;
    }
}

// Fetch current question
if ($_SESSION['quiz_mode'] && !$quiz_complete) {
    $question_index = $_SESSION['quiz_current'];
    $question_id = $_SESSION['quiz_questions'][$question_index];
    $sql = "SELECT * FROM fillintheblanks WHERE fib_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();
} elseif (!$_SESSION['quiz_mode']) {
    // Practice mode - random question
    $sql = "SELECT * FROM fillintheblanks ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $question = $result->fetch_assoc();
    } else {
        die("No questions available in database!");
    }
}

// Get message from session
$message = isset($_SESSION['game_message']) ? $_SESSION['game_message'] : '';
$message_class = isset($_SESSION['game_message_class']) ? $_SESSION['game_message_class'] : '';
unset($_SESSION['game_message']);
unset($_SESSION['game_message_class']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill in the Blanks - SmartlyGo</title>
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
            line-height: 1.6;
            white-space: pre-wrap;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            animation: slideIn 0.5s ease;
        }
        
        .error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #a855f7, #9333ea);
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(168, 85, 247, 0.4);
        }
        
        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #a855f7, #ec4899);
            transition: width 0.3s ease;
        }
    </style>
</head>
<body class="font-sans">
    
    <div class="container max-w-4xl mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-5xl font-bold mb-2" style="background: linear-gradient(135deg, #a855f7, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                📝 Fill in the Blanks
            </h1>
            <p class="text-gray-700 text-lg">Complete the PHP code by filling in the missing part!</p>
            <p class="text-gray-600 mt-2">Welcome, <strong><?php echo htmlspecialchars($user_fullname); ?></strong>! 👋</p>
        </div>
        
        <?php if ($quiz_complete): ?>
            <!-- Quiz Complete Screen -->
            <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-6 text-center">
                <div class="text-8xl mb-6">🏆</div>
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Quiz Complete!</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-8">
                    <div class="bg-purple-50 rounded-xl p-6">
                        <div class="text-4xl mb-2">📊</div>
                        <div class="text-3xl font-bold text-purple-600"><?php echo $_SESSION['quiz_score']; ?></div>
                        <div class="text-gray-600">Total Score</div>
                    </div>
                    
                    <div class="bg-green-50 rounded-xl p-6">
                        <div class="text-4xl mb-2">✅</div>
                        <div class="text-3xl font-bold text-green-600"><?php echo $_SESSION['quiz_correct']; ?>/<?php echo $_SESSION['quiz_total']; ?></div>
                        <div class="text-gray-600">Correct Answers</div>
                    </div>
                    
                    <div class="bg-orange-50 rounded-xl p-6">
                        <div class="text-4xl mb-2">📈</div>
                        <div class="text-3xl font-bold text-orange-600">
                            <?php echo round(($_SESSION['quiz_correct'] / $_SESSION['quiz_total']) * 100); ?>%
                        </div>
                        <div class="text-gray-600">Accuracy</div>
                    </div>
                </div>
                
                <?php if ($user_id > 0): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-6">
                    <p class="text-green-800">✅ <strong>Progress Saved!</strong> Your score has been added to your profile.</p>
                    <p class="text-green-700 text-sm mt-1">Check your dashboard to see your updated stats!</p>
                </div>
                <?php endif; ?>
                
                <div class="space-y-4">
                    <a href="?reset=1" class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-3 px-8 rounded-lg hover:from-purple-700 hover:to-pink-700 transition">
                        🔄 Take Another Quiz
                    </a>
                    <br>
                    <a href="dashboard.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition mr-2">
                        📊 View Dashboard
                    </a>
                    <a href="tools.php" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-8 rounded-lg transition">
                        ← Back to Tools
                    </a>
                </div>
            </div>
            
        <?php elseif (!$_SESSION['quiz_mode']): ?>
            <!-- Quiz Selection Screen -->
            <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Choose Your Challenge Mode</h2>
                
                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-3">
                            🎯 Select Number of Questions:
                        </label>
                        <select name="quiz_count" class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-500 text-lg">
                            <option value="10">10 Questions </option>
                            <option value="20">20 Questions - </option>
                            <option value="30">30 Questions - </option>
                            <option value="50">50 Questions - </option>
                        </select>
                    </div>
                    
                    <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                        <h3 class="font-bold text-purple-800 mb-2">📊 Scoring System:</h3>
                        <ul class="text-purple-700 space-y-1">
                            <li>✅ Correct Answer: <strong>+10 points</strong></li>
                            <li>❌ Wrong Answer: <strong>0 points</strong></li>
                            <li>🏆 Maximum Score: <strong>{Selected Questions} × 10</strong></li>
                        </ul>
                    </div>
                    
                    <button type="submit" name="start_quiz" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-4 px-6 rounded-lg text-lg transition">
                        🚀 Start Quiz
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <!-- <p class="text-gray-600 mb-4">Or practice without time limits:</p> -->
                    <!-- <a href="fill-blanks.php?mode=practice" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-8 rounded-lg transition">
                        🎮 Practice Mode
                    </a> -->
                </div>
            </div>
            
        <?php else: ?>
            <!-- Quiz Progress -->
            <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-xl shadow-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-600">Question <?php echo $_SESSION['quiz_current'] + 1; ?> of <?php echo $_SESSION['quiz_total']; ?></span>
                    <span class="text-sm font-semibold text-purple-600">Score: <?php echo $_SESSION['quiz_score']; ?> pts</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo (($_SESSION['quiz_current']) / $_SESSION['quiz_total']) * 100; ?>%"></div>
                </div>
            </div>
            
            <!-- Result Message -->
            <?php if (!empty($message)): ?>
                <div class="<?php echo $message_class; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Question Card -->
            <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-6">
                
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Question:</h2>
                    <p class="text-lg text-gray-700"><?php echo htmlspecialchars($question['question']); ?></p>
                </div>
                
                <div class="code-box mb-6">
                    <?php echo htmlspecialchars($question['question']); ?>
                </div>
                
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="fib_id" value="<?php echo $question['fib_id']; ?>">
                    
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">
                            Your Answer:
                        </label>
                        <input 
                            type="text" 
                            name="user_answer" 
                            class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-500 text-lg"
                            placeholder="Type your answer here..."
                            required 
                            autofocus
                        >
                    </div>
                    
                    <button 
                        type="submit" 
                        name="submit_answer"
                        class="btn-primary w-full text-white font-bold py-3 px-6 rounded-lg text-lg"
                    >
                        ✓ Submit Answer
                    </button>
                </form>
                
            </div>
            
            <!-- Current Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                    <div class="text-3xl mb-1">✅</div>
                    <div class="text-2xl font-bold text-green-600"><?php echo $_SESSION['quiz_correct']; ?></div>
                    <div class="text-sm text-gray-600">Correct</div>
                </div>
                <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                    <div class="text-3xl mb-1">❌</div>
                    <div class="text-2xl font-bold text-red-600"><?php echo $_SESSION['quiz_current'] - $_SESSION['quiz_correct']; ?></div>
                    <div class="text-sm text-gray-600">Wrong</div>
                </div>
                <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                    <div class="text-3xl mb-1">📊</div>
                    <div class="text-2xl font-bold text-purple-600"><?php echo $_SESSION['quiz_score']; ?></div>
                    <div class="text-sm text-gray-600">Score</div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Back Button -->
        <?php if (!$quiz_complete && !$_SESSION['quiz_mode']): ?>
        <div class="text-center">
            <a 
                href="tools.php" 
                class="inline-block bg-white bg-opacity-80 hover:bg-opacity-100 text-purple-600 font-semibold py-3 px-8 rounded-lg transition shadow-lg"
            >
                ← Back to Tools
            </a>
        </div>
        <?php endif; ?>
        
    </div>
    
</body>
</html>