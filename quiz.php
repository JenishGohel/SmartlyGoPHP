<?php
session_start();
require_once 'db.php';
require_once 'save_game_progress.php'; // IMPORTANT for 7-parameter function

// Clean input function
if (!function_exists('clean_input')) {
    function clean_input($data) {
        global $conn;
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $conn->real_escape_string($data);
    }
}
$user_fullname = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Guest Learner";

$user_id = $_SESSION['user_id'] ?? 0;

// Initialize quiz session
if (!isset($_SESSION['mcq_quiz_mode'])) {
    $_SESSION['mcq_quiz_mode'] = false;
    $_SESSION['mcq_quiz_total'] = 0;
    $_SESSION['mcq_quiz_current'] = 0;
    $_SESSION['mcq_quiz_correct'] = 0;
    $_SESSION['mcq_quiz_score'] = 0;
    $_SESSION['mcq_quiz_questions'] = [];
}

// Start Quiz
if (isset($_POST['start_quiz'])) {
    $total_questions = (int)$_POST['quiz_count'];

    $_SESSION['mcq_quiz_mode'] = true;
    $_SESSION['mcq_quiz_total'] = $total_questions;
    $_SESSION['mcq_quiz_current'] = 0;
    $_SESSION['mcq_quiz_correct'] = 0;
    $_SESSION['mcq_quiz_score'] = 0;

    // Fetch random questions
    $sql = "SELECT quiz_id FROM quiz ORDER BY RAND() LIMIT $total_questions";
    $result = $conn->query($sql);

    $_SESSION['mcq_quiz_questions'] = [];
    while ($row = $result->fetch_assoc()) {
        $_SESSION['mcq_quiz_questions'][] = $row['quiz_id'];
    }
}

// Reset Quiz
if (isset($_GET['reset'])) {
    $_SESSION['mcq_quiz_mode'] = false;
    $_SESSION['mcq_quiz_total'] = 0;
    $_SESSION['mcq_quiz_current'] = 0;
    $_SESSION['mcq_quiz_correct'] = 0;
    $_SESSION['mcq_quiz_score'] = 0;
    $_SESSION['mcq_quiz_questions'] = [];
    header("Location: quiz.php");
    exit();
}

// Handle answer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_answer'])) {

    $quiz_id = (int)$_POST['quiz_id'];
    $user_answer = clean_input($_POST['user_answer']);

    // Fetch correct answer
    $stmt = $conn->prepare("SELECT correct_answer FROM quiz WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $correct = $res->fetch_assoc();

    $is_correct = ($user_answer == $correct['correct_answer']);

    if ($_SESSION['mcq_quiz_mode']) {
        $_SESSION['mcq_quiz_current']++;

        if ($is_correct) {
            $_SESSION['mcq_quiz_correct']++;
            $_SESSION['mcq_quiz_score'] += 10;
        }
    }

    // UI Messages
    if ($is_correct) {
        $msg = "Correct!";
        $msg_class = "success";
    } else {
        $msg = "Incorrect! Correct answer: " . htmlspecialchars($correct['correct_answer']);
        $msg_class = "error";
    }

    $_SESSION['game_message'] = $msg;
    $_SESSION['game_message_class'] = $msg_class;
}

// Quiz complete condition
$quiz_complete = ($_SESSION['mcq_quiz_mode'] &&
    $_SESSION['mcq_quiz_current'] >= $_SESSION['mcq_quiz_total']);


// ----------------------------
// SAVE GAME PROGRESS HERE (7-PARAMETERS)
// ----------------------------
if ($quiz_complete && $user_id > 0) {

    $points    = $_SESSION['mcq_quiz_score'];
    $questions = $_SESSION['mcq_quiz_total'];
    $correct   = $_SESSION['mcq_quiz_correct'];
    $wrong     = $questions - $correct;

    // 7-Parameter Required Call
    saveGameProgress(
        $conn,
        $user_id,
        "mcq-quiz",   // GAME TYPE
        $points,
        $questions,
        $correct,
        $wrong
    );
}


