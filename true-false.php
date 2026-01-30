<?php
session_start();
require_once 'db.php';
require_once 'save_game_progress.php';

$user_fullname = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Guest Learner";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Initialize quiz session
if (!isset($_SESSION['tf_quiz_mode'])) {
    $_SESSION['tf_quiz_mode'] = false;
    $_SESSION['tf_quiz_total'] = 0;
    $_SESSION['tf_quiz_current'] = 0;
    $_SESSION['tf_quiz_correct'] = 0;
    $_SESSION['tf_quiz_score'] = 0;
    $_SESSION['tf_quiz_questions'] = [];
}

// Start Quiz Mode
if (isset($_POST['start_quiz'])) {
    $total_questions = (int)$_POST['quiz_count'];
    $_SESSION['tf_quiz_mode'] = true;
    $_SESSION['tf_quiz_total'] = $total_questions;
    $_SESSION['tf_quiz_current'] = 0;
    $_SESSION['tf_quiz_correct'] = 0;
    $_SESSION['tf_quiz_score'] = 0;
    
    // Fetch random questions for the quiz
    $sql = "SELECT tf_id FROM truefalse ORDER BY RAND() LIMIT $total_questions";
    $result = $conn->query($sql);
    $_SESSION['tf_quiz_questions'] = [];
    while ($row = $result->fetch_assoc()) {
        $_SESSION['tf_quiz_questions'][] = $row['tf_id'];
    }
}

// Reset Quiz
if (isset($_GET['reset'])) {
    $_SESSION['tf_quiz_mode'] = false;
    $_SESSION['tf_quiz_total'] = 0;
    $_SESSION['tf_quiz_current'] = 0;
    $_SESSION['tf_quiz_correct'] = 0;
    $_SESSION['tf_quiz_score'] = 0;
    $_SESSION['tf_quiz_questions'] = [];
    header("Location: true-false.php");
    exit();
}

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_answer'])) {
    $tf_id = (int)$_POST['tf_id'];
    $user_answer = (int)$_POST['user_answer']; // 1 for True, 0 for False

    // Get correct answer
    $sql = "SELECT is_true, description FROM truefalse WHERE tf_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tf_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $correct = $result->fetch_assoc();

    $is_correct = ($user_answer == $correct['is_true']);
    
    if ($_SESSION['tf_quiz_mode']) {
        $_SESSION['tf_quiz_current']++;
        if ($is_correct) {
            $_SESSION['tf_quiz_correct']++;
            $_SESSION['tf_quiz_score'] += 10;
        }
    }

    if ($is_correct) {
        $message_class = "success";
        $message = "Correct! Your answer is <strong>" . ($user_answer ? "True" : "False") . "</strong>.";
    } else {
        $message_class = "error";
        $message = "Incorrect! Your answer was <strong>" . ($user_answer ? "True" : "False") . "</strong>. The correct answer is <strong>" . ($correct['is_true'] ? "True" : "False") . "</strong>.";
    }

    if (!empty($correct['description'])) {
        $message .= "<br><br>Explanation: " . htmlspecialchars($correct['description']);
    }

    $_SESSION['game_message'] = $message;
    $_SESSION['game_message_class'] = $message_class;
}

// Check if quiz is complete
$quiz_complete = false;
if ($_SESSION['tf_quiz_mode'] && $_SESSION['tf_quiz_current'] >= $_SESSION['tf_quiz_total']) {
    $quiz_complete = true;
}

// --------------------------------------
// SAVE GAME PROGRESS WHEN QUIZ COMPLETES
// --------------------------------------
if ($quiz_complete) {

    $totalQ   = $_SESSION['tf_quiz_total'];
    $correctQ = $_SESSION['tf_quiz_correct'];
    $wrongQ   = $totalQ - $correctQ;

    saveGameProgress(
        $conn,
        $user_id,
        "true-false",
        $_SESSION['tf_quiz_score'],   // points earned
        $totalQ,                      // questions attempted
        $correctQ,                    // correct answers
        $wrongQ                       // wrong answers
    );

    // After saving, stop quiz mode
    $_SESSION['tf_quiz_mode'] = false;
}

