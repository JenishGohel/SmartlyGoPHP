<?php
session_start();
require_once 'db.php';

/* -------------------------
   USER INFO (SESSION)
-------------------------- */
$user_fullname = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Guest Learner";


/* -------------------------
   SESSION-BASED COUNTER
-------------------------- */
if (!isset($_SESSION['cards_reviewed'])) {
    $_SESSION['cards_reviewed'] = 0;
}
$cards_reviewed = $_SESSION['cards_reviewed'];

/* -------------------------
   FETCH RANDOM FLASHCARD
-------------------------- */
$sql = "SELECT card_id, question, correct_answer FROM flashcardflipper ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $card = $result->fetch_assoc();
} else {
    die("No flashcards available in database!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Flashcards</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%);
            min-height: 100vh;
        }
        .flip-card {
            background-color: transparent;
            max-width: 600px;
            height: 400px;
            perspective: 1000px;
            margin: auto;
        }
        .flip-card-inner {
            width: 100%;
            height: 100%;
            transition: transform 0.8s;
            transform-style: preserve-3d;
            position: relative;
        }
        .flip-card.flipped .flip-card-inner {
            transform: rotateY(180deg);
        }
        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            color: white;
            text-align: center;
        }
        .flip-card-front {
            background: linear-gradient(135deg, #a855f7, #ec4899);
        }
        .flip-card-back {
            background: linear-gradient(135deg, #10b981, #059669);
            transform: rotateY(180deg);
        }
    </style>
</head>

<body class="font-sans">

<div class="container max-w-4xl mx-auto px-4 py-8">

    <!-- HEADER -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold mb-2 bg-gradient-to-r from-purple-600 to-green-500 bg-clip-text text-transparent">
            🎴 PHP Flashcards
        </h1>
        <p class="text-gray-700">Click the card to flip and reveal the answer</p>
        <p class="text-gray-600 mt-2">
            Welcome, <strong><?php echo htmlspecialchars($user_fullname); ?></strong> 👋
        </p>
    </div>

    <!-- FLASHCARD -->
    <div class="flip-card" id="flashCard" onclick="flipCard()">
        <div class="flip-card-inner">

            <!-- FRONT -->
            <div class="flip-card-front">
                <div>
                    <div class="text-6xl mb-4">❓</div>
                    <h2 class="text-3xl font-bold mb-4">Question</h2>
                    <p class="text-xl">
                        <?php echo htmlspecialchars($card['question']); ?>
                    </p>
                    <p class="mt-6 opacity-75">👆 Click to flip</p>
                </div>
            </div>

            <!-- BACK -->
            <div class="flip-card-back">
                <div>
                    <div class="text-6xl mb-4">✅</div>
                    <h2 class="text-3xl font-bold mb-4">Answer</h2>
                    <p class="text-xl">
                        <?php echo htmlspecialchars($card['correct_answer']); ?>
                    </p>
                    <p class="mt-6 opacity-75">👆 Click to flip back</p>
                </div>
            </div>

        </div>
    </div>

    <!-- BUTTONS -->
    <div class="text-center mt-12 space-x-4">
        <button onclick="flipCard()" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-lg">
            🔄 Flip Card
        </button>
        <a href="flashcards.php" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg">
            ➡️ Next Card
        </a>
    </div>

    <!-- STATS -->
    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white bg-opacity-80 rounded-xl p-6 text-center">
            <div class="text-4xl mb-2">🎯</div>
            <div class="text-2xl font-bold text-purple-600" id="cardsReviewed">
                <?php echo (int)$cards_reviewed; ?>
            </div>
            <div class="text-sm text-gray-600">Cards Reviewed (Session)</div>
        </div>
        <div class="bg-white bg-opacity-80 rounded-xl p-6 text-center">
            <div class="text-4xl mb-2">⭐</div>
            <div class="text-2xl font-bold">-</div>
            <div class="text-sm text-gray-600">Mastered</div>
        </div>
        <div class="bg-white bg-opacity-80 rounded-xl p-6 text-center">
            <div class="text-4xl mb-2">🔥</div>
            <div class="text-2xl font-bold">-</div>
            <div class="text-sm text-gray-600">Streak</div>
        </div>
    </div>

    <!-- BACK -->
    <div class="text-center mt-8">
        <a href="tools.php" class="text-purple-600 font-semibold">
            ← Back to Tools
        </a>
    </div>

</div>

<script>
let cardsReviewed = <?php echo (int)$cards_reviewed; ?>;
let counted = false;

function flipCard() {
    const card = document.getElementById('flashCard');
    card.classList.toggle('flipped');

    if (card.classList.contains('flipped') && !counted) {
        counted = true;
        cardsReviewed++;
        document.getElementById('cardsReviewed').innerText = cardsReviewed;

        fetch('session_increment.php', { method: 'POST' });
    }
}
</script>

</body>
</html>
