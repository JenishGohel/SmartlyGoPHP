<?php
session_start();
require_once 'db.php';

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
$user_fullname = $_SESSION['user_name'] ?? "Guest Learner";
$user_id = $_SESSION['user_id'] ?? 0;

// Fetch a random question only if no current question in session
if(!isset($_SESSION['current_question_id'])) {
    $result = $conn->query("SELECT * FROM predictoutput ORDER BY RAND() LIMIT 1");
    $question = $result->fetch_assoc();
    $_SESSION['current_question_id'] = $question['predict_id'];
} else {
    // Load the same question if user hasn't submitted yet
    $stmt = $conn->prepare("SELECT * FROM predictoutput WHERE predict_id=?");
    $stmt->bind_param("i", $_SESSION['current_question_id']);
    $stmt->execute();
    $question = $stmt->get_result()->fetch_assoc();
}

// Handle AJAX submission
if(isset($_POST['ajax_submit'])) {
    $predict_id = (int)$_POST['predict_id'];
    $user_answer = clean_input($_POST['user_answer']);

    $stmt = $conn->prepare("SELECT expected_output, description FROM predictoutput WHERE predict_id=?");
    $stmt->bind_param("i", $predict_id);
    $stmt->execute();
    $correct = $stmt->get_result()->fetch_assoc();

    $is_correct = strtolower(trim($user_answer)) === strtolower(trim($correct['expected_output']));

    $response = [
        'message' => $is_correct 
            ? "🎉 Correct! The output is indeed: <strong>".htmlspecialchars($correct['expected_output'])."</strong>"
            : "❌ Incorrect! The correct output is: <strong>".htmlspecialchars($correct['expected_output'])."</strong>",
        'class' => $is_correct ? 'success' : 'error'
    ];

    if(!empty($correct['description'])) {
        $response['message'] .= "<br><br>📝 Explanation: " . htmlspecialchars($correct['description']);
    }

    // Fetch next question but send it after delay in JS
    $result = $conn->query("SELECT * FROM predictoutput ORDER BY RAND() LIMIT 1");
    $new_question = $result->fetch_assoc();
    $_SESSION['current_question_id'] = $new_question['predict_id'];
    $response['new_question'] = $new_question;

    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Predict Output - PHP Learning</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
/* CSS unchanged */
body { background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%); min-height: 100vh; }
.code-box { background: #2d3748; color: #e2e8f0; padding: 25px; border-radius: 12px; font-family: 'Courier New', monospace; font-size: 16px; line-height: 1.8; white-space: pre-wrap; box-shadow: 0 10px 30px rgba(0,0,0,0.3); border-left: 4px solid #f59e0b; }
.output-box { background: #1a202c; color: #48bb78; padding: 20px; border-radius: 8px; font-family: 'Courier New', monospace; border: 2px solid #48bb78; }
.success { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 20px; border-radius: 10px; margin: 20px 0; animation: slideIn 0.5s ease; }
.error { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 20px; border-radius: 10px; margin: 20px 0; animation: slideIn 0.5s ease; }
@keyframes slideIn { from { transform: translateY(-20px); opacity:0; } to { transform: translateY(0); opacity:1; } }
.btn-primary { background: linear-gradient(135deg, #f59e0b, #d97706); transition: all 0.3s; }
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(245,158,11,0.4); }
</style>
</head>
<body class="font-sans">
<div class="container max-w-5xl mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-5xl font-bold mb-2" style="background: linear-gradient(135deg,#f59e0b,#ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            🎯 Predict the Output
        </h1>
        <p class="text-gray-700 text-lg">What will this PHP code output?</p>
        <p class="text-gray-600 mt-2">Welcome, <strong><?php echo htmlspecialchars($user_fullname); ?></strong>! 👋</p>
    </div>

    <div id="game-message"></div>

    <div id="question-container" class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">📄 Code Snippet:</h2>
        <div id="code-box" class="code-box mb-6"><?php echo htmlspecialchars($question['code_snippet']); ?></div>
        <form id="predict-form" class="space-y-6">
            <input type="hidden" name="predict_id" value="<?php echo $question['predict_id']; ?>">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <p class="text-yellow-800">💡 <strong>Tip:</strong> Trace through the code step by step. What will be displayed on the screen?</p>
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-700 mb-2">🖥️ Your Predicted Output:</label>
                <div class="output-box mb-2"><span class="opacity-50">Output: </span></div>
                <input type="text" name="user_answer" class="w-full px-4 py-3 border-2 border-orange-300 rounded-lg focus:outline-none focus:border-orange-500 text-lg font-mono" placeholder="Enter the expected output..." required autofocus>
                <p class="text-sm text-gray-500 mt-2">Type exactly what the code will output</p>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="btn-primary flex-1 text-white font-bold py-3 px-6 rounded-lg text-lg">✓ Submit Prediction</button>
                <a href="#" id="next-btn" class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition">⏭️ Next Question</a>
            </div>
        </form>
    </div>

    <div class="text-center">
        <a href="tools.php" class="inline-block bg-white bg-opacity-80 hover:bg-opacity-100 text-purple-600 font-semibold py-3 px-8 rounded-lg transition shadow-lg">← Back to Tools</a>
    </div>
</div>

<script>
const form = document.getElementById('predict-form');
const msgDiv = document.getElementById('game-message');
const codeBox = document.getElementById('code-box');
const nextBtn = document.getElementById('next-btn');

function showMessageAndLoadNext(answer=null) {
    const formData = new FormData(form);
    formData.append('ajax_submit','1');
    if(answer !== null) formData.append('user_answer', answer);

    fetch('predict-output.php',{method:'POST', body: formData})
    .then(res=>res.json())
    .then(data=>{
        if(answer !== null){
            msgDiv.innerHTML = data.message;
            msgDiv.className = data.class;

            // Wait 2 seconds before loading next question
            setTimeout(()=>{
                codeBox.innerText = data.new_question.code_snippet;
                form.predict_id.value = data.new_question.predict_id;
                form.user_answer.value = '';
                msgDiv.innerHTML = '';
                msgDiv.className = '';
            }, 2000);
        } else {
            // If user clicked Next Question without submitting, just show new question instantly
            if(data.new_question){
                codeBox.innerText = data.new_question.code_snippet;
                form.predict_id.value = data.new_question.predict_id;
                form.user_answer.value = '';
                msgDiv.innerHTML = '';
                msgDiv.className = '';
            }
        }
    });
}

// Submit prediction
form.addEventListener('submit', function(e){
    e.preventDefault();
    showMessageAndLoadNext(form.user_answer.value);
});

// Next question button
nextBtn.addEventListener('click', function(e){
    e.preventDefault();
    showMessageAndLoadNext(null); // null = skip showing message
});
</script>
</body>
</html>
