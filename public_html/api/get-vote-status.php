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

try {
    $pdo = getDB();
    $sessionId = session_id();
    
    if (empty($sessionId)) {
        jsonResponse([
            'success' => true,
            'votes' => []
        ]);
    }
    
    // Get all votes for this session with timestamps
    $stmt = $pdo->prepare("
        SELECT category_id, participant_id, voted_at 
        FROM votes 
        WHERE voter_session = ?
        ORDER BY voted_at DESC
    ");
    $stmt->execute([$sessionId]);
    $votes = $stmt->fetchAll();
    
    // Format as category_id => {participant_id, voted_at, can_vote_again_at} map
    $voteMap = [];
    $ip = getClientIP();
    $twoHoursInSeconds = 2 * 60 * 60;
    
    foreach ($votes as $vote) {
        $lastVoteTime = strtotime($vote['voted_at']);
        $currentTime = time();
        $timeDifference = $currentTime - $lastVoteTime;
        $canVoteAgainAt = date('Y-m-d H:i:s', $lastVoteTime + $twoHoursInSeconds);
        $timeRemaining = max(0, $twoHoursInSeconds - $timeDifference);
        
        // Only include if less than 2 hours have passed (still in cooldown)
        if ($timeDifference < $twoHoursInSeconds) {
            $voteMap[$vote['category_id']] = [
                'participant_id' => (int)$vote['participant_id'],
                'voted_at' => $vote['voted_at'],
                'can_vote_again_at' => $canVoteAgainAt,
                'time_remaining' => $timeRemaining
            ];
        }
    }
    
    jsonResponse([
        'success' => true,
        'votes' => $voteMap
    ]);
} catch (PDOException $e) {
    error_log("Error fetching vote status: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to fetch vote status'], 500);
}

