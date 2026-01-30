<?php
session_start();
require_once 'db.php';

if (!function_exists('clean_input')) {
    function clean_input($data) {
        global $conn;
        $data = trim($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return isset($conn) ? $conn->real_escape_string($data) : $data;
    }
}

$user_fullname = $_SESSION['user_name'] ?? "Guest Learner";
$user_id = $_SESSION['user_id'] ?? 0;

// Initialize progress
if (!isset($_SESSION['hunt_progress'])) {
    $_SESSION['hunt_progress'] = 1;
}

$current_level  = $_SESSION['hunt_progress'];
$hunt_completed = false;

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {

    $hunt_id     = (int)$_SESSION['current_hunt_id']; // from session
    $user_answer = clean_input($_POST['user_answer']);

    $stmt = $conn->prepare("SELECT correct_answer, description FROM treasurehunt WHERE hunt_id = ?");
    $stmt->bind_param("i", $hunt_id);
    $stmt->execute();
    $result  = $stmt->get_result();
    $correct = $result->fetch_assoc();

    if ($correct && strcasecmp(trim($user_answer), trim($correct['correct_answer'])) === 0) {

        $_SESSION['hunt_progress']++;

        $message = "🎉 Correct! You found a clue!";

        if (!empty($correct['description'])) {
            $message .= "<br><br>📝 " . htmlspecialchars($correct['description']);
        }

        $message_class = "success";

    } else {

        $message = "❌ Incorrect! The correct answer is: <strong>" . 
                   htmlspecialchars($correct['correct_answer'] ?? 'N/A') . 
                   "</strong>";
        $message_class = "error";
    }

    $_SESSION['game_message'] = $message;
    $_SESSION['game_message_class'] = $message_class;

    header("Location: treasure-hunt.php");
    exit();
}

// Reset hunt
if (isset($_GET['reset'])) {
    $_SESSION['hunt_progress'] = 1;
    unset($_SESSION['game_message'], $_SESSION['game_message_class'], $_SESSION['current_hunt_id']);
    header("Location: treasure-hunt.php");
    exit();
}

// Fetch RANDOM question
$stmt = $conn->prepare("SELECT * FROM treasurehunt ORDER BY RAND() LIMIT 1");
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $question = $result->fetch_assoc();
    $_SESSION['current_hunt_id'] = $question['hunt_id']; // store current question id
} else {
    $hunt_completed = true;
}

// Total questions
$total_result = $conn->query("SELECT COUNT(*) AS total FROM treasurehunt");
$total_questions = $total_result->fetch_assoc()['total'] ?? 0;

// Flash messages
$message = $_SESSION['game_message'] ?? '';
$message_class = $_SESSION['game_message_class'] ?? '';
unset($_SESSION['game_message'], $_SESSION['game_message_class']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Treasure Hunt - PHP Learning</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%); min-height:100vh; }
.treasure-map { background: linear-gradient(135deg, #f59e0b, #d97706); border: 4px solid #92400e; position: relative; overflow: hidden; }
.treasure-map::before { content:'🗺️'; position:absolute; font-size:200px; opacity:0.1; right:-50px; bottom:-50px; }
.clue-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
.success { background: linear-gradient(135deg, #10b981, #059669); color:white; padding:20px; border-radius:10px; margin:20px 0; animation:slideIn 0.5s ease; }
.error { background: linear-gradient(135deg, #ef4444, #dc2626); color:white; padding:20px; border-radius:10px; margin:20px 0; animation:slideIn 0.5s ease; }
@keyframes slideIn { from {transform:translateY(-20px);opacity:0;} to {transform:translateY(0);opacity:1;} }
.progress-step { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; transition:all 0.3s; }
.progress-step.completed { background: linear-gradient(135deg, #10b981, #059669); color:white; transform:scale(1.1); }
.progress-step.current { background: linear-gradient(135deg, #f59e0b, #d97706); color:white; animation:pulse 2s infinite; }
.progress-step.locked { background:#e5e7eb; color:#9ca3af; }
@keyframes pulse { 0%,100% { box-shadow:0 0 0 0 rgba(245,158,11,0.7);} 50% { box-shadow:0 0 0 10px rgba(245,158,11,0);} }
</style>
</head>
<body class="font-sans">

<div class="container max-w-5xl mx-auto px-4 py-8">

<div class="text-center mb-8">
<h1 class="text-5xl font-bold mb-2" style="background: linear-gradient(135deg, #f59e0b, #dc2626); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">🗺️ PHP Treasure Hunt</h1>
<p class="text-gray-700 text-lg">Follow the clues and solve puzzles to find the treasure!</p>
<p class="text-gray-600 mt-2">Welcome, <strong><?php echo htmlspecialchars($user_fullname); ?></strong>! 👋</p>
</div>

<?php if(!empty($message)): ?>
<div class="<?php echo $message_class; ?>"><?php echo $message;?></div>
<?php endif; ?>

<div class="clue-card rounded-2xl shadow-2xl p-8 mb-6">

<div class="treasure-map rounded-xl p-6 mb-6 text-white">
<div class="flex items-center justify-between">
<div><h2 class="text-3xl font-bold mb-2">Random Clue</h2>
<p class="text-lg opacity-90">Every time a new question appears!</p></div>
<div class="text-6xl">🧩</div>
</div>
</div>

<div class="mb-6">
<h3 class="text-2xl font-bold text-gray-800 mb-4">Question:</h3>
<p class="text-xl text-gray-700"><?php echo htmlspecialchars($question['question']); ?></p>
</div>

<form method="POST" class="space-y-6">
<div>
<label class="block text-lg font-semibold text-gray-700 mb-2">🔐 Your Answer:</label>
<input type="text" name="user_answer" class="w-full px-4 py-3 border-2 border-orange-300 rounded-lg focus:outline-none focus:border-orange-500 text-lg" placeholder="Enter your answer..." required autofocus>
</div>

<div class="flex gap-4">
<button type="submit" name="submit_answer" class="flex-1 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition">🔍 Submit Answer</button>
<a href="treasure-hunt.php?reset=1" class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition">🔄 Restart Hunt</a>
</div>
</form>

</div>

<div class="text-center">
<a href="tools.php" class="inline-block bg-white bg-opacity-80 hover:bg-opacity-100 text-purple-600 font-semibold py-3 px-8 rounded-lg transition shadow-lg">← Back to Tools</a>
</div>

</div>
</body>
</html>
