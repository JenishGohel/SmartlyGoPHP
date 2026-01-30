<?php
session_start();
require_once 'db.php';

$user_fullname = $_SESSION['user_name'] ?? "Guest Learner";
$user_id = $_SESSION['user_id'] ?? 0;

/* -------------------- NEXT / SKIP -------------------- */
if (isset($_GET['next'])) {
    unset($_SESSION['current_fix_id']);
    unset($_SESSION['user_code']);
    unset($_SESSION['show_solution']);
    unset($_SESSION['correct_code']);
}

/* -------------------- LOAD RANDOM QUESTION -------------------- */
// If no current question locked, or after refresh, pick a new random one
if (!isset($_SESSION['current_fix_id'])) {
    $sql = "SELECT fix_id FROM fixthecode ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['current_fix_id'] = $row['fix_id'];
    } else {
        die("No questions available in database!");
    }
}

// Fetch question
$stmt = $conn->prepare("SELECT * FROM fixthecode WHERE fix_id = ?");
$stmt->bind_param("i", $_SESSION['current_fix_id']);
$stmt->execute();
$question = $stmt->get_result()->fetch_assoc();

/* -------------------- SUBMIT CODE -------------------- */
$autoNext = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_code'])) {
    $fix_id = $_SESSION['current_fix_id'];
    $user_code = $_POST['user_code'];

    $stmt = $conn->prepare("SELECT correct_code FROM fixthecode WHERE fix_id = ?");
    $stmt->bind_param("i", $fix_id);
    $stmt->execute();
    $correct = $stmt->get_result()->fetch_assoc();

    if (trim($user_code) === trim($correct['correct_code'])) {
        // Correct submission → auto load next question
        $message = "🎉 Perfect! Your code is correct!";
        $message_class = "success";
        $autoNext = true;

        unset($_SESSION['current_fix_id']);
        unset($_SESSION['user_code']);
        unset($_SESSION['show_solution']);
        unset($_SESSION['correct_code']);
    } else {
        // Wrong submission → message shown, same question remains
        $message = "❌ Not quite right. Keep trying or see the solution!";
        $message_class = "error";
        $_SESSION['user_code'] = $user_code;
    }
}

/* -------------------- SHOW SOLUTION -------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['show_solution'])) {
    $_SESSION['show_solution'] = true;

    $stmt = $conn->prepare("SELECT correct_code FROM fixthecode WHERE fix_id = ?");
    $stmt->bind_param("i", $_SESSION['current_fix_id']);
    $stmt->execute();
    $solution_data = $stmt->get_result()->fetch_assoc();

    $_SESSION['correct_code'] = $solution_data['correct_code'];
}

/* -------------------- SESSION DATA -------------------- */
$message = $message ?? '';
$message_class = $message_class ?? '';
$show_solution = $_SESSION['show_solution'] ?? false;
$correct_code = $_SESSION['correct_code'] ?? '';
$user_code = $_SESSION['user_code'] ?? $question['buggy_code'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fix the Code - PHP Learning</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%); min-height: 100vh; }
.code-box { background: #2d3748; color: #e2e8f0; padding: 20px; border-radius: 12px; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; white-space: pre-wrap; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
textarea { font-family: 'Courier New', monospace; background: #2d3748; color: #e2e8f0; }
.success { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 15px; border-radius: 10px; margin: 20px 0; animation: slideIn 0.5s ease; }
.error { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 15px; border-radius: 10px; margin: 20px 0; animation: slideIn 0.5s ease; }
@keyframes slideIn { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.btn-primary { background: linear-gradient(135deg, #ef4444, #dc2626); transition: all 0.3s; }
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(239,68,68,0.4); }
</style>
</head>
<body class="font-sans">

<div class="container max-w-6xl mx-auto px-4 py-8">

<div class="text-center mb-8">
<h1 class="text-5xl font-bold mb-2" style="background: linear-gradient(135deg, #ef4444, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">🔧 Fix the Code</h1>
<p class="text-gray-700 text-lg">Repair the broken PHP code and make it work!</p>
<p class="text-gray-600 mt-2">Welcome, <strong><?php echo htmlspecialchars($user_fullname); ?></strong>! 👋</p>
</div>

<?php if (!empty($message)): ?>
<div id="game-message" class="<?php echo $message_class; ?>"><?php echo $message; ?></div>
<?php endif; ?>

<div id="question-container" class="bg-white bg-opacity-90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 mb-6">

<h2 class="text-2xl font-bold mb-4">Problem Statement:</h2>
<p class="text-lg"><?php echo htmlspecialchars($question['problem_statement']); ?></p>

<h3 class="text-xl font-bold mt-6 mb-3">🐛 Broken Code:</h3>
<div class="code-box"><?php echo htmlspecialchars($question['buggy_code']); ?></div>

<form method="POST" class="space-y-6 mt-6">
<textarea name="user_code" rows="12" class="w-full px-4 py-3 border-2 border-red-300 rounded-lg" required><?php echo htmlspecialchars($user_code); ?></textarea>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<button type="submit" name="submit_code" class="btn-primary text-white py-3 rounded">✓ Submit Fix</button>
<button type="submit" name="show_solution" class="bg-green-600 text-white py-3 rounded">🔓 Show Solution</button>
<a href="fix-code.php?next=1" class="bg-gray-600 text-white py-3 rounded text-center">⏭️ Skip</a>
</div>
</form>

<?php if($show_solution): ?>
<div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl mt-6">
<h3 class="text-2xl font-bold mb-3">✅ Correct Solution:</h3>
<pre class="bg-white bg-opacity-20 p-4 rounded"><?php echo htmlspecialchars($correct_code); ?></pre>
</div>
<?php endif; ?>

</div>

<div class="text-center">
<a href="tools.php" class="bg-white px-6 py-3 rounded shadow">← Back to Tools</a>
</div>

</div>

<?php if($autoNext): ?>
<script>
// Automatically load new question without white flash
setTimeout(function(){
    window.location.href = 'fix-code.php';
}, 1500);
</script>
<?php endif; ?>

</body>
</html> 