// Fetch current question
if ($_SESSION['tf_quiz_mode'] && !$quiz_complete) {
    $question_index = $_SESSION['tf_quiz_current'];
    $question_id = $_SESSION['tf_quiz_questions'][$question_index];
    $sql = "SELECT * FROM truefalse WHERE tf_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();
} elseif (!$_SESSION['tf_quiz_mode']) {
    // Practice mode - random question
    $sql = "SELECT * FROM truefalse ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $question = $result->fetch_assoc();
    } else {
        die("No questions available in database!");
    }
}

// Get messages
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
    <title>True or False - SmartlyGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%); 
            min-height:100vh;
        }
        .answer-btn{
            transition:all 0.3s;
            position:relative;
            overflow:hidden;
        }
        .answer-btn::before{
            content:'';
            position:absolute;
            top:50%;
            left:50%;
            width:0;
            height:0;
            border-radius:50%;
            background:rgba(255,255,255,0.3);
            transform:translate(-50%,-50%);
            transition:width 0.6s,height 0.6s;
        }
        .answer-btn:hover::before{
            width:300px;
            height:300px;
        }
        .answer-btn:hover{
            transform:scale(1.05);
            box-shadow:0 20px 40px rgba(0,0,0,0.2);
        }
        .true-btn{
            background:linear-gradient(135deg,#10b981,#059669);
        }
        .false-btn{
            background:linear-gradient(135deg,#ef4444,#dc2626);
        }
        .success{
            background:linear-gradient(135deg,#10b981,#059669);
            color:white;
            padding:20px;
            border-radius:10px;
            margin:20px 0;
            animation:slideIn 0.5s ease;
        }
        .error{
            background:linear-gradient(135deg,#ef4444,#dc2626);
            color:white;
            padding:20px;
            border-radius:10px;
            margin:20px 0;
            animation:slideIn 0.5s ease;
        }
        @keyframes slideIn{
            from{transform:translateY(-20px);opacity:0;}
            to{transform:translateY(0);opacity:1;}
        }
        @keyframes float{
            0%,100%{transform:translateY(0px);}
            50%{transform:translateY(-20px);}
        }
        .float-icon{
            animation:float 3s ease-in-out infinite;
        }
        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #ef4444);
            transition: width 0.3s ease;
        }
    </style>
</head>
<body class="font-sans">

<div class="container max-w-4xl mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-5xl font-bold mb-2" style="background: linear-gradient(135deg, #10b981, #ef4444); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            ✅ True or False
        </h1>
        <p class="text-gray-700 text-lg">Quick-fire PHP knowledge test!</p>
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
                    <div class="text-3xl font-bold text-purple-600"><?php echo $_SESSION['tf_quiz_score']; ?></div>
                    <div class="text-gray-600">Total Score</div>
                </div>
                
                <div class="bg-green-50 rounded-xl p-6">
                    <div class="text-4xl mb-2">✅</div>
                    <div class="text-3xl font-bold text-green-600"><?php echo $_SESSION['tf_quiz_correct']; ?>/<?php echo $_SESSION['tf_quiz_total']; ?></div>
                    <div class="text-gray-600">Correct Answers</div>
                </div>
                
                <div class="bg-orange-50 rounded-xl p-6">
                    <div class="text-4xl mb-2">📈</div>
                    <div class="text-3xl font-bold text-orange-600">
                        <?php echo round(($_SESSION['tf_quiz_correct'] / $_SESSION['tf_quiz_total']) * 100); ?>%
                    </div>
                    <div class="text-gray-600">Accuracy</div>
                </div>
            </div>
            
            <div class="space-y-4">
                <a href="?reset=1" class="inline-block bg-gradient-to-r from-green-600 to-red-600 text-white font-bold py-3 px-8 rounded-lg hover:from-green-700 hover:to-red-700 transition">
                    🔄 Take Another Quiz
                </a>
                <br>
                <a href="tools.php" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-8 rounded-lg transition">
                    ← Back to Tools
                </a>
            </div>
        </div>
        
    <?php elseif (!$_SESSION['tf_quiz_mode']): ?>
        <!-- Quiz Selection Screen -->
        <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Choose Your Challenge Mode</h2>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-lg font-semibold text-gray-700 mb-3">
                        🎯 Select Number of Questions:
                    </label>
                    <select name="quiz_count" class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:outline-none focus:border-green-500 text-lg">
                        <option value="10">10 Questions </option>
                        <option value="20">20 Questions </option>
                        <option value="30">30 Questions </option>
                        <option value="50">50 Questions </option>
                    </select>
                </div>
                
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <h3 class="font-bold text-green-800 mb-2">📊 Scoring System:</h3>
                    <ul class="text-green-700 space-y-1">
                        <li>✅ Correct Answer: <strong>+10 points</strong></li>
                        <li>❌ Wrong Answer: <strong>0 points</strong></li>
                        <li>🏆 Maximum Score: <strong>{Selected Questions} × 10</strong></li>
                    </ul>
                </div>
                
                <button type="submit" name="start_quiz" class="w-full bg-gradient-to-r from-green-600 to-red-600 hover:from-green-700 hover:to-red-700 text-white font-bold py-4 px-6 rounded-lg text-lg transition">
                    🚀 Start Quiz
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <!-- <p class="text-gray-600 mb-4">Or practice without time limits:</p>
                <a href="true-false.php?mode=practice" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-8 rounded-lg transition">
                    🎮 Practice Mode
                </a> -->
            </div>
        </div>
        
    <?php else: ?>
        <!-- Quiz Progress -->
        <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-xl shadow-lg p-4 mb-6">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-gray-600">Question <?php echo $_SESSION['tf_quiz_current'] + 1; ?> of <?php echo $_SESSION['tf_quiz_total']; ?></span>
                <span class="text-sm font-semibold text-green-600">Score: <?php echo $_SESSION['tf_quiz_score']; ?> pts</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo (($_SESSION['tf_quiz_current']) / $_SESSION['tf_quiz_total']) * 100; ?>%"></div>
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
            <div class="text-center mb-8">
                <div class="text-7xl mb-6 float-icon">❓</div>
                <div class="bg-gradient-to-r from-green-100 to-red-100 rounded-xl p-6">
                    <h2 class="text-3xl font-bold text-gray-800">
                        <?php echo htmlspecialchars($question['question']); ?>
                    </h2>
                </div>
            </div>

            <form method="POST" class="space-y-6">
                <input type="hidden" name="tf_id" value="<?php echo $question['tf_id']; ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <button type="submit" name="user_answer" value="1" class="answer-btn true-btn text-white font-bold py-12 px-6 rounded-2xl text-2xl relative">
                        <div class="relative z-10">
                            <div class="text-6xl mb-4">✓</div>
                            <div class="text-3xl">TRUE</div>
                        </div>
                    </button>

                    <button type="submit" name="user_answer" value="0" class="answer-btn false-btn text-white font-bold py-12 px-6 rounded-2xl text-2xl relative">
                        <div class="relative z-10">
                            <div class="text-6xl mb-4">✗</div>
                            <div class="text-3xl">FALSE</div>
                        </div>
                    </button>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mt-6">
                    <p class="text-blue-800">💡 <strong>Tip:</strong> Take your time and think carefully about the statement!</p>
                </div>
            </form>
        </div>

        <!-- Current Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                <div class="text-3xl mb-1">✅</div>
                <div class="text-2xl font-bold text-green-600"><?php echo $_SESSION['tf_quiz_correct']; ?></div>
                <div class="text-sm text-gray-600">Correct</div>
            </div>
            <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                <div class="text-3xl mb-1">❌</div>
                <div class="text-2xl font-bold text-red-600"><?php echo $_SESSION['tf_quiz_current'] - $_SESSION['tf_quiz_correct']; ?></div>
                <div class="text-sm text-gray-600">Wrong</div>
            </div>
            <div class="bg-white bg-opacity-80 rounded-xl p-4 text-center">
                <div class="text-3xl mb-1">📊</div>
                <div class="text-2xl font-bold text-purple-600"><?php echo $_SESSION['tf_quiz_score']; ?></div>
                <div class="text-sm text-gray-600">Score</div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Back Button -->
    <?php if (!$quiz_complete && !$_SESSION['tf_quiz_mode']): ?>
    <div class="text-center">
        <a href="tools.php" class="inline-block bg-white bg-opacity-80 hover:bg-opacity-100 text-green-600 font-semibold py-3 px-8 rounded-lg transition shadow-lg">
            ← Back to Tools
        </a>
    </div>
    <?php endif; ?>

</div>

</body>
</html>