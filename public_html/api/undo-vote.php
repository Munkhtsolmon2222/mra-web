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
$categoryId = isset($input['category_id']) ? intval($input['category_id']) : 0;

if ($categoryId <= 0) {
    jsonResponse(['success' => false, 'error' => 'Invalid category ID'], 400);
}

try {
    $pdo = getDB();
    
    // Verify category exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
    $stmt->execute([$categoryId]);
    $category = $stmt->fetch();
    
    if (!$category) {
        jsonResponse(['success' => false, 'error' => 'Category not found'], 404);
    }
    
    // Get client IP and session
    $ip = getClientIP();
    $sessionId = session_id();
    
    // Ensure we have a valid session ID
    if (empty($sessionId)) {
        jsonResponse(['success' => false, 'error' => 'Session not found'], 403);
    }
    
    // Find and delete the vote for this category by this user
    // First, get the participant_id to update vote count
    $stmt = $pdo->prepare("
        SELECT participant_id 
        FROM votes 
        WHERE category_id = ? AND voter_session = ? 
        ORDER BY voted_at DESC 
        LIMIT 1
    ");
    $stmt->execute([$categoryId, $sessionId]);
    $vote = $stmt->fetch();
    
    if (!$vote) {
        jsonResponse(['success' => false, 'error' => 'No vote found to undo'], 404);
    }
    
    $participantId = $vote['participant_id'];
    
    // Delete the vote
    $stmt = $pdo->prepare("
        DELETE FROM votes 
        WHERE category_id = ? AND voter_session = ?
    ");
    $stmt->execute([$categoryId, $sessionId]);
    
    // Get updated vote count for the participant
    $voteCount = getVoteCount($participantId);
    
    jsonResponse([
        'success' => true,
        'message' => 'Vote undone successfully',
        'participant_id' => $participantId,
        'vote_count' => $voteCount
    ]);
} catch (PDOException $e) {
    error_log("Error undoing vote: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to undo vote'], 500);
}

