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
    
    // Check if user has already voted in this category
    // Uses IP + Session combination to allow different devices on same network
    if (hasVotedInCategory($categoryId, $ip, $sessionId)) {
        jsonResponse(['success' => false, 'error' => 'You have already voted in this category'], 403);
    }
    
    // Rate limiting: Check votes from same IP in last hour
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM votes 
        WHERE voter_ip = ? 
        AND voted_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
    ");
    $stmt->execute([$ip]);
    $recentVotes = $stmt->fetch();
    
    if ($recentVotes['count'] >= 10) {
        jsonResponse(['success' => false, 'error' => 'Too many votes. Please try again later.'], 429);
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

