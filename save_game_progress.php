<?php
/**
 * Save Game Progress Helper Function
 * Call this function whenever a user completes a quiz/game
 */

function saveGameProgress($conn, $user_id, $game_type, $points, $questions, $correct, $wrong) {
    // Calculate accuracy
    $accuracy = $questions > 0 ? round(($correct / $questions) * 100, 2) : 0;
    
    // Insert game progress
    $sql = "INSERT INTO user_game_progress 
            (user_id, game_type, points_earned, questions_attempted, correct_answers, wrong_answers, accuracy, completed_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("isiiiid", $user_id, $game_type, $points, $questions, $correct, $wrong, $accuracy);
        $stmt->execute();
        $stmt->close();
    }
    
    // Update daily streak
    updateUserStreak($conn, $user_id);
    
    return true;
}

function updateUserStreak($conn, $user_id) {
    // Get or create streak record
    $sql = "SELECT streak_id, current_streak, longest_streak, last_activity_date 
            FROM user_streaks WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $today = date('Y-m-d');
    
    if ($result->num_rows > 0) {
        // User has streak record
        $streak_data = $result->fetch_assoc();
        $last_date = $streak_data['last_activity_date'];
        $current_streak = $streak_data['current_streak'];
        $longest_streak = $streak_data['longest_streak'];
        
        if ($last_date === null || $last_date != $today) {
            // Check if yesterday
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            
            if ($last_date == $yesterday) {
                // Continue streak
                $current_streak++;
            } else {
                // Streak broken, restart
                $current_streak = 1;
            }
            
            // Update longest streak if needed
            if ($current_streak > $longest_streak) {
                $longest_streak = $current_streak;
            }
            
            // Update streak
            $update_sql = "UPDATE user_streaks 
                          SET current_streak = ?, longest_streak = ?, last_activity_date = ? 
                          WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iisi", $current_streak, $longest_streak, $today, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
    } else {
        // Create new streak record
        $insert_sql = "INSERT INTO user_streaks (user_id, current_streak, longest_streak, last_activity_date) 
                      VALUES (?, 1, 1, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("is", $user_id, $today);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    
    $stmt->close();
}

/**
 * Usage Example:
 * 
 * // After quiz completion in fill-blanks.php, true-false.php, quiz.php
 * if ($_SESSION['quiz_mode'] && $quiz_complete) {
 *     require_once 'save_game_progress.php';
 *     saveGameProgress(
 *         $conn, 
 *         $user_id, 
 *         'fill-blanks',  // or 'true-false', 'quiz', etc.
 *         $_SESSION['quiz_score'], 
 *         $_SESSION['quiz_total'], 
 *         $_SESSION['quiz_correct'], 
 *         $_SESSION['quiz_total'] - $_SESSION['quiz_correct']
 *     );
 * }
 */
?>