// Fetch current or random question
if ($_SESSION['mcq_quiz_mode'] && !$quiz_complete) {

    $index = $_SESSION['mcq_quiz_current'];
    $qid = $_SESSION['mcq_quiz_questions'][$index];

    $stmt = $conn->prepare("SELECT * FROM quiz WHERE quiz_id = ?");
    $stmt->bind_param("i", $qid);
    $stmt->execute();
    $question = $stmt->get_result()->fetch_assoc();

} elseif (!$_SESSION['mcq_quiz_mode']) {

    $result = $conn->query("SELECT * FROM quiz ORDER BY RAND() LIMIT 1");

    if ($result->num_rows > 0) {
        $question = $result->fetch_assoc();
    } else {
        die("No questions available!");
    }
}

$message = $_SESSION['game_message'] ?? '';
$message_class = $_SESSION['game_message_class'] ?? '';
unset($_SESSION['game_message'], $_SESSION['game_message_class']);
?>

<!-- Your existing HTML from here onward -->
<!-- I am NOT editing your UI code at all -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Challenge - SmartlyGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%);
            min-height: 100vh;
        }
        
        .option-card {
            transition: all 0.3s;
            cursor: pointer;
            border: 3px solid transparent;
        }
        
        .option-card:hover {
            border-color: #a855f7;
            transform: translateX(10px);
            box-shadow: 0 10px 20px rgba(168, 85, 247, 0.3);
        }
        
        .option-card input[type="radio"]:checked + label {
            background: linear-gradient(135deg, #a855f7, #ec4899);
            color: white;
            border-color: #a855f7;
        }
        
        .success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            animation: slideIn 0.5s ease;
        }
        
        .error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #a855f7, #ec4899);
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
                📚 Quiz Challenge
            </h1>
            <p class="text-gray-700 text-lg">Test your PHP knowledge with multiple-choice questions!</p>
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
                        <div class="text-3xl font-bold text-purple-600"><?php echo $_SESSION['mcq_quiz_score']; ?></div>
                        <div class="text-gray-600">Total Score</div>
                    </div>
                    
                    <div class="bg-green-50 rounded-xl p-6">
                        <div class="text-4xl mb-2">✅</div>
                        <div class="text-3xl font-bold text-green-600"><?php echo $_SESSION['mcq_quiz_correct']; ?>/<?php echo $_SESSION['mcq_quiz_total']; ?></div>
                        <div class="text-gray-600">Correct Answers</div>
                    </div>
                    
                    <div class="bg-orange-50 rounded-xl p-6">
                        <div class="text-4xl mb-2">📈</div>
                        <div class="text-3xl font-bold text-orange-600">
                            <?php echo round(($_SESSION['mcq_quiz_correct'] / $_SESSION['mcq_quiz_total']) * 100); ?>%
                        </div>
                        <div class="text-gray-600">Accuracy</div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <a href="?reset=1" class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-3 px-8 rounded-lg hover:from-purple-700 hover:to-pink-700 transition">
                        🔄 Take Another Quiz
                    </a>
                    <br>
                    <a href="tools.php" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-8 rounded-lg transition">
                        ← Back to Tools
                    </a>
                </div>
            </div>
            
        <?php elseif (!$_SESSION['mcq_quiz_mode']): ?>
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
                            <option value="20">20 Questions </option>
                            <option value="30">30 Questions </option>
                            <option value="50">50 Questions </option>
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
                    <!-- <p class="text-gray-600 mb-4">Or practice without time limits:</p>
                    <a href="quiz.php?mode=practice" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-8 rounded-lg transition">
                        🎮 Practice Mode
                    </a> -->
                </div>
            </div>
            
        <?php else: ?>
            <!-- Quiz Progress -->
            <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-xl shadow-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-600">Question <?php echo $_SESSION['mcq_quiz_current'] + 1; ?> of <?php echo $_SESSION['mcq_quiz_total']; ?></span>
                    <span class="text-sm font-semibold text-purple-600">Score: <?php echo $_SESSION['mcq_quiz_score']; ?> pts</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo (($_SESSION['mcq_quiz_current']) / $_SESSION['mcq_quiz_total']) * 100; ?>%"></div>
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
                
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-purple-600 bg-purple-100 px-4 py-2 rounded-full">
                            <!-- Question #<?php echo $question['quiz_id']; ?> -->
                        </span>
                        <span class="text-sm font-semibold text-gray-600">
                            🎯 10 Points
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        <?php echo htmlspecialchars($question['question']); ?>
                    </h2>
                </div>
                
                <form method="POST" id="quizForm">
                    <input type="hidden" name="quiz_id" value="<?php echo $question['quiz_id']; ?>">
                    
                    <div class="space-y-4 mb-8">
                        
                        <!-- Option 1 -->
                        <div class="option-card bg-white rounded-xl p-5 border-3">
                            <input type="radio" name="user_answer" id="option1" value="<?php echo htmlspecialchars($question['option_1']); ?>" class="hidden" required>
                            <label for="option1" class="flex items-center cursor-pointer p-3 rounded-lg">
                                <span class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center font-bold text-purple-600 mr-4">A</span>
                                <span class="text-lg"><?php echo htmlspecialchars($question['option_1']); ?></span>
                            </label>
                        </div>
                        
                        <!-- Option 2 -->
                        <div class="option-card bg-white rounded-xl p-5 border-3">
                            <input type="radio" name="user_answer" id="option2" value="<?php echo htmlspecialchars($question['option_2']); ?>" class="hidden" required>
                            <label for="option2" class="flex items-center cursor-pointer p-3 rounded-lg">
                                <span class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center font-bold text-pink-600 mr-4">B</span>
                                <span class="text-lg"><?php echo htmlspecialchars($question['option_2']); ?></span>
                            </label>
                        </div>
                        
                        <!-- Option 3 -->
                        <div class="option-card bg-white rounded-xl p-5 border-3">
                            <input type="radio" name="user_answer" id="option3" value="<?php echo htmlspecialchars($question['option_3']); ?>" class="hidden" required>
                            <label for="option3" class="flex items-center cursor-pointer p-3 rounded-lg">
                                <span class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center font-bold text-orange-600 mr-4">C</span>
                                <span class="text-lg"><?php echo htmlspecialchars($question['option_3']); ?></span>
                            </label>
                        </div>
                        
                        <!-- Option 4 -->
                        <div class="option-card bg-white rounded-xl p-5 border-3">
                            <input type="radio" name="user_answer" id="option4" value="<?php echo htmlspecialchars($question['option_4']); ?>" class="hidden" required>
                            <label for="option4" class="flex items-center cursor-pointer p-3 rounded-lg">
                                <span class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center font-bold text-green-600 mr-4">D</span>
                                <span class="text-lg"><?php echo htmlspecialchars($question['option_4']); ?></span>
                            </label>
                        </div>
                        
                    </div>
                    
                    <button 
                        type="submit" 
                        name="submit_answer"
                        class="btn-primary w-full text-white font-bold py-4 px-6 rounded-lg text-lg"
                    >
                        ✓ Submit Answer
                    </button>
                </form>
                
            </div>
            
            <!-- Current Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                    <div class="text-3xl mb-1">✅</div>
                    <div class="text-2xl font-bold text-green-600"><?php echo $_SESSION['mcq_quiz_correct']; ?></div>
                    <div class="text-sm text-gray-600">Correct</div>
                </div>
                <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                    <div class="text-3xl mb-1">❌</div>
                    <div class="text-2xl font-bold text-red-600"><?php echo $_SESSION['mcq_quiz_current'] - $_SESSION['mcq_quiz_correct']; ?></div>
                    <div class="text-sm text-gray-600">Wrong</div>
                </div>
                <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                    <div class="text-3xl mb-1">📊</div>
                    <div class="text-2xl font-bold text-purple-600"><?php echo $_SESSION['mcq_quiz_score']; ?></div>
                    <div class="text-sm text-gray-600">Score</div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Back Button -->
        <?php if (!$quiz_complete && !$_SESSION['mcq_quiz_mode']): ?>
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
    
    <script>
        // Make entire card clickable
        document.querySelectorAll('.option-card').forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });
    </script>
    
</body>
</html>