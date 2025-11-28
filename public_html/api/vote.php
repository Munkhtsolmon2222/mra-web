<?php
// Start session with proper settings for unique sessions per device
if (session_status() === PHP_SESSION_NONE) {
    // Configure session cookie settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Lax');
    
    session_start();
    
    // Regenerate session ID on first request to ensure uniqueness
    if (!isset($_SESSION['initialized'])) {
        session_regenerate_id(true);
        $_SESSION['initialized'] = true;
    }
}
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'error' => 'Invalid request method'], 405);
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$participantId = isset($input['participant_id']) ? intval($input['participant_id']) : 0;

if ($participantId <= 0) {
    jsonResponse(['success' => false, 'error' => 'Invalid participant ID'], 400);
}

try {
    $pdo = getDB();
    
    // Get participant and category info
    $stmt = $pdo->prepare("SELECT id, category_id FROM participants WHERE id = ?");
    $stmt->execute([$participantId]);
    $participant = $stmt->fetch();
    
    if (!$participant) {
        jsonResponse(['success' => false, 'error' => 'Participant not found'], 404);
    }
    
    $categoryId = $participant['category_id'];
    
    // Get client IP and session
    $ip = getClientIP();
    $sessionId = session_id();
    
    // Ensure we have a valid session ID
    if (empty($sessionId)) {
        session_regenerate_id(true);
        $sessionId = session_id();
    }
    
    // Check if user has already voted in this category within the last 2 hours
    // Users can vote again after 2 hours have passed
    $voteCheck = hasVotedInCategory($categoryId, $ip, $sessionId);
    
    if ($voteCheck['has_voted']) {
        $timeRemaining = $voteCheck['time_remaining'];
        $hours = floor($timeRemaining / 3600);
        $minutes = floor(($timeRemaining % 3600) / 60);
        
        $message = 'Та энэ ангилалд санал өгсөн байна. ';
        if ($hours > 0) {
            $message .= $hours . ' цаг ';
        }
        if ($minutes > 0) {
            $message .= $minutes . ' минут ';
        }
        $message .= 'дараа дахин санал өгөх боломжтой.';
        
        jsonResponse([
            'success' => false, 
            'error' => $message,
            'can_vote_again_at' => $voteCheck['can_vote_again_at'],
            'time_remaining' => $timeRemaining
        ], 403);
    }
    
    // Rate limiting: Check votes from same session in last hour
    // This prevents abuse while allowing legitimate voting across categories
    // Since we already have 2-hour cooldown per category, this is just a safety net
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM votes 
        WHERE voter_session = ? 
        AND voted_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
    ");
    $stmt->execute([$sessionId]);
    $recentVotes = $stmt->fetch();
    
    // Allow up to 20 votes per hour per session (8 categories + undo/re-vote allowance)
    // This is more lenient since we already have 2-hour cooldown per category
    if ($recentVotes['count'] >= 20) {
        jsonResponse(['success' => false, 'error' => 'Хэт олон санал өгсөн байна. Түр хүлээгээд дахин оролдоно уу.'], 429);
    }
    
    // Record vote
    $stmt = $pdo->prepare("
        INSERT INTO votes (participant_id, category_id, voter_ip, voter_session) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$participantId, $categoryId, $ip, $sessionId]);
    
    // Get updated vote count
    $voteCount = getVoteCount($participantId);
    
    jsonResponse([
        'success' => true,
        'message' => 'Vote recorded successfully',
        'vote_count' => $voteCount
    ]);
} catch (PDOException $e) {
    error_log("Error recording vote: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to record vote'], 500);
}

