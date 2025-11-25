<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'error' => 'Invalid request method'], 405);
}

if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    jsonResponse(['success' => false, 'error' => 'Invalid CSRF token'], 403);
}

$participantId = intval($_POST['participant_id'] ?? 0);

if ($participantId <= 0) {
    jsonResponse(['success' => false, 'error' => 'Invalid participant ID'], 400);
}

try {
    $pdo = getDB();
    
    // Get logo path before deletion
    $stmt = $pdo->prepare("SELECT logo_path FROM participants WHERE id = ?");
    $stmt->execute([$participantId]);
    $participant = $stmt->fetch();
    
    if (!$participant) {
        jsonResponse(['success' => false, 'error' => 'Participant not found'], 404);
    }
    
    // Delete participant (cascade will delete votes)
    $stmt = $pdo->prepare("DELETE FROM participants WHERE id = ?");
    $stmt->execute([$participantId]);
    
    // Delete logo file if exists
    if ($participant['logo_path'] && file_exists(__DIR__ . '/../../' . $participant['logo_path'])) {
        @unlink(__DIR__ . '/../../' . $participant['logo_path']);
    }
    
    jsonResponse([
        'success' => true,
        'message' => 'Participant deleted successfully'
    ]);
} catch (PDOException $e) {
    error_log("Error deleting participant: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to delete participant'], 500);
}

